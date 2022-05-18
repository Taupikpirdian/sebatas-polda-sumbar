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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PerkaraExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class AdminLaporController extends Controller
{

    public function index(){
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
        $lapors = Lapor::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'lapors.kategori_bagian_id')
          ->select([
            'lapors.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan',
          ])
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->limit(4)
          ->get();

        /** top kasus */
        $top_kasus = DB::table('lapors')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(lapors.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
        ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
        ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
        ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
        ->orderBy('total', 'desc')
        ->where('kategoris.id', 3)
        ->where('lapors.user_id', '=', Auth::user()->id)
        ->limit(3)
        ->get();

        // supaya tidak error di hosting
        $top_kasus_satker = null;
        $top_kasus_satker_bagian = null;
        $top_kasus_satker_polda = null;
        $top_kasus_satker_polres = null;
        $top_kasus_satker_polsek = null;

        /** total kasus */
        $count_kasus = Lapor::where('lapors.user_id', '=', Auth::user()->id)->count();
        /** total kasus this year */
        $count_kasus_this_y = Lapor::where('lapors.user_id', '=', Auth::user()->id)->whereYear('date', date('Y'))->count();

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
          'lapors.id', 
          'lapors.no_stplp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('lapors', 'logs.lapor_id', '=', 'lapors.id')
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
        $lapors = Lapor::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'lapors.kategori_bagian_id')
          ->select([
            'lapors.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan'
          ])
          ->limit(4)
          ->get();

        /** top kasus */
        $top_kasus = DB::table('lapors')
        ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
        ->select('jenis_pidanas.name', DB::raw('count(lapors.jenis_pidana) as total'))
        ->groupBy('jenis_pidanas.name')
        ->orderBy('total', 'desc')
        ->limit(3)
        ->get();

        $top_kasus_satker = Lapor::select('kategoris.id', 'kategoris.name', DB::raw('count(lapors.kategori_id) as total'))
          ->leftJoin('kategoris', 'lapors.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategoris.id', 'kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian = DB::table('lapors')
          ->leftJoin('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(lapors.kategori_bagian_id) as total'))
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();

        /** total kasus */
        $count_kasus = Lapor::count();
        /** total kasus this year */
        $count_kasus_this_y = Lapor::whereYear('date', date('Y'))->count();

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
          'lapors.id', 
          'lapors.no_stplp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('lapors', 'logs.lapor_id', '=', 'lapors.id')
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

      return view('admin.dashboard-lapor', compact(
        'lapors',
        'top_kasus',
        'count_kasus',
        'is_open',
        'count_kasus_this_y',
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
    
    public function filterAduan(Request $request)
    {
      /** flaging filter data */
      $is_open = true;
      /** hitung user login */
      $activities = Activity::usersBySeconds(30)->get();
      $numberOfUsers = Activity::users()->count();
      $jenispidanas = JenisPidana::orderBy('name', 'asc')->get();

      $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
        ->where('user_groups.user_id', Auth::id())
        ->select('groups.name AS group')
        ->first();
      /** param */
      $satker_param       = $request->satker;
      $jenis_kasus_param  = $request->jenis_kasus;
      $tahun_param        = $request->tahun;
      /** untuk label */
      $satker_fr_param = KategoriBagian::where('id', $satker_param)->first();
      $jenis_pidana_fr_param = JenisPidana::where('id', $jenis_kasus_param)->first();

      // Data untuk role selain admin
      if($login->group != 'Admin')
      { // untuk user selain admin 
        // filter satker dan jenis pidana
        $petas = Lapor::orderBy('created_at', 'desc')
        ->where('lapors.user_id', '=', Auth::user()->id)
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->get();

        // total kasus
        $count_kasus_f_map = $petas->count();
        
        // diagram batang
        /** januari */
        $jan_f_diagram = Lapor::whereMonth('date', '=', '01')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $jan_f_diagram_done     = $jan_f_diagram->count();

        /** februari */
        $feb_f_diagram = Lapor::whereMonth('date', '=', '02')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $feb_f_diagram_done     = $feb_f_diagram->count();

        /** maret */
        $mar_f_diagram = Lapor::whereMonth('date', '=', '03')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $mar_f_diagram_done     = $mar_f_diagram->count();

        /** april */
        $apr_f_diagram = Lapor::whereMonth('date', '=', '04')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $apr_f_diagram_done     = $apr_f_diagram->count();

        /** mei */
        $mei_f_diagram = Lapor::whereMonth('date', '=', '05')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $mei_f_diagram_done     = $mei_f_diagram->count();

        /** juni */
        $jun_f_diagram = Lapor::whereMonth('date', '=', '06')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $jun_f_diagram_done     = $jun_f_diagram->count();

        /** juli */
        $jul_f_diagram = Lapor::whereMonth('date', '=', '07')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $jul_f_diagram_done     = $jul_f_diagram->count();

        /** agustus */
        $aug_f_diagram = Lapor::whereMonth('date', '=', '08')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $aug_f_diagram_done     = $aug_f_diagram->count();

        /** september */
        $sep_f_diagram = Lapor::whereMonth('date', '=', '09')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $sep_f_diagram_done     = $sep_f_diagram->count();

        /** oktober */
        $oct_f_diagram = Lapor::whereMonth('date', '=', '10')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $oct_f_diagram_done     = $oct_f_diagram->count();

        /** november */
        $nov_f_diagram = Lapor::whereMonth('date', '=', '11')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $nov_f_diagram_done     = $nov_f_diagram->count();

        /** desember */
        $des_f_diagram = Lapor::whereMonth('date', '=', '12')
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $des_f_diagram_done     = $des_f_diagram->count();
        
        /** kebutuhan filter */
        $kategori_bagians = Akses::select('kategori_bagians.id', 'kategori_bagians.name')
              ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
              ->where('akses.user_id', Auth::user()->id)
              ->get();
              
        /** data kasus terbaru */
        $lapors = Lapor::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'lapors.kategori_bagian_id')
          ->select([
            'lapors.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan'
          ])
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->limit(4)
          ->get();

        /** top kasus */
        $top_kasus = DB::table('lapors')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(lapors.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->limit(3)
          ->get();

            /** top kasus Filter*/
        $top_kasus_filter = DB::table('lapors')
        ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
        ->select('jenis_pidanas.name', DB::raw('count(lapors.jenis_pidana) as total'))
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->where('lapors.user_id', '=', Auth::user()->id)
        ->groupBy('jenis_pidanas.name')
        ->orderBy('total', 'desc')
        ->limit(3)
        ->get();

        $top_kasus_satker_filter = DB::table('lapors')
          ->leftJoin('kategoris', 'lapors.kategori_id', '=', 'kategoris.id')
          ->select('kategoris.name', DB::raw('count(lapors.kategori_id) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->groupBy('kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian_filter = DB::table('lapors')
          ->leftJoin('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(lapors.kategori_bagian_id) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda_filter = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres_filter = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek_filter = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();

        // supaya tidak error di hosting
        $top_kasus_satker = null;
        $top_kasus_satker_bagian = null;
        $top_kasus_satker_polda = null;
        $top_kasus_satker_polres = null;
        $top_kasus_satker_polsek = null;

        /** total kasus */
        $count_kasus = Lapor::where('lapors.user_id', '=', Auth::user()->id)->count();
      
        /** total kasus this year */
        $count_kasus_this_y = Lapor::where('lapors.user_id', '=', Auth::user()->id)->whereYear('date', date('Y'))->count();

        // diagram batang this year
        /** januari */
        $jan_f_diagram_ty = Lapor::whereMonth('date', '=', '01')
          ->where('lapors.user_id', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** februari */
        $feb_f_diagram_ty = Lapor::whereMonth('date', '=', '02')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** maret */
        $mar_f_diagram_ty = Lapor::whereMonth('date', '=', '03')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** april */
        $apr_f_diagram_ty = Lapor::whereMonth('date', '=', '04')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** mei */
        $mei_f_diagram_ty = Lapor::whereMonth('date', '=', '05')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** juni */
        $jun_f_diagram_ty = Lapor::whereMonth('date', '=', '06')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** juli */
        $jul_f_diagram_ty = Lapor::whereMonth('date', '=', '07')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** agustus */
        $aug_f_diagram_ty = Lapor::whereMonth('date', '=', '08')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** september */
        $sep_f_diagram_ty = Lapor::whereMonth('date', '=', '09')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** oktober */
        $oct_f_diagram_ty = Lapor::whereMonth('date', '=', '10')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** november */
        $nov_f_diagram_ty = Lapor::whereMonth('date', '=', '11')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        /** desember */
        $des_f_diagram_ty = Lapor::whereMonth('date', '=', '12')
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->whereYear('date', date('Y'))
          ->count();

        $top_kasus_satker_filter = DB::table('lapors')
        ->leftJoin('kategoris', 'lapors.kategori_id', '=', 'kategoris.id')
        ->select('kategoris.name', DB::raw('count(lapors.kategori_id) as total'))
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->where('lapors.user_id', '=', Auth::user()->id)
        ->groupBy('kategoris.name')
        ->orderBy('total', 'desc')
        ->limit(3)
        ->get();
          
        // logs
        $logs = Log::select([
          'lapors.id', 
          'lapors.no_stplp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('lapors', 'logs.lapor_id', '=', 'lapors.id')
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

        // data time for chart
        $time_session_1 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('00:00'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_2 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('03:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_3 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('06:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_4 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('09:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_5 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('12:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_6 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('15:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_7 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('18:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
          ->count();

        $time_session_8 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('21:01'))
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
          ->where('lapors.user_id', '=', Auth::user()->id)
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
        $range = $max/10; // data range chart

        // Persentase perkembangan jumlah kejahatan
        // jumlah kejahatan tahun ini
        $x = Lapor::when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })->where('lapors.user_id', '=', Auth::user()->id)->count();

        $tahun_param_sebelum = $tahun_param - 1;
        // jumlah kejahatan tahun sebelumnya
        $y = Lapor::when(!empty($tahun_param_sebelum), function ($query) use ($tahun_param_sebelum) {
          $query->whereYear('date', $tahun_param_sebelum);
        })->where('lapors.user_id', '=', Auth::user()->id)->count();
        if($y != 0){
          $persentase_perkembangan_jumlah_kejahatan = (($x-$y)/$y)*100;
        }else{
          $persentase_perkembangan_jumlah_kejahatan = 0;
        }

        // Perhitungan persentase penyelesaian kejahatan
        $count_kasus_f_persentase         = Lapor::whereYear('date', $tahun_param)->where('lapors.user_id', '=', Auth::user()->id)->count();

        // Selang waktu terjadi kejahatan
        if($count_kasus_f_persentase != 0){
          $selang_waktu       = (365*24*60*60)/$count_kasus_f_persentase;
          $convert_menit      = $selang_waktu/60;
          $bulat_selang_waktu = ceil($convert_menit);
        }else{
          $bulat_selang_waktu = 0;
        }

        // perbandingan jumlah polisi dengan jumlah penduduk
        // $data_master = DataMaster::first();
        // $perbandingan_jumlah_polisi_dgn_penduduk = ceil($data_master->jumlah_penduduk/$data_master->jumlah_polisi);

        // resiko penduduk terkena perkara
        // $resiko_terkena_pidana = ($count_kasus_f_persentase*100000)/$data_master->jumlah_penduduk;
        
      }else{ // untuk user admin
        // filter satker dan jenis pidana
        $petas = Lapor::orderBy('created_at', 'desc')
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->get();

        // total kasus
        $count_kasus_f_map = $petas->count();

        // diagram barang
        /** januari */
        $jan_f_diagram = Lapor::whereMonth('date', '=', '01')
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
        $jan_f_diagram_done     = $jan_f_diagram->count();

        /** februari */
        $feb_f_diagram = Lapor::whereMonth('date', '=', '02')
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
        $feb_f_diagram_done     = $feb_f_diagram->count();

        /** maret */
        $mar_f_diagram = Lapor::whereMonth('date', '=', '03')
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
        $mar_f_diagram_done     = $mar_f_diagram->count();

        /** april */
        $apr_f_diagram = Lapor::whereMonth('date', '=', '04')
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
        $apr_f_diagram_done     = $apr_f_diagram->count();
        /** mei */
        $mei_f_diagram = Lapor::whereMonth('date', '=', '05')
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
        $mei_f_diagram_done     = $mei_f_diagram->count();

        /** juni */
        $jun_f_diagram = Lapor::whereMonth('date', '=', '06')
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
        $jun_f_diagram_done     = $jun_f_diagram->count();

        /** juli */
        $jul_f_diagram = Lapor::whereMonth('date', '=', '07')
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
        $jul_f_diagram_done     = $jul_f_diagram->count();;

        /** agustus */
        $aug_f_diagram = Lapor::whereMonth('date', '=', '08')
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
        $aug_f_diagram_done     = $aug_f_diagram->count();;

        /** september */
        $sep_f_diagram = Lapor::whereMonth('date', '=', '09')
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
        $sep_f_diagram_done     = $sep_f_diagram->count();

        /** oktober */
        $oct_f_diagram = Lapor::whereMonth('date', '=', '10')
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
        $oct_f_diagram_done     = $oct_f_diagram->count();

        /** november */
        $nov_f_diagram = Lapor::whereMonth('date', '=', '11')
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
        $nov_f_diagram_done     = $nov_f_diagram->count();
        /** desember */
        $des_f_diagram = Lapor::whereMonth('date', '=', '12')
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
        $des_f_diagram_done     = $des_f_diagram->count();

        /** kebutuhan filter */
        $kategori_bagians = KategoriBagian::orderBy('name', 'asc')->get();

        /** data kasus terbaru */
        $lapors = Lapor::orderBy('created_at', 'desc')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'lapors.kategori_bagian_id')
          ->select([
            'lapors.*',
            'jenis_pidanas.name as pidana',
            'kategori_bagians.name as satuan'
          ])
          ->limit(4)
          ->get();

        /** top kasus */
        $top_kasus = DB::table('lapors')
          ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
          ->select('jenis_pidanas.name', DB::raw('count(lapors.jenis_pidana) as total'))
          ->groupBy('jenis_pidanas.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker = DB::table('lapors')
          ->leftJoin('kategoris', 'lapors.kategori_id', '=', 'kategoris.id')
          ->select('kategoris.name', DB::raw('count(lapors.kategori_id) as total'))
          ->groupBy('kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian = DB::table('lapors')
          ->leftJoin('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(lapors.kategori_bagian_id) as total'))
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->join('kategoris', 'kategori_bagians.kategori_id', '=', 'kategoris.id')
          ->groupBy('kategori_bagians.id', 'kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();


           /** top kasus Filter*/
        $top_kasus_filter = DB::table('lapors')
        ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
        ->select('jenis_pidanas.name', DB::raw('count(lapors.jenis_pidana) as total'))
        ->when(!empty($satker_param), function ($query) use ($satker_param) {
          $query->where('kategori_bagian_id', $satker_param);
        })
        ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
          $query->where('jenis_pidana', $jenis_kasus_param);
        })
        ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
          $query->whereYear('date', $tahun_param);
        })
        ->groupBy('jenis_pidanas.name')
        ->orderBy('total', 'desc')
        ->limit(3)
        ->get();

        $top_kasus_satker_filter = DB::table('lapors')
          ->leftJoin('kategoris', 'lapors.kategori_id', '=', 'kategoris.id')
          ->select('kategoris.name', DB::raw('count(lapors.kategori_id) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->groupBy('kategoris.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_bagian_filter = DB::table('lapors')
          ->leftJoin('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->select('kategori_bagians.name', DB::raw('count(lapors.kategori_bagian_id) as total'))
          ->when(!empty($satker_param), function ($query) use ($satker_param) {
            $query->where('kategori_bagian_id', $satker_param);
          })
          ->when(!empty($jenis_kasus_param), function ($query) use ($jenis_kasus_param) {
            $query->where('jenis_pidana', $jenis_kasus_param);
          })
          ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
            $query->whereYear('date', $tahun_param);
          })
          ->groupBy('kategori_bagians.name')
          ->orderBy('total', 'desc')
          ->limit(3)
          ->get();

        $top_kasus_satker_polda_filter = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
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
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 1)
          ->limit(3)
          ->get();

        $top_kasus_satker_polres_filter = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
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
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 2)
          ->limit(3)
          ->get();

        $top_kasus_satker_polsek_filter = Lapor::select('kategori_bagians.id', 'kategori_bagians.name', DB::raw('count(lapors.kategori_id) as total'))
          ->join('kategori_bagians', 'lapors.kategori_bagian_id', '=', 'kategori_bagians.id')
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
          ->orderBy('total', 'desc')
          ->where('kategoris.id', 3)
          ->limit(3)
          ->get();

        /** total kasus */
        $count_kasus = Lapor::count();
       
        $count_kasus_this_y = Lapor::whereYear('date', date('Y'))->count();

        // diagram batang this year
        /** januari */
        $jan_f_diagram_ty = Lapor::whereMonth('date', '=', '01')
          ->whereYear('date', date('Y'))
          ->count();

        /** februari */
        $feb_f_diagram_ty = Lapor::whereMonth('date', '=', '02')
          ->whereYear('date', date('Y'))
          ->count();

        /** maret */
        $mar_f_diagram_ty = Lapor::whereMonth('date', '=', '03')
          ->whereYear('date', date('Y'))
          ->count();

        /** april */
        $apr_f_diagram_ty = Lapor::whereMonth('date', '=', '04')
          ->whereYear('date', date('Y'))
          ->count();

        /** mei */
        $mei_f_diagram_ty = Lapor::whereMonth('date', '=', '05')
          ->whereYear('date', date('Y'))
          ->count();

        /** juni */
        $jun_f_diagram_ty = Lapor::whereMonth('date', '=', '06')
          ->whereYear('date', date('Y'))
          ->count();

        /** juli */
        $jul_f_diagram_ty = Lapor::whereMonth('date', '=', '07')
          ->whereYear('date', date('Y'))
          ->count();

        /** agustus */
        $aug_f_diagram_ty = Lapor::whereMonth('date', '=', '08')
          ->whereYear('date', date('Y'))
          ->count();

        /** september */
        $sep_f_diagram_ty = Lapor::whereMonth('date', '=', '09')
          ->whereYear('date', date('Y'))
          ->count();

        /** oktober */
        $oct_f_diagram_ty = Lapor::whereMonth('date', '=', '10')
          ->whereYear('date', date('Y'))
          ->count();

        /** november */
        $nov_f_diagram_ty = Lapor::whereMonth('date', '=', '11')
          ->whereYear('date', date('Y'))
          ->count();

        /** desember */
        $des_f_diagram_ty = Lapor::whereMonth('date', '=', '12')
          ->whereYear('date', date('Y'))
          ->count();

        // logs
        $logs = Log::select([
          'lapors.id', 
          'lapors.no_stplp', 
          'logs.status',
          'logs.created_at',
          'users.name',
        ])->join('lapors', 'logs.lapor_id', '=', 'lapors.id')
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

        // data time for chart
        $time_session_1 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('00:00'))
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
          ->count();

        $time_session_2 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('03:01'))
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
          ->count();

        $time_session_3 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('06:01'))
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
          ->count();

        $time_session_4 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('09:01'))
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
          ->count();

        $time_session_5 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('12:01'))
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
          ->count();

        $time_session_6 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('15:01'))
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
          ->count();

        $time_session_7 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('18:01'))
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
          ->count();

        $time_session_8 = Lapor::whereTime('time', '>=', \Carbon\Carbon::parse('21:01'))
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

        // Persentase perkembangan jumlah kejahatan
        // jumlah kejahatan tahun ini
        // $x = Lapor::when(!empty($tahun_param), function ($query) use ($tahun_param) {
        //   $query->whereYear('date', $tahun_param);
        // })->count();
        // $tahun_param_sebelum = $tahun_param - 1;
        // // jumlah kejahatan tahun sebelumnya
        // $y = Lapor::when(!empty($tahun_param_sebelum), function ($query) use ($tahun_param_sebelum) {
        //   $query->whereYear('date', $tahun_param_sebelum);
        // })->count();
        // $persentase_perkembangan_jumlah_kejahatan = (($x-$y)/$y)*100;
        $count_kasus_f_persentase         = Lapor::whereYear('date', $tahun_param)->count();

        // Selang waktu terjadi kejahatan
        // $selang_waktu       = (365*24*60*60)/$count_kasus_f_persentase;
        // $convert_menit      = $selang_waktu/60;
        // $bulat_selang_waktu = ceil($convert_menit);

        // perbandingan jumlah polisi dengan jumlah penduduk
        // $data_master = DataMaster::first();
        // $perbandingan_jumlah_polisi_dgn_penduduk = ceil($data_master->jumlah_penduduk/$data_master->jumlah_polisi);

        // resiko penduduk terkena perkara
        // $resiko_terkena_pidana = ($count_kasus_f_persentase*100000)/$data_master->jumlah_penduduk;
      }

      $petas = $petas->take(5000)->where('lat', '!=', null)->where('long', '!=', null)->where('pin', '!=', null)->where('divisi', '!=', null);
        
      return view('admin.dashboard-lapor', compact(
        'lapors',
        'top_kasus',
        'count_kasus',
        'top_kasus_filter',
        'is_open',
        'count_kasus_this_y',
        'kategori_bagians',
        'jenispidanas',
        'numberOfUsers',
        'activities',
        'top_kasus_satker',
        'top_kasus_satker_bagian',
        'petas',
        'count_kasus_f_map',
        'tahun_param',
        'satker_param',
        'jenis_kasus_param',
        'satker_fr_param',
        'jenis_pidana_fr_param',
        'jan_f_diagram_done',
        'feb_f_diagram_done',
        'mar_f_diagram_done',
        'apr_f_diagram_done',
        'mei_f_diagram_done',
        'jun_f_diagram_done',
        'jul_f_diagram_done',
        'aug_f_diagram_done',
        'sep_f_diagram_done',
        'oct_f_diagram_done',
        'nov_f_diagram_done',
        'des_f_diagram_done',
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
        'data_time',
        'min',
        'max',
        'range',
        // 'bulat_selang_waktu',
        // 'perbandingan_jumlah_polisi_dgn_penduduk',
        // 'resiko_terkena_pidana',
        'top_kasus_satker_polda',
        'top_kasus_satker_polres',
        'top_kasus_satker_filter',
        'top_kasus_satker_polsek_filter',
        'top_kasus_satker_polres_filter',
        'top_kasus_satker_polda_filter',
        'top_kasus_satker_bagian_filter',
        'top_kasus_satker_polsek'
      ));
    }
}
