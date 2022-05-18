<?php

namespace App\Http\Controllers\admin;

use Activity;
use DB;
use Auth;
use App\Perkara;
use App\JenisPidana;
use App\KategoriBagian;
use App\UserGroup;
use App\TrafficAccident;
use App\Group;
use App\Akses;
use App\Log;
use App\User;
use App\DataMaster;
use App\Lapor;
use App\JumlahPenduduk;
use App\TurunanSatuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PerkaraExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      /** flaging filter data */
      $is_open = false;
      /** hitung user login */
      $activities = Activity::usersBySeconds(30)->get();
      $numberOfUsers = Activity::users()->count();
      $jenispidanas = JenisPidana::orderBy('name', 'asc')->get();

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

      // Data untuk role selain admin
      if($login->group!='Admin')
      { // untuk user selain admin 
        /** kebutuhan filter */
        $kategori_bagians = Akses::select('kategori_bagians.id', 'kategori_bagians.name')->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->where('akses.user_id', Auth::user()->id)
                ->get();
        /** data kasus terbaru */
        $perkaras = Perkara::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
          ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
          ->select([
            'perkaras.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan',
            'korbans.nama',
            'korbans.barang_bukti',
          ])
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->limit(4)
          ->get();

        /** top kasus */
        $top_kasus = DB::table('perkaras')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(perkaras.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->limit(3)
          ->get();

        // supaya tidak error di hosting
        $top_kasus_satker = null;
        $top_kasus_satker_bagian = null;
        $top_kasus_satker_polda = null;
        $top_kasus_satker_polres = null;
        $top_kasus_satker_polsek = null;

        /** total kasus */
        $count_kasus = Perkara::where('perkaras.user_id', '=', Auth::user()->id)->count();
        $count_kasus_belum = Perkara::where('perkaras.user_id', '=', Auth::user()->id)->where('status_id', '1')->count();
        $count_kasus_selesai = $count_kasus - $count_kasus_belum;
        /** hitung percentase */
        if($count_kasus != 0){
          $percent_done = ($count_kasus_selesai/$count_kasus)*100;
          $percent_progress = ($count_kasus_belum/$count_kasus)*100;
        }else{
          $percent_done = 0;
          $percent_progress = 0;
        }
        /** total kasus this year */
        $count_kasus_this_y = Perkara::where('perkaras.user_id', '=', Auth::user()->id)->whereYear('date', date('Y'))->count();

        // diagram batang this year
        /** januari */
        $jan_f_diagram_ty = Perkara::whereMonth('date', '=', '01')
          ->where('perkaras.user_id', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** februari */
        $feb_f_diagram_ty = Perkara::whereMonth('date', '=', '02')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** maret */
        $mar_f_diagram_ty = Perkara::whereMonth('date', '=', '03')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** april */
        $apr_f_diagram_ty = Perkara::whereMonth('date', '=', '04')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** mei */
        $mei_f_diagram_ty = Perkara::whereMonth('date', '=', '05')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** juni */
        $jun_f_diagram_ty = Perkara::whereMonth('date', '=', '06')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** juli */
        $jul_f_diagram_ty = Perkara::whereMonth('date', '=', '07')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** agustus */
        $aug_f_diagram_ty = Perkara::whereMonth('date', '=', '08')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** september */
        $sep_f_diagram_ty = Perkara::whereMonth('date', '=', '09')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** oktober */
        $oct_f_diagram_ty = Perkara::whereMonth('date', '=', '10')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** november */
        $nov_f_diagram_ty = Perkara::whereMonth('date', '=', '11')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** desember */
        $des_f_diagram_ty = Perkara::whereMonth('date', '=', '12')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        // logs
        $logs = Log::select([
          'perkaras.id', 
          'perkaras.no_lp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('perkaras', 'logs.perkara_id', '=', 'perkaras.id')
          ->join('users', 'logs.user_id', '=', 'users.id')
          ->where('logs.user_id', '=', Auth::user()->id)
          ->orderBy('logs.created_at', 'desc')
          ->limit(25)
          ->get();

        foreach($logs as $key=>$log){
          $dataTime = Carbon::parse($log->created_at);
          $nowTime  = Carbon::now()->toDateTimeString();
          // for time
          $hours   =  $dataTime->diff($nowTime)->format('%H');
          $minutes =  $dataTime->diff($nowTime)->format('%I');
          // for day
          $age_of_data = \Carbon\Carbon::parse($log->created_at)->diff(\Carbon\Carbon::now())->format('%d');
          if($age_of_data == 0){
            // include data to collection
            if($hours == 0){
              $log->age_of_data   = $minutes." minutes ago";
            }else{
              $log->age_of_data   = $hours." hours ago";
            }
          }else{
            // include data to collection
            $log->age_of_data   = $age_of_data." days ago";
          }
        }

        // data perkara sudah lewat 
        $count_kasus_lama = Perkara::where('date_no_lp', '<=', date('Y-m-d', strtotime('-6 months')))->where('status_id', 1)->where('perkaras.user_id', '=', Auth::user()->id)->count();

        // data time for chart
        
      }else{ // untuk user admin
        /** kebutuhan filter */
        $kategori_bagians = KategoriBagian::orderBy('name', 'asc')->get();

        /** data kasus terbaru */
        $perkaras = Perkara::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
          ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
          ->select([
            'perkaras.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan',
            'korbans.nama',
            'korbans.barang_bukti',
          ])
          ->limit(4)
          ->get();

        /** top kasus */
        $top_kasus = DB::table('perkaras')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(perkaras.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker = Perkara::select('kategoris.id', 'kategoris.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->leftJoin('kategoris', 'perkaras.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategoris.id', 'kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian = DB::table('perkaras')
          ->leftJoin('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(perkaras.kategori_bagian_id) as total'))
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();

        /** total kasus */
        $count_kasus = Perkara::count();
        $count_kasus_belum = Perkara::where('status_id', '1')->count();
        $count_kasus_selesai = $count_kasus - $count_kasus_belum;
        /** hitung percentase */
        $percent_done = ($count_kasus_selesai/$count_kasus)*100;
        $percent_progress = ($count_kasus_belum/$count_kasus)*100;
        /** total kasus this year */
        $count_kasus_this_y = Perkara::whereYear('date', date('Y'))->count();

        // diagram batang this year
        /** januari */
        $jan_f_diagram_ty = Perkara::whereMonth('date', '=', '01')
          ->whereYear('date', date('Y'))
          ->count();

        /** februari */
        $feb_f_diagram_ty = Perkara::whereMonth('date', '=', '02')
          ->whereYear('date', date('Y'))
          ->count();

        /** maret */
        $mar_f_diagram_ty = Perkara::whereMonth('date', '=', '03')
          ->whereYear('date', date('Y'))
          ->count();

        /** april */
        $apr_f_diagram_ty = Perkara::whereMonth('date', '=', '04')
          ->whereYear('date', date('Y'))
          ->count();

        /** mei */
        $mei_f_diagram_ty = Perkara::whereMonth('date', '=', '05')
          ->whereYear('date', date('Y'))
          ->count();

        /** juni */
        $jun_f_diagram_ty = Perkara::whereMonth('date', '=', '06')
          ->whereYear('date', date('Y'))
          ->count();

        /** juli */
        $jul_f_diagram_ty = Perkara::whereMonth('date', '=', '07')
          ->whereYear('date', date('Y'))
          ->count();

        /** agustus */
        $aug_f_diagram_ty = Perkara::whereMonth('date', '=', '08')
          ->whereYear('date', date('Y'))
          ->count();

        /** september */
        $sep_f_diagram_ty = Perkara::whereMonth('date', '=', '09')
          ->whereYear('date', date('Y'))
          ->count();

        /** oktober */
        $oct_f_diagram_ty = Perkara::whereMonth('date', '=', '10')
          ->whereYear('date', date('Y'))
          ->count();

        /** november */
        $nov_f_diagram_ty = Perkara::whereMonth('date', '=', '11')
          ->whereYear('date', date('Y'))
          ->count();

        /** desember */
        $des_f_diagram_ty = Perkara::whereMonth('date', '=', '12')
          ->whereYear('date', date('Y'))
          ->count();

        // logs
        $logs = Log::select([
          'perkaras.id', 
          'perkaras.no_lp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('perkaras', 'logs.perkara_id', '=', 'perkaras.id')
          ->join('users', 'logs.user_id', '=', 'users.id')
          ->orderBy('logs.created_at', 'desc')
          ->limit(25)
          ->get();

        foreach($logs as $key=>$log){
          $dataTime = Carbon::parse($log->created_at);
          $nowTime  = Carbon::now()->toDateTimeString();
          // for time
          $hours   =  $dataTime->diff($nowTime)->format('%H');
          $minutes =  $dataTime->diff($nowTime)->format('%I');
          // for day
          $age_of_data = \Carbon\Carbon::parse($log->created_at)->diff(\Carbon\Carbon::now())->format('%d');
          if($age_of_data == 0){
            // include data to collection
            if($hours == 0){
              $log->age_of_data   = $minutes." minutes ago";
            }else{
              $log->age_of_data   = $hours." hours ago";
            }
          }else{
            // include data to collection
            $log->age_of_data   = $age_of_data." days ago";
          }
        }

        // data perkara sudah lewat 
        $count_kasus_lama = Perkara::where('date_no_lp', '<=', date('Y-m-d', strtotime('-6 months')))->where('status_id', 1)->count();
      }

      return view('admin.admin', compact(
        'perkaras',
        'top_kasus',
        'count_kasus',
        'is_open',
        'count_kasus_this_y',
        'count_kasus_selesai',
        'count_kasus_belum',
        'percent_done',
        'percent_progress',
        'kategori_bagians',
        'jenispidanas',
        'numberOfUsers',
        'activities',
        'top_kasus_satker',
        'top_kasus_satker_bagian',
        'jan_f_diagram_ty',
        'feb_f_diagram_ty',
        'mar_f_diagram_ty',
        'apr_f_diagram_ty',
        'mei_f_diagram_ty',
        'jun_f_diagram_ty',
        'jul_f_diagram_ty',
        'aug_f_diagram_ty',
        'sep_f_diagram_ty',
        'oct_f_diagram_ty',
        'nov_f_diagram_ty',
        'des_f_diagram_ty',
        'logs',
        'count_kasus_lama',
        'top_kasus_satker_polda',
        'top_kasus_satker_polres',
        'top_kasus_satker_polsek'
      ));
    }

    public function filter(Request $request)
    {
      /** flaging filter data */
      $is_open = true;
      /** hitung user login */
      $activities = Activity::usersBySeconds(30)->get();
      $numberOfUsers = Activity::users()->count();

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
        ->where('user_groups.user_id', Auth::id())
        ->select('groups.name AS group')
        ->first();
      /** param */
      $satker_param       = $request->satker;
      $jenis_kasus_param  = $request->jenis_kasus;
      $tahun_param        = $request->tahun;
      $bulan_param        = $request->bulan;
      // array bulan
      $month = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desmber',
      ];

      // Data untuk role selain admin
      if($login->group != 'Admin')
      { // untuk user selain admin 
        // Line 1
        /** total kasus */
        $count_kasus = Perkara::where('perkaras.user_id', '=', Auth::user()->id)->count();
        // data perkara sudah lewat 
        $count_kasus_lama = Perkara::where('date_no_lp', '<=', date('Y-m-d', strtotime('-6 months')))->where('status_id', 1)->where('perkaras.user_id', '=', Auth::user()->id)->count();
        /** total kasus this year */
        $count_kasus_this_y = Perkara::where('perkaras.user_id', '=', Auth::user()->id)->whereYear('date', date('Y'))->count();
        // diagram batang this year
        /** januari */
        $jan_f_diagram_ty = Perkara::whereMonth('date', '=', '01')
          ->where('perkaras.user_id', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** februari */
        $feb_f_diagram_ty = Perkara::whereMonth('date', '=', '02')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** maret */
        $mar_f_diagram_ty = Perkara::whereMonth('date', '=', '03')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** april */
        $apr_f_diagram_ty = Perkara::whereMonth('date', '=', '04')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** mei */
        $mei_f_diagram_ty = Perkara::whereMonth('date', '=', '05')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** juni */
        $jun_f_diagram_ty = Perkara::whereMonth('date', '=', '06')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** juli */
        $jul_f_diagram_ty = Perkara::whereMonth('date', '=', '07')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** agustus */
        $aug_f_diagram_ty = Perkara::whereMonth('date', '=', '08')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** september */
        $sep_f_diagram_ty = Perkara::whereMonth('date', '=', '09')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** oktober */
        $oct_f_diagram_ty = Perkara::whereMonth('date', '=', '10')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** november */
        $nov_f_diagram_ty = Perkara::whereMonth('date', '=', '11')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** desember */
        $des_f_diagram_ty = Perkara::whereMonth('date', '=', '12')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();
          
        /** progress perkara */
        $count_kasus_belum = Perkara::where('perkaras.user_id', '=', Auth::user()->id)->where('status_id', '1')->count();
        $count_kasus_selesai = $count_kasus - $count_kasus_belum;
        /** hitung percentase */
        if($count_kasus != 0){
          /** hitung percentase */
          $percent_done     = ($count_kasus_selesai/$count_kasus)*100;
          $percent_progress = ($count_kasus_belum/$count_kasus)*100;
        }else{
          $percent_done     = 0;
          $percent_progress = 0;
        }

        // Line 2
        // logs
        $logs = Log::select([
          'perkaras.id', 
          'perkaras.no_lp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('perkaras', 'logs.perkara_id', '=', 'perkaras.id')
          ->join('users', 'logs.user_id', '=', 'users.id')
          ->where('logs.user_id', '=', Auth::user()->id)
          ->orderBy('logs.created_at', 'desc')
          ->limit(25)
          ->get();

        foreach($logs as $key=>$log){
          $dataTime = Carbon::parse($log->created_at);
          $nowTime  = Carbon::now()->toDateTimeString();
          // for time
          $hours   =  $dataTime->diff($nowTime)->format('%H');
          $minutes =  $dataTime->diff($nowTime)->format('%I');
          // for day
          $age_of_data = \Carbon\Carbon::parse($log->created_at)->diff(\Carbon\Carbon::now())->format('%d');
          if($age_of_data == 0){
            // include data to collection
            if($hours == 0){
              $log->age_of_data   = $minutes." minutes ago";
            }else{
              $log->age_of_data   = $hours." hours ago";
            }
          }else{
            // include data to collection
            $log->age_of_data   = $age_of_data." days ago";
          }
        }

        /** data kasus terbaru */
        $perkaras = Perkara::orderBy('created_at', 'desc')
        ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
        ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
        ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
        ->select([
          'perkaras.*',
          'jenis_pidanas.name as pidana',
          'kategori_bagians.name as satuan',
          'korbans.nama',
          'korbans.barang_bukti',
        ])
        ->where('perkaras.user_id', '=', Auth::user()->id)
        ->limit(4)
        ->get();

        // Line 3
        /** top kasus */
        $top_kasus = DB::table('perkaras')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(perkaras.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->limit(3)
          ->get();

        // supaya tidak error di hosting
        $top_kasus_satker = null;
        $top_kasus_satker_bagian = null;
        $top_kasus_satker_polda = null;
        $top_kasus_satker_polres = null;
        $top_kasus_satker_polsek = null;
     
        // Line 4
        /** kebutuhan filter */
        $kategori_bagians = Akses::select('kategori_bagians.id', 'kategori_bagians.name')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
          ->where('akses.user_id', Auth::user()->id)
          ->get();
        $jenispidanas = JenisPidana::orderBy('name', 'asc')->get();

        // Line 5
        /** untuk label */
        $satker_fr_param = KategoriBagian::where('id', $satker_param)->first();
        $jenis_pidana_fr_param = JenisPidana::where('id', $jenis_kasus_param)->first();

        // Line 6
        // Rumus
        // Persentase perkembangan jumlah kejahatan
        // jumlah kejahatan tahun ini
        $x = Perkara::when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })->where('perkaras.user_id', '=', Auth::user()->id)->count();

        $tahun_param_sebelum = $tahun_param - 1;
        // jumlah kejahatan tahun sebelumnya
        $y = Perkara::when(!empty($tahun_param_sebelum), function ($query) use ($tahun_param_sebelum) {
          $query->whereYear('date', $tahun_param_sebelum);
        })->where('perkaras.user_id', '=', Auth::user()->id)->count();

        $persentase_perkembangan_jumlah_kejahatan = (($x-$y)/$y)*100;

        // Perhitungan persentase penyelesaian kejahatan
        $count_kasus_f_persentase         = Perkara::whereYear('date', $tahun_param)->where('perkaras.user_id', '=', Auth::user()->id)->count();
        $count_kasus_selesai_f_persentase = Perkara::where('status_id', '!=', '1')->where('perkaras.user_id', '=', Auth::user()->id)->count();
        $persentase_penyelesaian_perkara  = ($count_kasus_selesai_f_persentase*100)/$count_kasus_f_persentase;

        // Selang waktu terjadi kejahatan
        $selang_waktu       = (365*24*60*60)/$count_kasus_f_persentase;
        $convert_menit      = $selang_waktu/60;
        $bulat_selang_waktu = ceil($convert_menit);

        // perbandingan jumlah polisi dengan jumlah penduduk
        // get data polisi
        $data_master = DataMaster::first();
        // get data penduduk
        // jika param satker dipilih
        $akses_satker = Akses::select(['akses.sakter_id'])->where('akses.user_id', '=', Auth::user()->id)->first();
        $satker_induk = TurunanSatuan::select(['turunan_satuans.satker_id'])->where('satker_turunan_id', $akses_satker->sakter_id)->first();
        
        if($satker_induk){ // jika polda, tidak ada data turunan satuan
          $data_penduduk = JumlahPenduduk::where('kategori_bagian_id', $satker_induk->satker_id)->first();
        }else{
          $data_penduduk = null;
        }

        if($data_penduduk != null){
          $jumlah_penduduk  = $data_penduduk->pria + $data_penduduk->wanita;
        }else{
          $jumlah_pria      = JumlahPenduduk::sum('pria');
          $jumlah_wanita    = JumlahPenduduk::sum('wanita');
          $jumlah_penduduk  = $jumlah_pria + $jumlah_wanita;
        }

        $perbandingan_jumlah_polisi_dgn_penduduk = ceil($data_master->jumlah_penduduk/$data_master->jumlah_polisi);

        // resiko penduduk terkena perkara
        $resiko_terkena_pidana = ($count_kasus_f_persentase*100000)/$data_master->jumlah_penduduk;

        // Line 7
        /** top kasus Filter*/
        $top_kasus_filter = DB::table('perkaras')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(perkaras.jenis_pidana) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        // supaya tidak error di hosting
        $top_kasus_satker_filter = null;
        $top_kasus_satker_bagian_filter = null;
        $top_kasus_satker_polda_filter = null;
        $top_kasus_satker_polres_filter = null;
        $top_kasus_satker_polsek_filter = null;

        // Line 8
        // filter satker dan jenis pidana
        $petas = Perkara::orderBy('created_at', 'desc')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->get();

        // total kasus
        $count_kasus_f_map = $petas->count();
        // kasus selesai
        $count_kasus_belum_f_map = $petas->where('status_id', '1')->count();
        // kasus belum selesai
        $count_kasus_selesai_f_map = $count_kasus_f_map - $count_kasus_belum_f_map;
        // persentase kasus
        if($count_kasus_f_map > 0){
          $percent_done_f_map = ($count_kasus_selesai_f_map/$count_kasus_f_map)*100;
        }else{
          $percent_done_f_map = 0;
        }

        // Line 9
        // diagram batang
        /** januari */
        $jan_f_diagram = Perkara::whereMonth('date', '=', '01')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $jan_f_diagram_done     = $jan_f_diagram->where('status_id', '!=', 1)->count();
        $jan_f_diagram_progres  = $jan_f_diagram->where('status_id', 1)->count();

        /** februari */
        $feb_f_diagram = Perkara::whereMonth('date', '=', '02')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $feb_f_diagram_done     = $feb_f_diagram->where('status_id', '!=', 1)->count();
        $feb_f_diagram_progres  = $feb_f_diagram->where('status_id', 1)->count();

        /** maret */
        $mar_f_diagram = Perkara::whereMonth('date', '=', '03')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $mar_f_diagram_done     = $mar_f_diagram->where('status_id', '!=', 1)->count();
        $mar_f_diagram_progres  = $mar_f_diagram->where('status_id', 1)->count();

        /** april */
        $apr_f_diagram = Perkara::whereMonth('date', '=', '04')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $apr_f_diagram_done     = $apr_f_diagram->where('status_id', '!=', 1)->count();
        $apr_f_diagram_progres  = $apr_f_diagram->where('status_id', 1)->count();

        /** mei */
        $mei_f_diagram = Perkara::whereMonth('date', '=', '05')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $mei_f_diagram_done     = $mei_f_diagram->where('status_id', '!=', 1)->count();
        $mei_f_diagram_progres  = $mei_f_diagram->where('status_id', 1)->count();

        /** juni */
        $jun_f_diagram = Perkara::whereMonth('date', '=', '06')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $jun_f_diagram_done     = $jun_f_diagram->where('status_id', '!=', 1)->count();
        $jun_f_diagram_progres  = $jun_f_diagram->where('status_id', 1)->count();

        /** juli */
        $jul_f_diagram = Perkara::whereMonth('date', '=', '07')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $jul_f_diagram_done     = $jul_f_diagram->where('status_id', '!=', 1)->count();
        $jul_f_diagram_progres  = $jul_f_diagram->where('status_id', 1)->count();

        /** agustus */
        $aug_f_diagram = Perkara::whereMonth('date', '=', '08')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $aug_f_diagram_done     = $aug_f_diagram->where('status_id', '!=', 1)->count();
        $aug_f_diagram_progres  = $aug_f_diagram->where('status_id', 1)->count();

        /** september */
        $sep_f_diagram = Perkara::whereMonth('date', '=', '09')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $sep_f_diagram_done     = $sep_f_diagram->where('status_id', '!=', 1)->count();
        $sep_f_diagram_progres  = $sep_f_diagram->where('status_id', 1)->count();

        /** oktober */
        $oct_f_diagram = Perkara::whereMonth('date', '=', '10')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $oct_f_diagram_done     = $oct_f_diagram->where('status_id', '!=', 1)->count();
        $oct_f_diagram_progres  = $oct_f_diagram->where('status_id', 1)->count();

        /** november */
        $nov_f_diagram = Perkara::whereMonth('date', '=', '11')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $nov_f_diagram_done     = $nov_f_diagram->where('status_id', '!=', 1)->count();
        $nov_f_diagram_progres  = $nov_f_diagram->where('status_id', 1)->count();

        /** desember */
        $des_f_diagram = Perkara::whereMonth('date', '=', '12')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $des_f_diagram_done     = $des_f_diagram->where('status_id', '!=', 1)->count();
        $des_f_diagram_progres  = $des_f_diagram->where('status_id', 1)->count();

        // Line 9
        // data time for chart
        $time_session_1 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('00:00'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('03:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_2 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('03:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('06:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_3 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('06:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('09:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_4 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('09:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('12:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_5 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('12:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('15:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_6 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('15:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('18:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_7 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('18:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('21:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_8 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('21:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('23:59'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->count();

        // array data time
        $data_time = [
          $time_session_1,
          $time_session_2,
          $time_session_3,
          $time_session_4,
          $time_session_5,
          $time_session_6,
          $time_session_7,
          $time_session_8,
        ];

        $min = min($data_time); // data maksimal chart
        $max = max($data_time); // data min chart
        $range = 100; // data range chart
        
      }else{ // untuk user admin
        // Line 1
        /** total kasus */
        $count_kasus = Perkara::count();
        // data perkara sudah lewat 
        $count_kasus_lama = Perkara::where('date_no_lp', '<=', date('Y-m-d', strtotime('-6 months')))->where('status_id', 1)->count();
        /** total kasus this year */
        $count_kasus_this_y = Perkara::whereYear('date', date('Y'))->count();
        // diagram batang this year
        /** januari */
        $jan_f_diagram_ty = Perkara::whereMonth('date', '=', '01')
          ->whereYear('date', date('Y'))
          ->count();

        /** februari */
        $feb_f_diagram_ty = Perkara::whereMonth('date', '=', '02')
          ->whereYear('date', date('Y'))
          ->count();

        /** maret */
        $mar_f_diagram_ty = Perkara::whereMonth('date', '=', '03')
          ->whereYear('date', date('Y'))
          ->count();

        /** april */
        $apr_f_diagram_ty = Perkara::whereMonth('date', '=', '04')
          ->whereYear('date', date('Y'))
          ->count();

        /** mei */
        $mei_f_diagram_ty = Perkara::whereMonth('date', '=', '05')
          ->whereYear('date', date('Y'))
          ->count();

        /** juni */
        $jun_f_diagram_ty = Perkara::whereMonth('date', '=', '06')
          ->whereYear('date', date('Y'))
          ->count();

        /** juli */
        $jul_f_diagram_ty = Perkara::whereMonth('date', '=', '07')
          ->whereYear('date', date('Y'))
          ->count();

        /** agustus */
        $aug_f_diagram_ty = Perkara::whereMonth('date', '=', '08')
          ->whereYear('date', date('Y'))
          ->count();

        /** september */
        $sep_f_diagram_ty = Perkara::whereMonth('date', '=', '09')
          ->whereYear('date', date('Y'))
          ->count();

        /** oktober */
        $oct_f_diagram_ty = Perkara::whereMonth('date', '=', '10')
          ->whereYear('date', date('Y'))
          ->count();

        /** november */
        $nov_f_diagram_ty = Perkara::whereMonth('date', '=', '11')
          ->whereYear('date', date('Y'))
          ->count();

        /** desember */
        $des_f_diagram_ty = Perkara::whereMonth('date', '=', '12')
          ->whereYear('date', date('Y'))
          ->count();
        /** progress perkara */
        $count_kasus_belum = Perkara::where('status_id', '1')->count();
        $count_kasus_selesai = $count_kasus - $count_kasus_belum;
        /** hitung percentase */
        $percent_done = ($count_kasus_selesai/$count_kasus)*100;
        $percent_progress = ($count_kasus_belum/$count_kasus)*100;

        // Line 2
        // logs
        $logs = Log::select([
          'perkaras.id', 
          'perkaras.no_lp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('perkaras', 'logs.perkara_id', '=', 'perkaras.id')
          ->join('users', 'logs.user_id', '=', 'users.id')
          ->orderBy('logs.created_at', 'desc')
          ->limit(25)
          ->get();

        foreach($logs as $key=>$log){
          $dataTime = Carbon::parse($log->created_at);
          $nowTime  = Carbon::now()->toDateTimeString();
          // for time
          $hours   =  $dataTime->diff($nowTime)->format('%H');
          $minutes =  $dataTime->diff($nowTime)->format('%I');
          // for day
          $age_of_data = \Carbon\Carbon::parse($log->created_at)->diff(\Carbon\Carbon::now())->format('%d');
          if($age_of_data == 0){
            // include data to collection
            if($hours == 0){
              $log->age_of_data   = $minutes." minutes ago";
            }else{
              $log->age_of_data   = $hours." hours ago";
            }
          }else{
            // include data to collection
            $log->age_of_data   = $age_of_data." days ago";
          }
        }

        /** data kasus terbaru */
        $perkaras = Perkara::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
          ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
          ->select([
            'perkaras.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan',
            'korbans.nama',
            'korbans.barang_bukti',
          ])
          ->limit(4)
          ->get();

        // Line 3
        /** top kasus */
        $top_kasus = DB::table('perkaras')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(perkaras.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker = DB::table('perkaras')
          ->leftJoin('kategoris', 'perkaras.kategori_id', '=', 'kategoris.id')
          ->select('kategoris.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->groupBy('kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian = DB::table('perkaras')
          ->leftJoin('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(perkaras.kategori_bagian_id) as total'))
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();

        // Line 4
        /** kebutuhan filter */
        $kategori_bagians = KategoriBagian::orderBy('name', 'asc')->get();
        $jenispidanas = JenisPidana::orderBy('name', 'asc')->get();

        // Line 5
        /** untuk label */
        $satker_fr_param = KategoriBagian::where('id', $satker_param)->first();
        $jenis_pidana_fr_param = JenisPidana::where('id', $jenis_kasus_param)->first();

        // Line 6
        // Rumus
        // Persentase perkembangan jumlah kejahatan
        // jumlah kejahatan tahun ini
        $x = Perkara::when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })->when(!empty($bulan_param), function ($query) use ($bulan_param) {
          $query->whereMonth('date', $bulan_param);
        })->count();

        $tahun_param_sebelum = $tahun_param - 1;
        // jumlah kejahatan tahun sebelumnya
        $y = Perkara::when(!empty($tahun_param_sebelum), function ($query) use ($tahun_param_sebelum) {
          $query->whereYear('date', $tahun_param_sebelum);
        })->when(!empty($bulan_param), function ($query) use ($bulan_param) {
          $query->whereMonth('date', $bulan_param);
        })->count();

        $persentase_perkembangan_jumlah_kejahatan = (($x-$y)/$y)*100;

        // Perhitungan persentase penyelesaian kejahatan
        $count_kasus_f_persentase  = Perkara::whereYear('date', $tahun_param)
                                      ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
                                        $query->whereMonth('date', $bulan_param);
                                      })->count();

        $count_kasus_selesai_f_persentase = Perkara::where('status_id', '!=', '1')->count();
        $persentase_penyelesaian_perkara  = ($count_kasus_selesai_f_persentase*100)/$count_kasus_f_persentase;

        // Selang waktu terjadi kejahatan
        $selang_waktu       = (365*24*60*60)/$count_kasus_f_persentase;
        $convert_menit      = $selang_waktu/60;
        $bulat_selang_waktu = ceil($convert_menit);

        // perbandingan jumlah polisi dengan jumlah penduduk
        // get data polisi
        $data_master = DataMaster::first();
        // get data penduduk
        // jika param satker dipilih
        $data_penduduk = JumlahPenduduk::where('kategori_bagian_id', $satker_param)->first();
        if($data_penduduk != null){
          $jumlah_penduduk  = $data_penduduk->pria + $data_penduduk->wanita;
        }else{
          $jumlah_pria      = JumlahPenduduk::sum('pria');
          $jumlah_wanita    = JumlahPenduduk::sum('wanita');
          $jumlah_penduduk  = $jumlah_pria + $jumlah_wanita;
        }

        $perbandingan_jumlah_polisi_dgn_penduduk = ceil($jumlah_penduduk/$data_master->jumlah_polisi);

        // resiko penduduk terkena perkara
        $resiko_terkena_pidana = ($count_kasus_f_persentase*100000)/$jumlah_penduduk;

        // Line 7
        /** top kasus Filter*/
        $top_kasus_filter = DB::table('perkaras')
        ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
        ->select('jenis_pidanas.name', DB::raw('count(perkaras.jenis_pidana) as total'))
        ->whereTime('time', '>=', \Carbon\Carbon::parse('21:01'))
        ->whereTime('time', '<=', \Carbon\Carbon::parse('23:59'))
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
          $query->whereMonth('date', $bulan_param);
        })
        ->groupBy('jenis_pidanas.name')
        ->orderBy('total', 'desc')
        ->limit(3)
        ->get();

        $top_kasus_satker_filter = DB::table('perkaras')
          ->leftJoin('kategoris', 'perkaras.kategori_id', '=', 'kategoris.id')
          ->select('kategoris.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->groupBy('kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian_filter = DB::table('perkaras')
          ->leftJoin('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(perkaras.kategori_bagian_id) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda_filter = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres_filter = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek_filter = Perkara::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(perkaras.kategori_id) as total'))
          ->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();

        // Line 8
        // filter satker dan jenis pidana
        $petas = Perkara::orderBy('created_at', 'desc')
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
          $query->whereMonth('date', $bulan_param);
        })
        ->get();

        // total kasus
        $count_kasus_f_map = $petas->count();
        // kasus selesai
        $count_kasus_belum_f_map = $petas->where('status_id', '1')->count();
        // kasus belum selesai
        $count_kasus_selesai_f_map = $count_kasus_f_map - $count_kasus_belum_f_map;
        // persentase kasus
        if($count_kasus_f_map > 0){
          $percent_done_f_map = ($count_kasus_selesai_f_map/$count_kasus_f_map)*100;
        }else{
          $percent_done_f_map = 0;
        }

        // Line 9
        // diagram barang
        /** januari */
        $jan_f_diagram = Perkara::whereMonth('date', '=', '01')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $jan_f_diagram_done     = $jan_f_diagram->where('status_id', '!=', 1)->count();
        $jan_f_diagram_progres  = $jan_f_diagram->where('status_id', 1)->count();

        /** februari */
        $feb_f_diagram = Perkara::whereMonth('date', '=', '02')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $feb_f_diagram_done     = $feb_f_diagram->where('status_id', '!=', 1)->count();
        $feb_f_diagram_progres  = $feb_f_diagram->where('status_id', 1)->count();

        /** maret */
        $mar_f_diagram = Perkara::whereMonth('date', '=', '03')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $mar_f_diagram_done     = $mar_f_diagram->where('status_id', '!=', 1)->count();
        $mar_f_diagram_progres  = $mar_f_diagram->where('status_id', 1)->count();

        /** april */
        $apr_f_diagram = Perkara::whereMonth('date', '=', '04')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $apr_f_diagram_done     = $apr_f_diagram->where('status_id', '!=', 1)->count();
        $apr_f_diagram_progres  = $apr_f_diagram->where('status_id', 1)->count();

        /** mei */
        $mei_f_diagram = Perkara::whereMonth('date', '=', '05')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $mei_f_diagram_done     = $mei_f_diagram->where('status_id', '!=', 1)->count();
        $mei_f_diagram_progres  = $mei_f_diagram->where('status_id', 1)->count();

        /** juni */
        $jun_f_diagram = Perkara::whereMonth('date', '=', '06')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $jun_f_diagram_done     = $jun_f_diagram->where('status_id', '!=', 1)->count();
        $jun_f_diagram_progres  = $jun_f_diagram->where('status_id', 1)->count();

        /** juli */
        $jul_f_diagram = Perkara::whereMonth('date', '=', '07')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $jul_f_diagram_done     = $jul_f_diagram->where('status_id', '!=', 1)->count();
        $jul_f_diagram_progres  = $jul_f_diagram->where('status_id', 1)->count();

        /** agustus */
        $aug_f_diagram = Perkara::whereMonth('date', '=', '08')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $aug_f_diagram_done     = $aug_f_diagram->where('status_id', '!=', 1)->count();
        $aug_f_diagram_progres  = $aug_f_diagram->where('status_id', 1)->count();

        /** september */
        $sep_f_diagram = Perkara::whereMonth('date', '=', '09')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $sep_f_diagram_done     = $sep_f_diagram->where('status_id', '!=', 1)->count();
        $sep_f_diagram_progres  = $sep_f_diagram->where('status_id', 1)->count();

        /** oktober */
        $oct_f_diagram = Perkara::whereMonth('date', '=', '10')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $oct_f_diagram_done     = $oct_f_diagram->where('status_id', '!=', 1)->count();
        $oct_f_diagram_progres  = $oct_f_diagram->where('status_id', 1)->count();

        /** november */
        $nov_f_diagram = Perkara::whereMonth('date', '=', '11')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $nov_f_diagram_done     = $nov_f_diagram->where('status_id', '!=', 1)->count();
        $nov_f_diagram_progres  = $nov_f_diagram->where('status_id', 1)->count();

        /** desember */
        $des_f_diagram = Perkara::whereMonth('date', '=', '12')
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })->get();
        /** count */
        $des_f_diagram_done     = $des_f_diagram->where('status_id', '!=', 1)->count();
        $des_f_diagram_progres  = $des_f_diagram->where('status_id', 1)->count();

        // Line 9
        // data time for chart
        $time_session_1 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('00:00'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('03:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_2 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('03:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('06:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_3 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('06:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('09:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_4 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('09:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('12:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_5 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('12:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('15:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_6 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('15:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('18:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_7 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('18:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('21:00'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();

        $time_session_8 = Perkara::whereTime('time', '>=', \Carbon\Carbon::parse('21:01'))
          ->whereTime('time', '<=', \Carbon\Carbon::parse('23:59'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->when(!empty($bulan_param), function ($query) use ($bulan_param) {
            $query->whereMonth('date', $bulan_param);
          })
          ->count();
        
        // array data time
        $data_time = [
          $time_session_1,
          $time_session_2,
          $time_session_3,
          $time_session_4,
          $time_session_5,
          $time_session_6,
          $time_session_7,
          $time_session_8,
        ];

        $min = min($data_time); // data maksimal chart
        $max = max($data_time); // data min chart
        $range = 100; // data range chart
      }

      // dulu dipake karena pin d map gk muncul
      // $petas = $petas->take(5000)->where('lat', '!=', null)->where('long', '!=', null)->where('pin', '!=', null)->where('divisi', '!=', null);
        
      return view('admin.admin', compact(
        'perkaras',
        'top_kasus',
        'top_kasus_filter',
        'count_kasus',
        'is_open',
        'top_kasus_satker_filter',
        'top_kasus_satker_polsek_filter',
        'top_kasus_satker_polres_filter',
        'top_kasus_satker_polda_filter',
        'top_kasus_satker_bagian_filter',
        'count_kasus_this_y',
        'count_kasus_selesai',
        'count_kasus_belum',
        'percent_done',
        'percent_progress',
        'kategori_bagians',
        'jenispidanas',
        'numberOfUsers',
        'activities',
        'top_kasus_satker',
        'top_kasus_satker_bagian',
        'petas',
        'count_kasus_f_map',
        'count_kasus_belum_f_map',
        'count_kasus_selesai_f_map',
        'percent_done_f_map',
        'tahun_param',
        'satker_param',
        'jenis_kasus_param',
        'satker_fr_param',
        'jenis_pidana_fr_param',
        'jan_f_diagram_done',
        'jan_f_diagram_progres',
        'feb_f_diagram_done',
        'feb_f_diagram_progres',
        'mar_f_diagram_done',
        'mar_f_diagram_progres',
        'apr_f_diagram_done',
        'apr_f_diagram_progres',
        'mei_f_diagram_done',
        'mei_f_diagram_progres',
        'jun_f_diagram_done',
        'jun_f_diagram_progres',
        'jul_f_diagram_done',
        'jul_f_diagram_progres',
        'aug_f_diagram_done',
        'aug_f_diagram_progres',
        'sep_f_diagram_done',
        'sep_f_diagram_progres',
        'oct_f_diagram_done',
        'oct_f_diagram_progres',
        'nov_f_diagram_done',
        'nov_f_diagram_progres',
        'des_f_diagram_done',
        'des_f_diagram_progres',
        'jan_f_diagram_ty',
        'feb_f_diagram_ty',
        'mar_f_diagram_ty',
        'apr_f_diagram_ty',
        'mei_f_diagram_ty',
        'jun_f_diagram_ty',
        'jul_f_diagram_ty',
        'aug_f_diagram_ty',
        'sep_f_diagram_ty',
        'oct_f_diagram_ty',
        'nov_f_diagram_ty',
        'des_f_diagram_ty',
        'logs',
        'count_kasus_lama',
        'data_time',
        'min',
        'max',
        'range',
        'persentase_perkembangan_jumlah_kejahatan',
        'persentase_penyelesaian_perkara',
        'bulat_selang_waktu',
        'perbandingan_jumlah_polisi_dgn_penduduk',
        'resiko_terkena_pidana',
        'top_kasus_satker_polda',
        'top_kasus_satker_polres',
        'top_kasus_satker_polsek',
        'bulan_param',
        'month'
      ));
    }

    public function export_excel(Request $request)
    {
      $arr = [$request->satker_selected, $request->pidana_selected];
      $b = implode(", ",$arr);
      return Excel::download(new PerkaraExport($b), 'rekapitulasi.xlsx');
    }

    public function profil(Request $request)
    {
      $count_kasus = Perkara::where('user_id', Auth::user()->id)->count();
      $count_kasus_selesai = Perkara::where('user_id', Auth::user()->id)->where('status_id', '!=', 1)->count();
      if($count_kasus > 0){
        $persentase = ($count_kasus_selesai / $count_kasus)*100;
        $english_format_number = number_format($persentase);
      }else{
        $english_format_number = 0;
      }

      $aktifitas = Perkara::orderBy('updated_at', 'desc')
                    ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
                    ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
                    ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
                    ->select(
                                'jenis_pidanas.name as pidana',
                                'kategori_bagians.name as satuan',
                                'korbans.nama',
                                'korbans.barang_bukti',
                                'perkaras.*'
                            )
                    ->where('user_id', Auth::user()->id)
                    ->paginate(10);

      // data group perbulan selesai
      $januari = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '01')
            ->where('status_id', '!=', 1)
            ->count();

      $februari = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '02')
            ->where('status_id', '!=', 1)
            ->count();

      $maret = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '03')
            ->where('status_id', '!=', 1)
            ->count();

      $april = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '04')
            ->where('status_id', '!=', 1)
            ->count();

      $mei = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '05')
            ->where('status_id', '!=', 1)
            ->count();

      $juni = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '06')
            ->where('status_id', '!=', 1)
            ->count();

      $juli = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '07')
            ->where('status_id', '!=', 1)
            ->count();

      $agustus = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '08')
            ->where('status_id', '!=', 1)
            ->count();

      $september = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '09')
            ->where('status_id', '!=', 1)
            ->count();

      $oktober = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '10')
            ->where('status_id', '!=', 1)
            ->count();

      $november = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '11')
            ->where('status_id', '!=', 1)
            ->count();

      $desember = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '12')
            ->where('status_id', '!=', 1)
            ->count();

      // data group perbulan blm selesai
      $belum_januari = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '01')
            ->where('status_id', '=', 1)
            ->count();

      $belum_februari = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '02')
            ->where('status_id', '=', 1)
            ->count();

      $belum_maret = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '03')
            ->where('status_id', '=', 1)
            ->count();

      $belum_april = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '04')
            ->where('status_id', '=', 1)
            ->count();

      $belum_mei = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '05')
            ->where('status_id', '=', 1)
            ->count();

      $belum_juni = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '06')
            ->where('status_id', '=', 1)
            ->count();

      $belum_juli = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '07')
            ->where('status_id', '=', 1)
            ->count();

      $belum_agustus = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '08')
            ->where('status_id', '=', 1)
            ->count();

      $belum_september = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '09')
            ->where('status_id', '=', 1)
            ->count();

      $belum_oktober = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '10')
            ->where('status_id', '=', 1)
            ->count();

      $belum_november = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '11')
            ->where('status_id', '=', 1)
            ->count();

      $belum_desember = Perkara::where('user_id', Auth::user()->id)
            ->whereYear('date', date('Y'))
            ->whereMonth('date', '=', '12')
            ->where('status_id', '=', 1)
            ->count();


      return view('admin.profile', compact(
                                  'belum_januari',
                                  'belum_februari',
                                  'belum_maret',
                                  'belum_april',
                                  'belum_mei',
                                  'belum_juni',
                                  'belum_juli',
                                  'belum_agustus',
                                  'belum_september',
                                  'belum_oktober',
                                  'belum_november',
                                  'belum_desember',
                                  'januari', 
                                  'februari', 
                                  'maret', 
                                  'april', 
                                  'mei', 
                                  'juni', 
                                  'juli', 
                                  'agustus', 
                                  'september', 
                                  'oktober', 
                                  'november', 
                                  'desember', 
                                  'count_kasus', 
                                  'count_kasus_selesai', 
                                  'english_format_number', 
                                  'aktifitas'));
    }
    
    public function donePerkara(Request $request)
    {
      /** param */
      $satker_param       = $request->satker;
      $jenis_kasus_param  = $request->jenis_kasus;
      $tahun_param        = $request->tahun;
      $search_bar         = $request->search;

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
        ->where('user_groups.user_id', Auth::id())
        ->select('groups.name AS group')
        ->first();

      // Data untuk role selain admin
      if($login->group != 'Admin')
      { // untuk user selain admin 
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->where('status_id', '!=', 1)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }else{
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('status_id', '!=', 1)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }

      return view('admin.perkara.done', compact('perkaras', 'satker_param', 'jenis_kasus_param', 'tahun_param', 'search_bar'));
    }

    public function progressPerkara(Request $request)
    {
      /** param */
      $satker_param       = $request->satker;
      $jenis_kasus_param  = $request->jenis_kasus;
      $tahun_param        = $request->tahun;
      $search_bar         = $request->search;

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
        ->where('user_groups.user_id', Auth::id())
        ->select('groups.name AS group')
        ->first();

      // Data untuk role selain admin
      if($login->group != 'Admin')
      { // untuk user selain admin 
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->where('status_id', '=', 1)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }else{
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('status_id', '=', 1)
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }
      return view('admin.perkara.not_done',compact('perkaras', 'satker_param', 'jenis_kasus_param', 'tahun_param', 'search_bar'));
    }

    public function perkaraLama(Request $request)
    {
      /** param */
      $search_bar         = $request->search;
      $month              = $request->month;

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
        ->where('user_groups.user_id', Auth::id())
        ->select('groups.name AS group')
        ->first();

      if($month != null){
        if($month == 3){
          $param_mount = date('Y-m-d', strtotime('-3 months'));
        }elseif($month == 6){
          $param_mount = date('Y-m-d', strtotime('-6 months'));
        }elseif($month == 12){
          $param_mount = date('Y-m-d', strtotime('-12 months'));
        }
      }else{
        $month = 3;
        $param_mount = date('Y-m-d', strtotime('-3 months'));
      }

      // Data untuk role selain admin
      if($login->group != 'Admin')
      { // untuk user selain admin 
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->where('status_id', '=', 1)
          ->when(!empty($param_mount), function ($query) use ($param_mount) {
            $query->where('date_no_lp', '<=', $param_mount);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }else{
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('status_id', '=', 1)
          ->when(!empty($param_mount), function ($query) use ($param_mount) {
            $query->where('date_no_lp', '<=', $param_mount);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }
      return view('admin.perkara.lama',compact('perkaras', 'search_bar', 'month'));
    }

    public function totalCrime(Request $request)
    {
        // use livewire
        return view('admin.perkara.total');
    }

    public function totalCrimeThisYear(Request $request)
    {
      /** param */
      $search_bar         = $request->search;
      $month              = $request->month;

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
        ->where('user_groups.user_id', Auth::id())
        ->select('groups.name AS group')
        ->first();

      if($month != null){
        if($month == 3){
          $param_mount = date('Y-m-d', strtotime('-3 months'));
        }elseif($month == 6){
          $param_mount = date('Y-m-d', strtotime('-6 months'));
        // }elseif($month == 12){
        //   $param_mount = date('Y-m-d', strtotime('-12 months'));
        }
      }else{
        $month = 3;
        $param_mount = date('Y-m-d', strtotime('-3 months'));
      }

      // Data untuk role selain admin
      if($login->group != 'Admin')
      { // untuk user selain admin 
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          ->where('perkaras.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          // ->where('status_id', '=', 1)
          ->when(!empty($param_mount), function ($query) use ($param_mount) {
            $query->where('date_no_lp', '<=', $param_mount);
          })
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }else{
        $perkaras = Perkara::orderBy('perkaras.updated_at', 'desc')
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
              'jenis_pidanas.name as pidana',
              'perkaras.status_id',
              'statuses.name as status')
          // ->where('status_id', '=', 1)
          ->when(!empty($param_mount), function ($query) use ($param_mount) {
            $query->where('date_no_lp', '<=', $param_mount);
          })
          ->whereYear('date', date('Y'))
          ->where(function ($query) use ($search_bar) {
            $query->where('perkaras.no_lp', 'like', "%$search_bar%")
              ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
              ->orWhere('perkaras.nama_petugas', 'like', "%$search_bar%")
              ->orWhere('korbans.nama', 'like', "%$search_bar%")
              ->orWhere('jenis_pidanas.name', 'like', "%$search_bar%");
          })
          ->paginate(25);
      }
      return view('admin.perkara.total_this_year',compact('perkaras', 'search_bar', 'month'));
    }

}
