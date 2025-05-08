<?php

namespace App\Imports;

use App\Models\PartUnschedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class PartUnscheduleImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi wajib isi
        $requiredFields = ['nama_sparepart', 'tanggal', 'type', 'model', 'no_orderan', 'keterangan'];
        foreach ($requiredFields as $field) {
            if (!isset($row[$field]) || trim($row[$field]) === '') {
                // Skip baris jika ada field yang kosong
                return null;
            }
        }

        // Konversi tanggal dari format Excel atau string d/m/Y
        try {
            $date = is_numeric($row['tanggal']) 
                ? Date::excelToDateTimeObject($row['tanggal']) 
                : Carbon::createFromFormat('d/m/Y', $row['tanggal']);
        } catch (\Exception $e) {
            // Skip baris jika gagal parsing tanggal
            return null;
        }

        return new PartUnschedule([
            'nama_sparepart' => $row['nama_sparepart'],
            'tanggal'        => $date->format('Y-m-d'),
            'type'           => $row['type'],
            'model'          => $row['model'],
            'no_orderan'     => $row['no_orderan'],
            'keterangan'     => $row['keterangan'],
        ]);
    }
}
