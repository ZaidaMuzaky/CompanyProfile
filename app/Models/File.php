<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';
    protected $primaryKey = 'id_file';

    protected $fillable = ['id_folder', 'nama_file', 'id_user_upload'];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'id_folder');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_upload');
    }
}
