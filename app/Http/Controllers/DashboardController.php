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

        // Fetch online and offline users
        $onlineUsers = User::where('is_online', true)->take($perPage)->get();
        $remainingSlots = max(0, $perPage - $onlineUsers->count());
        $offlineUsers = User::where('is_online', false)->take($remainingSlots)->get();

        $totalOnlineUsers = User::where('is_online', true)->count();
        $totalOfflineUsers = User::where('is_online', false)->count();

        $today = Carbon::today();
        $loggedInTodayUsers = User::whereDate('last_login_at', $today)->get();

        return view('dashboard', compact(
            'totalFolders',
            'totalSubfolders',
            'totalFiles',
            'onlineUsers',
            'offlineUsers',
            'totalOnlineUsers',
            'totalOfflineUsers',
            'perPage',
            'loggedInTodayUsers'
        ));
    }
}
