<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Player Dashboard Controller
 * Handles player dashboard display (view-only)
 */
class DashboardController extends Controller
{
    /**
     * Display player dashboard
     * Note: Players cannot self-register, they are created by schools
     * This dashboard shows their profile information
     */
    public function index()
    {
        // For now, players don't have their own user accounts
        // They are managed by schools
        // This could be expanded later if players get their own accounts

        return view('player.dashboard');
    }
}
