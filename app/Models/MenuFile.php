<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuFile extends Model
{
    use HasFactory;
    protected $table = 'menu_files';

    protected $fillable = [
        'menu_brand_id',
        'judul',
        'deskripsi',
        'path',
        'tipe',
    ];

    public function brand()
    {
        return $this->belongsTo(MenuBrand::class, 'menu_brand_id');
    }
}
