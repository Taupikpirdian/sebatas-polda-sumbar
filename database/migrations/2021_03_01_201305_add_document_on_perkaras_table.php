<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentOnPerkarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perkaras', function (Blueprint $table) {
            $table->string('document')->after('tanggal_surat_sprint_penyidik')->nullable();
        });
        Schema::table('perkaras', function (Blueprint $table) {
            $table->date('tgl_document')->after('tanggal_surat_sprint_penyidik')->nullable();
        });
        Schema::table('perkaras', function (Blueprint $table) {
            $table->text('keterangan')->after('tanggal_surat_sprint_penyidik')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perkaras', function (Blueprint $table) {
            $table->dropColumn('document');
        });
        Schema::table('perkaras', function (Blueprint $table) {
            $table->dropColumn('tgl_document');
        });
        Schema::table('perkaras', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
}
