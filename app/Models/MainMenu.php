<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainMenu extends Model
{
    use HasFactory;
    protected $table = 'main_menus';

    protected $fillable = ['nama'];

    public function menuSections()
    {
        return $this->hasMany(MenuSection::class, 'main_menu_id');
    }
}
