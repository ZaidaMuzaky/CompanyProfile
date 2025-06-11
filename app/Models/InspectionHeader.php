<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionHeader extends Model
{
    use HasFactory;

    protected $table = 'inspection_headers';

    protected $fillable = [
        'header_image',
    ];
}
