<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\HelpPost;
use Illuminate\Http\Request;

/**
 * School Help Post Controller
 * Handles approval/rejection of player help posts by school admins
 */
class HelpPostController extends Controller
{
    /**
     * List all help posts from this school's players
     */
    public function index(Request $request)
    {
        $school = auth()->user()->school;

        $query = HelpPost::with('player')
            ->whereHas('player', fn($q) => $q->where('school_id', $school->id))
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $helpPosts = $query->get();

        $pendingCount = HelpPost::whereHas('player', fn($q) => $q->where('school_id', $school->id))
            ->where('status', 'pending')->count();

        return view('school.help-posts.index', compact('helpPosts', 'pendingCount'));
    }

    /**
     * Show a specific help post
     */
    public function show(HelpPost $helpPost)
    {
        $school = auth()->user()->school;

        // Ensure the post belongs to a player in this school
        if ($helpPost->player->school_id !== $school->id) {
            abort(403, 'Unauthorized.');
        }

        $helpPost->load('player.school');

        return view('school.help-posts.show', compact('helpPost'));
    }

    /**
     * Approve a help post
     */
    public function approve(HelpPost $helpPost)
    {
        $school = auth()->user()->school;

        if ($helpPost->player->school_id !== $school->id) {
            abort(403, 'Unauthorized.');
        }

        $helpPost->update([
            'status' => HelpPost::STATUS_APPROVED,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('school.help-posts.show', $helpPost)
            ->with('success', 'Help post approved successfully. It is now visible on the website.');
    }

    /**
     * Reject a help post
     */
    public function reject(Request $request, HelpPost $helpPost)
    {
        $school = auth()->user()->school;

        if ($helpPost->player->school_id !== $school->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $helpPost->update([
            'status' => HelpPost::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'approved_at' => null,
        ]);

        return redirect()->route('school.help-posts.show', $helpPost)
            ->with('success', 'Help post has been rejected.');
    }
}
