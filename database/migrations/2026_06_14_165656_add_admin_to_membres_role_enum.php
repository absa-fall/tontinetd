<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE membres MODIFY role ENUM('membre', 'gerant', 'admin') NOT NULL DEFAULT 'membre'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE membres MODIFY role ENUM('membre', 'gerant') NOT NULL DEFAULT 'membre'");
    }
};