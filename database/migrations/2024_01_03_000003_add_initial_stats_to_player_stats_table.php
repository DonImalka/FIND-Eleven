<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add initial_stats JSON column to store baseline career stats
     * entered before the player was registered in the system.
     */
    public function up(): void
    {
        Schema::table('player_stats', function (Blueprint $table) {
            $table->json('initial_stats')->nullable()->after('player_id');
        });
    }

    public function down(): void
    {
        Schema::table('player_stats', function (Blueprint $table) {
            $table->dropColumn('initial_stats');
        });
    }
};
