<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\Perkara;
use App\KategoriBagian;
use App\TurunanSatuan;
use App\JenisPidana;    
use App\Status;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ambil data dan di groupby sesuai satker
        $rekap = DB::table('perkaras')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->select('kategori_bagians.name', 'kategori_bagian_id', DB::raw('count(kategori_bagian_id) as total'))
            ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
            ->orderBy('kategori_bagians.name', 'asc')
            ->get();

        // hitung jumlah kasus selesai
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

        // hitung jumlah data untuk parameter
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

        $count_kasus = Perkara::count();
        $count_kasus_selesai = Perkara::where('status_id', '!=', 1)->count();
        $count_kasus_belum_selesai = Perkara::where('status_id', 1)->count();

        if($count_kasus > 0){
	        $persentase = ($count_kasus_selesai / $count_kasus)*100;
	        $english_format_number = number_format($persentase);
	      }else{
	        $english_format_number = 0;
	      }

        return view('admin.laporan.rekap', compact('rekap', 'rekap_selesai', 'count_kasus', 'count_kasus_selesai', 'count_kasus_belum_selesai', 'english_format_number'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rekapPolda(Request $request)
    {
        // params
        $divisi = $request->divisi;
        $search = $request->search;

        $data = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.kategori_id', 1)
            ->when(!empty($divisi), function ($query) use ($divisi) {
              $query->where('kategori_bagian_id', $divisi);
            })
            ->where(function ($query) use ($search) {
              $query->where('perkaras.no_lp', 'like', "%$search%")
                ->orWhere('kategori_bagians.name', 'like', "%$search%")
                ->orWhere('perkaras.nama_petugas', 'like', "%$search%")
                ->orWhere('korbans.nama', 'like', "%$search%")
                ->orWhere('jenis_pidanas.name', 'like', "%$search%");
            })
            ->paginate(25);

        // Ditreskrimsus Pie Selesai
        $pie_polda = Perkara::where('kategori_bagian_id', $divisi)
            ->where('status_id', '!=', 1)
            ->count();

        // Ditreskrimsus Pie Belum Selesai
        $pie_polda_belum = Perkara::where('kategori_bagian_id', $divisi)
            ->where('status_id', '=', 1)
            ->count();

        /** untuk label */
        $satker_fr_param = KategoriBagian::where('id', $divisi)->first();

        return view('admin.laporan.rekap_polda', compact(
            'pie_polda',
            'pie_polda_belum',
            'data',
            'divisi',
            'satker_fr_param'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listRekapPolres()
    {
        $data = KategoriBagian::orderBy('name', 'asc')->where('kategori_id', 2)->get();
        // Satreskrim Pie Selesai
        $pie_satreskrim = Perkara::where('divisi', 'Satreskrim')
            ->where('status_id', '!=', 1)
            ->count();

        // Satreskrim Pie Belum Selesai
        $pie_satreskrim_belum = Perkara::where('divisi', 'Satreskrim')
            ->where('status_id', '=', 1)
            ->count();

        // Satnarkoba Pie Selesai
        $pie_satnarkoba = Perkara::where('divisi', 'Satnarkoba')
            ->where('status_id', '!=', 1)
            ->count();

        // Satnarkoba Pie Belum Selesai
        $pie_satnarkoba_belum = Perkara::where('divisi', 'Satnarkoba')
            ->where('status_id', '=', 1)
            ->count();

            return view('admin.laporan.list_rekap_polres', compact(
            'pie_satreskrim',
            'pie_satreskrim_belum',
            'pie_satnarkoba',
            'pie_satnarkoba_belum',
            'data'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rekapPolres(Request $request)
    {
      // params
      $polres_param = $request->polres;
      $divisi_param = $request->divisi;
      $search       = $request->search;
      $unit         = $request->unit;
      // label
      $label_polres = KategoriBagian::where('id', $polres_param)->first();

      // data
      $data = Perkara::orderBy('perkaras.updated_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
          ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
          ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
          ->select(
              'perkaras.id',
              'perkaras.no_lp',
              'kategori_bagians.name as satuan',
              'perkaras.nama_petugas',
              'korbans.nama',
              'korbans.barang_bukti',
              'perkaras.date',
              'perkaras.time',
              'perkaras.divisi',
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('perkaras.divisi', '=', $divisi_param)
          ->where('perkaras.kategori_bagian_id', $polres_param)
          ->where('perkaras.kategori_id', 2)
          ->where(function ($query) use ($search) {
            $query->where('perkaras.no_lp', 'like', "%$search%")
              ->orWhere('kategori_bagians.name', 'like', "%$search%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search%")
              ->orWhere('korbans.nama', 'like', "%$search%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search%");
          })
          ->paginate(25);

      // data pie
      $pie = Perkara::where('divisi', $divisi_param)
          ->where('status_id', '!=', 1)
          ->where('perkaras.kategori_bagian_id', $polres_param)
          ->count();

      $pie_belum = Perkara::where('divisi', $divisi_param)
          ->where('status_id', '=', 1)
          ->where('perkaras.kategori_bagian_id', $polres_param)
          ->count();

      // data turunan
      $dataTurunans = TurunanSatuan::select([
            'perkaras.id',
            'perkaras.no_lp',
            'kategori_bagians.name as satuan',
            'perkaras.nama_petugas',
            'korbans.nama',
            'korbans.barang_bukti',
            'perkaras.date',
            'perkaras.time',
            'perkaras.divisi',
            'jenis_pidanas.name as pidana',
            'perkaras.status_id',
            'statuses.name as status'
        ])->where('turunan_satuans.satker_id', $polres_param)
          ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
          ->join('korbans', 'perkaras.no_lp', '=', 'korbans.no_lp')
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('statuses', 'perkaras.status_id', '=', 'statuses.id')
          ->join('jenis_pidanas', 'perkaras.jenis_pidana', '=', 'jenis_pidanas.id')
          ->where(function ($query) use ($unit) {
            $query->where('perkaras.no_lp', 'like', "%$unit%")
              ->orWhere('kategori_bagians.name', 'like', "%$unit%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$unit%")
              ->orWhere('korbans.nama', 'like', "%$unit%")
              ->orWhere('jenis_pidanas.name', 'like', "%$unit%");
          })
          ->paginate(25);

      // data statistik
      // ambil data dan di groupby sesuai satker
      $grouping = TurunanSatuan::select([
          'kategori_bagians.name',
          'kategori_bagian_id',
          DB::raw('count(kategori_bagian_id) as total')
      ])->where('turunan_satuans.satker_id', $polres_param)
        ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
        ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
        ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
        ->orderBy('kategori_bagians.name', 'asc')
        ->get();

      // hitung jumlah kasus selesai
      foreach ($grouping as $key => $add) {
          $rekap_blm_selesai = TurunanSatuan::select([
              'kategori_bagians.name',
              'kategori_bagian_id',
              DB::raw('count(kategori_bagian_id) as total')
            ])->where('turunan_satuans.satker_id', $polres_param)
              ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
              ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
              ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
              ->orderBy('kategori_bagians.name', 'asc')
              ->where('status_id', 1)
              ->get();

          foreach ($rekap_blm_selesai as $key => $add2) {
              if($add->kategori_bagian_id == $add2->kategori_bagian_id){
                  $add->kasus_selesai = $add->total - $add2->total;
              }
          }
      }

      // hitung jumlah data untuk parameter
      foreach ($grouping as $key => $countArray) {
          $count = count(get_object_vars($countArray));
          $countArray->array = $count;
      }

      // hitung persentase kasus selesai
      foreach ($grouping as $key => $persentase) {
          if($persentase->array == 3){
              $persentase->percent_success = 0;
          }else{
              $persentase->percent_success = number_format(($persentase->kasus_selesai/$persentase->total)*100);
          }
      }

      // hitung total kasus
      $count_kasus = TurunanSatuan::join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                    ->where('turunan_satuans.satker_id', $polres_param)
                    ->count();
      
      // hitung total kasus selesai
      $count_kasus_selesai = TurunanSatuan::join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                    ->where('turunan_satuans.satker_id', $polres_param)
                    ->where('status_id', '!=', 1)
                    ->count();

      // hitung total kasus belum selesai
      $count_kasus_belum_selesai = TurunanSatuan::join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                    ->where('turunan_satuans.satker_id', $polres_param)
                    ->where('status_id', 1)
                    ->count();

      if($count_kasus > 0){
        $persentase = ($count_kasus_selesai / $count_kasus)*100;
        $english_format_number = number_format($persentase);
      }else{
        $english_format_number = 0;
      }

      return view('admin.laporan.rekap_polres', compact(
          'pie',
          'pie_belum',
          'data',
          'divisi_param',
          'label_polres',
          'dataTurunans',
          'polres_param',
          'grouping',
          'count_kasus_selesai',
          'count_kasus_belum_selesai',
          'count_kasus'
      ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listRekapPolsek()
    {
        $data = KategoriBagian::orderBy('name', 'asc')->where('kategori_id', 3)->get();
        
        // Unit Reskrim Pie Selesai
        $pie_unit_reskrim = Perkara::where('kategori_id', '3')
            ->where('status_id', '!=', 1)
            ->count();

        // Unit Reskrim Pie Belum Selesai
        $pie_unit_reskrim_belum = Perkara::where('kategori_id', '3')
            ->where('status_id', '=', 1)
            ->count();

        return view('admin.laporan.list_rekap_polsek', compact(
            'pie_unit_reskrim',
            'pie_unit_reskrim_belum',
            'data'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rekapPolsek($id, Request $request)
    {
        $start       = $request->get('start_date');
        $end         = $request->get('end_date');
        $date_range  = array($request->get('start_date'), $request->get('end_date'));
        $no_lp       = $request->no_lp;
        $petugas     = $request->petugas;
        $korban      = $request->korban;
        $pidana_id   = $request->pidana;
        $status_id   = $request->status;
        $bukti       = $request->bukti;
        $satuan = KategoriBagian::where('id', $id)->first();
        $data_unit_reskrim = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.divisi', '=', 'Unit Reskrim')
            ->where('perkaras.kategori_bagian_id', $id)
            ->when(!empty($request->no_lp), function ($query) use ($request) {
                $query->where('perkaras.no_lp', 'like', "%{$request->no_lp}%");
            })
            ->when(!empty($request->petugas), function ($query) use ($request) {
                $query->where('nama_petugas', 'like', "%{$request->petugas}%");
            })
            ->when(!empty($request->korban), function ($query) use ($request) {
                $query->where('korbans.nama', 'like', "%{$request->korban}%");
            })
            ->when(!empty($request->bukti), function ($query) use ($request) {
                $query->where('barang_bukti', 'like', "%{$request->bukti}%");
            })
            ->when(!empty($request->start_date && $request->end_date), function ($query) use ($date_range) {
                $query->whereBetween('date', $date_range);
            })
            ->when(!empty($request->pidana), function ($query) use ($request) {
                $query->where('jenis_pidana', $request->pidana);
              })
            ->when(!empty($request->status), function ($query) use ($request) {
                $query->where('status_id', $request->status);
            })
            ->paginate(25);

        // Unit Reskrim Pie Selesai
        $pie_unit_reskrim = Perkara::where('kategori_id', '3')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('status_id', '!=', 1)
            ->count();

        // Unit Reskrim Pie Belum Selesai
        $pie_unit_reskrim_belum = Perkara::where('kategori_id', '3')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('status_id', '=', 1)
            ->count();

        $jenispidanas = JenisPidana::orderBy('name', 'asc')->get();
        $statuses = Status::orderBy('name', 'asc')->get();
        return view('admin.laporan.rekap_polsek', compact(
            'pie_unit_reskrim', 
            'pie_unit_reskrim_belum', 
            'data_unit_reskrim', 
            'satuan',
            'id',
            'no_lp',
            'petugas',
            'korban',
            'bukti',
            'status_id',
            'pidana_id',
            'jenispidanas',
            'statuses'
        ));
    }

    public function rekapPolsekFilter(Request $request)
    {
        $id = $request->id;
        $satuan = KategoriBagian::where('id', $id)->first();
        $data_unit_reskrim = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.divisi', '=', 'Unit Reskrim')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('perkaras.no_lp', 'LIKE','%'.$request->search.'%')
            ->paginate(25);

        // Unit Reskrim Pie Selesai
        $pie_unit_reskrim = Perkara::where('kategori_id', '3')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('status_id', '!=', 1)
            ->where('perkaras.no_lp', 'LIKE','%'.$request->search.'%')
            ->count();

        // Unit Reskrim Pie Belum Selesai
        $pie_unit_reskrim_belum = Perkara::where('kategori_id', '3')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('perkaras.no_lp', 'LIKE','%'.$request->search.'%')
            ->where('status_id', '=', 1)
            ->count();

        return view('admin.laporan.rekap_polsek', compact(
            'pie_unit_reskrim', 
            'pie_unit_reskrim_belum', 
            'data_unit_reskrim', 
            'satuan',
            'id'
        ));
    }

    public function rekapPoldaFilter(Request $request)
    {
        $id = $request->id;
        $satuan = KategoriBagian::where('id', $id)->first();
        $data = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.kategori_id', 1)
            ->paginate(25);

        // Ditreskrimsus Pie Selesai
        $pie_ditreskrimsus = Perkara::where('kategori_bagian_id', '179')
            ->where('status_id', '!=', 1)
            ->count();

        // Ditreskrimum Pie Selesai
        $pie_ditreskrimum = Perkara::where('kategori_bagian_id', '171')
            ->where('status_id', '!=', 1)
            ->count();

        // Ditresnarkoba Pie Selesai
        $pie_ditresnarkoba = Perkara::where('kategori_bagian_id', '1')
            ->where('status_id', '!=', 1)
            ->count();

        // Ditreskrimsus Pie Belum Selesai
        $pie_ditreskrimsus_belum = Perkara::where('kategori_bagian_id', '179')
            ->where('status_id', '=', 1)
            ->count();

        // Ditreskrimum Pie Belum Selesai
        $pie_ditreskrimum_belum = Perkara::where('kategori_bagian_id', '171')
            ->where('status_id', '=', 1)
            ->count();

        // Ditresnarkoba Pie Belum Selesai
        $pie_ditresnarkoba_belum = Perkara::where('kategori_bagian_id', '1')
            ->where('status_id', '=', 1)
            ->count();

        return view('admin.laporan.rekap_polda', compact(
            'pie_ditreskrimsus',
            'pie_ditreskrimum',
            'pie_ditresnarkoba',
            'pie_ditreskrimsus_belum',
            'pie_ditreskrimum_belum',
            'pie_ditresnarkoba_belum',
            'data'
        ));
    }

    public function rekapPolresSearch(Request $request)
    {
        // $search_bar   = $request->search;
        $id = $request->id;
        $satuan = KategoriBagian::where('id', $id)->first();
        $data_satreskrim = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.divisi', '=', 'Satreskrim')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('perkaras.no_lp', 'LIKE','%'.$request->search.'%')
            ->where('perkaras.kategori_id', 2)
            ->paginate(25);

        $data_satnarkoba = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.pelaku',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.divisi', '=', 'Satnarkoba')
            ->where('perkaras.kategori_bagian_id', $id)
            ->where('perkaras.no_lp', 'LIKE','%'.$request->search.'%')
            ->where('perkaras.kategori_id', 2)
            ->paginate(25);

        $satuan = KategoriBagian::where('id', $id)->first();
        // Satreskrim Pie Selesai
        $pie_satreskrim = Perkara::where('divisi', 'Satreskrim')
            ->where('status_id', '!=', 1)
            ->where('perkaras.kategori_bagian_id', $id)
            ->count();

        // Satreskrim Pie Belum Selesai
        $pie_satreskrim_belum = Perkara::where('divisi', 'Satreskrim')
            ->where('status_id', '=', 1)
            ->where('perkaras.kategori_bagian_id', $id)
            ->count();

        // Satnarkoba Pie Selesai
        $pie_satnarkoba = Perkara::where('divisi', 'Satnarkoba')
            ->where('status_id', '!=', 1)
            ->where('perkaras.kategori_bagian_id', $id)
            ->count();

        // Satnarkoba Pie Belum Selesai
        $pie_satnarkoba_belum = Perkara::where('divisi', 'Satnarkoba')
            ->where('status_id', '=', 1)
            ->where('perkaras.kategori_bagian_id', $id)
            ->count();

        return view('admin.laporan.rekap_polres', compact(
            'pie_satreskrim', 
            'pie_satreskrim_belum', 
            'pie_satnarkoba', 
            'pie_satnarkoba_belum', 
            'data_satreskrim', 
            'data_satnarkoba', 
            'satuan',
            'id'
        ));
    }

    public function rekapSearch(Request $request)
    {
        $search_bar   = $request->search;

        // ambil data dan di groupby sesuai satker
        $rekap = DB::table('perkaras')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->select('kategori_bagians.name', 'kategori_bagian_id', DB::raw('count(kategori_bagian_id) as total'))
            ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
            ->where('kategori_bagians.name', 'LIKE','%'.$search_bar.'%')
            ->orderBy('kategori_bagians.name', 'asc')
            ->get();

        // hitung jumlah kasus selesai
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

        // hitung jumlah data untuk parameter
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

        $count_kasus = Perkara::count();
        $count_kasus_selesai = Perkara::where('status_id', '!=', 1)->count();
        $count_kasus_belum_selesai = Perkara::where('status_id', 1)->count();

        if($count_kasus > 0){
	        $persentase = ($count_kasus_selesai / $count_kasus)*100;
	        $english_format_number = number_format($persentase);
	      }else{
	        $english_format_number = 0;
	      }

        return view('admin.laporan.rekap', compact('rekap', 'count_kasus', 'count_kasus_selesai', 'count_kasus_belum_selesai', 'english_format_number'));

    }

    public function rekapPoldaSearch(Request $request)
    {
        $search_bar   = $request->search;

        $data = Perkara::orderBy('perkaras.updated_at', 'desc')
            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
            ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
            ->leftJoin('statuses', 'statuses.id', '=', 'perkaras.status_id')
            ->select(
                'perkaras.id',
                'perkaras.no_lp',
                'kategori_bagians.name as satuan',
                'perkaras.nama_petugas',
                'korbans.nama',
                'korbans.barang_bukti',
                'perkaras.date',
                'perkaras.time',
                'perkaras.divisi',
                'jenis_pidanas.name as pidana',
                'perkaras.status_id',
                'statuses.name as status')
            ->where('perkaras.no_lp', 'LIKE','%'.$search_bar.'%')
            ->where('perkaras.kategori_id', 1)
            ->paginate(25);

        // Ditreskrimsus Pie Selesai
        $pie_ditreskrimsus = Perkara::where('kategori_bagian_id', '179')
            ->where('status_id', '!=', 1)
            ->count();

        // Ditreskrimum Pie Selesai
        $pie_ditreskrimum = Perkara::where('kategori_bagian_id', '171')
            ->where('status_id', '!=', 1)
            ->count();

        // Ditresnarkoba Pie Selesai
        $pie_ditresnarkoba = Perkara::where('kategori_bagian_id', '1')
            ->where('status_id', '!=', 1)
            ->count();

        // Ditreskrimsus Pie Belum Selesai
        $pie_ditreskrimsus_belum = Perkara::where('kategori_bagian_id', '179')
            ->where('status_id', '=', 1)
            ->count();

        // Ditreskrimum Pie Belum Selesai
        $pie_ditreskrimum_belum = Perkara::where('kategori_bagian_id', '171')
            ->where('status_id', '=', 1)
            ->count();

        // Ditresnarkoba Pie Belum Selesai
        $pie_ditresnarkoba_belum = Perkara::where('kategori_bagian_id', '1')
            ->where('status_id', '=', 1)
            ->count();

        return view('admin.laporan.rekap_polda', compact(
            'pie_ditreskrimsus',
            'pie_ditreskrimum',
            'pie_ditresnarkoba',
            'pie_ditreskrimsus_belum',
            'pie_ditreskrimum_belum',
            'pie_ditresnarkoba_belum',
            'data'
        ));
    }

    public function searchlistRekapPolres(Request $request)
    {
        $search_bar   = $request->search;

        $data = KategoriBagian::orderBy('name', 'asc')->where('kategori_id', 2)
            ->where('name', 'LIKE','%'.$search_bar.'%')
            ->get();
        // Satreskrim Pie Selesai
        $pie_satreskrim = Perkara::where('divisi', 'Satreskrim')
            ->where('status_id', '!=', 1)
            ->count();

        // Satreskrim Pie Belum Selesai
        $pie_satreskrim_belum = Perkara::where('divisi', 'Satreskrim')
            ->where('status_id', '=', 1)
            ->count();

        // Satnarkoba Pie Selesai
        $pie_satnarkoba = Perkara::where('divisi', 'Satnarkoba')
            ->where('status_id', '!=', 1)
            ->count();

        // Satnarkoba Pie Belum Selesai
        $pie_satnarkoba_belum = Perkara::where('divisi', 'Satnarkoba')
            ->where('status_id', '=', 1)
            ->count();

            return view('admin.laporan.list_rekap_polres', compact(
            'pie_satreskrim',
            'pie_satreskrim_belum',
            'pie_satnarkoba',
            'pie_satnarkoba_belum',
            'data'
        ));
    }

    public function searchlistRekapPolsek(Request $request)
    {
        $search_bar   = $request->search;

        $data = KategoriBagian::orderBy('name', 'asc')->where('kategori_id', 3)
            ->where('name', 'LIKE','%'.$search_bar.'%')
            ->get();
        
        // Unit Reskrim Pie Selesai
        $pie_unit_reskrim = Perkara::where('kategori_id', '3')
            ->where('status_id', '!=', 1)
            ->count();

        // Unit Reskrim Pie Belum Selesai
        $pie_unit_reskrim_belum = Perkara::where('kategori_id', '3')
            ->where('status_id', '=', 1)
            ->count();

        return view('admin.laporan.list_rekap_polsek', compact(
            'pie_unit_reskrim',
            'pie_unit_reskrim_belum',
            'data'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function satkerPolres($id, Request $request)
    {
        // kebutuhan filter
        $start       = $request->get('start_date');
        $end         = $request->get('end_date');
        $date_range  = array($request->get('start_date'), $request->get('end_date'));
        $no_lp       = $request->no_lp;
        $petugas     = $request->petugas;
        $korban      = $request->korban;
        $pidana_id   = $request->pidana;
        $status_id   = $request->status;
        $bukti       = $request->bukti;
        $satker       = $request->satuan;
        $satuan = KategoriBagian::where('id', $id)->first();

        // untuk data option di blade
        $satuanBawah = TurunanSatuan::select([
          'turunan_satuans.satker_turunan_id',
          'kategori_bagians.name as satuan',
        ])->where('turunan_satuans.satker_id', $id)
          ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->distinct()
          ->get();

        $data = TurunanSatuan::select([
            'perkaras.id',
            'perkaras.no_lp',
            'kategori_bagians.name as satuan',
            'perkaras.nama_petugas',
            'korbans.nama',
            'korbans.barang_bukti',
            'perkaras.date',
            'perkaras.time',
            'perkaras.divisi',
            'jenis_pidanas.name as pidana',
            'perkaras.status_id',
            'statuses.name as status'
        ])->where('turunan_satuans.satker_id', $id)
          ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
          ->join('korbans', 'perkaras.no_lp', '=', 'korbans.no_lp')
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('statuses', 'perkaras.status_id', '=', 'statuses.id')
          ->join('jenis_pidanas', 'perkaras.jenis_pidana', '=', 'jenis_pidanas.id')
          ->when(!empty($request->no_lp), function ($query) use ($request) {
            $query->where('perkaras.no_lp', 'like', "%{$request->no_lp}%");
          })
          ->when(!empty($request->satuan), function ($query) use ($request) {
            $query->where('perkaras.kategori_bagian_id', 'like', "%{$request->satuan}%");
          })
          ->when(!empty($request->petugas), function ($query) use ($request) {
              $query->where('nama_petugas', 'like', "%{$request->petugas}%");
          })
          ->when(!empty($request->korban), function ($query) use ($request) {
              $query->where('korbans.nama', 'like', "%{$request->korban}%");
          })
          ->when(!empty($request->bukti), function ($query) use ($request) {
              $query->where('barang_bukti', 'like', "%{$request->bukti}%");
          })
          ->when(!empty($request->start_date && $request->end_date), function ($query) use ($date_range) {
              $query->whereBetween('date', $date_range);
          })
          ->when(!empty($request->pidana), function ($query) use ($request) {
              $query->where('jenis_pidana', $request->pidana);
          })
          ->when(!empty($request->status), function ($query) use ($request) {
              $query->where('status_id', $request->status);
          })
          ->paginate(25);

        // ambil data dan di groupby sesuai satker
        $grouping = TurunanSatuan::select([
            'kategori_bagians.name',
            'kategori_bagian_id',
            DB::raw('count(kategori_bagian_id) as total')
        ])->where('turunan_satuans.satker_id', $id)
          ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
          ->orderBy('kategori_bagians.name', 'asc')
          ->get();

        // hitung jumlah kasus selesai
        foreach ($grouping as $key => $add) {
            $rekap_blm_selesai = TurunanSatuan::select([
                'kategori_bagians.name',
                'kategori_bagian_id',
                DB::raw('count(kategori_bagian_id) as total')
              ])->where('turunan_satuans.satker_id', $id)
                ->join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
                ->groupBy('kategori_bagians.name', 'kategori_bagian_id')
                ->orderBy('kategori_bagians.name', 'asc')
                ->where('status_id', 1)
                ->get();

            foreach ($rekap_blm_selesai as $key => $add2) {
                if($add->kategori_bagian_id == $add2->kategori_bagian_id){
                    $add->kasus_selesai = $add->total - $add2->total;
                }
            }
        }

        // hitung jumlah data untuk parameter
        foreach ($grouping as $key => $countArray) {
            $count = count(get_object_vars($countArray));
            $countArray->array = $count;
        }

        // hitung persentase kasus selesai
        foreach ($grouping as $key => $persentase) {
            if($persentase->array == 3){
                $persentase->percent_success = 0;
            }else{
                $persentase->percent_success = number_format(($persentase->kasus_selesai/$persentase->total)*100);
            }
        }

        // hitung total kasus
        $count_kasus = TurunanSatuan::join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                      ->where('turunan_satuans.satker_id', $id)
                      ->count();
        
        // hitung total kasus selesai
        $count_kasus_selesai = TurunanSatuan::join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                      ->where('turunan_satuans.satker_id', $id)
                      ->where('status_id', '!=', 1)
                      ->count();

        // hitung total kasus belum selesai
        $count_kasus_belum_selesai = TurunanSatuan::join('perkaras', 'turunan_satuans.satker_turunan_id', '=', 'perkaras.kategori_bagian_id')
                      ->where('turunan_satuans.satker_id', $id)
                      ->where('status_id', 1)
                      ->count();

        if($count_kasus > 0){
	        $persentase = ($count_kasus_selesai / $count_kasus)*100;
	        $english_format_number = number_format($persentase);
	      }else{
	        $english_format_number = 0;
	      }

        $satuan = KategoriBagian::where('id', $id)->first();
        $jenispidanas = JenisPidana::orderBy('name', 'asc')->get();
        $statuses = Status::orderBy('name', 'asc')->get();

        return view('admin.laporan.rekap_bawah_polres', compact(
            'count_kasus',
            'count_kasus_selesai',
            'count_kasus_belum_selesai',
            'grouping',
            'data',
            'satuan',
            'id',
            'no_lp',
            'petugas',
            'korban',
            'bukti',
            'jenispidanas',
            'pidana_id',
            'statuses',
            'status_id',
            'satuanBawah',
            'satker'
        ));
    }
}
