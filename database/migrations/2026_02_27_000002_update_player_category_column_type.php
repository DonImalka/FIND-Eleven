<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('players')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE players MODIFY player_category VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE players ALTER COLUMN player_category TYPE VARCHAR(255)');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE players ALTER COLUMN player_category NVARCHAR(255) NOT NULL');
        } else {
            // sqlite or other drivers: enum is stored as TEXT, no change required
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: reverting to enum is not required for this feature.
    }
};
