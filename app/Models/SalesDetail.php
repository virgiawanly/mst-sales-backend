<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDetail extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_sales_det';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sales_id',
        'barang_id',
        'harga_bandrol',
        'qty',
        'diskon_pct',
        'diskon_nilai',
        'harga_diskon',
        'total',
    ];

    /**
     * Get the barang of the sales detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id')->withTrashed();
    }
}
