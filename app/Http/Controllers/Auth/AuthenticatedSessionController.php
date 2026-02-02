<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Redirects to role-specific dashboard after login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get authenticated user and redirect based on role
        $user = Auth::user();

        // Check if school user is approved
        if ($user->role === User::ROLE_SCHOOL) {
            $school = $user->school;
            
            if (!$school || !$school->isApproved()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = !$school 
                    ? 'School profile not found. Please contact administrator.'
                    : ($school->isPending() 
                        ? 'Your school registration is pending approval. Please wait for admin approval.'
                        : 'Your school registration has been rejected. Please contact administrator.');

                return redirect()->route('login')->with('error', $message);
            }
        }

        // Role-based redirect
        return match($user->role) {
            User::ROLE_ADMIN => redirect()->intended(route('admin.dashboard')),
            User::ROLE_SCHOOL => redirect()->intended(route('school.dashboard')),
            User::ROLE_PLAYER => redirect()->intended(route('player.dashboard')),
            default => redirect()->intended(route('dashboard')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
