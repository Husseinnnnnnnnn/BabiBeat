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
        Schema::create('listens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // guest plays possible
            $table->nullableMorphs('playable'); 
            // Song or Episode
            $table->integer('position_seconds')->default(0); // how far the user reached
            $table->string('device')->nullable(); // web, mobile, etc.
            $table->ipAddress('ip')->nullable();
            $table->timestamp('played_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'played_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listens');
    }
};
