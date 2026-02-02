<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Player;
use Illuminate\Http\Request;

/**
 * Home Controller
 * Handles the public home page
 */
class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get some statistics for the home page
        $totalSchools = School::where('status', School::STATUS_APPROVED)->count();
        $totalPlayers = Player::count();

        return view('website.home.index', compact('totalSchools', 'totalPlayers'));
    }
}
