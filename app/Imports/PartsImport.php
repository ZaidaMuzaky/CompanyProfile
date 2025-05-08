<?php

namespace App\Imports;

use App\Models\Part;
use Maatwebsite\Excel\Concerns\ToModel;

class PartsImport implements ToModel
{
    protected $subcategory_id;

    public function __construct($subcategory_id)
    {
        $this->subcategory_id = $subcategory_id;
    }

    public function model(array $row)
    {
        // Pastikan qty_stock adalah angka
        $qty_stock = is_numeric($row[2]) ? (int)$row[2] : 0; // Jika qty_stock bukan angka, beri nilai default 0

        // Validasi status (hanya 'open' atau 'close' yang diterima)
        $status = strtolower(trim($row[3])); // Menghilangkan spasi ekstra dan memastikan dalam format kecil
        if (!in_array($status, ['open', 'close'])) {
            // Jika status tidak valid, beri nilai default 'open' atau 'close'
            $status = 'open'; // Bisa sesuaikan dengan kebutuhan Anda
        }

        // Memastikan semua field yang diperlukan terisi dengan benar
        if (!empty($row[0]) && !empty($row[1]) && !empty($row[3])) {
            return new Part([
                'subcategory_id' => $this->subcategory_id,
                'nama_sparepart' => $row[0], // Sesuaikan dengan kolom di file Excel
                'type' => $row[1],
                'qty_stock' => $qty_stock,
                'status' => $status, // Pastikan status valid
            ]);
        }

        // Mengabaikan baris yang tidak valid (jika ada nilai kosong)
        return null;
    }
}
