<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccidentVictimtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accident_victimts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('traffic_accident_id')->nullable();
            $table->string('nama')->nullable();
            $table->integer('umur')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('asal')->nullable();
            $table->foreign('traffic_accident_id')->references('id')->on('traffic_accidents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accident_victimts');
    }
}
