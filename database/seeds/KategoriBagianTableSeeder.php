<?php

use Illuminate\Database\Seeder;
use App\KategoriBagian;

class KategoriBagianTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 1;
        $kategori_bagian->kategori_id = 1;
        $kategori_bagian->name 		  = 'Polda Sumbar';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 2;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Dharmasraya';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 3;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Bukit Tinggi';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 4;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Padang Panjang';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 170; // ini jadi 170 asalnya 5
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Solok Kota';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 6;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Solok Selatan';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 7;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Agam';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 8;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Sijunjung';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 9;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Pesisir Selatan';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 10;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Pasaman Barat';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 11;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Payakumbuh';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 12;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres 50 kota';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 13;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Solok';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 14;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Pariaman';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 15;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Pasaman';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 16;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polresta Padang';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 17;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Tanah Datar';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 18;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Padang Pariaman';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 19;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Sawahlunto';
        $kategori_bagian->save();

        $kategori_bagian = new KategoriBagian();
        $kategori_bagian->id = 20;
        $kategori_bagian->kategori_id = 2;
        $kategori_bagian->name 		  = 'Polres Mentawai';
        $kategori_bagian->save();

    }
}
