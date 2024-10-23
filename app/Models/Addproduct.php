<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addproduct extends Model
{
    use HasFactory;

    protected $table = 'products'; // Tabel yang akan digunakan
    protected $fillable = [
        'namaProduk',
        'kodeProduk',
        'kategori',
        'stok',
        'hargaJual',
        'keterangan',
        'image',
    ];
}
