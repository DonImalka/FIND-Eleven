<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_innings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('cricket_matches')->onDelete('cascade');
            $table->foreignId('batting_school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('bowling_school_id')->constrained('schools')->onDelete('cascade');
            $table->tinyInteger('inning_number'); // 1 or 2
            $table->integer('total_runs')->default(0);
            $table->integer('total_wickets')->default(0);
            $table->string('total_overs')->default('0.0'); // e.g. "25.3"
            $table->integer('extras')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();

            $table->unique(['match_id', 'inning_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_innings');
    }
};
