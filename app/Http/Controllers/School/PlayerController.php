<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

/**
 * School Player Controller
 * Handles CRUD operations for players belonging to the school
 */
class PlayerController extends Controller
{
    /**
     * Display all players for this school
     */
    public function index(Request $request)
    {
        $school = auth()->user()->school;
        
        $query = $school->players()->latest();

        // Filter by age category if provided
        if ($request->has('age_category') && $request->age_category) {
            $query->where('age_category', $request->age_category);
        }

        // Filter by player category if provided
        if ($request->has('player_category') && $request->player_category) {
            $query->where('player_category', $request->player_category);
        }

        $players = $query->paginate(15);
        $ageCategories = Player::getAgeCategories();
        $playerCategories = Player::getPlayerCategories();

        return view('school.players.index', compact(
            'players',
            'ageCategories',
            'playerCategories'
        ));
    }

    /**
     * Show form to create a new player
     */
    public function create()
    {
        $playerCategories = Player::getPlayerCategories();
        $battingStyles = Player::getBattingStyles();
        $bowlingStyles = Player::getBowlingStyles();

        return view('school.players.create', compact(
            'playerCategories',
            'battingStyles',
            'bowlingStyles'
        ));
    }

    /**
     * Store a new player
     */
    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'player_category' => 'required|in:' . implode(',', Player::getPlayerCategories()),
            'batting_style' => 'required|in:' . implode(',', Player::getBattingStyles()),
            'bowling_style' => 'required|in:' . implode(',', Player::getBowlingStyles()),
            'jersey_number' => 'nullable|string|max:10',
        ]);

        // Auto-calculate age category based on date of birth
        $validated['age_category'] = Player::calculateAgeCategory($validated['date_of_birth']);
        $validated['school_id'] = $school->id;

        Player::create($validated);

        return redirect()->route('school.players.index')
            ->with('success', 'Player created successfully.');
    }

    /**
     * Show player details
     */
    public function show(Player $player)
    {
        // Ensure player belongs to this school
        $this->authorizePlayer($player);

        return view('school.players.show', compact('player'));
    }

    /**
     * Show form to edit a player
     */
    public function edit(Player $player)
    {
        // Ensure player belongs to this school
        $this->authorizePlayer($player);

        $playerCategories = Player::getPlayerCategories();
        $battingStyles = Player::getBattingStyles();
        $bowlingStyles = Player::getBowlingStyles();

        return view('school.players.edit', compact(
            'player',
            'playerCategories',
            'battingStyles',
            'bowlingStyles'
        ));
    }

    /**
     * Update a player
     */
    public function update(Request $request, Player $player)
    {
        // Ensure player belongs to this school
        $this->authorizePlayer($player);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'player_category' => 'required|in:' . implode(',', Player::getPlayerCategories()),
            'batting_style' => 'required|in:' . implode(',', Player::getBattingStyles()),
            'bowling_style' => 'required|in:' . implode(',', Player::getBowlingStyles()),
            'jersey_number' => 'nullable|string|max:10',
        ]);

        // Auto-calculate age category based on date of birth
        $validated['age_category'] = Player::calculateAgeCategory($validated['date_of_birth']);

        $player->update($validated);

        return redirect()->route('school.players.index')
            ->with('success', 'Player updated successfully.');
    }

    /**
     * Delete a player
     */
    public function destroy(Player $player)
    {
        // Ensure player belongs to this school
        $this->authorizePlayer($player);

        $player->delete();

        return redirect()->route('school.players.index')
            ->with('success', 'Player deleted successfully.');
    }

    /**
     * Authorize that the player belongs to the authenticated school
     */
    private function authorizePlayer(Player $player): void
    {
        $school = auth()->user()->school;

        if ($player->school_id !== $school->id) {
            abort(403, 'You are not authorized to access this player.');
        }
    }
}
