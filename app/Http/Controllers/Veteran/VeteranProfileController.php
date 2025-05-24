<?php

namespace App\Http\Controllers\Veteran;

use App\Http\Controllers\Controller;
use App\Models\VeteranProfile;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class VeteranProfileController extends Controller
{
    /**
     * Display the profile form.
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== UserRole::VETERAN->value) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $profile = VeteranProfile::firstOrCreate(['user_id' => $user->id]);

        return view('veteran-profile.edit', compact('profile'));
    }

    /**
     * Update the veteran profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== UserRole::VETERAN->value) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to update this profile.');
        }

        $validated = $request->validate([
            'needs_description' => 'nullable|string|max:1000',
            'military_unit' => 'nullable|string|max:255',
            'service_period' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
            'is_visible' => 'boolean',
        ]);

        // Handle checkbox properly
        $validated['is_visible'] = $request->has('is_visible');

        $profile = VeteranProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('dashboard');
    }
}
