<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(KategoriTableSeeder::class);
        $this->call(KategoriBagianTableSeeder::class);
        $this->call(JenisPidanaTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(PolsekTableSeeder::class);
        $this->call(JumlahPendudukTableSeeder::class);
        $this->call(FaktorKecelakaanSeeder::class);
        $this->call(KlasifikasiKecelakaanSeeder::class);
        $this->call(KondisiKorbanSeeder::class);
    }
}
