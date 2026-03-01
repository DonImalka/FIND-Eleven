<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('player_match_performances', function (Blueprint $table) {
            $table->foreignId('match_id')->nullable()->after('player_id')
                  ->constrained('cricket_matches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('player_match_performances', function (Blueprint $table) {
            $table->dropForeign(['match_id']);
            $table->dropColumn('match_id');
        });
    }
};
