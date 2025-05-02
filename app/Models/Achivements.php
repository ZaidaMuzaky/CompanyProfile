<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achivements extends Model
{
    use HasFactory;

    protected $table = 'achievements'; // Nama tabel di database

    // Tentukan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
    ];
}
