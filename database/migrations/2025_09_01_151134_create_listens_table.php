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
        Schema::create('listens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('userss')->nullOnDelete();
            $table->nullableMorphs('playable'); // playable_type + playable_id
            $table->integer('position_seconds')->default(0);
            $table->string('device')->nullable(); // web, mobile
            $table->ipAddress('ip')->nullable();
            $table->timestamp('played_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'played_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('listens');
    }
};
