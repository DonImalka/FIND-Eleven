<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * School Profile Controller
 * Handles school profile viewing and updating
 */
class ProfileController extends Controller
{
    /**
     * Display school profile
     */
    public function index()
    {
        $user = auth()->user();
        $school = $user->school;

        return view('school.profile.index', compact('school', 'user'));
    }

    /**
     * Show edit form for school profile
     */
    public function edit()
    {
        $user = auth()->user();
        $school = $user->school;
        $schoolTypes = \App\Models\School::getSchoolTypes();

        return view('school.profile.edit', compact('school', 'user', 'schoolTypes'));
    }

    /**
     * Update school profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $school = $user->school;

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_type' => 'required|in:Government,Private,Semi-Government',
            'district' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'school_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'cricket_incharge_name' => 'required|string|max:255',
            'cricket_incharge_contact' => 'required|string|max:20',
        ]);

        $school->update($validated);

        return redirect()->route('school.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}
