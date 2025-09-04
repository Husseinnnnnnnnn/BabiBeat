<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Userr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialController extends Controller
{
    public function likeSong(Request $request, Song $song)
    {
        DB::table('user_likes')->updateOrInsert(
            ['user_id' => $request->user()->id, 'song_id' => $song->id],
            ['updated_at' => now(), 'created_at' => now()]
        );
        return response()->json(['message' => 'liked']);
    }

    public function unlikeSong(Request $request, Song $song)
    {
        DB::table('user_likes')->where([
            'user_id' => $request->user()->id,
            'song_id' => $song->id,
        ])->delete();
        return response()->json(['message' => 'unliked']);
    }

    public function followUser(Request $request, Userr $user)
    {
        DB::table('user_follows')->updateOrInsert(
            ['follower_id' => $request->user()->id, 'followed_id' => $user->id],
            ['updated_at' => now(), 'created_at' => now()]
        );
        return response()->json(['message' => 'followed']);
    }

    public function unfollowUser(Request $request, Userr $user)
    {
        DB::table('user_follows')->where([
            'follower_id' => $request->user()->id,
            'followed_id' => $user->id,
        ])->delete();
        return response()->json(['message' => 'unfollowed']);
    }
}
