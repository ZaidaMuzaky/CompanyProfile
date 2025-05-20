<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'image_path',
        'description',
        'upload_date',
    ];

    /**
     * Relasi ke model Audit
     */
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
