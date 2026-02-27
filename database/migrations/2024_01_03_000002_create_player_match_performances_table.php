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
        Schema::create('player_match_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');

            // Match context
            $table->date('match_date');
            $table->string('opponent')->nullable();
            $table->string('match_description')->nullable(); // e.g. "Quarter Final vs Trinity"

            // ===== BATTING (per match) =====
            $table->unsignedInteger('batting_runs')->default(0);
            $table->unsignedInteger('batting_balls_faced')->default(0);
            $table->unsignedInteger('batting_fours')->default(0);
            $table->unsignedInteger('batting_sixes')->default(0);
            $table->boolean('batting_not_out')->default(false);

            // ===== BOWLING (per match) =====
            $table->decimal('bowling_overs', 5, 1)->default(0); // e.g. 3.4 means 3 overs 4 balls
            $table->unsignedInteger('bowling_maidens')->default(0);
            $table->unsignedInteger('bowling_runs_conceded')->default(0);
            $table->unsignedInteger('bowling_wickets')->default(0);

            // ===== FIELDING (per match) =====
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
        Schema::dropIfExists('player_match_performances');
    }
};
