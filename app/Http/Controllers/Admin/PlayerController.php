<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\School;
use Illuminate\Http\Request;

/**
 * Admin Player Controller
 * Handles viewing all players (read-only for admin)
 */
class PlayerController extends Controller
{
    /**
     * Display all players with filtering options
     */
    public function index(Request $request)
    {
        $query = Player::with('school')->latest();

        // Filter by school if provided
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        // Filter by age category if provided
        if ($request->has('age_category') && $request->age_category) {
            $query->where('age_category', $request->age_category);
        }

        // Filter by player category if provided
        if ($request->has('player_category') && $request->player_category) {
            $query->where('player_category', $request->player_category);
        }

        // Group players by school
        $allPlayers = $query->get();
        $playersBySchool = $allPlayers->groupBy('school_id');

        $schools = School::where('status', School::STATUS_APPROVED)->orderBy('school_name')->get();
        $ageCategories = Player::getAgeCategories();
        $playerCategories = Player::getPlayerCategories(true);
        $totalPlayers = $allPlayers->count();

        return view('admin.players.index', compact(
            'playersBySchool',
            'schools',
            'ageCategories',
            'playerCategories',
            'totalPlayers'
        ));
    }

    /**
     * Show player details (read-only)
     */
    public function show(Player $player)
    {
        $player->load('school');
        
        return view('admin.players.show', compact('player'));
    }
}
