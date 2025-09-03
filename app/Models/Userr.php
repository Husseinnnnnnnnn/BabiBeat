<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Userr extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'userss';

    protected $fillable = [
        'username','name','email','password','avatar_url','subscription_id',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // --- Relationships ---

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }


    public function playlists()
    {
        return $this->hasMany(Playlist::class, 'user_id');
    }

    public function likedSongs()
    {
        return $this->belongsToMany(Song::class, 'user_likes', 'user_id', 'song_id')
                    ->withTimestamps();
    }

    public function followers()
    {
        // users who follow ME
        return $this->belongsToMany(Userr::class, 'user_follows', 'followed_id', 'follower_id')
                    ->withTimestamps();
    }

    public function following()
    {
        // users I follow
        return $this->belongsToMany(Userr::class, 'user_follows', 'follower_id', 'followed_id')
                    ->withTimestamps();
    }

    public function downloadedSongs()
    {
        return $this->morphedByMany(Song::class, 'downloadable', 'user_downloads', 'user_id')
                    ->withPivot('downloaded_at')
                    ->withTimestamps();
    }

    public function downloadedEpisodes()
    {
        return $this->morphedByMany(Episode::class, 'downloadable', 'user_downloads', 'user_id')
                    ->withPivot('downloaded_at')
                    ->withTimestamps();
    }

    public function listens()
    {
        return $this->hasMany(Listen::class, 'user_id');
    }
}
