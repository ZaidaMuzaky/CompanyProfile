<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory;
    protected $table = 'folders';
    protected $primaryKey = 'id_folder';

    protected $fillable = ['divisi'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_folder');
    }
}
