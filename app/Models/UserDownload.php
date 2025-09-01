<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDownload extends Model
{
    use HasFactory;

    protected $table = 'user_downloads';

    protected $fillable = ['user_id','downloadable_type','downloadable_id','downloaded_at'];

    protected $casts = ['downloaded_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloadable()
    {
        return $this->morphTo();
    }
}
