<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * School Registration Controller
 * Handles public registration for schools
 */
class SchoolRegisterController extends Controller
{
    /**
     * Display the school registration view.
     */
    public function create(): View
    {
        $schoolTypes = School::getSchoolTypes();
        
        return view('auth.school-register', compact('schoolTypes'));
    }

    /**
     * Handle an incoming school registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate user data
        $request->validate([
            // User fields
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // School fields
            'school_name' => ['required', 'string', 'max:255'],
            'school_type' => ['required', 'in:Government,Private,Semi-Government'],
            'district' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'school_address' => ['required', 'string'],
            'contact_number' => ['required', 'string', 'max:20'],
            'cricket_incharge_name' => ['required', 'string', 'max:255'],
            'cricket_incharge_contact' => ['required', 'string', 'max:20'],
        ]);

        // Create user with SCHOOL role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_SCHOOL,
        ]);

        // Create school profile with PENDING status
        School::create([
            'user_id' => $user->id,
            'school_name' => $request->school_name,
            'school_type' => $request->school_type,
            'district' => $request->district,
            'province' => $request->province,
            'school_address' => $request->school_address,
            'contact_number' => $request->contact_number,
            'cricket_incharge_name' => $request->cricket_incharge_name,
            'cricket_incharge_contact' => $request->cricket_incharge_contact,
            'status' => School::STATUS_PENDING,
        ]);

        event(new Registered($user));

        // Don't log in the user - they need to wait for approval
        return redirect()->route('login')
            ->with('success', 'Registration successful! Your school registration is pending admin approval. You will be able to login once approved.');
    }
}
