<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'tgl',
        'cust_id',
        'subtotal',
        'diskon',
        'ongkir',
        'total_bayar',
    ];

    /**
     * The attributes that are searchable in the query.
     *
     * @var array<int, string>
     */
    protected $searchables = [
        't_sales.kode',
        't_sales.tgl',
        't_sales.subtotal',
        't_sales.diskon',
        't_sales.ongkir',
        't_sales.total_bayar',
    ];

    /**
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [
        'kode' => 'LIKE',
        'tgl' => 'LIKE',
        'cust_id' => '=',
        'subtotal' => 'LIKE',
        'diskon' => 'LIKE',
        'ongkir' => 'LIKE',
        'total_bayar' => 'LIKE',
    ];

    /**
     * The columns or expressions that will be sorted if the given parameter exists.
     *
     * @var array<string, string>
     */
    protected $sortColumns = [
        'customer' => 'm_customer.nama',
        'details_count' => 'details_count',
    ];

    /**
     * Get the customer of the sales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }

    /**
     * Get the details of the sales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(SalesDetail::class, 'sales_id', 'id');
    }
}
