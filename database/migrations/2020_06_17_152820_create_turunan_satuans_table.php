<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurunanSatuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turunan_satuans', function (Blueprint $table) {
            $table->unsignedBigInteger('satker_id');
            $table->unsignedBigInteger('satker_turunan_id');
            $table->primary('satker_turunan_id');
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
        Schema::dropIfExists('turunan_satuans');
    }
}
