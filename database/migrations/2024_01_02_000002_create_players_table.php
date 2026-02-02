<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Players table - only APPROVED schools can create players
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->date('date_of_birth');
            // Age category auto-calculated based on DOB: U13, U15, U17, U19
            $table->enum('age_category', ['U13', 'U15', 'U17', 'U19']);
            // Player category - primary role
            $table->enum('player_category', [
                'Top Order Batter',
                'Power Hitter',
                'Fast Bowler',
                'Medium Bowler',
                'Finger Spin Bowler',
                'Wrist Spin Bowler',
                'Fast Bowling All-Rounder',
                'Spin All-Rounder'
            ]);
            $table->enum('batting_style', ['Right-hand Bat', 'Left-hand Bat']);
            $table->enum('bowling_style', [
                'Right-arm Fast',
                'Left-arm Fast',
                'Right-arm Medium',
                'Left-arm Medium',
                'Right-arm Off Spin',
                'Left-arm Orthodox',
                'Right-arm Leg Spin',
                'Left-arm Chinaman',
                'Does Not Bowl'
            ]);
            $table->string('jersey_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
