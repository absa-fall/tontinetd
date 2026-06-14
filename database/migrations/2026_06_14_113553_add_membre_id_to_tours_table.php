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
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('membre_id')->nullable()->after('tontine_id')->constrained('membres')->onDelete('cascade');
            $table->enum('mode_reception', ['presentiel', 'operateur'])->nullable()->after('etat');
            $table->boolean('notifie')->default(false)->after('mode_reception');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeign(['membre_id']);
            $table->dropColumn(['membre_id', 'mode_reception', 'notifie']);
        });
    }
};