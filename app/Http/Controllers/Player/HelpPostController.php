<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\HelpPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Player Help Post Controller
 * Handles CRUD for player help/support posts
 */
class HelpPostController extends Controller
{
    /**
     * List all help posts for the logged-in player
     */
    public function index()
    {
        $player = auth()->user()->player;

        if (!$player) {
            return redirect()->route('player.dashboard')
                ->with('error', 'No player profile linked to your account.');
        }

        $helpPosts = $player->helpPosts()->latest()->get();

        return view('player.help-posts.index', compact('helpPosts', 'player'));
    }

    /**
     * Show form to create a new help post
     */
    public function create()
    {
        $player = auth()->user()->player;

        if (!$player) {
            return redirect()->route('player.dashboard')
                ->with('error', 'No player profile linked to your account.');
        }

        return view('player.help-posts.create', compact('player'));
    }

    /**
     * Store a new help post
     */
    public function store(Request $request)
    {
        $player = auth()->user()->player;

        if (!$player) {
            return redirect()->route('player.dashboard')
                ->with('error', 'No player profile linked to your account.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'contact_number' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
        ]);

        $helpPost = new HelpPost();
        $helpPost->player_id = $player->id;
        $helpPost->title = $validated['title'];
        $helpPost->description = $validated['description'];
        $helpPost->contact_number = $validated['contact_number'] ?? null;
        $helpPost->contact_email = $validated['contact_email'] ?? null;
        $helpPost->status = HelpPost::STATUS_PENDING;

        // Handle file upload
        if ($request->hasFile('proof_document')) {
            $path = $request->file('proof_document')->store('help-posts/proofs', 'public');
            $helpPost->proof_document = $path;
        }

        $helpPost->save();

        return redirect()->route('player.help-posts.index')
            ->with('success', 'Help post submitted successfully. Waiting for school approval.');
    }

    /**
     * Show a specific help post
     */
    public function show(HelpPost $helpPost)
    {
        $player = auth()->user()->player;

        if (!$player || $helpPost->player_id !== $player->id) {
            abort(403, 'Unauthorized.');
        }

        return view('player.help-posts.show', compact('helpPost'));
    }

    /**
     * Delete a help post (only if pending)
     */
    public function destroy(HelpPost $helpPost)
    {
        $player = auth()->user()->player;

        if (!$player || $helpPost->player_id !== $player->id) {
            abort(403, 'Unauthorized.');
        }

        if (!$helpPost->isPending()) {
            return back()->with('error', 'Only pending posts can be deleted.');
        }

        // Delete the proof document if exists
        if ($helpPost->proof_document) {
            Storage::disk('public')->delete($helpPost->proof_document);
        }

        $helpPost->delete();

        return redirect()->route('player.help-posts.index')
            ->with('success', 'Help post deleted successfully.');
    }
}
