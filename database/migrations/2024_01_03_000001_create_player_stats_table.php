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
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');

            // ===== BATTING STATS (for batters, all-rounders, power hitters) =====
            $table->unsignedInteger('batting_matches')->default(0);
            $table->unsignedInteger('batting_innings')->default(0);
            $table->unsignedInteger('batting_runs')->default(0);
            $table->unsignedInteger('batting_balls_faced')->default(0);
            $table->unsignedInteger('batting_not_outs')->default(0);
            $table->unsignedInteger('batting_highest_score')->default(0);
            $table->unsignedInteger('batting_fifties')->default(0);
            $table->unsignedInteger('batting_hundreds')->default(0);
            $table->unsignedInteger('batting_fours')->default(0);
            $table->unsignedInteger('batting_sixes')->default(0);
            $table->decimal('batting_average', 8, 2)->default(0);
            $table->decimal('batting_strike_rate', 8, 2)->default(0);

            // ===== BOWLING STATS (for bowlers, all-rounders) =====
            $table->unsignedInteger('bowling_matches')->default(0);
            $table->unsignedInteger('bowling_innings')->default(0);
            $table->unsignedInteger('bowling_overs')->default(0);
            $table->unsignedInteger('bowling_maidens')->default(0);
            $table->unsignedInteger('bowling_runs_conceded')->default(0);
            $table->unsignedInteger('bowling_wickets')->default(0);
            $table->unsignedInteger('bowling_best_wickets')->default(0);  // best figures: wickets
            $table->unsignedInteger('bowling_best_runs')->default(0);     // best figures: runs
            $table->unsignedInteger('bowling_five_wickets')->default(0);
            $table->decimal('bowling_average', 8, 2)->default(0);
            $table->decimal('bowling_economy', 8, 2)->default(0);
            $table->decimal('bowling_strike_rate', 8, 2)->default(0);

            // ===== FIELDING STATS (for all players) =====
            $table->unsignedInteger('fielding_catches')->default(0);
            $table->unsignedInteger('fielding_run_outs')->default(0);
            $table->unsignedInteger('fielding_stumpings')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
