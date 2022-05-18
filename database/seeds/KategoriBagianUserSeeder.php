<?php

use Illuminate\Database\Seeder;
use App\KategoriBagianUser;

class KategoriBagianUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Polda - Ditreskrimsus
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 2;
        $kategori_bagian->sakter_id 	= 179;
        $kategori_bagian->save();

        // Polda - Ditreskrimum
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 3;
        $kategori_bagian->sakter_id 	= 171;
        $kategori_bagian->save();

        // Polda - Ditresnarkoba
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 4;
        $kategori_bagian->sakter_id 	= 1;
        $kategori_bagian->save();

        // Polres Dharmasraya - Satreskrim
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 5;
        $kategori_bagian->sakter_id 	= 2;
        $kategori_bagian->save();

        // Polres Dharmasraya - Satnarkoba
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 6;
        $kategori_bagian->sakter_id 	= 2;
        $kategori_bagian->save();

        // Polres Dharmasraya - Unit Reskrim
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 7;
        $kategori_bagian->sakter_id 	= 2;
        $kategori_bagian->save();

        // Polres Bukit Tinggi - Satreskrim
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 8;
        $kategori_bagian->sakter_id 	= 3;
        $kategori_bagian->save();

        // Polres Bukit Tinggi - Satnarkoba
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 9;
        $kategori_bagian->sakter_id 	= 3;
        $kategori_bagian->save();

        // Polres Bukit Tinggi - Unit Reskrim
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 10;
        $kategori_bagian->sakter_id 	= 3;
        $kategori_bagian->save();

        // Polres Padang Panjang - Satreskrim
        $kategori_bagian = new KategoriBagianUser();
        $kategori_bagian->user_id 	    = 11;
        $kategori_bagian->sakter_id 	= 4;
        $kategori_bagian->save();
    }
}
