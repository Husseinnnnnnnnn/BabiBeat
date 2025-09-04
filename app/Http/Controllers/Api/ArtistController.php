<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $q = Artist::query();
        if ($s = $request->query('search')) {
            $q->where('name','like',"%{$s}%");
        }
        return $q->orderBy('name')->paginate($request->integer('per_page', 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'bio' => ['nullable','string'],
            'country' => ['nullable','string','max:100'],
            'image_url' => ['nullable','string','max:1024'],
        ]);
        $artist = Artist::create($data);
        return response()->json($artist, 201);
    }

    public function show(Artist $artist) { return $artist; }

    public function update(Request $request, Artist $artist)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'bio' => ['sometimes','nullable','string'],
            'country' => ['sometimes','nullable','string','max:100'],
            'image_url' => ['sometimes','nullable','string','max:1024'],
        ]);
        $artist->update($data);
        return $artist;
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return response()->json(['message' => 'deleted']);
    }
}
