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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained('podcasts')->cascadeOnDelete();
            $table->string('title')->index();
            $table->integer('duration_seconds')->nullable();
            $table->date('release_date')->nullable();
            $table->string('audio_url');
            $table->unsignedBigInteger('plays_count')->default(0);
            $table->timestamps();

            $table->index(['podcast_id', 'release_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('episodes');
    }
};
