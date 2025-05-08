<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
    protected $fillable = [
        'subcategory_id', // Pastikan ada di sini
        'nama_sparepart',
        'type',
        'qty_stock',
        'status',
    ];

    public function subcategory() {
    return $this->belongsTo(Subcategory::class);
    }

}
