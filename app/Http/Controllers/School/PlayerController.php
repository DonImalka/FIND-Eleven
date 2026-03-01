<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        // Filter by player category if provided
        if ($request->has('player_category') && $request->player_category) {
            $query->where('player_category', $request->player_category);
        }

        $allPlayers = $query->get();

        // Group players by age category (U15, U17, U19 only)
        $playersByAge = [
            'U15' => $allPlayers->where('age_category', 'U15')->sortBy('full_name'),
            'U17' => $allPlayers->where('age_category', 'U17')->sortBy('full_name'),
            'U19' => $allPlayers->where('age_category', 'U19')->sortBy('full_name'),
        ];

        $playerCategories = Player::getPlayerCategories(true);
        $totalPlayers = $allPlayers->count();

        return view('school.players.index', compact(
            'playersByAge',
            'playerCategories',
            'totalPlayers'
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
            'player_category' => ['required', Rule::in(Player::getPlayerCategories())],
            'batting_style' => 'required|in:' . implode(',', Player::getBattingStyles()),
            'bowling_style' => 'required|in:' . implode(',', Player::getBowlingStyles()),
            'jersey_number' => 'nullable|string|max:10',
        ]);

        // Auto-calculate age category based on date of birth
        $validated['age_category'] = Player::calculateAgeCategory($validated['date_of_birth']);
        $validated['school_id'] = $school->id;

        // Generate random username and password for the player account
        $username = Str::slug($validated['full_name'], '_') . '_' . rand(1000, 9999);
        $plainPassword = Str::random(8);

        // Ensure unique email/username
        while (User::where('email', $username . '@player.findeleven.lk')->exists()) {
            $username = Str::slug($validated['full_name'], '_') . '_' . rand(1000, 9999);
        }

        $playerEmail = $username . '@player.findeleven.lk';

        // Create the user account
        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $playerEmail,
            'password' => Hash::make($plainPassword),
            'role' => User::ROLE_PLAYER,
            'email_verified_at' => now(),
        ]);

        $validated['user_id'] = $user->id;
        $validated['username'] = $playerEmail;
        $validated['plain_password'] = $plainPassword;

        Player::create($validated);

        return redirect()->route('school.players.index')
            ->with('success', 'Player created successfully. You can view login credentials in the players list.');
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
        if (!in_array($player->player_category, $playerCategories, true)) {
            $playerCategories[] = $player->player_category;
        }
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
            'player_category' => ['required', Rule::in(Player::getPlayerCategories(true))],
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
