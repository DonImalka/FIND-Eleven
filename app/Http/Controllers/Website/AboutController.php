<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * About Controller
 * Handles the public about page
 */
class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        return view('website.about.index');
    }
}
