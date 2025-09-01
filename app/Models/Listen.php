<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','playable_type','playable_id','position_seconds','device','ip','played_at',
    ];

    protected $casts = [
        'position_seconds' => 'integer',
        'played_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Userr::class, 'user_id');
    }


    public function playable()
    {
        return $this->morphTo();
    }
}
