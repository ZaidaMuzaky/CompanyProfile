<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AdminInspectionController extends Controller
{
    // display inspection data
    public function show()
{
    $client = new Client();
    $client->setApplicationName('Inspection after Service Form');
    $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->setAccessType('offline');

    $service = new Sheets($client);
    $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
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
            $userForms[] = $data; // tidak ada filter username
        }
    }

    return view('admin.inspection.form-show', compact('userForms', 'headers'));
}

    // delete inspection data
    public function destroy($id)
    {
        try {
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google/credentials.json'));
            $client->addScope(Sheets::SPREADSHEETS);
    
            $service = new Sheets($client);
            $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
            $range = 'Sheet1!A2:BD'; // Ambil semua data dari baris 2 ke bawah
    
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
    
            $targetRow = null;
    
            foreach ($values as $index => $row) {
                $currentId = $row[0] ?? null;
                $status = strtolower($row[13] ?? ''); // Kolom Status ada di kolom 14 (index 13)
    
                if ($currentId == $id && $status === 'pending') {
                    $targetRow = $index + 2; // Karena range dimulai dari A2, tambahkan 2
                    break;
                }
            }
    
            if ($targetRow !== null) {
                // Hapus baris dari sheet ID yang sesuai
                $sheetMetadata = $service->spreadsheets->get($spreadsheetId);
                $sheetId = null;
    
                foreach ($sheetMetadata->getSheets() as $sheet) {
                    if ($sheet->getProperties()->getTitle() === 'Sheet1') {
                        $sheetId = $sheet->getProperties()->getSheetId();
                        break;
                    }
                }
    
                if ($sheetId === null) {
                    throw new \Exception("Sheet 'Sheet1' tidak ditemukan.");
                }
    
                $batchUpdateRequest = new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                    'requests' => [
                        [
                            'deleteDimension' => [
                                'range' => [
                                    'sheetId' => $sheetId,
                                    'dimension' => 'ROWS',
                                    'startIndex' => $targetRow - 1,
                                    'endIndex' => $targetRow,
                                ],
                            ],
                        ],
                    ],
                ]);
    
                $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
    
                return redirect()->route('admin.inspection.form-show')->with('success', 'Formulir berhasil dihapus.');
            } else {
                return redirect()->route('admin.inspection.form-show')->with('error', 'Formulir tidak ditemukan atau status bukan Pending.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.inspection.form-show')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function index()
    {
        $client = new Client();
        $client->setApplicationName('Inspection after Service Form');
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
    
        $service = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
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
                $userForms[] = $data; // Tidak lagi difilter berdasarkan Username
            }
        }
    
        $supervisorForms = $this->groupBySupervisor($userForms);
    
        return view('admin.inspection.index', compact('userForms', 'headers', 'supervisorForms'));
    }
    

    private function groupBySupervisor($forms)
    {
        $supervisors = ['Ari Handoko', 'Teo Hermansyah', 'Herri Setiawan', 'Andrian'];

        $grouped = [];
        foreach ($supervisors as $supervisor) {
            $grouped[$supervisor] = array_filter($forms, function ($form) use ($supervisor) {
                return isset($form['Supervisor']) && trim($form['Supervisor']) === $supervisor;
            });
        }

        return $grouped;
    }


    Public function approveForm(Request $request, $formId)
    {
        $client = new Client();
        $client->setApplicationName('Backlog Service Form');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        $service = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
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


public function updateTemuanCaseAjax(Request $request, $id)
{
    $keyRaw = $request->input('key'); // contoh: "AC SYSTEM_AC hoot"
    $index = (int) $request->input('index'); // fallback
    $type = $request->input('type'); // 'action' atau 'statusCase'
    $value = $request->input('value');

    if (!in_array($type, ['action', 'statusCase'])) {
        return response()->json(['success' => false, 'message' => 'Invalid type']);
    }

    // Pecah key menjadi sub_component dan temuan
    $underscorePos = strpos($keyRaw, '_');
    if ($underscorePos === false) {
        return response()->json(['success' => false, 'message' => 'Key format invalid']);
    }

    $subComponent = trim(substr($keyRaw, 0, $underscorePos));
    $temuan = trim(substr($keyRaw, $underscorePos + 1));
    $columnKey = $subComponent; // diasumsikan kolom pakai nama subComponent

    // Setup Google Sheets API
    $client = new Client();
    $client->setApplicationName('Inspection Service Form');
    $client->setScopes([Sheets::SPREADSHEETS]);
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $service = new Sheets($client);

    $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
    $sheetName = 'Sheet1';

    $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
    $values = $response->getValues();

    $headers = $values[0] ?? [];
    $headerIndex = array_flip($headers);
    $colIndex = $headerIndex[$columnKey] ?? null;

    if ($colIndex === null) {
        return response()->json(['success' => false, 'message' => 'Kolom tidak ditemukan']);
    }

    foreach ($values as $rowIndex => $row) {
        if ($rowIndex === 0) continue;

        if (($row[0] ?? '') == $id) {
            $row = array_pad($row, count($headers), '');
            $jsonRaw = $row[$colIndex] ?? '[]';

            $json = json_decode($jsonRaw, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format JSON tidak valid: ' . json_last_error_msg()
                ]);
            }

            $updated = false;

            if (is_array($json)) {
                // Jika array of objects
                if (array_keys($json) === range(0, count($json) - 1)) {
                    foreach ($json as &$item) {
                        if (
                            isset($item['sub_component'], $item['temuan']) &&
                            $item['sub_component'] === $subComponent &&
                            $item['temuan'] === $temuan
                        ) {
                            $item[$type] = $value;
                            $updated = true;
                            break;
                        }
                    }
                }
                // Object tunggal
                elseif (isset($json[$type])) {
                    $json[$type] = $value;
                    $updated = true;
                }
            }

            if ($updated) {
                $newValue = json_encode($json, JSON_UNESCAPED_UNICODE);
                $columnLetter = $this->columnIndexToLetter($colIndex);
                $range = $sheetName . '!' . $columnLetter . ($rowIndex + 1);

                $body = new ValueRange([
                    'values' => [[$newValue]]
                ]);

                $service->spreadsheets_values->update($spreadsheetId, $range, $body, [
                    'valueInputOption' => 'USER_ENTERED'
                ]);

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan untuk diperbarui']);
            }
        }
    }

    return response()->json(['success' => false, 'message' => 'ID tidak ditemukan']);
}




private function columnIndexToLetter($index)
{
    $letter = '';
    while ($index >= 0) {
        $letter = chr($index % 26 + 65) . $letter;
        $index = intval($index / 26) - 1;
    }
    return $letter;
}





}
