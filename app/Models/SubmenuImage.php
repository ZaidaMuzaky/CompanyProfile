<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmenuImage extends Model
{
    use HasFactory;

    protected $table = 'submenu_images';

    protected $fillable = [
        'submenu_id',
        'image_path', 
        'description',
    ];
    

    // Relasi ke Submenu (jika foreign key dipakai)
    public function submenu()
    {
        return $this->belongsTo(Submenu::class, 'submenu_id', 'id_submenu');
    }
}
