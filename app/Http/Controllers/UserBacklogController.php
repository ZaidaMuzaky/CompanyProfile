<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserBacklogController extends Controller
{
    public function form()
    {
        return view('user.backlog.form');
    }

    public function show()
    {
        return view('user.backlog.show');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'tanggal_service' => 'required|date',
            'nama_mekanik' => 'required|string',
            'waktu_serah_terima' => 'required',
            'nik' => 'required|string',
            'section' => 'required|string',
            'supervisor' => 'required|string',
            'model_unit' => 'required|string',
            'cn_unit' => 'required|string',
            'periodical_service' => 'required|string',
            'hour_meter' => 'required|numeric',
            'temuan' => 'array',
            'evidence.*' => 'file|max:102400'
        ]);
    
        $temuanFields = [
            'ENGINE', 'ELECTRICAL SYSTEM', 'GENERAL (ACCESSORIES, CABIN, ETC)', 'AC SYSTEM',
            'BRAKE SYSTEM', 'DIFFERENTIAL & FINAL DRIVE', 'HYDRAULIC SYSTEM',
            'MAIN FRAME / CHASSIS / VESSEL', 'PERIODICAL SERVICE', 'PREVENTIVE MAINTENANCE',
            'PREDICTIVE MAINTENANCE', 'IT SYSTEM', 'PNEUMATIC SYSTEM',
            'TRANSMISSION SYSTEM', 'STEERING SYSTEM', 'PROBLEM SDT', 'PROBLEM TYRE SDT',
            'UNDERCARRIAGE', 'WORK EQUIPMENT', 'TYRE', 'LAINNYA'
        ];
    
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
        $isSheetEmpty = empty($values);
    
        // ✅ Cari ID terbesar, bukan jumlah baris
        $maxId = 0;
        if (is_array($values)) {
            foreach ($values as $row) {
                if (isset($row[0]) && is_numeric($row[0])) {
                    $maxId = max($maxId, intval($row[0]));
                }
            }
        }
        
        $id = (string)($maxId + 1);
    
        // Header kolom
        $headers = [
            'ID',
            'Timestamp',
            'Username',
            'Email',
            'Tanggal Service',
            'Nama Mekanik',
            'Waktu Serah Terima',
            'NIK',
            'Section',
            'Supervisor',
            'Model Unit',
            'CN Unit',
            'Periodical Service',
            'Hour Meter'
        ];
        $headers = array_merge($headers, $temuanFields);
        $headers[] = 'Evidence';
        $headers[] = 'Status';
        $headers[] = 'Approved By';
        $headers[] = 'Note';
        $headers[] = 'Status Case'; 
        $headers[] = 'Note Case';   
    
        // Temuan
        $temuanData = [];
        foreach ($temuanFields as $field) {
            $temuanData[] = strtoupper($request->temuan[$field] ?? '');
        }
    
        // Upload evidence
        $evidenceUrls = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('evidence', 'public');
                $evidenceUrls[] = asset('storage/' . $path);
            }
        }
    
        // Data baris
        $row = array_merge([
            $id,
            now()->toDateTimeString(),
            Auth::user()->username ?? '-',
            $request->email,
            $request->tanggal_service,
            $request->nama_mekanik,
            $request->waktu_serah_terima,
            $request->nik,
            $request->section,
            $request->supervisor,
            $request->model_unit === 'Other' ? $request->other_model_unit : $request->model_unit,
            $request->cn_unit,
            $request->periodical_service,
            $request->hour_meter,
        ], $temuanData, [
            implode(', ', $evidenceUrls),
            'Pending',
            '', // Approved By
            '',  // Note
            'Open', // Status Case
            '', // Note Case
        ]);
    
        // Tulis header jika sheet kosong
        if ($isSheetEmpty) {
            $service->spreadsheets_values->append($spreadsheetId, $sheetName, new Sheets\ValueRange([
                'values' => [$headers]
            ]), [
                'valueInputOption' => 'USER_ENTERED'
            ]);
        }
    
        // Tambahkan data ke sheet
        $service->spreadsheets_values->append($spreadsheetId, $sheetName, new Sheets\ValueRange([
            'values' => [$row]
        ]), [
            'valueInputOption' => 'USER_ENTERED'
        ]);
    
        return back()->with('success', 'Form berhasil dikirim.');
    }
    

    // status form
    public function status()
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
    
                if (($data['Username'] ?? '') === (auth()->user()->username ?? '')) {
                    $userForms[] = $data;
                }
            }
        }
    
        return view('user.backlog.show', compact('userForms', 'headers'));
    }
// dellete form user
public function destroy($id)
{
    try {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);

        $service = new Sheets($client);
        $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
        $range = 'Sheet1!A2:AO'; // Sesuaikan jika perlu

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $targetRow = null;

        foreach ($values as $index => $row) {
            // Pastikan ID berada di kolom pertama dan status di kolom AK (indeks 36)
            if (isset($row[0]) && $row[0] == $id) {
                if (isset($row[36]) && strtolower($row[36]) === 'pending') {
                    $targetRow = $index + 2; // Baris sesungguhnya
                }
                break;
            }
        }

        if ($targetRow) {
            // Jika ditemukan, hapus baris tersebut
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

            return redirect()->route('user.backlog.show')->with('success', 'Formulir berhasil dihapus.');
        } else {
            return redirect()->route('user.backlog.show')->with('error', 'Formulir tidak ditemukan atau status bukan Pending.');
        }
    } catch (\Exception $e) {
        return redirect()->route('user.backlog.show')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}




    // resubmit form user
    public function edit($id)
{
    // Set up Google Sheets client
    $client = new Client();
    $client->setApplicationName('Backlog Service Form');
    $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->setAccessType('offline');

    $service = new Sheets($client);
    $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
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
            break;
        }
    }

    if (!$form) {
        return abort(403, 'Data tidak ditemukan atau tidak dapat diedit.');
    }

    return view('user.backlog.edit', compact('form'));
}

    
    public function resubmit(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'model_unit' => 'required',
            'other_model_unit' => $request->model_unit === 'Other' ? 'required|string' : 'nullable',
            'cn_unit' => 'required|string',
        ]);
    
        // Inisialisasi Google Client
        $client = new Client();
        $client->setApplicationName('Backlog Service Form');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
    
        $service = new Sheets($client);
        $spreadsheetId = '1GGgBGiWCIoWjbwM6LLllcUUqdk4bAsHn94gdZz8uHAA';
        $sheetName = 'Sheet1';
    
        // Ambil data dari spreadsheet
        $response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();
    
        $headers = $values[0];
        $rowIndex = null;
    
        foreach ($values as $index => $row) {
            if ($index === 0) continue;
    
            $data = array_combine($headers, array_pad($row, count($headers), ''));
            if ($data['ID'] == $id && $data['Username'] == Auth::user()->username && $data['Status'] === 'Rejected') {
                $rowIndex = $index;
                break;
            }
        }
    
        if ($rowIndex === null) {
            return back()->with('error', 'Data tidak ditemukan atau tidak dapat diubah.');
        }
    
        // Ambil index kolom
        $modelUnitIndex = array_search('Model Unit', $headers);
        $cnUnitIndex = array_search('CN Unit', $headers);
        $statusIndex = array_search('Status', $headers);
        $noteIndex = array_search('Note', $headers);
        $approvedByIndex = array_search('Approved By', $headers);
    
        if ($modelUnitIndex === false || $cnUnitIndex === false || $statusIndex === false || $noteIndex === false || $approvedByIndex === false) {
            return back()->with('error', 'Kolom tidak ditemukan di spreadsheet.');
        }
    
        // Gunakan value dari other_model_unit jika model_unit adalah "Other"
        $modelUnitFinal = $request->input('model_unit') === 'Other'
            ? $request->input('other_model_unit')
            : $request->input('model_unit');
    
        $cnUnitFinal = $request->input('cn_unit');
    
        // Update nilai-nilai berdasarkan input form
        $values[$rowIndex][$modelUnitIndex] = $modelUnitFinal;
        $values[$rowIndex][$cnUnitIndex] = $cnUnitFinal;
        $values[$rowIndex][$statusIndex] = 'Pending';
        $values[$rowIndex][$noteIndex] = '';
        $values[$rowIndex][$approvedByIndex] = '';
    
        // Siapkan data update
        $body = new \Google\Service\Sheets\ValueRange([
            'values' => [
                array_map(function ($value) {
                    return $value ?? '';
                }, $values[$rowIndex])
            ]
        ]);
    
        $range = $sheetName . '!A' . ($rowIndex + 1);
    
        try {
            $service->spreadsheets_values->update($spreadsheetId, $range, $body, [
                'valueInputOption' => 'USER_ENTERED'
            ]);
        } catch (\Google\Service\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data ke Google Sheets: ' . $e->getMessage());
        }
    
        return redirect()->route('user.backlog.show')->with('success', 'Form berhasil dikirim ulang.');
    }
    




}
