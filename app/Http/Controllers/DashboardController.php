<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;

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
        $loggedInTodayUsers = User::whereDate('last_login_at', $today)->get();
        $totalLoggedInTodayUsers = $loggedInTodayUsers->count();
        $startLoggedIn = ($currentLoggedInPage - 1) * $perPageLoggedIn;
        $displayedLoggedInTodayUsers = $loggedInTodayUsers->slice($startLoggedIn, $perPageLoggedIn)->values(); // Ensure consistent indexing

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
            'currentLoggedInPage'
        ));
    }
}
