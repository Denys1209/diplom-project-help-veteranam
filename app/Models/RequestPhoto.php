<?php

namespace App\Models;

use App\Enums\PhotoType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestPhoto extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'help_request_id',
        'user_id',
        'photo_path',
        'caption',
        'is_completion_photo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_completion_photo' => 'boolean',
    ];

    /**
     * Get photo type label in Ukrainian
     *
     * @return string
     */
    public function getPhotoTypeLabel()
    {
        if ($this->is_completion_photo)
        {
            return PhotoType::COMPLETION->label();
        }
        return PhotoType::REGULAR->label();
    }

    /**
     * Get the help request that owns the photo.
     */
    public function helpRequest()
    {
        return $this->belongsTo(HelpRequest::class);
    }

    /**
     * Get the user who uploaded the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
