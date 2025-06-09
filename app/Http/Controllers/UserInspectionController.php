<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Service\Sheets\ValueRange;

class UserInspectionController extends Controller
{
    public function form()
    {
        return view('user.inspection.form');
    }

    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'tanggal_service' => 'required|date',
            'nama_mekanik' => 'required|string|max:255',
            'waktu_serah_terima' => 'required',
            'nik' => 'required|string|max:50',
            'section' => 'required|string|in:Section DT,Section SDT,Section A2B',
            'supervisor' => 'required|string|in:Ari Handoko,Teo Hermansyah,Herri Setiawan,Budi Wahono',
            'model_unit' => 'required|string',
            'other_model_unit' => 'nullable|string|max:255',
            'cn_unit' => 'required|string|max:100',
            'hour_meter' => 'required|integer|min:0',
            'condition' => 'required|array',
            'condition.*' => 'required|string|in:OK,BAD',
            'recommendation' => 'nullable|array',
            'recommendation.*' => 'nullable|string|max:255',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:10240',
        ]);
    
        $modelUnit = $request->model_unit === 'Other' ? $request->other_model_unit : $request->model_unit;
    
        $evidenceFiles = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('evidence_files_inspection', 'public');
                    $evidenceFiles[] = asset('storage/' . $path);
                }
            }
        }
    
        $inspectionTitles = array_merge(
            [
                "Engine Oil level",
                "Radiator Coolant Level",
                "Final Drive Oil Level",
                "Differential Oil Level",
                "Transmission & Steering Oil Level",
                "Hydraulic Oil Level",
                "Fuel Level",
                "PTO Oil",
                "Brake Oil",
                "Compressor Oil Level"
            ],
            [
                "Check Leaking",
                "Check tighting Bolt",
                "Check Abnormal Noise",
                "Check Abnormal Temperature",
                "Check Abnormal Smoke/Smell",
                "Check Abnormal Vibration",
                "Check Abnormal Bending/Crack",
                "Check Abnormal Tention",
                "Check Abnormal Pressure",
                "Check Error Vault Code"
            ],
            [
                "AC SYSTEM",
                "BRAKE SYSTEM",
                "DIFFERENTIAL & FINAL DRAVE",
                "ELECTRICAL SYSTEM",
                "ENGINE",
                "GENERAL ( ACCESSORIES, CABIN, ETC )",
                "HYDRAULIC SYSTEM",
                "IT SYSTEM",
                "MAIN FRAME / CHASSIS / VASSEL",
                "PERIODICAL SERVICE",
                "PNEUMATIC SYSTEM",
                "PREEICTIVE MAINTENANCE",
                "PREVENTIF MAINTENANCE",
                "PROBLEM SDT",
                "PROBLEM TYRE SDT",
                "STEERING SYSTEM",
                "TRANSMISSION SYSTEM",
                "TYRE",
                "UNDERGRADUATE",
                "WORK EQUIPMENT"
            ]
        );
    
        $conditions = $request->input('condition', []);
        $recommendations = $request->input('recommendation', []);
        $inspectionData = [];
    
        foreach ($inspectionTitles as $index => $title) {
            $cond = $conditions[$index] ?? '';
            $rec = $recommendations[$index] ?? '';
            $statusCase = $cond === 'OK' ? 'close' : 'open';
    
            $inspectionData[] = json_encode([
                'condition' => $cond,
                'recommendation' => $rec,
                'action' => 'CHECK',
                'statusCase' => $statusCase,
            ]);
        }
    
        // Setup Google Sheets client
        $client = new Client();
        $client->setApplicationName('Laravel Service inspection');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
    
        $service = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
        $range = 'Sheet1!A1';
    
        // Ambil isi sheet untuk cek dan buat header dulu
        $response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!A1:Z');
        $rows = $response->getValues();
    
        if (empty($rows) || $rows[0][0] !== 'ID') {
            $headers = array_merge([
                'ID',
                'Timestamp',
                'Username',
                'Tanggal Service',
                'Nama Mekanik',
                'Waktu Serah Terima',
                'NIK',
                'Section',
                'Supervisor',
                'Model Unit',
                'CN Unit',
                'Hour Meter',
                'Evidence Files',
                'Status',
                'Approved By',
                'Note'
            ], $inspectionTitles);
    
            $headerBody = new ValueRange([
                'values' => [$headers],
            ]);
            $service->spreadsheets_values->append($spreadsheetId, $range, $headerBody, ['valueInputOption' => 'RAW']);
            
            // Update rows again after adding header
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!A1:Z');
            $rows = $response->getValues();
        }
    
      // Ambil semua ID yang ada (kolom pertama)
$existingIds = [];

foreach ($rows as $index => $row) {
    if ($index === 0) continue; // skip header
    $existingIds[] = intval($row[0] ?? 0);
}

$id = empty($existingIds) ? 1 : max($existingIds) + 1;

    
        // Data yang akan dikirim
        $values = [
            array_merge([
                $id,
                now()->toDateTimeString(),
                Auth::user()->username ?? '-',
                $request->tanggal_service,
                $request->nama_mekanik,
                $request->waktu_serah_terima,
                $request->nik,
                $request->section,
                $request->supervisor,
                $modelUnit,
                $request->cn_unit,
                $request->hour_meter,
                implode(", ", $evidenceFiles),
                'Pending',
                '',
                ''
            ], $inspectionData)
        ];
    
        $body = new ValueRange([
            'values' => $values
        ]);
    
        $params = ['valueInputOption' => 'RAW'];
        $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    
        return back()->with('success', 'Data Inspeksi Final berhasil disimpan dan dikirim ke Google Sheets.');
    }
    

    public function status()
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
    
                if (($data['Username'] ?? '') === (auth()->user()->username ?? '')) {
                    $userForms[] = $data;
                }
            }
        }
    
        return view('user.inspection.show', compact('userForms', 'headers'));
    }


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
    
                return redirect()->route('user.inspection.show')->with('success', 'Formulir berhasil dihapus.');
            } else {
                return redirect()->route('user.inspection.show')->with('error', 'Formulir tidak ditemukan atau status bukan Pending.');
            }
        } catch (\Exception $e) {
            return redirect()->route('user.inspection.show')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function updateCase(Request $request, $id)
    {
        // Setup Google Client
        $client = new Client();
        $client->setApplicationName('Inspection Service Form');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
    
        $service = new Sheets($client);
    
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
        $sheetName = 'Sheet1';
    
        // Ambil semua data dari sheet
        $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();
    
        if (empty($values)) {
            return back()->with('error', 'Spreadsheet is empty.');
        }
    
        // Ambil header dan buat index kolom per header
        $headers = array_map('trim', $values[0]);
        $headerIndex = array_flip($headers);
    
        // Ambil input dari request
        // 'case_keys' = array nama kolom yang ingin diupdate
        // 'statuses' = associative array ['nama_kolom' => 'nilai_status_baru']
        $keys = $request->input('keys', []);
        $statuses = $request->input('statuses', []);
    
        foreach ($values as $index => $row) {
            if ($index === 0) continue; // skip header
    
            // Pastikan row array panjang sesuai header
            $row = array_pad($row, count($headers), '');
    
            // Cek apakah kolom ID sesuai dengan $id
            if (isset($row[$headerIndex['ID']]) && $row[$headerIndex['ID']] == $id) {
    
                foreach ($keys as $key) {
                    if (isset($headerIndex[$key])) {
                        $colIndex = $headerIndex[$key];
                        $jsonString = $row[$colIndex] ?? '{}';
    
                        // Decode JSON string ke array
                        $data = json_decode($jsonString, true);
                        if (!is_array($data)) {
                            $data = [];
                        }
    
                        // Ambil status baru dari request, kalau tidak ada pakai nilai lama
                        $newStatus = $statuses[$key] ?? ($data['statusCase'] ?? '');
    
                        // Update statusCase saja
                        $data['statusCase'] = $newStatus;
    
                        // Encode ulang jadi JSON
                        $row[$colIndex] = json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
    
                // Buat array row baru sesuai urutan header
                $newRow = [];
                foreach ($headers as $header) {
                    $colIndex = $headerIndex[$header];
                    $newRow[] = $row[$colIndex] ?? '';
                }
    
                // Update row ke Google Sheets
                $range = $sheetName . '!A' . ($index + 1);
                $service->spreadsheets_values->update(
                    $spreadsheetId,
                    $range,
                    new ValueRange(['values' => [$newRow]]),
                    ['valueInputOption' => 'USER_ENTERED']
                );
    
                return back()->with('success', 'Status case berhasil diperbarui.');
            }
        }
    
        return back()->with('error', 'Data dengan ID tersebut tidak ditemukan.');
    }


    public function updateActionInspection(Request $request, $id){
        $actions = $request->input('actions', []);
    
        // Validasi action
        foreach ($actions as $key => $action) {
            if (!in_array($action, ['CHECK', 'INSTALL', 'REPLACE', 'MONITORING', 'REPAIR'])) {
                return back()->withErrors(['Invalid action: ' . $action])->withInput();
            }
        }
    
        // Setup Google Sheets API
        $client = new Client();
        $client->setApplicationName('Inspection Service Form');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
        $service = new Sheets($client);
    
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
        $sheetName = 'Sheet1';
    
        // Ambil semua data sheet
        $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();
    
        // Daftar nama kolom yang akan dicari posisinya
        $inspectionKeys = array_keys($actions);
    
        foreach ($values as $rowIndex => $row) {
            if (isset($row[0]) && $row[0] == $id) {
                // Loop setiap aksi dari user
                foreach ($actions as $key => $action) {
                    // Cari posisi kolom berdasarkan header baris pertama
                    $headerRow = $values[0] ?? [];
                    $position = array_search($key, $headerRow);
    
                    if ($position === false) {
                        continue;
                    }
    
                    // Ambil nilai lama di kolom tersebut (jika ada)
                    $oldValue = $row[$position] ?? '{}';
                    $data = json_decode($oldValue, true);
                    if (!is_array($data)) {
                        $data = [];
                    }
    
                    // Update field 'action'
                    $data['action'] = strtoupper($action);
                    $newValue = json_encode($data, JSON_UNESCAPED_UNICODE);
    
                    // Konversi posisi kolom (0-based) ke huruf Excel (A, B, ..., Z, AA, AB, ...)
                    $columnLetter = $this->columnIndexToLetter($position);
                    $range = $sheetName . '!' . $columnLetter . ($rowIndex + 1); // +1 karena 1-based indexing
    
                    // Update value ke Google Sheets
                    $service->spreadsheets_values->update(
                        $spreadsheetId,
                        $range,
                        new Sheets\ValueRange([
                            'values' => [[$newValue]]
                        ]),
                        ['valueInputOption' => 'USER_ENTERED']
                    );
                }
    
                break; // Keluar loop setelah ketemu baris ID yang cocok
            }
        }
    
        return back()->with('success', 'Action Inspection berhasil diperbarui.');
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
    
                if (($data['Username'] ?? '') === (auth()->user()->username ?? '')) {
                    $userForms[] = $data;
                }
            }
        }
    
        return view('user.inspection.showall', compact('userForms', 'headers'));

}
 


    public function edit($id)
    {
        // Set up Google Sheets client
        $client = new Client();
        $client->setApplicationName('Backlog Service Form');
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');

        $service = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
        $sheetName = 'Sheet1';

        // Get spreadsheet data
        $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();

        $headers = $values[0] ?? [];
        $form = null;

        foreach ($values as $index => $row) {
            if ($index === 0) continue;

            $data = array_combine($headers, array_pad($row, count($headers), ''));
            if (
                $data['ID'] == $id &&
                $data['Username'] == Auth::user()->username &&
                $data['Status'] === 'Rejected'
            ) {
                // Format waktu agar cocok dengan <input type="time">
                try {
                    $data['Waktu Serah Terima'] = Carbon::parse($data['Waktu Serah Terima'])->format('H:i');
                } catch (\Exception $e) {
                    $data['Waktu Serah Terima'] = '';
                }

                // Ubah JSON string temuan menjadi array
                if (isset($data['Temuan']) && $data['Temuan']) {
                    $data['temuan'] = json_decode($data['Temuan'], true) ?? [];
                } else {
                    $data['temuan'] = [];
                }

                $form = $data;
                // Ambil semua field yang format-nya JSON inspection dan simpan ke inspectionValues
                $inspectionValues = [];

                foreach ($data as $key => $value) {
                    if (
                        is_string($value) &&
                        substr($value, 0, 1) === '{' &&
                        strpos($value, 'condition') !== false
                    ){
                        $decoded = json_decode($value, true);
                        if (isset($decoded['condition'])) {
                            $inspectionValues[$key] = $decoded;
                        }
                    }
                }

                break;
            }
        }

        if (!$form) {
            return abort(403, 'Data tidak ditemukan atau tidak dapat diedit.');
        }

        return view('user.inspection.edit', compact('form', 'inspectionValues'));
    }

    
    public function resubmit(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'tanggal_service' => 'required|date',
        'nama_mekanik' => 'required|string|max:255',
        'waktu_serah_terima' => 'required',
        'nik' => 'required|string|max:50',
        'section' => 'required|string|in:Section DT,Section SDT,Section A2B',
        'supervisor' => 'required|string|in:Ari Handoko,Teo Hermansyah,Herri Setiawan,Budi Wahono',
        'model_unit' => 'required|string',
        'other_model_unit' => 'nullable|string|max:255',
        'cn_unit' => 'required|string|max:100',
        'hour_meter' => 'required|integer|min:0',
        'condition' => 'required|array',
        'condition.*' => 'required|string|in:OK,BAD',
        'recommendation' => 'nullable|array',
        'recommendation.*' => 'nullable|string|max:255',
        'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:10240',
    ]);

    $modelUnit = $request->model_unit === 'Other' ? $request->other_model_unit : $request->model_unit;

    $evidenceFiles = [];
    if ($request->hasFile('evidence')) {
        foreach ($request->file('evidence') as $file) {
            if ($file->isValid()) {
                $path = $file->store('evidence_files_inspection', 'public');
                $evidenceFiles[] = asset('storage/' . $path);
            }
        }
    }

    // Copy dari fungsi store()
    $inspectionTitles = array_merge(
        [
            "Engine Oil level",
            "Radiator Coolant Level",
            "Final Drive Oil Level",
            "Differential Oil Level",
            "Transmission & Steering Oil Level",
            "Hydraulic Oil Level",
            "Fuel Level",
            "PTO Oil",
            "Brake Oil",
            "Compressor Oil Level"
        ],
        [
            "Check Leaking",
            "Check tighting Bolt",
            "Check Abnormal Noise",
            "Check Abnormal Temperature",
            "Check Abnormal Smoke/Smell",
            "Check Abnormal Vibration",
            "Check Abnormal Bending/Crack",
            "Check Abnormal Tention",
            "Check Abnormal Pressure",
            "Check Error Vault Code"
        ],
        [
            "AC SYSTEM",
            "BRAKE SYSTEM",
            "DIFFERENTIAL & FINAL DRAVE",
            "ELECTRICAL SYSTEM",
            "ENGINE",
            "GENERAL ( ACCESSORIES, CABIN, ETC )",
            "HYDRAULIC SYSTEM",
            "IT SYSTEM",
            "MAIN FRAME / CHASSIS / VASSEL",
            "PERIODICAL SERVICE",
            "PNEUMATIC SYSTEM",
            "PREEICTIVE MAINTENANCE",
            "PREVENTIF MAINTENANCE",
            "PROBLEM SDT",
            "PROBLEM TYRE SDT",
            "STEERING SYSTEM",
            "TRANSMISSION SYSTEM",
            "TYRE",
            "UNDERGRADUATE",
            "WORK EQUIPMENT"
        ]
    );

    $conditions = $request->input('condition', []);
    $recommendations = $request->input('recommendation', []);
    $inspectionData = [];

    foreach ($inspectionTitles as $index => $title) {
        $cond = $conditions[$index] ?? '';
        $rec = $recommendations[$index] ?? '';
        $statusCase = $cond === 'OK' ? 'close' : 'open';

        $inspectionData[] = json_encode([
            'condition' => $cond,
            'recommendation' => $rec,
            'action' => 'CHECK',
            'statusCase' => $statusCase,
        ]);
    }

    // Setup Google Sheets
    $client = new Client();
    $client->setApplicationName('Backlog Service Form');
    $client->setScopes([Sheets::SPREADSHEETS]);
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->setAccessType('offline');

    $service = new Sheets($client);
    $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
    $sheetName = 'Sheet1';

    $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
    $rows = $response->getValues();

    if (empty($rows)) {
        return back()->with('error', 'Sheet kosong.');
    }

    $headers = $rows[0];
    $rowIndex = null;

    foreach ($rows as $index => $row) {
        if ($index === 0) continue;
        $rowData = array_combine($headers, array_pad($row, count($headers), ''));

        if (
            ($rowData['ID'] ?? null) == $id &&
            ($rowData['Username'] ?? null) === Auth::user()->username &&
            ($rowData['Status'] ?? '') === 'Rejected'
        ) {
            $rowIndex = $index;
            break;
        }
    }

    if ($rowIndex === null) {
        return back()->with('error', 'Data tidak ditemukan atau tidak memenuhi syarat untuk resubmit.');
    }

    // Data untuk update ulang
    $dataUpdate = array_merge([
        $id,
        now()->toDateTimeString(),
        Auth::user()->username ?? '-',
        $request->tanggal_service,
        $request->nama_mekanik,
        $request->waktu_serah_terima,
        $request->nik,
        $request->section,
        $request->supervisor,
        $modelUnit,
        $request->cn_unit,
        $request->hour_meter,
        implode(", ", $evidenceFiles),
        'Pending',
        '',
        ''
    ], $inspectionData);

    $body = new ValueRange([
        'values' => [array_map(function($v) {
            return $v ?? '';
        }, $dataUpdate)]
    ]);
    

    $range = $sheetName . '!A' . ($rowIndex + 1);

    try {
        $service->spreadsheets_values->update($spreadsheetId, $range, $body, [
            'valueInputOption' => 'USER_ENTERED'
        ]);
    } catch (\Google\Service\Exception $e) {
        return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
    }

    return redirect()->route('user.inspection.show')->with('success', 'Form berhasil dikirim ulang.');
}


}
