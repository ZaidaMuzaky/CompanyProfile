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
}
