<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan dalam database
    protected $table = 'tikets';

    // Kolom yang dapat diisi massal
    protected $fillable = [
        'namaTiket',
        'stok',
        'hargaJual',
        'keterangan',
    ];

    // Jika menggunakan timestamps, aktifkan ini
    // public $timestamps = true;

    // Anda dapat menambahkan relasi, accessor, atau mutator di sini jika diperlukan
}
