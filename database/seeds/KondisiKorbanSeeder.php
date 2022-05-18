<?php

use Illuminate\Database\Seeder;
use App\KondisiKorban;

class KondisiKorbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kondisi = new KondisiKorban();
        $kondisi->id = 1;
        $kondisi->name = 'Meninggal';
        $kondisi->save();

        $kondisi = new KondisiKorban();
        $kondisi->id = 2;
        $kondisi->name = 'Luka Berat';
        $kondisi->save();

        $kondisi = new KondisiKorban();
        $kondisi->id = 3;
        $kondisi->name = 'Luka Ringan';
        $kondisi->save();
    }
}
