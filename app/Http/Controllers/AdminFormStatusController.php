<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class AdminFormStatusController extends Controller
{
    public function index()
    {
        $client = new Client();
        $client->setApplicationName('Backlog Service Form');
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        $service = new Sheets($client);
        $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
        $sheetName = 'Sheet1';

        $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();

        $userForms = [];
        $headers = [];

        if (!empty($values)) {
            $headers = array_map('trim', $values[0]);

            for ($i = 1; $i < count($values); $i++) {
                $row = $values[$i];
                $data = array_combine($headers, array_pad($row, count($headers), ''));

                // Tambahkan ke semua form (tidak memfilter username)
                $userForms[] = $data;
            }
        }

        return view('admin.backlog.form-status', ['allForms' => $userForms]);

    }
    public function destroy($id)
{
    try {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);

        $service = new Sheets($client);
        $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
        $range = 'Sheet1!A2:AO'; // Sesuaikan range sesuai data kamu

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $targetRow = null;

        foreach ($values as $index => $row) {
            // Pastikan ID berada di kolom pertama
            if (isset($row[0]) && $row[0] == $id) {
                $targetRow = $index + 2; // Karena range mulai dari A2, jadi baris sesungguhnya
                break;
            }
        }

        if ($targetRow) {
            $batchUpdateRequest = new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                'requests' => [
                    ['deleteDimension' => [
                        'range' => [
                            'sheetId' => 0, // Ganti jika sheet bukan yang pertama
                            'dimension' => 'ROWS',
                            'startIndex' => $targetRow - 1,
                            'endIndex' => $targetRow,
                        ],
                    ]],
                ]
            ]);

            $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);

            return redirect()->route('admin.backlog.form-status')->with('success', 'Formulir berhasil dihapus.');
        } else {
            return redirect()->route('admin.backlog.form-status')->with('error', 'Formulir tidak ditemukan.');
        }
    } catch (\Exception $e) {
        return redirect()->route('admin.backlog.form-status')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
