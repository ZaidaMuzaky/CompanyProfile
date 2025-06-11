<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\CnUnit;

class CnUnitImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new CnUnit([
            'name' => $row['name'], // sesuai nama kolom di baris pertama Excel
        ]);
    }
}


