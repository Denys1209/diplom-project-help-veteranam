<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use App\Models\RequestComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestCommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, HelpRequest $helpRequest)
    {
        // Validate the comment
        $validated = $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // Create the comment
        $comment = new RequestComment();
        $comment->user_id = Auth::id();
        $comment->help_request_id = $helpRequest->id;
        $comment->comment = $validated['comment'];
        $comment->save();

        return redirect()->route('help-requests.show', $helpRequest)
            ->with('success', 'Коментар успішно додано.');
    }

    /**
     * Remove the specified comment from storage.
     */
   public function destroy(HelpRequest $helpRequest, RequestComment $comment)
{
    // Check if the comment belongs to this help request
    if ($comment->help_request_id !== $helpRequest->id) {
        abort(404);
    }

    // Only the comment author or admin can delete it
    if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
        abort(403);
    }

    $comment->delete();

    return redirect()->route('help-requests.show', $helpRequest)
        ->with('success', 'Коментар успішно видалено.');
}
}
