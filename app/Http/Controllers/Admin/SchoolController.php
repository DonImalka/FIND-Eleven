<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

/**
 * Admin School Management Controller
 * Handles viewing and approving/rejecting schools
 */
class SchoolController extends Controller
{
    /**
     * Display all schools with filtering options
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = School::with('user')->latest();

        // Filter by status if provided
        if ($status && in_array($status, School::getStatuses())) {
            $query->where('status', $status);
        }

        $schools = $query->paginate(15);

        return view('admin.schools.index', compact('schools', 'status'));
    }

    /**
     * Display pending schools for approval
     */
    public function pending()
    {
        $schools = School::with('user')
            ->where('status', School::STATUS_PENDING)
            ->latest()
            ->paginate(15);

        return view('admin.schools.pending', compact('schools'));
    }

    /**
     * Show school details
     */
    public function show(School $school)
    {
        $school->load(['user', 'players']);
        
        return view('admin.schools.show', compact('school'));
    }

    /**
     * Approve a school registration
     */
    public function approve(School $school)
    {
        $school->update(['status' => School::STATUS_APPROVED]);

        return redirect()->back()->with('success', "School '{$school->school_name}' has been approved successfully.");
    }

    /**
     * Reject a school registration
     */
    public function reject(School $school)
    {
        $school->update(['status' => School::STATUS_REJECTED]);

        return redirect()->back()->with('success', "School '{$school->school_name}' has been rejected.");
    }
}
