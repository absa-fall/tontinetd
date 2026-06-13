<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membre_tontine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('tontine_id')->constrained('tontines')->onDelete('cascade');
            $table->enum('role', ['admin', 'membre'])->default('membre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membre_tontine');
    }
};