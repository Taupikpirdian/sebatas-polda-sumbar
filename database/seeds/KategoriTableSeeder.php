<?php

use Illuminate\Database\Seeder;
use App\Kategori;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = new Kategori();
        $kategori->id = 1;
        $kategori->name = 'Polda';
        $kategori->save();

        $kategori = new Kategori();
        $kategori->id = 2;
        $kategori->name = 'Polres';
        $kategori->save();

        $kategori = new Kategori();
        $kategori->id = 3;
        $kategori->name = 'Polsek';
        $kategori->save();
    }
}
