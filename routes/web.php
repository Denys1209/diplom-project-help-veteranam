<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestCommentController;
use App\Http\Controllers\Veteran\VeteranProfileController;
use App\Http\Controllers\ApprovalController;
use Illuminate\Support\Facades\Route;

// Approval status route (accessible without approval check)
Route::middleware(['auth'])->group(function () {
    Route::get('/approval/status', [ApprovalController::class, 'status'])->name('approval.status');
});

// Main application routes with approval check
Route::middleware(['auth', 'check.approval'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/veteran-profile', [VeteranProfileController::class, 'edit'])
        ->name('veteran-profile.edit');
    Route::put('/veteran-profile', [VeteranProfileController::class, 'update'])
        ->name('veteran-profile.update');

    // Help request routes
    Route::get('/help-requests', [HelpRequestController::class, 'index'])->name('help-requests.index');
    Route::get('/help-requests/create', [HelpRequestController::class, 'create'])->name('help-requests.create');
    Route::post('/help-requests', [HelpRequestController::class, 'store'])->name('help-requests.store');
    Route::get('/help-requests/{helpRequest}', [HelpRequestController::class, 'show'])->name('help-requests.show');
    Route::get('/help-requests/{helpRequest}/edit', [HelpRequestController::class, 'edit'])->name('help-requests.edit');
    Route::put('/help-requests/{helpRequest}', [HelpRequestController::class, 'update'])->name('help-requests.update');
    Route::delete('/help-requests/{helpRequest}', [HelpRequestController::class, 'destroy'])->name('help-requests.destroy');

    // Volunteer specific routes
    Route::patch('/help-requests/{helpRequest}/volunteer', [HelpRequestController::class, 'volunteer'])
        ->name('help-requests.volunteer');
    Route::get('/help-requests/{helpRequest}/complete', [HelpRequestController::class, 'showCompleteForm'])
        ->name('help-requests.complete-form')
        ->middleware(['auth']);
    Route::patch('/help-requests/{helpRequest}/complete', [HelpRequestController::class, 'complete'])
        ->name('help-requests.complete')
        ->middleware(['auth']);

    // Comment routes
    Route::post('/help-requests/{helpRequest}/comments', [RequestCommentController::class, 'store'])
        ->name('request-comments.store');
    Route::delete('/help-requests/{helpRequest}/comments/{comment}', [RequestCommentController::class, 'destroy'])
        ->name('request-comments.destroy');

    // Dashboard routes
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
});

require __DIR__ . '/auth.php';
