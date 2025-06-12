<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnUnitFile extends Model
{
    use HasFactory;

    protected $table = 'cn_unit_file'; // Sesuaikan dengan nama tabel

    protected $fillable = [
        'cn_unit_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    /**
     * Relasi ke model CNUnit (jika ada)
     */
    public function cnUnit()
    {
        return $this->belongsTo(CnUnit::class, 'cn_unit_id');
    }
}
