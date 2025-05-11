<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuBrand extends Model
{
    use HasFactory;
    protected $table = 'menu_brands';

    protected $fillable = ['menu_section_id', 'nama'];

    public function section()
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id');
    }

    public function files()
    {
        return $this->hasMany(MenuFile::class, 'menu_brand_id');
    }
}
