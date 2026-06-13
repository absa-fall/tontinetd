<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cotisations', function (Blueprint $table) {
            if (!Schema::hasColumn('cotisations', 'ajout_par')) {
                $table->enum('ajout_par', ['membre', 'admin'])
                    ->default('membre')
                    ->after('date_cotisation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotisations', function (Blueprint $table) {
            if (Schema::hasColumn('cotisations', 'ajout_par')) {
                $table->dropColumn('ajout_par');
            }
        });
    }
};