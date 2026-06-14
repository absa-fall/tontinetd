<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToMembresTable extends Migration
{
    public function up()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->enum('role', ['membre', 'gerant', 'admin'])->default('membre')->after('statut');
        });
    }

    public function down()
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}