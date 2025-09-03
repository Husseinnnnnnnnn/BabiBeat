<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->integer('duration_seconds')->nullable();
            $table->string('genre')->nullable()->index();
            $table->string('audio_url');
            $table->foreignId('album_id')->nullable()->constrained('albums')->nullOnDelete();
            $table->unsignedBigInteger('plays_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->timestamps();

            $table->index(['album_id', 'genre']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('songs');
    }
};
