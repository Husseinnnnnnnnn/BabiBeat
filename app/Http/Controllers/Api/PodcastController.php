<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index(Request $request)
    {
        return Podcast::orderBy('created_at','desc')
            ->paginate($request->integer('per_page', 15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'host'        => ['nullable','string','max:100'],
            'cover_url'   => ['nullable','string','max:1024'],
        ]);
        $podcast = Podcast::create($data);
        return response()->json($podcast, 201);
    }

    public function show(Podcast $podcast) { return $podcast; }

    public function update(Request $request, Podcast $podcast)
    {
        $data = $request->validate([
            'title'       => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string'],
            'host'        => ['sometimes','nullable','string','max:100'],
            'cover_url'   => ['sometimes','nullable','string','max:1024'],
        ]);
        $podcast->update($data);
        return $podcast;
    }

    public function destroy(Podcast $podcast)
    {
        $podcast->delete();
        return response()->json(['message' => 'deleted']);
    }
}
