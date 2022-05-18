<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrafficAccidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traffic_accidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('no_lp')->unique();
            $table->date('date_no_lp')->nullable();
            $table->string('kategori_id')->nullable();
            $table->string('kategori_bagian_id')->nullable();
            $table->text('uraian')->nullable();
            $table->text('kerugian_material')->nullable();
            $table->string('nama_petugas')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('no_tlp')->nullable();
            $table->string('saksi')->nullable();
            // $table->text('terlapor')->nullable();
            $table->text('barang_bukti')->nullable();
            $table->string('tkp')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('pin')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->bigInteger('anggaran')->nullable();
            $table->integer('handle_bukti')->nullable();
            $table->integer('soft_delete_id')->nullable();
            $table->string('tanggal_surat_sprint_penyidik')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_document')->nullable();
            $table->string('document')->nullable();
            $table->string('divisi')->nullable();
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
        Schema::dropIfExists('traffic_accidents');
    }
}
