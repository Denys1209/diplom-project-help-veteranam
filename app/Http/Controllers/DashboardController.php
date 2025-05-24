<?php

namespace App\Http\Controllers;

use App\Enums\HelpRequestStatus;
use App\Models\HelpCategory;
use App\Models\HelpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with appropriate data based on user role
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Load all help requests with coordinates for the map (visible to all users)
        $allHelpRequests = HelpRequest::with(['category'])
            ->get();

        // Initialize variables to avoid undefined variable errors
        $helpRequests = collect();
        $categories = collect();

        if ($user->isVolunteer()) {
            // --- Logic for Volunteers: Fetch help requests for card display ---
            $query = HelpRequest::query()->with(['category']); // Eager load category

            // Show all pending requests plus those assigned to this volunteer
            $query->where(function($q) use ($user) {
                $q->where('status', HelpRequestStatus::PENDING->value);

            });

            // Filtering
            if ($request->filled('search_filter')) {
                $searchTerm = $request->input('search_filter');
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->filled('status_filter')) {
                $query->where('status', $request->input('status_filter'));
            }

            if ($request->filled('category_filter')) {
                $query->where('category_id', $request->input('category_filter'));
            }

            if ($request->filled('urgency_filter')) {
                $query->where('urgency', $request->input('urgency_filter'));
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'created_at');
            $sortDirection = $request->input('sort_direction', 'desc');
            $allowedSorts = ['title', 'status', 'urgency', 'created_at', 'deadline'];

            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc'); // Default sort
            }

            // Paginate for card layout
            $helpRequests = $query->paginate(6)->appends($request->query());
            $categories = HelpCategory::all();
        }

        return view('dashboard', compact('allHelpRequests', 'categories', 'helpRequests'));
    }
}
