<?php

use Illuminate\Database\Seeder;
use App\FaktorKecelakaan;

class FaktorKecelakaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faktor = new FaktorKecelakaan();
        $faktor->id = 1;
        $faktor->name = 'Cuaca/Iklim';
        $faktor->save();
        
        $faktor = new FaktorKecelakaan();
        $faktor->id = 2;
        $faktor->name = 'Kelalaian Manusia';
        $faktor->save();
        
        $faktor = new FaktorKecelakaan();
        $faktor->id = 3;
        $faktor->name = 'Kondisi Kendaraan';
        $faktor->save();
        
        $faktor = new FaktorKecelakaan();
        $faktor->id = 4;
        $faktor->name = 'Kondisi / Fasilitas Jalan yang Buruk';
        $faktor->save();
        
        $faktor = new FaktorKecelakaan();
        $faktor->id = 5;
        $faktor->name = 'Lain lain';
        $faktor->save();
    }
}
