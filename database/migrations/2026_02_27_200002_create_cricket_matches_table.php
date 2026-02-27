<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cricket_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('home_school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('away_school_id')->constrained('schools')->onDelete('cascade');
            $table->date('match_date');
            $table->string('venue');
            $table->integer('overs_per_side')->default(20);
            $table->enum('status', ['upcoming', 'live', 'completed', 'cancelled'])->default('upcoming');
            $table->foreignId('toss_winner_school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->enum('toss_decision', ['bat', 'bowl'])->nullable();
            $table->text('result_summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cricket_matches');
    }
};
