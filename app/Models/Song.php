<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','duration_seconds','genre','audio_url','album_id','plays_count','likes_count',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'plays_count' => 'integer',
        'likes_count' => 'integer',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function artist()
    {
        return $this->hasOneThrough(Artist::class, Album::class, 'id', 'id', 'album_id', 'artist_id');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_song')
                    ->withTimestamps()
                    ->withPivot('position')
                    ->orderBy('pivot_position');
    }

    public function likedBy()
    {
        return $this->belongsToMany(Userr::class, 'user_likes', 'song_id', 'user_id')
                    ->withTimestamps();
    }

    public function downloadedBy()
    {
        return $this->morphToMany(Userr::class, 'downloadable', 'user_downloads', 'downloadable_id', 'user_id')
                    ->withPivot('downloaded_at')
                    ->withTimestamps();
    }


    // listens (history entries for this song)
    public function listens()
    {
        return $this->morphMany(Listen::class, 'playable');
    }
}
