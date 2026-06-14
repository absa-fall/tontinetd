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
        DB::statement("ALTER TABLE membres MODIFY statut ENUM('en_attente', 'approuve', 'refuse', 'desactive') NOT NULL DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE membres MODIFY statut ENUM('en_attente', 'approuve', 'refuse') NOT NULL DEFAULT 'en_attente'");
    }
};