<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batter_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_inning_id')->constrained('match_innings')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->integer('runs')->default(0);
            $table->integer('balls_faced')->default(0);
            $table->integer('fours')->default(0);
            $table->integer('sixes')->default(0);
            $table->enum('status', ['yet_to_bat', 'batting', 'out', 'not_out', 'retired'])->default('yet_to_bat');
            $table->string('dismissal_info')->nullable(); // e.g. "c Silva b Perera", "bowled", "lbw", "run out"
            $table->integer('batting_position')->default(0);
            $table->timestamps();

            $table->unique(['match_inning_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batter_scores');
    }
};
