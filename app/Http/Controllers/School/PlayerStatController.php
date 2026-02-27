<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerMatchPerformance;
use App\Models\PlayerStat;
use Illuminate\Http\Request;

class PlayerStatController extends Controller
{
    // ─── INITIAL / EXISTING STATS ───────────────────────────────

    /**
     * Show the form to enter (or edit) the player's existing career stats
     * — the stats they had BEFORE being registered in the system.
     */
    public function editInitial(Player $player)
    {
        $this->authorizePlayer($player);

        $stats = $player->stats ?? PlayerStat::create(['player_id' => $player->id]);
        $player->load('stats');

        $initialSections = PlayerStat::getInitialStatFields($player->player_category);

        return view('school.players.stats-initial', compact('player', 'initialSections'));
    }

    /**
     * Save the player's existing/initial career stats.
     */
    public function updateInitial(Request $request, Player $player)
    {
        $this->authorizePlayer($player);

        $stats = $player->stats ?? PlayerStat::create(['player_id' => $player->id]);

        // Gather all initial stat fields and validate
        $initialSections = PlayerStat::getInitialStatFields($player->player_category);
        $rules = [];
        foreach ($initialSections as $fields) {
            foreach ($fields as $fieldName => $label) {
                if (str_contains($fieldName, 'overs')) {
                    $rules[$fieldName] = 'nullable|numeric|min:0';
                } else {
                    $rules[$fieldName] = 'nullable|integer|min:0';
                }
            }
        }

        $validated = $request->validate($rules);

        // Store as JSON in initial_stats
        $initialData = [];
        foreach ($initialSections as $fields) {
            foreach ($fields as $fieldName => $label) {
                $initialData[$fieldName] = $validated[$fieldName] ?? 0;
            }
        }

        $stats->update(['initial_stats' => $initialData]);

        // Recalculate career totals (initial + any existing performances)
        PlayerStat::recalculateFromPerformances($player);

        return redirect()->route('school.players.show', $player)
            ->with('success', 'Existing career stats saved successfully!');
    }

    // ─── MATCH PERFORMANCES ─────────────────────────────────────

    /**
     * Show the form to add a new match performance.
     */
    public function create(Player $player)
    {
        $this->authorizePlayer($player);

        $formSections = PlayerMatchPerformance::getFieldsForCategory($player->player_category);

        return view('school.players.stats-add', compact('player', 'formSections'));
    }

    /**
     * Store a new match performance and recalculate career stats.
     */
    public function store(Request $request, Player $player)
    {
        $this->authorizePlayer($player);

        $request->validate([
            'match_date' => 'required|date|before_or_equal:today',
            'opponent' => 'nullable|string|max:255',
            'match_description' => 'nullable|string|max:255',
        ]);

        $formSections = PlayerMatchPerformance::getFieldsForCategory($player->player_category);
        $statRules = [];
        foreach ($formSections as $fields) {
            foreach ($fields as $fieldName => $meta) {
                if ($meta['type'] === 'checkbox') {
                    $statRules[$fieldName] = 'nullable|boolean';
                } elseif ($meta['step'] === '0.1') {
                    $statRules[$fieldName] = 'nullable|numeric|min:0';
                } else {
                    $statRules[$fieldName] = 'nullable|integer|min:0';
                }
            }
        }
        $request->validate($statRules);

        $performanceData = [
            'player_id' => $player->id,
            'match_date' => $request->match_date,
            'opponent' => $request->opponent,
            'match_description' => $request->match_description,
        ];
        foreach ($formSections as $fields) {
            foreach ($fields as $fieldName => $meta) {
                if ($meta['type'] === 'checkbox') {
                    $performanceData[$fieldName] = $request->has($fieldName) ? true : false;
                } else {
                    $performanceData[$fieldName] = $request->input($fieldName, 0) ?? 0;
                }
            }
        }

        PlayerMatchPerformance::create($performanceData);

        // Recalculate career = initial + all performances
        PlayerStat::recalculateFromPerformances($player);

        return redirect()->route('school.players.show', $player)
            ->with('success', 'Match performance added and career stats updated!');
    }

    /**
     * Show the match performance history.
     */
    public function history(Player $player)
    {
        $this->authorizePlayer($player);

        $performances = $player->matchPerformances()->paginate(15);
        $formSections = PlayerMatchPerformance::getFieldsForCategory($player->player_category);

        return view('school.players.stats-history', compact('player', 'performances', 'formSections'));
    }

    /**
     * Delete a match performance and recalculate.
     */
    public function destroyPerformance(Player $player, PlayerMatchPerformance $performance)
    {
        $this->authorizePlayer($player);
        abort_unless($performance->player_id === $player->id, 403);

        $performance->delete();
        PlayerStat::recalculateFromPerformances($player);

        return redirect()->route('school.players.stats.history', $player)
            ->with('success', 'Match performance deleted and career stats recalculated.');
    }

    // ─── AUTH ───────────────────────────────────────────────────

    private function authorizePlayer(Player $player): void
    {
        $school = auth()->user()->school;
        abort_unless($player->school_id === $school->id, 403, 'You are not authorized to access this player.');
    }
}
