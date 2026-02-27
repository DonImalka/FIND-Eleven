<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerCategory;
use Illuminate\Http\Request;

class PlayerCategoryController extends Controller
{
    /**
     * Display all player categories
     */
    public function index()
    {
        $categories = PlayerCategory::orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        return view('admin.player-categories.index', compact('categories'));
    }

    /**
     * Show form to create a new category
     */
    public function create()
    {
        return view('admin.player-categories.create');
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:player_categories,name',
            'is_active' => 'nullable|boolean',
        ]);

        PlayerCategory::create([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active', true),
            'is_default' => false,
        ]);

        return redirect()->route('admin.player-categories.index')
            ->with('success', 'Player category created successfully.');
    }

    /**
     * Show form to edit a category
     */
    public function edit(PlayerCategory $playerCategory)
    {
        return view('admin.player-categories.edit', compact('playerCategory'));
    }

    /**
     * Update a category
     */
    public function update(Request $request, PlayerCategory $playerCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:player_categories,name,' . $playerCategory->id,
            'is_active' => 'nullable|boolean',
        ]);

        $playerCategory->update([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.player-categories.index')
            ->with('success', 'Player category updated successfully.');
    }

    /**
     * Delete a category
     */
    public function destroy(PlayerCategory $playerCategory)
    {
        if ($playerCategory->is_default) {
            return redirect()->route('admin.player-categories.index')
                ->with('error', 'Default categories cannot be deleted.');
        }

        $inUse = Player::where('player_category', $playerCategory->name)->exists();

        if ($inUse) {
            return redirect()->route('admin.player-categories.index')
                ->with('error', 'This category is assigned to existing players and cannot be deleted.');
        }

        $playerCategory->delete();

        return redirect()->route('admin.player-categories.index')
            ->with('success', 'Player category deleted successfully.');
    }
}
