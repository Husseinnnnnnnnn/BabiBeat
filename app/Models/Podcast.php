<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','host','cover_url'];

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
