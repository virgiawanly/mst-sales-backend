<?php

namespace App\Services;

use App\Models\Sales;
use App\Repositories\Interfaces\BarangRepositoryInterface;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SalesService extends BaseResourceService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\SalesRepositoryInterface  $repository
     * @return void
     */
    public function __construct(SalesRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\SalesRepositoryInterface
     */
    public function repository(): SalesRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Generate sales number.
     *
     * @return string
     */
    public static function generateSalesCode(): string
    {
        $prefix = date('Ym') . '-';

        $incrementNumber = 1;
        $latestSale = Sales::where('kode', 'like', $prefix . '%')
            ->orderBy('kode', 'desc')
            ->first();

        if ($latestSale) {
            $incrementNumber = (int) str_replace($prefix, '', $latestSale->kode);
            $incrementNumber = $incrementNumber + 1;
        }

        $incrementNumber = str_pad($incrementNumber, 4, '0', STR_PAD_LEFT);

        return $prefix . $incrementNumber;
    }

    /**
     * Save a new sales.
     *
     * @param  array $payload
     * @return \App\Models\Sales
     */
    public function save(array $payload): Sales
    {
        $sales = $this->_saveSalesHeader($payload);
        $sales = $this->_saveSalesDetails($sales, $payload);
        $sales = $this->_syncSalesTotal($sales);

        return $sales;
    }

    /**
     * Save sales header.
     *
     * @param  array $payload
     * @return \App\Models\Sales
     */
    protected function _saveSalesHeader(array $payload): Sales
    {
        $kode = self::generateSalesCode();

        $salesPayload = [
            'kode' => $kode,
            'tgl' => $payload['tgl'],
            'cust_id' => $payload['cust_id'],
            'ongkir' => $payload['ongkir'],
            'diskon' => $payload['diskon'],
            'subtotal' => 0, // @note: updated after the details are saved
            'total_bayar' => 0 // @note: updated after the details are saved,
        ];

        $sales = $this->repository->save($salesPayload);

        return $sales;
    }

    /**
     * Save sales details.
     *
     * @param  \App\Models\Sales $sales
     * @param  array $payload
     * @return \App\Models\Sales
     */
    protected function _saveSalesDetails(Sales $sales, array $payload): Sales
    {
        // Prepare barang repository
        $barangRepository = app()->make(BarangRepositoryInterface::class);

        // Loop through sales details and save data
        $details = $payload['details'];
        foreach ($details as $index => $detail) {
            // Validate barang validity
            $barang = $barangRepository->find($detail['barang_id']);

            if (empty($barang)) {
                throw new ModelNotFoundException('Barang with ID ' . $detail['barang_id'] . ' not found. On line ' . ($index + 1));
            }

            // Calculate prices and discount
            $harga = $barang->harga;
            $discountPercentage = 0;
            $discountNilai = 0;
            $hargaDiscount = 0;
            $subtotal = 0;

            // Calculate discount from either percentage or value
            if (!empty($detail['diskon_pct'])) {
                $discountPercentage = (float) $detail['diskon_pct'];
                $discountNilai = ($harga * ((float) $detail['diskon_pct'])) / 100;
            } else if (!empty($detail['diskon_nilai'])) {
                $discountNilai = (float) $detail['diskon_nilai'];
                $discountPercentage = ($discountNilai / ((float) $detail['diskon_nilai'])) * 100;
            }

            $hargaDiscount = $harga - $discountNilai;
            $subtotal = $detail['qty'] * $hargaDiscount;

            // Prepare payload to save
            $detailPayload = [
                'barang_id' => $barang->id,
                'harga_bandrol' => $harga,
                'qty' => $detail['qty'] ?? 0,
                'diskon_pct' => $discountPercentage,
                'diskon_nilai' => $discountNilai,
                'harga_diskon' => $hargaDiscount,
                'total' => $subtotal,
            ];

            // Save sales details
            $sales->details()->create($detailPayload);
        }

        return $sales;
    }

    /**
     * Sync sales total with its details.
     *
     * @param  \App\Models\Sales $sales
     * @return \App\Models\Sales
     */
    protected function _syncSalesTotal(Sales $sales): Sales
    {
        // Get details subtotal
        $subtotal = $sales->details()->sum('total');

        // Calculate total bayar
        $additionalDiscount = $sales->diskon;
        $ongkir = $sales->ongkir;
        $totalBayar = $subtotal - $additionalDiscount + $ongkir;

        // Update sales
        $sales->subtotal = $subtotal;
        $sales->total_bayar = $totalBayar;
        $sales->save();

        return $sales;
    }

    /**
     * Update an existing sales.
     *
     * @param  int $id
     * @param  array $payload
     * @return \App\Models\Sales
     */
    public function update(int $id, array $payload): Sales
    {
        $sales = $this->repository->find($id);

        if (empty($sales)) {
            throw new ModelNotFoundException('Sales not found.');
        }

        $sales = $this->_updateSalesHeader($sales, $payload);
        $sales = $this->_updateSalesDetails($sales, $payload);
        $sales = $this->_syncSalesTotal($sales);

        return $sales;
    }

    /**
     * Update sales header
     *
     * @param  \App\Models\Sales $sales
     * @param  array $payload
     * @return \App\Models\Sales
     */
    protected function _updateSalesHeader(Sales $sales, array $payload): Sales
    {
        $salesPayload = [
            'tgl' => $payload['tgl'],
            'cust_id' => $payload['cust_id'],
            'ongkir' => $payload['ongkir'],
            'diskon' => $payload['diskon'],
            'subtotal' => 0, // @note: updated after the details are saved
            'total_bayar' => 0 // @note: updated after the details are saved,
        ];

        $sales->update($salesPayload);

        return $sales;
    }

    /**
     * Update sales details.
     *
     * @param  \App\Models\Sales $sales
     * @param  array $payload
     * @return \App\Models\Sales
     */
    protected function _updateSalesDetails(Sales $sales, array $payload): Sales
    {
        // Prepare barang repository
        $barangRepository = app()->make(BarangRepositoryInterface::class);

        // Remove old details
        $sales->details()->delete();

        // Loop through sales details and save new data
        $details = $payload['details'];
        foreach ($details as $index => $detail) {
            // Validate barang validity
            $barang = $barangRepository->find($detail['barang_id']);

            if (empty($barang)) {
                throw new ModelNotFoundException('Invalid barang on line: ' . ($index + 1));
            }

            // Calculate prices and discount
            $harga = $barang->harga;
            $discountPercentage = 0;
            $discountNilai = 0;
            $hargaDiscount = 0;
            $subtotal = 0;

            // Calculate discount from either percentage or value
            if (!empty($detail['diskon_pct'])) {
                $discountPercentage = (float) $detail['diskon_pct'];
                $discountNilai = ($harga * ((float) $detail['diskon_pct'])) / 100;
            } else if (!empty($detail['diskon_nilai'])) {
                $discountNilai = (float) $detail['diskon_nilai'];
                $discountPercentage = ($discountNilai / ((float) $detail['diskon_nilai'])) * 100;
            }

            $hargaDiscount = $harga - $discountNilai;
            $subtotal = $detail['qty'] * $hargaDiscount;

            // Prepare payload to save
            $detailPayload = [
                'barang_id' => $barang->id,
                'harga_bandrol' => $harga,
                'qty' => $detail['qty'] ?? 0,
                'diskon_pct' => $discountPercentage,
                'diskon_nilai' => $discountNilai,
                'harga_diskon' => $hargaDiscount,
                'total' => $subtotal,
            ];

            // Save sales details
            $sales->details()->create($detailPayload);
        }

        return $sales;
    }

    /**
     * Get sales number to display.
     *
     * @param  int|null $salesId
     * @return string
     */
    public function getSalesCode(int|null $salesId = null): string
    {
        $salesCode = '';

        if (!empty($salesId)) {
            $sales = $this->repository->find($salesId);
            $salesCode = $sales->kode;
        } else {
            $salesCode = self::generateSalesCode();
        }

        return $salesCode;
    }
}
