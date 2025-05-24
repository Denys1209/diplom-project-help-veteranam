<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HelpRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'veteran_id',
        'category_id',
        'title',
        'description',
        'status',
        'urgency',
        'latitude',
        'longitude',
        'deadline',
        'volunteer_id',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the veteran who created the help request.
     */
    public function veteran()
    {
        return $this->belongsTo(User::class, 'veteran_id');
    }

    /**
     * Get the volunteer assigned to the help request.
     */
    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    /**
     * Get the category of the help request.
     */
    public function category()
    {
        return $this->belongsTo(HelpCategory::class, 'category_id');
    }

    /**
     * Get the comments for the help request.
     */
    public function comments()
    {
        return $this->hasMany(RequestComment::class);
    }

    /**
     * Get the photos for the help request.
     */
    public function photos()
    {
        return $this->hasMany(RequestPhoto::class);
    }

    /**
     * Scope a query to only include pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in-progress requests.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include completed requests.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to include requests by urgency.
     */
    public function scopeByUrgency($query, $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    /**
     * Scope a query to include requests by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }


}
