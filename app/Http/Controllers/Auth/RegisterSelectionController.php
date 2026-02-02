<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Register Selection Controller
 * Shows role selection page for registration
 */
class RegisterSelectionController extends Controller
{
    /**
     * Display the registration role selection page
     */
    public function index()
    {
        return view('auth.register-selection');
    }
}
