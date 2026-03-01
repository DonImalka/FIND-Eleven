<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\HelpPost;
use Illuminate\Http\Request;

/**
 * Public Help Post Controller
 * Displays approved help posts on the website
 */
class HelpPostController extends Controller
{
    /**
     * Display all approved help posts
     */
    public function index()
    {
        $helpPosts = HelpPost::with(['player.school', 'player.stats'])
            ->where('status', HelpPost::STATUS_APPROVED)
            ->latest('approved_at')
            ->paginate(12);

        return view('website.help-posts.index', compact('helpPosts'));
    }

    /**
     * Show a single approved help post
     */
    public function show(HelpPost $helpPost)
    {
        if (!$helpPost->isApproved()) {
            abort(404);
        }

        $helpPost->load('player.school', 'player.stats');

        // Get other approved posts (excluding current) for sidebar
        $relatedPosts = HelpPost::with(['player.school', 'player.stats'])
            ->where('status', HelpPost::STATUS_APPROVED)
            ->where('id', '!=', $helpPost->id)
            ->latest('approved_at')
            ->take(4)
            ->get();

        return view('website.help-posts.show', compact('helpPost', 'relatedPosts'));
    }
}
