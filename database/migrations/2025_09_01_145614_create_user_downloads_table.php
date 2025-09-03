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
        Schema::create('user_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('userss')->cascadeOnDelete();
            $table->nullableMorphs('downloadable'); // downloadable_type + downloadable_id
            $table->timestamp('downloaded_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'downloadable_id', 'downloadable_type'], 'user_downloads_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_downloads');
    }
};
