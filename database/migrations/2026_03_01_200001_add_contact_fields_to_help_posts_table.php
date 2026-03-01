<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('help_posts', function (Blueprint $table) {
            $table->string('contact_number')->nullable()->after('description');
            $table->string('contact_email')->nullable()->after('contact_number');
        });
    }

    public function down(): void
    {
        Schema::table('help_posts', function (Blueprint $table) {
            $table->dropColumn(['contact_number', 'contact_email']);
        });
    }
};
