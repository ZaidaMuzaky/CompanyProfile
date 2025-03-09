<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Folder extends Model
{
    use HasFactory;
    protected $table = 'folders';
    protected $primaryKey = 'id_folder';


    protected $fillable = ['nama', 'parent_id', 'icon_path'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_folder');
    }

    public function subfolders(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }
}
