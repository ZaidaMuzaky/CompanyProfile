<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function links()
    {
        return $this->hasMany(CnUnitLink::class);
    }

        public function files()
    {
        return $this->hasMany(CnUnitFile::class);
    }

}

?>