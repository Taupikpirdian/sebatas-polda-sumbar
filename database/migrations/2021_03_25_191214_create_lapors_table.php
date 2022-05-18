<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lapors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('no_stplp')->unique();
            $table->date('date_no_stplp')->nullable();
            $table->string('kategori_id')->nullable();
            $table->string('kategori_bagian_id')->nullable();
            $table->text('uraian')->nullable();
            $table->text('modus_operasi')->nullable();
            $table->string('nama_petugas')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('no_tlp')->nullable();
            $table->string('nama_pelapor')->nullable();
            $table->integer('umur_pelapor')->nullable();
            $table->string('pendidikan_pelapor')->nullable();
            $table->string('pekerjaan_pelapor')->nullable();
            $table->text('asal_pelapor')->nullable();
            $table->string('saksi')->nullable();
            $table->text('terlapor')->nullable();
            $table->text('barang_bukti')->nullable();
            $table->string('tkp')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('pin')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('jenis_pidana')->nullable();
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
        Schema::dropIfExists('lapors');
    }
}
