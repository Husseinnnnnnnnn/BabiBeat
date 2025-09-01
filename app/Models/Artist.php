<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = ['name','bio','country','image_url'];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    // convenience: all songs through albums
    public function songs()
    {
        return $this->hasManyThrough(Song::class, Album::class);
    }
}
