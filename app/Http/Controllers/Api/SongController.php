<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $q = Song::query()->with(['album.artist']);

        // optional search
        if ($search = $request->query('search')) {
            $q->where(function($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                   ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        // simple pagination
        return $q->orderBy('created_at', 'desc')->paginate($request->integer('per_page', 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => ['required','string','max:255'],
            'duration_seconds' => ['nullable','integer','min:0'],
            'genre'            => ['nullable','string','max:100'],
            'audio_url'        => ['required','string','max:1024'],
            'album_id'         => ['nullable','exists:albums,id'],
            'plays_count'      => ['nullable','integer','min:0'],
            'likes_count'      => ['nullable','integer','min:0'],
        ]);

        $song = Song::create($data);

        return response()->json($song->load('album.artist'), 201);
    }

    public function show(Song $song)
    {
        return $song->load('album.artist');
    }

    public function update(Request $request, Song $song)
    {
        $data = $request->validate([
            'title'            => ['sometimes','string','max:255'],
            'duration_seconds' => ['sometimes','integer','min:0'],
            'genre'            => ['sometimes','string','max:100'],
            'audio_url'        => ['sometimes','string','max:1024'],
            'album_id'         => ['sometimes','nullable','exists:albums,id'],
            'plays_count'      => ['sometimes','integer','min:0'],
            'likes_count'      => ['sometimes','integer','min:0'],
        ]);

        $song->update($data);

        return $song->load('album.artist');
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return response()->json(['message' => 'deleted']);
    }

    

    public function destroyFromAlbum($albumId, $songId)
    {
    // check that the song exists and belongs to that album
        $song = \App\Models\Song::where('id', $songId)
            ->where('album_id', $albumId)
            ->first();

        if (!$song) {
            return response()->json([
                'message' => 'Song not found in this album'
            ], 404);
        }

        $song->delete();

        return response()->json(['message' => 'Song deleted successfully']);
    }

}
