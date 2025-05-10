<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $table = 'submenus';
    protected $primaryKey = 'id_submenu';
    protected $fillable = ['menu_id', 'nama'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id_menu');
    }
    public function images()
    {
        return $this->hasMany(SubmenuImage::class, 'submenu_id', 'id_submenu');
    }
}
