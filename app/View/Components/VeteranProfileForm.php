<?php

namespace App\View\Components;

use App\Enums\UserRole;
use App\Models\VeteranProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class VeteranProfileForm extends Component
{
    public $profile;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = Auth::user();

        // Check if user is a veteran
        if ($user && $user->role === UserRole::VETERAN) {
            // Get existing profile or create a new one
            $this->profile = VeteranProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'needs_description' => '',
                    'military_unit' => '',
                    'service_period' => '',
                    'medical_conditions' => '',
                    'is_visible' => true,
                ]
            );
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('components.veteran-profile-form');
    }
}
