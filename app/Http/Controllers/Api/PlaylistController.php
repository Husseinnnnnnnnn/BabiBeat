<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        // Show my playlists only (owner = auth user)
        $q = Playlist::query()
            ->where('user_id', $request->user()->id)
            ->withCount('songs');

        return $q->orderBy('created_at','desc')->paginate($request->integer('per_page', 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'is_public'   => ['boolean'],
        ]);

        $playlist = Playlist::create([
            'user_id'     => $request->user()->id,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'is_public'   => $data['is_public'] ?? true,
        ]);

        return response()->json($playlist, 201);
    }

    public function show(Request $request, Playlist $playlist)
    {
        // authorize owner
        abort_unless($playlist->user_id === $request->user()->id, 403);

        return $playlist->load('songs');
    }

    public function update(Request $request, Playlist $playlist)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'name'        => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string'],
            'is_public'   => ['sometimes','boolean'],
        ]);

        $playlist->update($data);

        return $playlist->load('songs');
    }

    public function destroy(Request $request, Playlist $playlist)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $playlist->delete();
        return response()->json(['message' => 'deleted']);
    }

    // POST /playlists/{playlist}/songs/{song}
    public function attachSong(Request $request, Playlist $playlist, Song $song)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $position = $request->integer('position', null);

        $playlist->songs()->syncWithoutDetaching([
            $song->id => ['position' => $position]
        ]);

        return response()->json(['message' => 'attached']);
    }

    // DELETE /playlists/{playlist}/songs/{song}
    public function detachSong(Request $request, Playlist $playlist, Song $song)
    {
        abort_unless($playlist->user_id === $request->user()->id, 403);

        $playlist->songs()->detach($song->id);

        return response()->json(['message' => 'detached']);
    }
}
