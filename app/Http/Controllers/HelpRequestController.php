<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use App\Models\HelpCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HelpRequestController extends Controller
{
    /**
     * Display a listing of the help requests.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isVeteran()) {
            $helpRequests = $user->helpRequestsCreated()->latest()->get();
        } elseif ($user->isVolunteer()) {
            // For volunteers, show available requests or assigned to them
            $helpRequests = HelpRequest::where(function($query) use ($user) {
                $query->where('volunteer_id', $user->id)
                      ->orWhereNull('volunteer_id');
            })->latest()->get();
        } elseif ($user->isAdmin()) {
            $helpRequests = HelpRequest::latest()->get();
        }

        return view('help-requests.index', compact('helpRequests'));
    }

    /**
     * Show the form for creating a new help request.
     */
    public function create()
    {
        // Only veterans can create help requests
        if (!Auth::user()->isVeteran()) {
            return redirect()->route('dashboard')
                ->with('error', 'Тільки ветерани можуть створювати запити допомоги.');
        }

        $categories = HelpCategory::all();
        return view('help-requests.create', compact('categories'));
    }

    /**
     * Store a newly created help request in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'category_id' => 'required|exists:help_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'urgency' => 'required|in:low,medium,high',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'deadline' => 'nullable|date|after:today',
            'photos.*' => 'nullable|image|max:5120', // Validate photos if present
        ]);

        // Create the help request
        $helpRequest = new HelpRequest($validated);
        $helpRequest->veteran_id = Auth::id();
        $helpRequest->status = 'pending';
        $helpRequest->save();

        // Handle photo uploads if included in the request
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                // Ensure the file is valid before proceeding
                if ($photo->isValid()) {
                    $path = $photo->store('request-photos', 'public');

                    $helpRequest->photos()->create([
                        'user_id' => Auth::id(),
                        'photo_path' => $path, // Using photo_path instead of path to match the database column
                        'caption' => $request->input('photo_caption', 'Фото до запиту допомоги')
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Запит допомоги успішно створено.');
    }

    /**
     * Display the specified help request.
     */
    public function show(HelpRequest $helpRequest)
    {
        // Check if the user has permission to view this request
        $user = Auth::user();

        // Veterans can only see their own requests
        if ($user->isVeteran() && $helpRequest->veteran_id !== $user->id) {
            abort(403);
        }

        return view('help-requests.show', compact('helpRequest'));
    }

    /**
     * Show the form for editing the specified help request.
     */
    public function edit(HelpRequest $helpRequest)
    {
        // Only the veteran who created the request or an admin can edit it
        if (Auth::user()->isVeteran() && $helpRequest->veteran_id !== Auth::id()) {
            abort(403);
        }

        $categories = HelpCategory::all();
        return view('help-requests.edit', compact('helpRequest', 'categories'));
    }

    /**
     * Update the specified help request in storage.
     */
    public function update(Request $request, HelpRequest $helpRequest)
    {
        // Only the veteran who created the request or an admin can update it
        if (Auth::user()->isVeteran() && $helpRequest->veteran_id !== Auth::id()) {
            abort(403);
        }

        // Validate request
        $validated = $request->validate([
            'category_id' => 'required|exists:help_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'urgency' => 'required|in:low,medium,high',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'deadline' => 'nullable|date|after:today',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'photos.*' => 'nullable|image|max:5120', // Validate photos if present
        ]);

        $helpRequest->update($validated);

        // Handle photo uploads if included in the request
        if ($request->hasFile('photos')) {
            // Delete existing photos
            foreach ($helpRequest->photos as $existingPhoto) {
                // Delete the file from storage
                Storage::disk('public')->delete($existingPhoto->photo_path);
                // Delete the record from the database
                $existingPhoto->delete();
            }

            // Upload and save new photos
            foreach ($request->file('photos') as $photo) {
                // Ensure the file is valid before proceeding
                if ($photo->isValid()) {
                    $path = $photo->store('request-photos', 'public');

                    $helpRequest->photos()->create([
                        'user_id' => Auth::id(),
                        'photo_path' => $path,
                        'caption' => $request->input('photo_caption', 'Фото до запиту допомоги')
                    ]);
                } else {
                    \Log::warning('Invalid photo uploaded', [
                        'request_id' => $helpRequest->id,
                        'user_id' => Auth::id(),
                        'error' => $photo->getErrorMessage()
                    ]);
                }
            }
        }

        return redirect()->route('help-requests.show', $helpRequest)
            ->with('success', 'Запит допомоги успішно оновлено.');
    }

    /**
     * Remove the specified help request from storage.
     */
    public function destroy(HelpRequest $helpRequest)
    {
        // Only the veteran who created the request or an admin can delete it
        if (!Auth::user()->isAdmin() && $helpRequest->veteran_id !== Auth::id()) {
            abort(403);
        }

        // Delete associated records
        $helpRequest->photos()->delete();
        $helpRequest->comments()->delete();

        $helpRequest->delete();

        return redirect()->route('profile.edit')
            ->with('success', 'Запит допомоги успішно видалено.');
    }

    /**
     * Allow a volunteer to take on a help request.
     */
    public function volunteer(HelpRequest $helpRequest)
    {
        // Only volunteers can take on help requests
        if (!Auth::user()->isVolunteer()) {
            return redirect()->back()
                ->with('error', 'Тільки волонтери можуть брати запити.');
        }

        // Request must be pending
        if ($helpRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Цей запит вже взято іншим волонтером або виконано.');
        }

        $helpRequest->volunteer_id = Auth::id();
        $helpRequest->status = 'in_progress';
        $helpRequest->save();

        return redirect()->route('help-requests.show', $helpRequest)
            ->with('success', 'Ви успішно взяли запит допомоги.');
    }

     public function showCompleteForm(HelpRequest $helpRequest)
    {
        // Only the assigned volunteer can complete
        if (Auth::id() !== $helpRequest->volunteer_id && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Тільки призначений волонтер може відмітити запит як виконаний.');
        }

        // Request must be in progress
        if ($helpRequest->status !== 'in_progress') {
            return redirect()->back()
                ->with('error', 'Тільки запити в процесі можуть бути відмічені як виконані.');
        }

        return view('help-requests.complete-form', compact('helpRequest'));
    }

    /**
     * Mark a help request as completed with comment and optional photo.
     */
    public function complete(Request $request, HelpRequest $helpRequest)
    {
        // Only the assigned volunteer can mark as complete
        if (Auth::id() !== $helpRequest->volunteer_id && !Auth::user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Тільки призначений волонтер може відмітити запит як виконаний.');
        }

        // Request must be in progress
        if ($helpRequest->status !== 'in_progress') {
            return redirect()->back()
                ->with('error', 'Тільки запити в процесі можуть бути відмічені як виконані.');
        }

        // Validate request
        $validated = $request->validate([
            'completion_comment' => 'required|string|max:500',
            'completion_photo' => 'nullable|image|max:5120',
        ]);

        // Add completion comment
        $helpRequest->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['completion_comment'],
        ]);

        // Handle completion photo upload if included
        if ($request->hasFile('completion_photo')) {
            $photo = $request->file('completion_photo');
            if ($photo->isValid()) {
                $path = $photo->store('request-photos', 'public');

                $helpRequest->photos()->create([
                    'user_id' => Auth::id(),
                    'photo_path' => $path,
                    'caption' => $request->input('photo_caption', 'Фото виконаного запиту'),
                    'is_completion_photo' => true,
                ]);
            }
        }

        // Update the help request status
        $helpRequest->status = 'completed';
        $helpRequest->completed_at = now();
        $helpRequest->save();

        return redirect()->route('help-requests.show', $helpRequest)
            ->with('success', 'Запит допомоги успішно відмічено як виконаний.');
    }
}
