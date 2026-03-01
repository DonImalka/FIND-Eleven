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
     */
    public function index()
    {
        $user = auth()->user();
        $player = $user->player;

        $helpPostCount = 0;
        $pendingCount = 0;
        $approvedCount = 0;

        if ($player) {
            $helpPostCount = $player->helpPosts()->count();
            $pendingCount = $player->helpPosts()->where('status', 'pending')->count();
            $approvedCount = $player->helpPosts()->where('status', 'approved')->count();
        }

        return view('player.dashboard', compact('player', 'helpPostCount', 'pendingCount', 'approvedCount'));
    }
}
