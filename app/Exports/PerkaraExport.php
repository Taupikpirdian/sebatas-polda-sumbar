<?php

namespace App\Exports;

use DB;
use App\Perkara;
use App\JenisPidana;
use App\KategoriBagian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PerkaraExport implements FromView
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
    	$param = (explode(", ",$this->name));

    	// foreach ($aa as $key => $value) {
     //      echo "<pre>";
     //      print_r(":p");
     //      echo "</pre>";
     //    }
     //    exit();

	    if($param[0] && $param[1]){

	        return view('excel.rekapitulasi_satker_pidana', [
	            'rekapitulasis' => DB::table('perkaras')
	                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
	                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
	                ->select('kategori_bagians.name', 'jenis_pidanas.name as pidana', DB::raw('count(kategori_bagian_id) as total'))
	                ->groupBy('kategori_bagians.name', 'jenis_pidanas.name')
	                ->where('kategori_bagian_id', $param[0])
	                ->where('jenis_pidana', $param[1])
	                ->get()
	        ]);
	        
        }elseif($param[0]){

	        return view('excel.rekapitulasi_satker', [
	            'rekapitulasis' => DB::table('perkaras')
	                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
	                ->select('kategori_bagians.name', DB::raw('count(kategori_bagian_id) as total'))
	                ->groupBy('kategori_bagians.name')
	                ->where('kategori_bagian_id', $param[0])
	                ->get()
	        ]);

        }elseif ($param[1]) {

	        return view('excel.rekapitulasi', [
	            'rekapitulasis' => DB::table('perkaras')
	                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
	                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
	                ->select('kategori_bagians.name', 'jenis_pidanas.name as pidana', DB::raw('count(kategori_bagian_id) as total'))
	                ->groupBy('kategori_bagians.name', 'jenis_pidanas.name')
	                ->where('jenis_pidana', $param[1])
	                ->get()
	        ]);

        } else{

	        $rekap = DB::table('perkaras')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->select('kategori_bagians.name', 'kategori_bagian_id', DB::raw('count(kategori_bagian_id) as total'))
            ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
            ->orderBy('kategori_bagians.name', 'asc')
            ->get();

	        foreach ($rekap as $key => $add) {
	            $rekap_selesai = DB::table('perkaras')
	                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
	                ->select('kategori_bagians.name', 'kategori_bagian_id', DB::raw('count(kategori_bagian_id) as total'))
	                ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
	                ->orderBy('kategori_bagians.name', 'asc')
	                ->where('status_id', 1)
	                ->get();


	            foreach ($rekap_selesai as $key => $add2) {
	                if($add->kategori_bagian_id == $add2->kategori_bagian_id){
	                    $add->kasus_selesai = $add->total - $add2->total;
	                }
	            }
	        }

	        foreach ($rekap as $key => $countArray) {
	            $count = count(get_object_vars($countArray));
	            $countArray->array = $count;
	        }

	        // hitung persentase kasus selesai
	        foreach ($rekap as $key => $persentase) {
	            if($persentase->array == 3){
	                $persentase->percent_success = 0;
	            }else{
	                $persentase->percent_success = number_format(($persentase->kasus_selesai/$persentase->total)*100);
	            }
	        }

        	return view('excel.rekapitulasi_all', [
	            'rekap' => $rekap
	        ]);
        }

    }
}
