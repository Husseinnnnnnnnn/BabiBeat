<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        return Episode::with('podcast')
            ->orderBy('created_at','desc')
            ->paginate($request->integer('per_page', 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'podcast_id'       => ['required','exists:podcasts,id'],
            'title'            => ['required','string','max:255'],
            'duration_seconds' => ['nullable','integer','min:0'],
            'release_date'     => ['nullable','date'],
            'audio_url'        => ['required','string','max:1024'],
            'plays_count'      => ['nullable','integer','min:0'],
        ]);
        $episode = Episode::create($data);
        return response()->json($episode->load('podcast'), 201);
    }

    public function show(Episode $episode) { return $episode->load('podcast'); }

    public function update(Request $request, Episode $episode)
    {
        $data = $request->validate([
            'podcast_id'       => ['sometimes','exists:podcasts,id'],
            'title'            => ['sometimes','string','max:255'],
            'duration_seconds' => ['sometimes','integer','min:0'],
            'release_date'     => ['sometimes','nullable','date'],
            'audio_url'        => ['sometimes','string','max:1024'],
            'plays_count'      => ['sometimes','integer','min:0'],
        ]);
        $episode->update($data);
        return $episode->load('podcast');
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();
        return response()->json(['message' => 'deleted']);
    }

    public function destroyFromPodcast($podcastId, $episodeId)
    {
        $episode = \App\Models\Episode::where('id', $episodeId)
            ->where('podcast_id', $podcastId)
            ->first();

        if (!$episode) {
            return response()->json(['message' => 'Episode not found in this podcast'], 404);
        }

        // (Optional) authorize here if podcasts are user-owned:
        // abort_unless($episode->podcast->user_id === request()->user()->id, 403);

        $episode->delete();

        return response()->json(['message' => 'Episode deleted successfully']);
    }

}
