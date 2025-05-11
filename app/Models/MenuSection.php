<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    use HasFactory;
    protected $table = 'menu_sections';

    protected $fillable = ['main_menu_id', 'nama'];

    public function mainMenu()
    {
        return $this->belongsTo(MainMenu::class, 'main_menu_id');
    }

    public function brands()
    {
        return $this->hasMany(MenuBrand::class, 'menu_section_id');
    }
}
