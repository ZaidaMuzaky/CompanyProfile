<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi agar tidak memproses baris kosong
        if (empty($row['username']) || empty($row['password']) || empty($row['type'])) {
            return null;
        }

        return new User([
            'username' => $row['username'],
            'password' => bcrypt($row['password']),
            'type' => $row['type'],
            'is_online' => 0,
            'last_login_at' => null,
        ]);
    }
}
