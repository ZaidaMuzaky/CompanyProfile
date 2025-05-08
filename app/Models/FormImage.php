<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormImage extends Model
{
    use HasFactory;

    protected $table = 'form_images'; // Nama tabel di database
    protected $fillable = [
        'form_id',      // ID form atau pengenal entri jika ada
        'image_path',   // Path gambar disimpan di VPS
        'image_url',    // URL yang akan dikirim ke Spreadsheet
        'section',      // (Opsional) bagian form mana gambar ini untuk
    ];
}
