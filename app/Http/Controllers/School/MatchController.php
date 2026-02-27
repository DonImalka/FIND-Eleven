<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;
use App\Models\MatchSquad;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * List all matches for this school.
     */
    public function index(Request $request)
    {
        $school = auth()->user()->school;

        $query = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool'])
            ->where(function ($q) use ($school) {
                $q->where('home_school_id', $school->id)
                  ->orWhere('away_school_id', $school->id);
            })
            ->latest('match_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $matches = $query->paginate(15);

        return view('school.matches.index', compact('matches'));
    }

    /**
     * Show match details.
     */
    public function show(CricketMatch $cricketMatch)
    {
        $school = auth()->user()->school;
        abort_unless($cricketMatch->hasSchool($school->id), 403);

        $cricketMatch->load([
            'tournament',
            'homeSchool',
            'awaySchool',
            'tossWinner',
            'innings.battingSchool',
            'innings.batterScores.player',
            'innings.bowlerScores.player',
        ]);

        $mySquad = $cricketMatch->squadForSchool($school->id);
        $myPlayingXI = $cricketMatch->playingXIForSchool($school->id);

        return view('school.matches.show', compact('cricketMatch', 'school', 'mySquad', 'myPlayingXI'));
    }

    /**
     * Show squad selection form.
     */
    public function editSquad(CricketMatch $cricketMatch)
    {
        $school = auth()->user()->school;
        abort_unless($cricketMatch->hasSchool($school->id), 403);

        if ($cricketMatch->isLive() || $cricketMatch->isCompleted()) {
            return redirect()->route('school.matches.show', $cricketMatch)
                ->with('error', 'Cannot modify squad after match has started.');
        }

        $allPlayers = $school->players()->orderBy('full_name')->get();
        $currentSquadIds = $cricketMatch->squads()
            ->where('school_id', $school->id)
            ->pluck('player_id')
            ->toArray();
        $currentXIIds = $cricketMatch->squads()
            ->where('school_id', $school->id)
            ->where('is_playing_xi', true)
            ->pluck('player_id')
            ->toArray();

        return view('school.matches.squad', compact(
            'cricketMatch', 'allPlayers', 'currentSquadIds', 'currentXIIds'
        ));
    }

    /**
     * Save squad & playing XI selections.
     */
    public function updateSquad(Request $request, CricketMatch $cricketMatch)
    {
        $school = auth()->user()->school;
        abort_unless($cricketMatch->hasSchool($school->id), 403);

        if ($cricketMatch->isLive() || $cricketMatch->isCompleted()) {
            return redirect()->route('school.matches.show', $cricketMatch)
                ->with('error', 'Cannot modify squad after match has started.');
        }

        $validated = $request->validate([
            'squad' => 'nullable|array',
            'squad.*' => 'exists:players,id',
            'playing_xi' => 'nullable|array|max:11',
            'playing_xi.*' => 'exists:players,id',
        ]);

        $squadIds = $validated['squad'] ?? [];
        $playingXIIds = $validated['playing_xi'] ?? [];

        // Ensure playing XI are part of squad
        $playingXIIds = array_intersect($playingXIIds, $squadIds);

        if (count($playingXIIds) > 0 && count($playingXIIds) !== 11) {
            return back()->with('error', 'Playing XI must have exactly 11 players. You selected ' . count($playingXIIds) . '.')
                ->withInput();
        }

        // Verify all players belong to this school
        $schoolPlayerIds = $school->players()->pluck('id')->toArray();
        foreach ($squadIds as $pid) {
            if (!in_array($pid, $schoolPlayerIds)) {
                abort(403);
            }
        }

        // Remove existing squad entries for this school & match
        MatchSquad::where('match_id', $cricketMatch->id)
            ->where('school_id', $school->id)
            ->delete();

        // Re-create
        foreach ($squadIds as $playerId) {
            MatchSquad::create([
                'match_id' => $cricketMatch->id,
                'school_id' => $school->id,
                'player_id' => $playerId,
                'is_playing_xi' => in_array($playerId, $playingXIIds),
            ]);
        }

        return redirect()->route('school.matches.show', $cricketMatch)
            ->with('success', 'Squad updated successfully.');
    }
}
