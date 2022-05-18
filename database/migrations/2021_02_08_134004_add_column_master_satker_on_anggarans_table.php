<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMasterSatkerOnAnggaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->integer('master_sakter_id')->after('perkara_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->dropColumn('master_sakter_id');
        });
    }
}
