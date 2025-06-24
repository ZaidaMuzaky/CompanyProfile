<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Sheets;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalFolders = Folder::whereNull('parent_id')->count();
        $totalSubfolders = Folder::whereNotNull('parent_id')->count();
        $totalFiles = File::count();

        $perPage = $request->input('per_page', 5); // Default to 5 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        // Fetch all online and offline users
        $onlineUsers = User::where('is_online', true)->get();
        $offlineUsers = User::where('is_online', false)->get();

        // Combine online and offline users, prioritizing online users
        $allUsers = $onlineUsers->concat($offlineUsers);

        // Calculate the range of users to display
        $totalUsers = $allUsers->count();
        $start = ($currentPage - 1) * $perPage;
        $displayedUsers = $allUsers->slice($start, $perPage)->values(); // Ensure consistent indexing

        $totalOnlineUsers = $onlineUsers->count();
        $totalOfflineUsers = $offlineUsers->count();

        // Pagination for "Users Logged In Today"
        $perPageLoggedIn = $request->input('per_page_logged_in', 5); // Default to 5 items per page
        $currentLoggedInPage = $request->input('logged_in_page', 1); // Default to page 1

        $today = Carbon::today();
        $searchLoggedIn = $request->input('search_logged_in', ''); // Get search query
        $loggedInTodayUsers = User::whereDate('last_login_at', $today)
            ->when($searchLoggedIn, function ($query, $searchLoggedIn) {
                return $query->where('username', 'like', '%' . $searchLoggedIn . '%');
            })
            ->get();
        $totalLoggedInTodayUsers = $loggedInTodayUsers->count();
        $startLoggedIn = ($currentLoggedInPage - 1) * $perPageLoggedIn;
        $displayedLoggedInTodayUsers = $loggedInTodayUsers->slice($startLoggedIn, $perPageLoggedIn)->values(); // Ensure consistent indexing

        // Calculate login recap
        $weeklyLogins = User::whereBetween('last_login_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthlyLogins = User::whereBetween('last_login_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
        $yearlyLogins = User::whereBetween('last_login_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();

        // Calculate visitor recap
        $weeklyVisitors = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthlyVisitors = User::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
        $yearlyVisitors = User::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();


        // CHART DATA INSPECTION
        $range = $request->input('range', 'all'); // bisa '7', '30', atau 'all'

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
    
        $headers = [];
        $statusCount = [
            'Approved' => 0,
            'Pending' => 0,
            'Rejected' => 0,
        ];
        $statusCaseCount = [
            'open' => 0,
            'close' => 0,
        ];
        $actionCount = [
            'CHECK' => 0,
            'INSTALL' => 0,
            'REPLACE' => 0,
            'MONITORING' => 0,
            'REPAIR' => 0,
        ];
    
        if (!empty($values)) {
            $headers = array_map('trim', $values[0]);
    
            for ($i = 1; $i < count($values); $i++) {
                $row = $values[$i];
                $data = array_combine($headers, array_pad($row, count($headers), ''));
            
                // ✅ Filter berdasarkan tanggal jika dipilih range
                $tanggalStr = $data['Tanggal'] ?? null;
                if (!$tanggalStr) continue;
            
                try {
                    $tanggal = Carbon::parse($tanggalStr);
                } catch (\Exception $e) {
                    continue;
                }
            
                if ($range === '7' && $tanggal->lt(Carbon::now()->subDays(7))) continue;
                if ($range === '30' && $tanggal->lt(Carbon::now()->subDays(30))) continue;
            
                // ✅ Hitung Status (Approved, Pending, Rejected)
                $status = $data['Status'] ?? 'Pending';
                if (isset($statusCount[$status])) {
                    $statusCount[$status]++;
                }
    
                // ✅ Hitung dari kolom inspeksi JSON
    foreach ($headers as $header) {
        if (in_array($header, [
            'Engine Oil level', 'Radiator Coolant Level', 'Final Drive Oil Level',
            'Differential Oil Level', 'Transmission & Steering Oil Level', 'Hydraulic Oil Level',
            'Fuel Level', 'PTO Oil', 'Brake Oil', 'Compressor Oil Level', 'Check Leaking',
            'Check tighting Bolt', 'Check Abnormal Noise', 'Check Abnormal Temperature',
            'Check Abnormal Smoke/Smell', 'Check Abnormal Vibration', 'Check Abnormal Bending/Crack',
            'Check Abnormal Tention', 'Check Abnormal Pressure', 'Check Error Vault Code',
            'AC SYSTEM', 'BRAKE SYSTEM', 'DIFFERENTIAL & FINAL DRAVE', 'ELECTRICAL SYSTEM', 'ENGINE',
            'GENERAL ( ACCESSORIES, CABIN, ETC )', 'HYDRAULIC SYSTEM', 'IT SYSTEM',
            'MAIN FRAME / CHASSIS / VASSEL', 'PERIODICAL SERVICE', 'PNEUMATIC SYSTEM',
            'PROBLEM SDT', 'PROBLEM TYRE SDT', 'STEERING SYSTEM', 'TRANSMISSION SYSTEM',
            'TYRE', 'UNDERCARRIAGE'
        ])) {
            $json = json_decode($data[$header] ?? '', true);
            if (is_array($json)) {
                if (array_keys($json) === range(0, count($json) - 1)) {
                    foreach ($json as $item) {
                        $statusCase = strtolower($item['statusCase'] ?? '');
                        $action = strtoupper($item['action'] ?? '');

                        if (isset($statusCaseCount[$statusCase])) $statusCaseCount[$statusCase]++;
                        if (isset($actionCount[$action])) $actionCount[$action]++;
                    }
                } else {
                    $statusCase = strtolower($json['statusCase'] ?? '');
                    $action = strtoupper($json['action'] ?? '');

                    if (isset($statusCaseCount[$statusCase])) $statusCaseCount[$statusCase]++;
                    if (isset($actionCount[$action])) $actionCount[$action]++;
                }
            }
        }
    }
            }
        }   

        return view('dashboard', compact(
            'totalFolders',
            'totalSubfolders',
            'totalFiles',
            'displayedUsers',
            'totalUsers',
            'totalOnlineUsers',
            'totalOfflineUsers',
            'perPage',
            'currentPage',
            'displayedLoggedInTodayUsers',
            'totalLoggedInTodayUsers',
            'perPageLoggedIn',
            'currentLoggedInPage',
            'weeklyLogins',
            'monthlyLogins',
            'yearlyLogins',
            'weeklyVisitors',
            'monthlyVisitors',
            'yearlyVisitors',
            'statusCount', 'statusCaseCount', 'actionCount', 'range'
        ));
    }
    
}
