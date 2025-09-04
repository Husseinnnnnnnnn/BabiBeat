<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\PodcastController;

//authentication for user
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    //authemtication for user
    Route::get('/auth/me',    [AuthController::class, 'me']);
    Route::post('/auth/logout',[AuthController::class, 'logout']);




    //artists
    Route::apiResource('artists', ArtistController::class);




    //albums
    Route::apiResource('albums',  AlbumController::class);



    //songs
    Route::apiResource('songs',   SongController::class);
    Route::delete('/albums/{album}/songs/{song}', [\App\Http\Controllers\Api\SongController::class, 'destroyFromAlbum']);



    //podcats
    Route::apiResource('podcasts', PodcastController::class);

});

