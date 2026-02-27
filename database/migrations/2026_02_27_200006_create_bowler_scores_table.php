<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bowler_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_inning_id')->constrained('match_innings')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->string('overs')->default('0'); // e.g. "4.2"
            $table->integer('maidens')->default(0);
            $table->integer('runs_conceded')->default(0);
            $table->integer('wickets')->default(0);
            $table->timestamps();

            $table->unique(['match_inning_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bowler_scores');
    }
};
