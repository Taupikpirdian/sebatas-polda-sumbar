<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJumlahPenduduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jumlah_penduduks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('kategori_bagian_id');
            $table->string('nama_wilayah')->nullable();
            $table->integer('pria');
            $table->integer('wanita');
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
        Schema::dropIfExists('jumlah_penduduks');
    }
}
