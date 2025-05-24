<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ApprovalStatus;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Show the approval status page
     */
    public function status()
    {
        $user = Auth::user();

        return view('approval.status', compact('user'));
    }
}
