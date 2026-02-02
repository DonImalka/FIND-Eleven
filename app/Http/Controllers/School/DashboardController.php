<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * School Dashboard Controller
 * Handles school dashboard display
 */
class DashboardController extends Controller
{
    /**
     * Display school dashboard with profile and player statistics
     */
    public function index()
    {
        $user = auth()->user();
        $school = $user->school;
        
        // Get player statistics for this school
        $totalPlayers = $school->players()->count();
        $playersByCategory = $school->players()
            ->selectRaw('age_category, count(*) as count')
            ->groupBy('age_category')
            ->pluck('count', 'age_category')
            ->toArray();

        // Get recent players
        $recentPlayers = $school->players()
            ->latest()
            ->take(5)
            ->get();

        return view('school.dashboard', compact(
            'school',
            'totalPlayers',
            'playersByCategory',
            'recentPlayers'
        ));
    }
}
