<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Schools table - linked to users with SCHOOL role
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('school_name');
            $table->enum('school_type', ['Government', 'Private', 'Semi-Government']);
            $table->string('district');
            $table->string('province');
            $table->text('school_address');
            $table->string('contact_number');
            $table->string('cricket_incharge_name');
            $table->string('cricket_incharge_contact');
            // Status for admin approval workflow
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
