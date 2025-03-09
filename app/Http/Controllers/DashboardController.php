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
        $onlineUsers = User::where('is_online', true)->paginate($perPage, ['*'], 'online_page');
        $offlineUsers = User::where('is_online', false)->paginate($perPage, ['*'], 'offline_page');
        $totalOnlineUsers = $onlineUsers->total();
        $totalOfflineUsers = $offlineUsers->total();

        $today = Carbon::today();
        $loggedInTodayUsers = User::whereDate('last_login_at', $today)->get();

        return view('dashboard', compact('totalFolders', 'totalSubfolders', 'totalFiles', 'onlineUsers', 'offlineUsers', 'totalOnlineUsers', 'totalOfflineUsers', 'perPage', 'loggedInTodayUsers'));
    }
}
