<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutToMembresTable extends Migration
{
    public function up()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'approuve', 'refuse'])->default('en_attente')->after('password');
        });
    }

    public function down()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
}