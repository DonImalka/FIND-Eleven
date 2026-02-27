<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::withCount('matches')
            ->latest()
            ->paginate(15);

        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        Tournament::create($validated);

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournament created successfully.');
    }

    public function show(Tournament $tournament)
    {
        $tournament->load(['matches.homeSchool', 'matches.awaySchool']);

        return view('admin.tournaments.show', compact('tournament'));
    }

    public function edit(Tournament $tournament)
    {
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $tournament->update($validated);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Tournament updated successfully.');
    }

    public function destroy(Tournament $tournament)
    {
        if ($tournament->matches()->exists()) {
            return redirect()->route('admin.tournaments.index')
                ->with('error', 'Cannot delete tournament that has matches.');
        }

        $tournament->delete();

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Tournament deleted successfully.');
    }
}
