<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartUnschedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_sparepart',
        'tanggal',
        'type',
        'model',
        'no_orderan',
        'keterangan',
    ];
}
