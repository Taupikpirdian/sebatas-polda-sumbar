<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerkarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perkaras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('no_lp')->unique();
            $table->string('kategori_id');
            $table->string('kategori_bagian_id');
            $table->text('uraian');
            $table->string('nama_petugas');
            $table->string('pangkat');
            $table->string('no_tlp');
            $table->string('lat');
            $table->string('long');
            $table->string('pin');
            $table->date('date');
            $table->string('time');
            $table->string('jenis_pidana');
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('perkaras');
    }
}
