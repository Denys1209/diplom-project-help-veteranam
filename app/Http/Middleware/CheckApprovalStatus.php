<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\ApprovalStatus;
use Illuminate\Support\Facades\Auth;

class CheckApprovalStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for guest users
        if (!auth()->check()) {
            return $next($request);
        }

        $user = Auth::user();



        // Check if user is waiting for approval or rejected
        if ($user->approval_status === ApprovalStatus::WAITING ||
            $user->approval_status === ApprovalStatus::REJECTED) {

            // Don't redirect if already on the approval status page
            if ($request->routeIs('approval.status')) {
                return $next($request);
            }

            // Don't redirect logout route
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('approval.status');
        }

        return $next($request);
    }
}
