<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Player;
use Illuminate\Http\Request;

/**
 * Admin Dashboard Controller
 * Handles admin dashboard and school/player overview
 */
class DashboardController extends Controller
{
    /**
     * Display admin dashboard with pending schools and statistics
     */
    public function index()
    {
        // Get counts for dashboard statistics
        $pendingSchoolsCount = School::where('status', School::STATUS_PENDING)->count();
        $approvedSchoolsCount = School::where('status', School::STATUS_APPROVED)->count();
        $rejectedSchoolsCount = School::where('status', School::STATUS_REJECTED)->count();
        $totalPlayersCount = Player::count();

        // Get recent pending schools
        $pendingSchools = School::with('user')
            ->where('status', School::STATUS_PENDING)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'pendingSchoolsCount',
            'approvedSchoolsCount',
            'rejectedSchoolsCount',
            'totalPlayersCount',
            'pendingSchools'
        ));
    }
}
