<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Google\Client;
use App\Models\CnUnit;
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
        // Validasi input
        $request->validate([
            'tanggal_service' => 'required|date',
            'nama_mekanik' => 'required|string|max:255',
            'waktu_serah_terima' => 'required',
            'nik' => 'required|string|max:50',
            'section' => 'required|string|in:Section DT,Section SDT,Section A2B',
            'supervisor' => 'required|string|in:Ari Handoko,Teo Hermansyah,Herri Setiawan,Andrian',
            'model_unit' => 'required|string',
            'other_model_unit' => 'nullable|string|max:255',
            'cn_unit' => 'required|string|max:100',
            'hour_meter' => 'required|integer|min:0',
            'condition' => 'nullable|array',
            'condition.*' => 'nullable|string|in:OK,BAD',
            'recommendation' => 'nullable|array',
            'recommendation.*' => 'nullable|string|max:255',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:10240',
            'temuan_sub_component' => 'nullable|array',
            'temuan_sub_component.*.sub_component' => 'required|string|min:1',
            'temuan_sub_component.*.temuan' => 'required|string',
            'temuan_sub_component.*.condition' => 'required|string|in:OK,BAD',
            'temuan_sub_component.*.recommendation' => 'nullable|string',
        ]);
    
        $modelUnit = $request->model_unit === 'Other' ? $request->other_model_unit : $request->model_unit;
    
        // Upload evidence files
        $evidenceFiles = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('evidence_files_inspection', 'public');
                    $evidenceFiles[] = asset('storage/' . $path);
                }
            }
        }
    
        // Judul inspeksi tetap
        $inspectionTitles = [
            "Engine Oil level", "Radiator Coolant Level", "Final Drive Oil Level",
            "Differential Oil Level", "Transmission & Steering Oil Level",
            "Hydraulic Oil Level", "Fuel Level", "PTO Oil", "Brake Oil", "Compressor Oil Level",
            "Check Leaking", "Check tighting Bolt", "Check Abnormal Noise",
            "Check Abnormal Temperature", "Check Abnormal Smoke/Smell", "Check Abnormal Vibration",
            "Check Abnormal Bending/Crack", "Check Abnormal Tention", "Check Abnormal Pressure",
            "Check Error Vault Code"
        ];
    
        $conditions = $request->input('condition', []);
        $recommendations = $request->input('recommendation', []);
        $inspectionData = [];
    
        foreach ($inspectionTitles as $index => $title) {
            $cond = $conditions[$index] ?? 'OK';
            $rec = $recommendations[$index] ?? '';
            $statusCase = $cond === 'OK' ? 'close' : 'open';
    
            $inspectionData[] = json_encode([
                'condition' => $cond,
                'recommendation' => $rec,
                'action' => 'CHECK',
                'statusCase' => $statusCase,
            ], JSON_UNESCAPED_UNICODE);
        }
    
        // Temuan sub komponen
        $temuanGabung = [];
        $temuanHeaders = [];
        $temuanData = [];
        $temuan_sub_component = $request->input('temuan_sub_component', []);
    
        if (!empty($temuan_sub_component)) {
            foreach ($temuan_sub_component as $temuan) {
                $sub = trim($temuan['sub_component'] ?? '');
                if ($sub === '') continue;
    
                $statusCase = $temuan['condition'] === 'OK' ? 'close' : 'open';
    
                $temuanGabung[$sub][] = [
                    'sub_component' => $sub,
                    'temuan' => $temuan['temuan'],
                    'condition' => $temuan['condition'],
                    'recommendation' => $temuan['recommendation'] ?? '',
                    'action' => 'CHECK',
                    'statusCase' => $statusCase
                ];
            }
    
            foreach ($temuanGabung as $sub => $listTemuan) {
                $header = "{$sub}";
                if (!in_array($header, $temuanHeaders)) {
                    $temuanHeaders[] = $header;
                }
                $temuanData[$header] = json_encode($listTemuan, JSON_UNESCAPED_UNICODE);
            }
        }
    
        // Pastikan semua header Section C masuk
        $sectionC = [
            "AC SYSTEM", "BRAKE SYSTEM", "DIFFERENTIAL & FINAL DRAVE",
            "ELECTRICAL SYSTEM", "ENGINE", "GENERAL ( ACCESSORIES, CABIN, ETC )",
            "HYDRAULIC SYSTEM", "IT SYSTEM", "MAIN FRAME / CHASSIS / VASSEL",
            "PERIODICAL SERVICE", "PNEUMATIC SYSTEM", "PROBLEM SDT", "PROBLEM TYRE SDT",
            "STEERING SYSTEM", "TRANSMISSION SYSTEM", "TYRE",
            "UNDERCARRIAGE"
        ];
    
        foreach ($sectionC as $sub) {
            $header = "{$sub}";
            if (!in_array($header, $temuanHeaders)) {
                $temuanHeaders[] = $header;
            }
            if (!array_key_exists($header, $temuanData)) {
                $temuanData[$header] = '';
            }
        }
    
        // Setup Google Sheets
        $client = new Client();
        $client->setApplicationName('Laravel Service Inspection');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
    
        $service = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
        $range = 'Sheet1!A1';
    
        // Ambil header
        $response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!A1:ZZ');
        $rows = $response->getValues();
        $existingHeaders = $rows[0] ?? [];
    
        if (empty($existingHeaders) || $existingHeaders[0] !== 'ID') {
            $headers = array_merge([
                'ID', 'Timestamp', 'Username', 'Tanggal Service', 'Nama Mekanik', 'Waktu Serah Terima',
                'NIK', 'Section', 'Supervisor', 'Model Unit', 'CN Unit', 'Hour Meter',
                'Evidence Files', 'Status', 'Approved By', 'Note'
            ], $inspectionTitles, $temuanHeaders);
    
            $headerBody = new ValueRange(['values' => [$headers]]);
            $service->spreadsheets_values->append($spreadsheetId, $range, $headerBody, ['valueInputOption' => 'RAW']);
            $existingHeaders = $headers;
        }
    
        $missingHeaders = array_diff($temuanHeaders, $existingHeaders);
        if (!empty($missingHeaders)) {
            $updatedHeaders = array_merge($existingHeaders, $missingHeaders);
            $headerUpdate = new ValueRange([
                'range' => 'Sheet1!A1',
                'values' => [$updatedHeaders]
            ]);
            $service->spreadsheets_values->update($spreadsheetId, 'Sheet1!A1', $headerUpdate, ['valueInputOption' => 'RAW']);
            $existingHeaders = $updatedHeaders;
        }
    
        $existingIds = [];
        if (is_array($rows)) {
            foreach ($rows as $i => $row) {
                if ($i === 0) continue;
                $existingIds[] = intval($row[0] ?? 0);
            }
        }
        $id = empty($existingIds) ? 1 : max($existingIds) + 1;
    
        $baseData = [
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
            count($evidenceFiles) ? implode(", ", $evidenceFiles) : '-',
            'Pending',
            '',
            ''
        ];
    
        foreach ($existingHeaders as $header) {
            if (!in_array($header, array_merge(array_keys($temuanData), $inspectionTitles, [
                'ID', 'Timestamp', 'Username', 'Tanggal Service', 'Nama Mekanik', 'Waktu Serah Terima',
                'NIK', 'Section', 'Supervisor', 'Model Unit', 'CN Unit', 'Hour Meter',
                'Evidence Files', 'Status', 'Approved By', 'Note'
            ]))) {
                $temuanData[$header] = '';
            }
        }
    
        $finalRow = [];
        foreach ($existingHeaders as $header) {
            if (in_array($header, [
                'ID', 'Timestamp', 'Username', 'Tanggal Service', 'Nama Mekanik', 'Waktu Serah Terima',
                'NIK', 'Section', 'Supervisor', 'Model Unit', 'CN Unit', 'Hour Meter',
                'Evidence Files', 'Status', 'Approved By', 'Note'
            ])) {
                $index = array_search($header, $existingHeaders);
                $finalRow[] = $baseData[$index] ?? '';
            } elseif (in_array($header, $inspectionTitles)) {
                $index = array_search($header, $inspectionTitles);
                $finalRow[] = $inspectionData[$index] ?? '';
            } else {
                $finalRow[] = $temuanData[$header] ?? '';
            }
        }
    
        // Kirim ke Google Sheets
        $body = new ValueRange(['values' => [$finalRow]]);
        $params = ['valueInputOption' => 'RAW'];
        $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    
        return back()->with('success', 'Data Inspeksi berhasil disimpan dan dikirim ke Google Sheets.');
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
        $keys = $request->input('keys', []);
        $statuses = $request->input('statuses', []);
    
        foreach ($values as $index => $row) {
            if ($index === 0) continue; // skip header
    
            // Pastikan row array panjang sesuai header
            $row = array_pad($row, count($headers), '');
    
            // Cek apakah kolom ID sesuai dengan $id
            if (isset($row[$headerIndex['ID']]) && $row[$headerIndex['ID']] == $id) {
                foreach ($keys as $key) {
                    // Pisahkan key menjadi header dan index (untuk Section C)
                    if (preg_match('/^(.+)_(\d+)$/', $key, $matches)) {
                        $header = $matches[1];
                        $itemIndex = $matches[2];
                        if (isset($headerIndex[$header])) {
                            $colIndex = $headerIndex[$header];
                            $jsonString = $row[$colIndex] ?? '[]';
                            $data = json_decode($jsonString, true);
                            if (!is_array($data)) {
                                $data = [];
                            }
                            // Update statusCase untuk item tertentu
                            if (isset($data[$itemIndex])) {
                                $data[$itemIndex]['statusCase'] = $statuses[$key] ?? $data[$itemIndex]['statusCase'];
                                $row[$colIndex] = json_encode($data, JSON_UNESCAPED_UNICODE);
                            }
                        }
                    } else {
                        // Untuk Section A dan B
                        if (isset($headerIndex[$key])) {
                            $colIndex = $headerIndex[$key];
                            $jsonString = $row[$colIndex] ?? '{}';
                            $data = json_decode($jsonString, true);
                            if (!is_array($data)) {
                                $data = [];
                            }
                            $data['statusCase'] = $statuses[$key] ?? ($data['statusCase'] ?? '');
                            $row[$colIndex] = json_encode($data, JSON_UNESCAPED_UNICODE);
                        }
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


    public function updateActionInspection(Request $request, $id)
    {
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
    
        if (empty($values)) {
            return back()->with('error', 'Spreadsheet is empty.');
        }
    
        foreach ($values as $rowIndex => $row) {
            if (isset($row[0]) && $row[0] == $id) {
                $headerRow = $values[0] ?? [];
                foreach ($actions as $key => $action) {
                    // Check if key is for Section C (format: header_index)
                    if (preg_match('/^(.+)_(\d+)$/', $key, $matches)) {
                        $header = $matches[1];
                        $itemIndex = $matches[2];
                        $position = array_search($header, $headerRow);
                        if ($position === false) {
                            continue;
                        }
                        $oldValue = $row[$position] ?? '[]';
                        $data = json_decode($oldValue, true);
                        if (!is_array($data)) {
                            $data = [];
                        }
                        if (isset($data[$itemIndex])) {
                            $data[$itemIndex]['action'] = strtoupper($action);
                            $newValue = json_encode($data, JSON_UNESCAPED_UNICODE);
                            $columnLetter = $this->columnIndexToLetter($position);
                            $range = $sheetName . '!' . $columnLetter . ($rowIndex + 1);
                            $service->spreadsheets_values->update(
                                $spreadsheetId,
                                $range,
                                new Sheets\ValueRange([
                                    'values' => [[$newValue]]
                                ]),
                                ['valueInputOption' => 'USER_ENTERED']
                            );
                        }
                    } else {
                        // For Sections A, B, and D
                        $position = array_search($key, $headerRow);
                        if ($position === false) {
                            continue;
                        }
                        $oldValue = $row[$position] ?? '{}';
                        $data = json_decode($oldValue, true);
                        if (!is_array($data)) {
                            $data = [];
                        }
                        $data['action'] = strtoupper($action);
                        $newValue = json_encode($data, JSON_UNESCAPED_UNICODE);
                        $columnLetter = $this->columnIndexToLetter($position);
                        $range = $sheetName . '!' . $columnLetter . ($rowIndex + 1);
                        $service->spreadsheets_values->update(
                            $spreadsheetId,
                            $range,
                            new Sheets\ValueRange([
                                'values' => [[$newValue]]
                            ]),
                            ['valueInputOption' => 'USER_ENTERED']
                        );
                    }
                }
                break;
            }
        }
    
        return back()->with('success', 'Action Inspection berhasil diperbarui.');
    }
    
    /**
     * Convert column index (0-based) to Excel column letter (A, B, ..., Z, AA, AB, ...)
     */
    private function columnIndexToLetter($index)
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = (int)($index / 26) - 1;
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
                $userForms[] = $data;
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
        $inspectionValues = [];

        // Section C keys
        $sectionCKeys = [
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
            "PROBLEM SDT",
            "PROBLEM TYRE SDT",
            "STEERING SYSTEM",
            "TRANSMISSION SYSTEM",
            "TYRE",
            "UNDERCARRIAGE",
        ];

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

                $form = $data;
                // Ambil semua field yang format-nya JSON inspection dan simpan ke inspectionValues
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
                // Section C: decode array of temuan per sub component
                $inspectionValues['section_c'] = [];
                foreach ($sectionCKeys as $subKey) {
                    if (!empty($data[$subKey]) && is_string($data[$subKey]) && substr(trim($data[$subKey]), 0, 1) === '[') {
                        $decoded = json_decode($data[$subKey], true);
                        if (is_array($decoded)) {
                            $inspectionValues['section_c'][$subKey] = $decoded;
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
            'supervisor' => 'required|string|in:Ari Handoko,Teo Hermansyah,Herri Setiawan,Andrian',
            'model_unit' => 'required|string',
            'other_model_unit' => 'nullable|string|max:255',
            'cn_unit' => 'required|string|max:100',
            'hour_meter' => 'required|integer|min:0',
            'condition' => 'required|array',
            'condition.*' => 'required|string|in:OK,BAD',
            'recommendation' => 'nullable|array',
            'recommendation.*' => 'nullable|string|max:255',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:10240',
            'temuan_sub_component' => 'nullable|array',
            'temuan_sub_component.*.sub_component' => 'required|string|min:1',
            'temuan_sub_component.*.temuan' => 'required|string',
            'temuan_sub_component.*.condition' => 'required|string|in:OK,BAD',
            'temuan_sub_component.*.recommendation' => 'nullable|string',
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

        // Judul inspeksi tetap
        $inspectionTitles = [
            "Engine Oil level", "Radiator Coolant Level", "Final Drive Oil Level",
            "Differential Oil Level", "Transmission & Steering Oil Level",
            "Hydraulic Oil Level", "Fuel Level", "PTO Oil", "Brake Oil", "Compressor Oil Level",
            "Check Leaking", "Check tighting Bolt", "Check Abnormal Noise",
            "Check Abnormal Temperature", "Check Abnormal Smoke/Smell", "Check Abnormal Vibration",
            "Check Abnormal Bending/Crack", "Check Abnormal Tention", "Check Abnormal Pressure",
            "Check Error Vault Code"
        ];

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

        // Temuan sub komponen (Section C)
        $temuanGabung = [];
        $temuanHeaders = [];
        $temuanData = [];
        $temuan_sub_component = $request->input('temuan_sub_component', []);

        if (!empty($temuan_sub_component)) {
            foreach ($temuan_sub_component as $temuan) {
                $sub = trim($temuan['sub_component'] ?? '');
                if ($sub === '') continue;
                $statusCase = $temuan['condition'] === 'OK' ? 'close' : 'open';
                $temuanGabung[$sub][] = [
                    'temuan' => $temuan['temuan'] ?? '',
                    'condition' => $temuan['condition'] ?? '',
                    'recommendation' => $temuan['recommendation'] ?? '',
                    'statusCase' => $statusCase,
                ];
            }
            foreach ($temuanGabung as $sub => $listTemuan) {
                $header = "{$sub}";
                if (!in_array($header, $temuanHeaders)) {
                    $temuanHeaders[] = $header;
                }
                $temuanData[$header] = json_encode($listTemuan, JSON_UNESCAPED_UNICODE);
            }
        }

        // Pastikan semua header Section C masuk
        $sectionC = [
            "AC SYSTEM", "BRAKE SYSTEM", "DIFFERENTIAL & FINAL DRAVE",
            "ELECTRICAL SYSTEM", "ENGINE", "GENERAL ( ACCESSORIES, CABIN, ETC )",
            "HYDRAULIC SYSTEM", "IT SYSTEM", "MAIN FRAME / CHASSIS / VASSEL",
            "PERIODICAL SERVICE", "PNEUMATIC SYSTEM", "PROBLEM SDT", "PROBLEM TYRE SDT",
            "STEERING SYSTEM", "TRANSMISSION SYSTEM", "TYRE",
            "UNDERCARRIAGE"
        ];
        foreach ($sectionC as $sub) {
            $header = "{$sub}";
            if (!in_array($header, $temuanHeaders)) {
                $temuanHeaders[] = $header;
            }
            if (!array_key_exists($header, $temuanData)) {
                $temuanData[$header] = '';
            }
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

        // Susun ulang data sesuai urutan header
        $baseData = [
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
            count($evidenceFiles) ? implode(", ", $evidenceFiles) : '-',
            'Pending',
            '',
            ''
        ];

        foreach ($headers as $header) {
            if (!in_array($header, array_merge(array_keys($temuanData), $inspectionTitles, [
                'ID', 'Timestamp', 'Username', 'Tanggal Service', 'Nama Mekanik', 'Waktu Serah Terima',
                'NIK', 'Section', 'Supervisor', 'Model Unit', 'CN Unit', 'Hour Meter',
                'Evidence Files', 'Status', 'Approved By', 'Note'
            ]))) {
                $temuanData[$header] = '';
            }
        }

        $finalRow = [];
        foreach ($headers as $header) {
            if (in_array($header, [
                'ID', 'Timestamp', 'Username', 'Tanggal Service', 'Nama Mekanik', 'Waktu Serah Terima',
                'NIK', 'Section', 'Supervisor', 'Model Unit', 'CN Unit', 'Hour Meter',
                'Evidence Files', 'Status', 'Approved By', 'Note'
            ])) {
                $index = array_search($header, $headers);
                $finalRow[] = $baseData[$index] ?? '';
            } elseif (in_array($header, $inspectionTitles)) {
                $index = array_search($header, $inspectionTitles);
                $finalRow[] = $inspectionData[$index] ?? '';
            } else {
                $finalRow[] = $temuanData[$header] ?? '';
            }
        }
    
        $body = new ValueRange([
            'values' => [array_map(function($v) {
                return $v ?? '';
            }, $finalRow)]
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

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');

        $results = CnUnit::where('name', 'like', '%' . $query . '%')
            ->pluck('name')
            ->take(10);

        return response()->json($results);
    }


}
