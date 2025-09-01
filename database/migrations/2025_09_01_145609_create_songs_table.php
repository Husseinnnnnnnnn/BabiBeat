<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->integer('duration_seconds')->nullable(); // duration in seconds
            $table->string('genre')->nullable()->index();
            $table->string('audio_url'); // storage path / CDN URL
            $table->foreignId('album_id')->nullable()->constrained('albums')->nullOnDelete();
            $table->unsignedBigInteger('plays_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->timestamps();
            $table->index(['album_id', 'genre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
