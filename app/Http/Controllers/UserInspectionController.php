<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Google\Client;
use App\Models\CnUnit;
use Google\Service\Sheets;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Storage;

class UserInspectionController extends Controller
{
    public function form()
    {
        return view('user.inspection.form');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'tanggal_service'                          => 'required|date',
            'nama_mekanik'                             => 'required|string|max:255',
            'waktu_serah_terima'                       => 'required|string',
            'nik'                                      => 'required|string|max:50',
            'section'                                  => 'required|string|in:Section DT,Section SDT,Section A2B',
            'supervisor'                               => 'required|string|in:Ari Handoko,Teo Hermansyah,Herri Setiawan,Andrian',
            'model_unit'                               => 'required|string',
            'other_model_unit'                         => 'nullable|string|max:255',
            'cn_unit'                                  => 'required|string|max:100',
            'hour_meter'                               => 'required|integer|min:0',

            // Section A/B
            'condition'                                => 'nullable|array',
            'condition.*'                              => 'nullable|string|in:OK,BAD',
            'recommendation'                           => 'nullable|array',
            'recommendation.*'                         => 'nullable|string|max:255',
            'action'                                   => 'nullable|array',
            'action.*'                                 => 'nullable|string|in:CHECK,INSTALL,REPLACE,MONITORING,REPAIR', 
            'evidence_item'                            => 'nullable|array',
            'evidence_item.*'                          => 'nullable|array',
            'evidence_item.*.*'                        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:102400',

            // Section C
            'temuan_sub_component'                     => 'nullable|array',
            'temuan_sub_component.*.sub_component'     => 'required|string|min:1',
            'temuan_sub_component.*.temuan'            => 'required|string',
            'temuan_sub_component.*.condition'         => 'required|string|in:OK,BAD',
            'temuan_sub_component.*.recommendation'    => 'nullable|string|max:255',
            'temuan_sub_component.*.action'            => 'nullable|string|in:CHECK,INSTALL,REPLACE,MONITORING,REPAIR',
            'temuan_sub_component.*.evidence'          => 'nullable|array',
            'temuan_sub_component.*.evidence.*'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:102400',
        ]);

        // 2. Tentukan Model Unit
        $modelUnit = $request->model_unit === 'Other'
                   ? $request->other_model_unit
                   : $request->model_unit;

        // 3. Upload evidence per item Section A/B
        $evidencePerItem = [];
        $evidenceItems = $request->file('evidence_item', []);
        foreach ($evidenceItems as $idx => $files) {
            if (is_array($files)) {
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('evidence_files_inspection', 'public');
                        $url = Storage::url($path);
                        $evidencePerItem[$idx][] = $url;
                    }
                }
            }
        }

        // 4. Siapkan judul dan data inspection A+B
        $inspectionTitles = [
            "Engine Oil level", "Radiator Coolant Level", "Final Drive Oil Level",
            "Differential Oil Level", "Transmission & Steering Oil Level",
            "Hydraulic Oil Level", "Fuel Level", "PTO Oil", "Brake Oil", "Compressor Oil Level",
            "Check Leaking", "Check tighting Bolt", "Check Abnormal Noise",
            "Check Abnormal Temperature", "Check Abnormal Smoke/Smell", "Check Abnormal Vibration",
            "Check Abnormal Bending/Crack", "Check Abnormal Tention", "Check Abnormal Pressure",
            "Check Error Vault Code"
        ];
        $conditions     = $request->input('condition', []);
        $recommendations= $request->input('recommendation', []);
        $inspectionData = [];

        foreach ($inspectionTitles as $i => $title) {
            $index      = $i < 10 ? "a_$i" : "b_" . ($i - 10);
            $cond       = $conditions[$i]    ?? 'OK';
            $rec        = $recommendations[$i] ?? '';
            $evidences  = $evidencePerItem[$index] ?? [];
            $statusCase = $cond === 'OK' ? 'close' : 'open';
            $action     = $request->input("action.{$i}", 'CHECK');


            $inspectionData[] = json_encode([
                'condition'      => $cond,
                'recommendation' => $rec,
                'evidence'       => $evidences,
                'action'         => $action,
                'statusCase'     => $statusCase,
            ], JSON_UNESCAPED_UNICODE);
        }

        // 5. Proses TEMUAN Sub‑component (Section C)
        $temuanInput    = $request->input('temuan_sub_component', []);
        $temuanGrouped  = [];
        $temuanHeaders  = [];
        $temuanData     = [];

        foreach ($temuanInput as $idx => $item) {
            $sub        = trim($item['sub_component']);
            $statusCase = $item['condition'] === 'OK' ? 'close' : 'open';

            // Upload file evidence untuk temuan ini
            $urls = [];
            if ($request->hasFile("temuan_sub_component.{$idx}.evidence")) {
                foreach ($request->file("temuan_sub_component.{$idx}.evidence") as $f) {
                    if ($f->isValid()) {
                        $p = $f->store('evidence_files_inspection', 'public');
                        $urls[] = asset("storage/{$p}");
                    }
                }
            }

            $temuanGrouped[$sub][] = [
                'sub_component' => $sub,
                'temuan'        => $item['temuan'],
                'condition'     => $item['condition'],
                'recommendation'=> $item['recommendation'] ?? '',
                'evidence'      => $urls,
                'action'        => $item['action'] ?? 'CHECK',
                'statusCase'    => $statusCase,
            ];
        }

        // Susun header & JSON per sub_component
        foreach ($temuanGrouped as $sub => $entries) {
            $temuanHeaders[]       = $sub;
            $temuanData[$sub]      = json_encode($entries, JSON_UNESCAPED_UNICODE);
        }

        // Pastikan semua sub‑component Section C ter-cover
        $allSectionC = [
            "AC SYSTEM", "BRAKE SYSTEM", "DIFFERENTIAL & FINAL DRAVE",
            "ELECTRICAL SYSTEM", "ENGINE", "GENERAL ( ACCESSORIES, CABIN, ETC )",
            "HYDRAULIC SYSTEM", "IT SYSTEM", "MAIN FRAME / CHASSIS / VASSEL",
            "PERIODICAL SERVICE", "PNEUMATIC SYSTEM", "PROBLEM SDT", "PROBLEM TYRE SDT",
            "STEERING SYSTEM", "TRANSMISSION SYSTEM", "TYRE", "UNDERCARRIAGE"
        ];
        foreach ($allSectionC as $sub) {
            if (! in_array($sub, $temuanHeaders)) {
                $temuanHeaders[]  = $sub;
                $temuanData[$sub] = '';
            }
        }

        // 6. KONEKSI dan KIRIM ke Google Sheets
        $client        = new Client();
        $client->setApplicationName('Laravel Service Inspection');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
        $service       = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
        $sheetRange    = 'Sheet1!A1';

        // a) ambil header
        $resp    = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!A1:ZZ');
        $rows    = $resp->getValues();
        $headers = $rows[0] ?? [];

        $fixed = ['ID','Timestamp','Username','Tanggal Service','Nama Mekanik','Waktu Unit Masuk Breakdown','NIK','Section','Supervisor','Model Unit','CN Unit','Hour Meter','Status','Approved By','Note'];
        // b) inisialisasi header jika belum ada
        if (empty($headers) || $headers[0] !== 'ID') {
            $fixed    = ['ID','Timestamp','Username','Tanggal Service','Nama Mekanik','Waktu Unit Masuk Breakdown','NIK','Section','Supervisor','Model Unit','CN Unit','Hour Meter','Status','Approved By','Note'];
            $allHeads = array_merge($fixed, $inspectionTitles, $temuanHeaders);
            $service->spreadsheets_values->append($spreadsheetId, $sheetRange, new ValueRange(['values'=>[$allHeads]]), ['valueInputOption'=>'RAW']);
            $headers = $allHeads;
        }

        // c) update header temuan baru
        $diff = array_diff($temuanHeaders, $headers);
        if (! empty($diff)) {
            $newHeads = array_merge($headers, array_values($diff));
            $service->spreadsheets_values->update($spreadsheetId, 'Sheet1!A1', new ValueRange(['values'=>[$newHeads]]), ['valueInputOption'=>'RAW']);
            $headers = $newHeads;
        }

        // d) generate new ID
        $rows = $resp->getValues();
        $rows = is_array($rows) ? $rows : [];

        $existingIds = [];

        foreach ($rows as $i => $r) {
            if ($i === 0) continue;
            $existingIds[] = isset($r[0]) ? (int)$r[0] : 0;
        }

        $newId = empty($existingIds) ? 1 : max($existingIds) + 1;

        // e) bangun base row
        $baseRow = [
            $newId,
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
            // Semua evidence Section A/B jadi satu kolom (opsional)
            'Pending','',''
        ];



        // f) bangun final row sesuai headers
        $finalRow = [];
        foreach ($headers as $h) {
            if (in_array($h, $fixed)) {
                $idx = array_search($h, $fixed);
                $finalRow[] = $baseRow[$idx] ?? '';
            } elseif (in_array($h, $inspectionTitles)) {
                $idx = array_search($h, $inspectionTitles);
                $finalRow[] = $inspectionData[$idx] ?? '';
            } else {
                $finalRow[] = $temuanData[$h] ?? '';
            }
        }

        // g) append ke sheet
        $service->spreadsheets_values->append(
            $spreadsheetId,
            $sheetRange,
            new ValueRange(['values'=>[$finalRow]]),
            ['valueInputOption'=>'RAW']
        );

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
            $range = 'Sheet1!A2:AZ'; // Ambil semua data dari baris 2 ke bawah
    
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
    
            $targetRow = null;
    
            foreach ($values as $index => $row) {
                $currentId = $row[0] ?? null;
                $status = strtolower($row[12] ?? ''); // Kolom Status ada di kolom 14 (index 13)
    
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
        $client->setApplicationName('Inspection Service Form');
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
                in_array($data['Status'], ['Rejected', 'Pending'])
            ) {
                // Format waktu agar cocok dengan <input type="time">
                try {
                    $data['Waktu Unit Masuk Breakdown'] = Carbon::parse($data['Waktu Unit Masuk Breakdown'])->format('H:i');
                } catch (\Exception $e) {
                    $data['Waktu Unit Masuk Breakdown'] = '';
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
        // 1. Validasi input (sama dengan store)
        $request->validate([
            'tanggal_service'                          => 'required|date',
            'nama_mekanik'                             => 'required|string|max:255',
            'waktu_serah_terima'                       => 'required|string',
            'nik'                                      => 'required|string|max:50',
            'section'                                  => 'required|string|in:Section DT,Section SDT,Section A2B',
            'supervisor'                               => 'required|string|in:Ari Handoko,Teo Hermansyah,Herri Setiawan,Andrian',
            'model_unit'                               => 'required|string',
            'other_model_unit'                         => 'nullable|string|max:255',
            'cn_unit'                                  => 'required|string|max:100',
            'hour_meter'                               => 'required|integer|min:0',
    
            'condition'                                => 'nullable|array',
            'condition.*'                              => 'nullable|string|in:OK,BAD',
            'recommendation'                           => 'nullable|array',
            'recommendation.*'                         => 'nullable|string|max:255',
            'action'                                   => 'nullable|array',
            'action.*'                                 => 'nullable|string|in:CHECK,INSTALL,REPLACE,MONITORING,REPAIR', 
            'evidence_item'                            => 'nullable|array',
            'evidence_item.*'                          => 'nullable|array',
            'evidence_item.*.*'                        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:102400',
    
            'temuan_sub_component'                     => 'nullable|array',
            'temuan_sub_component.*.sub_component'     => 'required|string|min:1',
            'temuan_sub_component.*.temuan'            => 'required|string',
            'temuan_sub_component.*.condition'         => 'required|string|in:OK,BAD',
            'temuan_sub_component.*.recommendation'    => 'nullable|string|max:255',
            'temuan_sub_component.*.action'            => 'nullable|string|in:CHECK,INSTALL,REPLACE,MONITORING,REPAIR',
            'temuan_sub_component.*.evidence'          => 'nullable|array',
            'temuan_sub_component.*.evidence.*'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,mp4,avi|max:102400',
        ]);
    
        // 2. Tentukan model unit
        $modelUnit = $request->model_unit === 'Other' ? $request->other_model_unit : $request->model_unit;
    
        // 3. Upload evidence per item Section A/B
        $evidencePerItem = [];
        $evidenceItems = $request->file('evidence_item', []);
        foreach ($evidenceItems as $idx => $files) {
            foreach ($files ?? [] as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('evidence_files_inspection', 'public');
                    $url = Storage::url($path);
                    $evidencePerItem[$idx][] = $url;
                }
            }
        }
    
        // 4. Judul inspection dan data A/B
        $inspectionTitles = [ /* ... sama seperti fungsi store ... */ 
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
    
        foreach ($inspectionTitles as $i => $title) {
            $index = $i < 10 ? "a_$i" : "b_" . ($i - 10);
            $cond = $conditions[$i] ?? 'OK';
            $rec = $recommendations[$i] ?? '';
            $evidences = $evidencePerItem[$index] ?? [];
            $statusCase = $cond === 'OK' ? 'close' : 'open';
            $action     = $request->input("action.{$i}", 'CHECK');
    
            $inspectionData[] = json_encode([
                'condition' => $cond,
                'recommendation' => $rec,
                'evidence' => $evidences,
                'action' => $action,
                'statusCase' => $statusCase,
            ], JSON_UNESCAPED_UNICODE);
        }
    
        // 5. Proses temuan Section C
        $temuanInput = $request->input('temuan_sub_component', []);
        $temuanGrouped = [];
        $temuanHeaders = [];
        $temuanData = [];
    
        foreach ($temuanInput as $idx => $item) {
            $sub = trim($item['sub_component']);
            $statusCase = $item['condition'] === 'OK' ? 'close' : 'open';
    
            $urls = [];
            if ($request->hasFile("temuan_sub_component.{$idx}.evidence")) {
                foreach ($request->file("temuan_sub_component.{$idx}.evidence") as $f) {
                    if ($f->isValid()) {
                        $p = $f->store('evidence_files_inspection', 'public');
                        $urls[] = asset("storage/{$p}");
                    }
                }
            }
    
            $temuanGrouped[$sub][] = [
                'sub_component' => $sub,
                'temuan' => $item['temuan'],
                'condition' => $item['condition'],
                'recommendation' => $item['recommendation'] ?? '',
                'evidence' => $urls,
                'action' => $item['action'] ?? 'CHECK',
                'statusCase' => $statusCase,
            ];
        }
    
        foreach ($temuanGrouped as $sub => $entries) {
            $temuanHeaders[] = $sub;
            $temuanData[$sub] = json_encode($entries, JSON_UNESCAPED_UNICODE);
        }
    
        $allSectionC = [/* ... sama seperti store ... */ 
            "AC SYSTEM", "BRAKE SYSTEM", "DIFFERENTIAL & FINAL DRAVE",
            "ELECTRICAL SYSTEM", "ENGINE", "GENERAL ( ACCESSORIES, CABIN, ETC )",
            "HYDRAULIC SYSTEM", "IT SYSTEM", "MAIN FRAME / CHASSIS / VASSEL",
            "PERIODICAL SERVICE", "PNEUMATIC SYSTEM", "PROBLEM SDT", "PROBLEM TYRE SDT",
            "STEERING SYSTEM", "TRANSMISSION SYSTEM", "TYRE", "UNDERCARRIAGE"
        ];
        foreach ($allSectionC as $sub) {
            if (!in_array($sub, $temuanHeaders)) {
                $temuanHeaders[] = $sub;
                $temuanData[$sub] = '';
            }
        }
    
        // 6. Kirim ke Google Sheet
        $client = new Client();
        $client->setApplicationName('Laravel Service Inspection');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
        $service = new Sheets($client);
        $spreadsheetId = '1BeBtZZNZBEQBfZHV28Jq_KWRhqBrSuRIBJIrLfNeFfY';
    
        $resp = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!A1:ZZ');
        $rows = $resp->getValues();
        $headers = $rows[0] ?? [];
    
        $fixed = ['ID','Timestamp','Username','Tanggal Service','Nama Mekanik','Waktu Unit Masuk Breakdown','NIK','Section','Supervisor','Model Unit','CN Unit','Hour Meter','Status','Approved By','Note'];
    
        // Update header jika ada temuan baru
        $diff = array_diff($temuanHeaders, $headers);
        if (! empty($diff)) {
            $headers = array_merge($headers, array_values($diff));
            $service->spreadsheets_values->update($spreadsheetId, 'Sheet1!A1', new ValueRange(['values'=>[$headers]]), ['valueInputOption'=>'RAW']);
        }
    
        // Temukan baris yang akan di-update berdasarkan ID
        $targetRowIndex = null;
        foreach ($rows as $i => $row) {
            if (isset($row[0]) && (string)$row[0] === (string)$id) {
                $targetRowIndex = $i + 1; // baris di Google Sheets dimulai dari 1
                break;
            }
        }
    
        if (is_null($targetRowIndex)) {
            return back()->with('error', 'Data tidak ditemukan di Google Sheets.');
        }

        // Ambil data lama dari baris yang akan diupdate
            $oldRow = $rows[$targetRowIndex - 1] ?? null;

            if ($oldRow) {
                foreach ($headers as $colIndex => $headerName) {
                    // Hapus evidence lama dari Section A/B
                    if (in_array($headerName, $inspectionTitles)) {
                        $data = $oldRow[$colIndex] ?? null;
                        if ($data) {
                            $decoded = json_decode($data, true);
                            if (is_array($decoded) && isset($decoded['evidence'])) {
                                foreach ($decoded['evidence'] as $url) {
                                    $relativePath = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
                                    Storage::disk('public')->delete($relativePath);
                                }
                            }
                        }
                    }

                    // Hapus evidence lama dari Section C (temuan)
                    elseif (!in_array($headerName, $fixed) && isset($oldRow[$colIndex])) {
                        $data = $oldRow[$colIndex];
                        $decoded = json_decode($data, true);
                        if (is_array($decoded)) {
                            foreach ($decoded as $temuanItem) {
                                if (isset($temuanItem['evidence']) && is_array($temuanItem['evidence'])) {
                                    foreach ($temuanItem['evidence'] as $url) {
                                        $relativePath = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
                                        Storage::disk('public')->delete($relativePath);
                                    }
                                }
                            }
                        }
                    }
                }
            }

    
        // Siapkan baseRow
        $baseRow = [
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
            'Pending', '', ''
        ];
    
        // Bangun final row sesuai urutan header
        $finalRow = [];
        foreach ($headers as $h) {
            if (in_array($h, $fixed)) {
                $idx = array_search($h, $fixed);
                $finalRow[] = $baseRow[$idx] ?? '';
            } elseif (in_array($h, $inspectionTitles)) {
                $idx = array_search($h, $inspectionTitles);
                $finalRow[] = $inspectionData[$idx] ?? '';
            } else {
                $finalRow[] = $temuanData[$h] ?? '';
            }
        }
    
        // Update baris di sheet
        $range = "Sheet1!A{$targetRowIndex}";
        $service->spreadsheets_values->update(
            $spreadsheetId,
            $range,
            new ValueRange(['values' => [$finalRow]]),
            ['valueInputOption' => 'RAW']
        );
    
        return back()->with('success', 'Data berhasil diperbarui dan dikirim ulang ke Google Sheets.');
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
