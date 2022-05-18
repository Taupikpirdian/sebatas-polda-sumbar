<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKlasifikasiIdOnTrafficAccidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffic_accidents', function (Blueprint $table) {
            $table->integer('klasifikasi_id')->after('status_id')->nullable();
            $table->integer('faktor_id')->after('klasifikasi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traffic_accidents', function (Blueprint $table) {
            $table->dropColumn('klasifikasi_id');
            $table->dropColumn('faktor_id');
        });
    }
}
