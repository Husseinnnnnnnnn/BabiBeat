<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        return Album::with('artist')
            ->orderBy('created_at','desc')
            ->paginate($request->integer('per_page', 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'release_date' => ['nullable','date'],
            'artist_id'    => ['required','exists:artists,id'],
            'cover_url'    => ['nullable','string','max:1024'],
        ]);
        $album = Album::create($data);
        return response()->json($album->load('artist'), 201);
    }

    public function show(Album $album) { return $album->load('artist'); }

    public function update(Request $request, Album $album)
    {
        $data = $request->validate([
            'title'        => ['sometimes','string','max:255'],
            'release_date' => ['sometimes','nullable','date'],
            'artist_id'    => ['sometimes','exists:artists,id'],
            'cover_url'    => ['sometimes','nullable','string','max:1024'],
        ]);
        $album->update($data);
        return $album->load('artist');
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return response()->json(['message' => 'deleted']);
    }
}
