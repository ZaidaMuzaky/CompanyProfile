<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnUnitLink extends Model
{
    use HasFactory;

    protected $fillable = ['cn_unit_id', 'spreadsheet_link', 'description'];

    public function cnUnit()
    {
        return $this->belongsTo(CnUnit::class);
    }
}

?>