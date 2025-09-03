<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Userr;
use App\Models\Subscription;
use App\Models\Artist;
use App\Models\Album;
use App\Models\Song;
use App\Models\Playlist;
use App\Models\Episode;
use App\Models\Podcast;
use App\Models\Listen;
use App\Models\UserDownload;

class TestCommand extends Command
{
    protected $signature = 'app:test-command';
    protected $description = 'Seed some test data into BabiBeats tables';

    public function handle()
{
    // 1) Subscription (unique by type + billing_cycle)
    $sub = \App\Models\Subscription::firstOrCreate(
        ['type' => 'Premium', 'billing_cycle' => 'monthly'],
        ['price' => 9.99, 'is_active' => true]
    );

    // 2) Users (unique by email)
    $user = \App\Models\Userr::firstOrCreate(
        ['email' => 'hussein@example.com'],
        [
            'username'        => 'hussein',
            'name'            => 'Hussein',
            'password'        => bcrypt('pass123'),
            'avatar_url'      => null,
            'subscription_id' => $sub->id,
        ]
    );

    $other = \App\Models\Userr::firstOrCreate(
        ['email' => 'maria@example.com'],
        [
            'username'        => 'maria',
            'name'            => 'Maria',
            'password'        => bcrypt('pass123'),
            'avatar_url'      => null,
            'subscription_id' => null,
        ]
    );

    // 3) Artist
    $artist = \App\Models\Artist::firstOrCreate(
        ['name' => 'Demo Artist'],
        ['bio' => 'This is a demo artist', 'country' => 'Lebanon', 'image_url' => null]
    );

    // 4) Album (unique by title + artist)
    $album = \App\Models\Album::firstOrCreate(
        ['title' => 'First Album', 'artist_id' => $artist->id],
        ['release_date' => now(), 'cover_url' => null]
    );

    // 5) Song (unique by title + album)
    $song = \App\Models\Song::firstOrCreate(
        ['title' => 'Hello World', 'album_id' => $album->id],
        [
            'duration_seconds' => 200,
            'genre'            => 'Pop',
            'audio_url'        => 'storage/songs/hello.mp3',
            'plays_count'      => 0,
            'likes_count'      => 0,
        ]
    );

    // 6) Podcast + Episode
    $podcast = \App\Models\Podcast::firstOrCreate(
        ['title' => 'Tech Talks'],
        ['description' => 'A demo podcast', 'host' => 'Abed', 'cover_url' => null]
    );

    $episode = \App\Models\Episode::firstOrCreate(
        ['podcast_id' => $podcast->id, 'title' => 'Episode 1'],
        [
            'duration_seconds' => 1800,
            'release_date'     => now(),
            'audio_url'        => 'storage/episodes/ep1.mp3',
            'plays_count'      => 0,
        ]
    );

    // 7) Playlist (unique by user + name)
    $playlist = \App\Models\Playlist::firstOrCreate(
        ['user_id' => $user->id, 'name' => 'My Mix'],
        ['description' => 'Test playlist', 'is_public' => true]
    );

    // Attach song to playlist (ignore if already attached)
    $playlist->songs()->syncWithoutDetaching([$song->id => ['position' => 1]]);

    // 8) Like a song (ignore duplicates)
    \DB::table('user_likes')->updateOrInsert(
        ['user_id' => $user->id, 'song_id' => $song->id],
        ['updated_at' => now(), 'created_at' => now()]
    );

    // 9) Follow another user (ignore duplicates)
    \DB::table('user_follows')->updateOrInsert(
        ['follower_id' => $user->id, 'followed_id' => $other->id],
        ['updated_at' => now(), 'created_at' => now()]
    );

    // 10) User download (Song) - idempotent on (user, downloadable_id, type)
    \App\Models\UserDownload::updateOrCreate(
        [
            'user_id'           => $user->id,
            'downloadable_id'   => $song->id,
            'downloadable_type' => \App\Models\Song::class,
        ],
        ['downloaded_at' => now()]
    );

    // 11) Listen history (allow multiple entries; create a fresh one)
    \App\Models\Listen::create([
        'user_id'         => $user->id,
        'playable_type'   => \App\Models\Song::class,
        'playable_id'     => $song->id,
        'position_seconds'=> 45,
        'device'          => 'web',
        'ip'              => '127.0.0.1',
        'played_at'       => now(),
    ]);

    $this->info('✅ Test data upserted successfully (safe to run multiple times).');
}

}
