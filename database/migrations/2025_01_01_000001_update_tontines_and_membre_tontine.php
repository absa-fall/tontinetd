<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter nombre_max_membres à la table tontines
        Schema::table('tontines', function (Blueprint $table) {
            $table->unsignedInteger('nombre_max_membres')->default(10)->after('frequence');
        });

        // Ajouter statut à la table pivot membre_tontine
        // statut : en_attente | approuve | refuse
        Schema::table('membre_tontine', function (Blueprint $table) {
            $table->string('statut')->default('approuve')->after('role');
            // 'approuve' par défaut pour ne pas casser les membres déjà ajoutés par le gérant
        });
    }

    public function down(): void
    {
        Schema::table('tontines', function (Blueprint $table) {
            $table->dropColumn('nombre_max_membres');
        });

        Schema::table('membre_tontine', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};