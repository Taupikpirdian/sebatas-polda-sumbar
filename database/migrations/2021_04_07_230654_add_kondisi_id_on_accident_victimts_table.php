<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKondisiIdOnAccidentVictimtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accident_victimts', function (Blueprint $table) {
            $table->integer('kondisi_id')->after('asal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accident_victimts', function (Blueprint $table) {
            $table->dropColumn('kondisi_id');
        });
    }
}
