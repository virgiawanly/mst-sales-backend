<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_barang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama',
        'harga',
    ];

    /**
     * The attributes that are searchable in the query.
     *
     * @var array<int, string>
     */
    protected $searchables = [
        'kode',
        'nama',
        'harga',
    ];

    /**
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [
        'kode' => 'LIKE',
        'nama' => 'LIKE',
        'harga' => 'LIKE',
    ];
}
