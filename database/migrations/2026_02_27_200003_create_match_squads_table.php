<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_squads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('cricket_matches')->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->boolean('is_playing_xi')->default(false);
            $table->timestamps();

            $table->unique(['match_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_squads');
    }
};
