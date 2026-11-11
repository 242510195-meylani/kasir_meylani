<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;

class Stok extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'produk_id',
        'nama_suplier',
        'jumlah',
        'tanggal'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}