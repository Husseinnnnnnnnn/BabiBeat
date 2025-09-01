<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','description','is_public'];
    protected $casts = ['is_public' => 'boolean'];

    public function owner()
    {
        return $this->belongsTo(Userr::class, 'user_id');
    }


    public function songs()
    {
        return $this->belongsToMany(Song::class, 'playlist_song')
                    ->withTimestamps()
                    ->withPivot('position')
                    ->orderBy('pivot_position');
    }
}
