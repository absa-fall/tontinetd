<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('tontine_id')->constrained('tontines')->onDelete('cascade')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeign(['tontine_id']);
            $table->dropColumn('tontine_id');
        });
    }
};