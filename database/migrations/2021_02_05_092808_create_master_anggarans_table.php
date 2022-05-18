<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterAnggaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_anggarans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('satker_id')->unsigned();
            $table->bigInteger('total_anggaran');
            $table->timestamps();

            // $table->foreign('satker_id')->references('id')->on('kategori_bagians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_anggarans');
    }
}
