<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add dot balls to match performances
        Schema::table('player_match_performances', function (Blueprint $table) {
            $table->unsignedInteger('bowling_dot_balls')->default(0)->after('bowling_wickets');
        });

        // Add dot balls and ranking points to aggregate stats
        Schema::table('player_stats', function (Blueprint $table) {
            $table->unsignedInteger('bowling_dot_balls')->default(0)->after('bowling_strike_rate');
            $table->unsignedInteger('ranking_points')->default(0)->after('fielding_stumpings');
        });
    }

    public function down(): void
    {
        Schema::table('player_match_performances', function (Blueprint $table) {
            $table->dropColumn('bowling_dot_balls');
        });
        Schema::table('player_stats', function (Blueprint $table) {
            $table->dropColumn(['bowling_dot_balls', 'ranking_points']);
        });
    }
};
