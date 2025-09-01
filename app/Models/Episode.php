<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_id','title','duration_seconds','release_date','audio_url','plays_count',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'release_date' => 'date',
        'plays_count' => 'integer',
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function downloadedBy()
    {
        return $this->morphToMany(Userr::class, 'downloadable', 'user_downloads', 'downloadable_id', 'user_id')
                    ->withPivot('downloaded_at')
                    ->withTimestamps();
    }


    public function listens()
    {
        return $this->morphMany(Listen::class, 'playable');
    }
}
