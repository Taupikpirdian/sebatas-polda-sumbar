<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnPerkarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('korbans', function (Blueprint $table) {
            $table->string('umur_korban')->after('nama')->nullale();
            $table->string('pendidikan_korban')->after('umur_korban')->nullale();
            $table->string('pekerjaan_korban')->after('pendidikan_korban')->nullale();
            $table->string('asal_korban')->after('pekerjaan_korban')->nullale();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('korbans', function (Blueprint $table) {
            $table->dropColumn('umur_korban');
            $table->dropColumn('pendidikan_korban');
            $table->dropColumn('pekerjaan_korban');
            $table->dropColumn('asal_korban');
        });
    }
}
