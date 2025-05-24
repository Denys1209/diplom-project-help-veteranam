<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use App\Enums\ApprovalStatus;
use App\Enums\UserRole;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->approval_status === ApprovalStatus::APPROVED;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'approval_status',
        'rejection_reason'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
        'approval_status' => ApprovalStatus::class,
    ];

    /**
     * Check if user is a veteran
     */
    public function isVeteran()
    {
        return $this->role === UserRole::VETERAN;
    }

    /**
     * Check if user is a volunteer
     */
    public function isVolunteer()
    {
        return $this->role === UserRole::VOLUNTEER;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user is approved
     */
    public function isApproved()
    {
        return $this->approval_status === ApprovalStatus::APPROVED;
    }

    /**
     * Check if user is pending approval
     */
    public function isPending()
    {
        return $this->approval_status === ApprovalStatus::WAITING;
    }

    /**
     * Check if user is rejected
     */
    public function isRejected()
    {
        return $this->approval_status === ApprovalStatus::REJECTED;
    }

    /**
     * Get the veteran profile associated with the user.
     */
    public function veteranProfile()
    {
        return $this->hasOne(VeteranProfile::class);
    }

    /**
     * Get help requests created by the veteran.
     */
    public function helpRequestsCreated()
    {
        return $this->hasMany(HelpRequest::class, 'veteran_id');
    }

    /**
     * Get help requests assigned to the volunteer.
     */
    public function helpRequestsAssigned()
    {
        return $this->hasMany(HelpRequest::class, 'volunteer_id');
    }

    /**
     * Get comments made by the user.
     */
    public function comments()
    {
        return $this->hasMany(RequestComment::class);
    }

    /**
     * Get photos uploaded by the user.
     */
    public function photos()
    {
        return $this->hasMany(RequestPhoto::class);
    }
}
