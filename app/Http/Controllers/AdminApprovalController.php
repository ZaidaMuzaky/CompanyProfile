<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Auth;

class AdminApprovalController extends Controller
{
    public function index()
    {
        // Setup Google Sheets API
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
        
            foreach ($values as $i => $row) {
                if ($i === 0) continue;
        
                $row = array_pad($row, count($headers), '');
                $data = array_combine($headers, $row);
        
                // Masukkan semua form tanpa cek role
                $userForms[] = $data;
            }
        }
        

        $supervisorForms = $this->groupBySupervisor($userForms);

        return view('admin.form-approval.index', compact('supervisorForms'));
    }

    private function groupBySupervisor($forms)
{
    $supervisors = ['Ari Handoko', 'Teo Hermansyah', 'Herri Setiawan', 'Budi Wahono'];

    $grouped = [];
    foreach ($supervisors as $supervisor) {
        $grouped[$supervisor] = array_filter($forms, function ($form) use ($supervisor) {
            return isset($form['Supervisor']) && trim($form['Supervisor']) === $supervisor;
        });
    }

    return $grouped;
}


    public function approveForm(Request $request, $formId)
    {
        $client = new Client();
        $client->setApplicationName('Backlog Service Form');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        $service = new Sheets($client);
        $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
        $sheetName = 'Sheet1';

        $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();

        if (empty($values)) {
            return back()->with('error', 'Spreadsheet is empty.');
        }

        $headers = array_map('trim', $values[0]);
        $headerIndex = array_flip($headers);

        foreach ($values as $index => $row) {
            if ($index === 0) continue;

            // Pad row to match headers
            $row = array_pad($row, count($headers), '');

            // Bandingkan ID
            if (isset($row[$headerIndex['ID']]) && $row[$headerIndex['ID']] == $formId) {
                // Set nilai baru
                $row[$headerIndex['Status']] = $request->status;
                $row[$headerIndex['Approved By']] = Auth::user()->username;

                if ($request->status === 'Rejected') {
                    $row[$headerIndex['Note']] = $request->note;
                }

                // Update data ke sheet
                $range = $sheetName . '!A' . ($index + 1);
                $service->spreadsheets_values->update($spreadsheetId, $range, new Sheets\ValueRange([
                    'values' => [$row]
                ]), [
                    'valueInputOption' => 'USER_ENTERED'
                ]);

                return back()->with('success', 'Form has been ' . $request->status);
            }
        }

        return back()->with('error', 'Form not found.');
    }

    // case status
    public function updateCase(Request $request, $id)
{
    $client = new Client();
    $client->setApplicationName('Backlog Service Form');
    $client->setScopes([Sheets::SPREADSHEETS]);
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->setAccessType('offline');

    $service = new Sheets($client);
    $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
    $sheetName = 'Sheet1';

    $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
    $values = $response->getValues();

    if (empty($values)) {
        return back()->with('error', 'Spreadsheet is empty.');
    }

    $headers = array_map('trim', $values[0]);
    $headerIndex = array_flip($headers);

    foreach ($values as $index => $row) {
        if ($index === 0) continue; // Skip header

        // Pastikan jumlah kolom sesuai
        $row = array_pad($row, count($headers), '');

        if (isset($row[$headerIndex['ID']]) && $row[$headerIndex['ID']] == $id) {
            // Update kolom Case Status dan Case Note jika disediakan
            if (isset($headerIndex['Status Case'])) {
                $row[$headerIndex['Status Case']] = $request->input('status_case');
            }            
        
            if (isset($headerIndex['Note Case']) && $request->filled('note_case')) {
                $row[$headerIndex['Note Case']] = $request->input('note_case');
            }

            // Buat ulang array numerik sesuai urutan headers
            $newRow = [];
            foreach ($headers as $header) {
                $colIndex = $headerIndex[$header];
                $newRow[] = isset($row[$colIndex]) ? $row[$colIndex] : '';
            }

            // Update ke spreadsheet
            $range = $sheetName . '!A' . ($index + 1);
            $service->spreadsheets_values->update(
                $spreadsheetId,
                $range,
                new \Google\Service\Sheets\ValueRange([
                    'values' => [$newRow]
                ]),
                ['valueInputOption' => 'USER_ENTERED']
            );

            return back()->with('success', 'Case status has been updated.');
        }
    }

    return back()->with('error', 'Form not found.');
}

public function updateActionInspection(Request $request, $id)
{
    $actions = $request->input('actions', []);

    // Validasi action
    foreach ($actions as $key => $action) {
        if (!in_array($action, ['CHECK', 'INSTALL', 'REPLACE', 'MONITORING', 'REPAIR'])) {
            return back()->withErrors(['Invalid action: ' . $action])->withInput();
        }
    }

    $client = new Client();
    $client->setApplicationName('Backlog Service Form');
    $client->setScopes([Sheets::SPREADSHEETS]);
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->setAccessType('offline');
    $service = new Sheets($client);

    $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
    $sheetName = 'Sheet1';

    // Ambil semua data sheet
    $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
    $values = $response->getValues();

    // Mendapatkan array key kolom action dari input form
    $inspectionColumns = array_keys($actions);

    foreach ($values as $rowIndex => $row) {
        if (isset($row[0]) && $row[0] == $id) {

            foreach ($actions as $key => $action) {
                $position = array_search($key, $inspectionColumns);
                if ($position === false) continue;

                // Misal kolom mulai dari indeks 20 (kolom U)
                $descIndex = 20 + $position;

                $oldValue = $row[$descIndex] ?? '';

                // Ekstrak deskripsi lama (teks setelah tanda ']')
                $descText = '';
                if (preg_match('/\](.*)/', $oldValue, $matches)) {
                    $descText = $matches[1];
                }

                // Buat nilai baru
                $newValue = '[' . strtoupper($action) . ']' . trim($descText);

                // Tentukan alamat sel, kolom huruf + nomor baris (1-based)
                $columnLetter = chr(65 + $descIndex); // 65 = A
                $range = $sheetName . '!' . $columnLetter . ($rowIndex + 1);

                $service->spreadsheets_values->update($spreadsheetId, $range, new Sheets\ValueRange([
                    'values' => [[$newValue]]
                ]), ['valueInputOption' => 'USER_ENTERED']);
            }

            break;
        }
    }

    return back()->with('success', 'Action Inspection berhasil diperbarui.');
}


public function edit($id)
{
    // Ambil data $form dari Google Sheets atau database sesuai $id
    $form = $this->getFormById($id); // contoh ambil data

    // Parsing status lama dari kolom Inspection Description
    foreach ($form as $key => $value) {
        if (strpos($key, 'Inspection Description') !== false) {
            preg_match('/\[(.*?)\]/', $value, $matches);
            $status = $matches[1] ?? null;
    
            // Ekstrak nomor dari "Inspection Description 1"
            if (preg_match('/Inspection Description (\d+)/', $key, $numberMatch)) {
                $number = $numberMatch[1];
                $form["Action Inspection $number"] = $status;
            }
        }
    }
    

    return view('admin.form-approval.index', compact('form'));
}


}
