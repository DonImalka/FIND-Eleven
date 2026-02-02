<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

/**
 * Middleware to check if a school user is approved
 * Blocks access for PENDING or REJECTED schools
 */
class ApprovedSchoolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only apply to school users
        if ($user && $user->role === User::ROLE_SCHOOL) {
            $school = $user->school;

            // If school profile doesn't exist or is not approved, deny access
            if (!$school || !$school->isApproved()) {
                // Log out the user and redirect with message
                auth()->logout();
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

        return $next($request);
    }
}
