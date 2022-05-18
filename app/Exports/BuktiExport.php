<?php

namespace App\Exports;

use DB;
use App\Perkara;
use App\JenisPidana;
use App\KategoriBagian;
use App\BuktiLain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class BuktiExport implements FromView
{
    use Exportable;
    
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function view(): View
    {
        // parameter
        $param = (explode(", ",$this->name));
        $no_lp           = $param[0];
        $satker          = $param[1];
        $jenis_pidana    = $param[2];
        $no_rangka       = $param[3];
        $no_mesin        = $param[4];
        $jenis_kendaraan = $param[5];

        // get data
        $buktis = BuktiLain::select(
            'perkaras.id as perkara_id',
            'perkaras.no_lp',
            'kategori_bagians.name as satuan',
            'jenis_pidanas.name as pidana',
            'bukti_lains.id',
            'bukti_lains.no_rangka',
            'bukti_lains.no_mesin',
            'bukti_lains.kode_kendaraan'
            )->join('perkaras', 'perkaras.id', '=', 'bukti_lains.perkara_id')
            ->join('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->join('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->orderBy('bukti_lains.created_at', 'desc')
            ->when(!empty($param[0]), function ($query) use ($no_lp) {
                $query->where('perkaras.no_lp', 'like', "%{$no_lp}%");
            })
            ->when(!empty($param[1]), function ($query) use ($satker) {
                $query->where('kategori_bagians.name', 'like', "%{$satker}%");
            })
            ->when(!empty($param[2]), function ($query) use ($jenis_pidana) {
                $query->where('jenis_pidanas.name', 'like', "%{$jenis_pidana}%");
            })
            ->when(!empty($param[3]), function ($query) use ($no_rangka) {
                $query->where('no_rangka', 'like', "%{$no_rangka}%");
            })
            ->when(!empty($param[4]), function ($query) use ($no_mesin) {
                $query->where('no_mesin', 'like', "%{$no_mesin}%");
            })
            ->when(!empty($param[5]), function ($query) use ($jenis_kendaraan) {
                $query->where('kode_kendaraan', 'like', "%{$jenis_kendaraan}%");
            })
            ->get();

        return view('excel.bukti', [
            'buktis' => $buktis
        ]);

    }
}
