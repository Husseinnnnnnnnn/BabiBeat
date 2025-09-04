<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Episode;
use App\Models\UserDownload;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function downloadSong(Request $request, Song $song)
    {
        UserDownload::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'downloadable_id' => $song->id,
                'downloadable_type' => Song::class,
            ],
            ['downloaded_at' => now()]
        );

        return response()->json(['message' => 'downloaded (song)']);
    }

    public function undownloadSong(Request $request, Song $song)
    {
        UserDownload::where([
            'user_id' => $request->user()->id,
            'downloadable_id' => $song->id,
            'downloadable_type' => Song::class,
        ])->delete();

        return response()->json(['message' => 'removed (song)']);
    }

    public function downloadEpisode(Request $request, Episode $episode)
    {
        UserDownload::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'downloadable_id' => $episode->id,
                'downloadable_type' => Episode::class,
            ],
            ['downloaded_at' => now()]
        );

        return response()->json(['message' => 'downloaded (episode)']);
    }

    public function undownloadEpisode(Request $request, Episode $episode)
    {
        UserDownload::where([
            'user_id' => $request->user()->id,
            'downloadable_id' => $episode->id,
            'downloadable_type' => Episode::class,
        ])->delete();

        return response()->json(['message' => 'removed (episode)']);
    }
}
