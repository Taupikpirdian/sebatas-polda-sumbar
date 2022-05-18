<?php

use Illuminate\Database\Seeder;
use App\JumlahPenduduk;

class JumlahPendudukTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 20;
        $jumlah_penduduk->nama_wilayah 	        = 'Kepulauan Mentawai';
        $jumlah_penduduk->pria 	                = 45477;
        $jumlah_penduduk->wanita 	            = 42146;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 9;
        $jumlah_penduduk->nama_wilayah 	        = 'Pesisir Selatan';
        $jumlah_penduduk->pria 	                = 253854;
        $jumlah_penduduk->wanita 	            = 250564;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 13;
        $jumlah_penduduk->nama_wilayah 	        = 'Kab.Solok';
        $jumlah_penduduk->pria 	                = 196899;
        $jumlah_penduduk->wanita 	            = 194598;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 8;
        $jumlah_penduduk->nama_wilayah 	        = 'Sijunjung';
        $jumlah_penduduk->pria 	                = 119126;
        $jumlah_penduduk->wanita 	            = 115919;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 17;
        $jumlah_penduduk->nama_wilayah 	        = 'Tanah Datar';
        $jumlah_penduduk->pria 	                = 186134;
        $jumlah_penduduk->wanita 	            = 185570;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 18;
        $jumlah_penduduk->nama_wilayah 	        = 'Padang Pariaman';
        $jumlah_penduduk->pria 	                = 215038;
        $jumlah_penduduk->wanita 	            = 215588;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 7;
        $jumlah_penduduk->nama_wilayah 	        = 'Agam';
        $jumlah_penduduk->pria 	                = 266848;
        $jumlah_penduduk->wanita 	            = 262290;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 12;
        $jumlah_penduduk->nama_wilayah 	        = 'Lima Puluh Kota';
        $jumlah_penduduk->pria 	                = 191736;
        $jumlah_penduduk->wanita 	            = 191789;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 15;
        $jumlah_penduduk->nama_wilayah 	        = 'Pasaman';
        $jumlah_penduduk->pria 	                = 150798;
        $jumlah_penduduk->wanita 	            = 149053;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 6;
        $jumlah_penduduk->nama_wilayah 	        = 'Solok Selatan';
        $jumlah_penduduk->pria 	                = 92859;
        $jumlah_penduduk->wanita 	            = 89168;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 2;
        $jumlah_penduduk->nama_wilayah 	        = 'Dharmasraya';
        $jumlah_penduduk->pria 	                = 116310;
        $jumlah_penduduk->wanita 	            = 112281;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 10;
        $jumlah_penduduk->nama_wilayah 	        = 'Pasaman Barat';
        $jumlah_penduduk->pria 	                = 218573;
        $jumlah_penduduk->wanita 	            = 213099;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 16;
        $jumlah_penduduk->nama_wilayah 	        = 'Padang';
        $jumlah_penduduk->pria 	                = 456329;
        $jumlah_penduduk->wanita 	            = 452711;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 170;
        $jumlah_penduduk->nama_wilayah 	        = 'Kota Solok';
        $jumlah_penduduk->pria 	                = 36990;
        $jumlah_penduduk->wanita 	            = 36448;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 19;
        $jumlah_penduduk->nama_wilayah 	        = 'Sawahlunto';
        $jumlah_penduduk->pria 	                = 32767;
        $jumlah_penduduk->wanita 	            = 32371;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 4;
        $jumlah_penduduk->nama_wilayah 	        = 'Padang Panjang';
        $jumlah_penduduk->pria 	                = 28286;
        $jumlah_penduduk->wanita 	            = 28025;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 3;
        $jumlah_penduduk->nama_wilayah 	        = 'Bukittinggi';
        $jumlah_penduduk->pria 	                = 60515;
        $jumlah_penduduk->wanita 	            = 60513;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 11;
        $jumlah_penduduk->nama_wilayah 	        = 'Payakumbuh';
        $jumlah_penduduk->pria 	                = 70250;
        $jumlah_penduduk->wanita 	            = 69326;
        $jumlah_penduduk->save();

        $jumlah_penduduk = new JumlahPenduduk();
        $jumlah_penduduk->kategori_bagian_id    = 14;
        $jumlah_penduduk->nama_wilayah 	        = 'Pariaman';
        $jumlah_penduduk->pria 	                = 47571;
        $jumlah_penduduk->wanita 	            = 46653;
        $jumlah_penduduk->save();
    }
}
