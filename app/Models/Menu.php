<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';
    protected $fillable = ['nama'];

    public function submenus()
    {
        return $this->hasMany(Submenu::class, 'menu_id', 'id_menu'); // Relasi ke tabel submenus
    }
}
