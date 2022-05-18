<?php

use Illuminate\Database\Seeder;
use App\KlasfikasiKecelakaan;

class KlasifikasiKecelakaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $klasifikasi = new KlasfikasiKecelakaan();
        $klasifikasi->id = 1;
        $klasifikasi->name = 'Ringan';
        $klasifikasi->save();

        $klasifikasi = new KlasfikasiKecelakaan();
        $klasifikasi->id = 2;
        $klasifikasi->name = 'Sedang';
        $klasifikasi->save();

        $klasifikasi = new KlasfikasiKecelakaan();
        $klasifikasi->id = 3;
        $klasifikasi->name = 'Berat/Menonjol';
        $klasifikasi->save();
    }
}
