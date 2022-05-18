<?php

namespace App\Services;

use Auth;
use App\Akses;
use App\Perkara;
use Illuminate\Http\Request;

class PerkaraListService
{
    public function showAdmin($perPage, $param)
    {
        $query_daterange    = $param['query_daterange'];
        $original_date_from = '';
        $original_date_to   = '';

        if($query_daterange){
            $arr_date    = explode("-",$query_daterange);
            $arr_date[0] = rtrim($arr_date[0]);
            $arr_date[1] = ltrim($arr_date[1]);
            // change format date from
            $replace_from           = str_replace("/","-",$arr_date[0]);
            $original_date_from     = $replace_from;
            // change format date to
            $replace_to           = str_replace("/","-",$arr_date[1]);
            $original_date_to     = $replace_to;
        }

        // param filter
        $query_no_lp        = $param['query_no_lp'];
        $query_satker       = $param['query_satker'];
        $query_petugas      = $param['query_petugas'];
        $query_korban       = $param['query_korban'];
        $query_bukti        = $param['query_bukti'];
        $query_kejadian     = $param['query_kejadian'];
        $query_pidana       = $param['query_pidana'];
        $query_status       = $param['query_status'];

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
            ->whereYear('date', '<', date('Y', strtotime('0 year')))
            ->when(!empty($query_no_lp), function ($query) use ($query_no_lp) {
              $query->where('perkaras.no_lp', 'like', "%$query_no_lp%");
            })
            ->when(!empty($query_satker), function ($query) use ($query_satker) {
                $query->where('kategori_bagians.name', 'like', "%$query_satker%");
            })
            ->when(!empty($query_petugas), function ($query) use ($query_petugas) {
                $query->where('perkaras.nama_petugas', 'like', "%$query_petugas%");
            })
            ->when(!empty($query_korban), function ($query) use ($query_korban) {
                $query->where('korbans.nama', 'like', "%$query_korban%");
            })
            ->when(!empty($query_bukti), function ($query) use ($query_bukti) {
                $query->where('korbans.barang_bukti', 'like', "%$query_bukti%");
            })
            ->when(!empty($query_kejadian), function ($query) use ($query_kejadian) {
                $query->where('perkaras.date', $query_kejadian);
            })
            ->when(!empty($query_pidana), function ($query) use ($query_pidana) {
                $query->where('jenis_pidanas.name', 'like', "%$query_pidana%");
            })
            ->when(!empty($query_status), function ($query) use ($query_status) {
                $query->where('statuses.name', 'like', "%$query_status%");
            })
            ->when(!empty($query_daterange), function ($query) use ($original_date_from, $original_date_to) {
                $query->whereBetween('perkaras.date', [$original_date_from, $original_date_to]);
            })
            ->paginate($perPage);

        return $perkaras;
    }

    public function showNotAdmin($perPage, $param)
    {
        $query_daterange    = $param['query_daterange'];
        $original_date_from = '';
        $original_date_to   = '';

        if($query_daterange){
            $arr_date    = explode("-",$query_daterange);
            $arr_date[0] = rtrim($arr_date[0]);
            $arr_date[1] = ltrim($arr_date[1]);
            // change format date from
            $replace_from           = str_replace("/","-",$arr_date[0]);
            $original_date_from     = $replace_from;
            // change format date to
            $replace_to           = str_replace("/","-",$arr_date[1]);
            $original_date_to     = $replace_to;
        }

        // param filter
        $query_no_lp        = $param['query_no_lp'];
        $query_petugas      = $param['query_petugas'];
        $query_korban       = $param['query_korban'];
        $query_bukti        = $param['query_bukti'];
        $query_kejadian     = $param['query_kejadian'];
        $query_pidana       = $param['query_pidana'];
        $query_status       = $param['query_status'];

        $perkaras = Akses::orderBy('perkaras.updated_at', 'desc')
              ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
              ->leftJoin('users', 'users.id', '=', 'akses.user_id')
              ->leftJoin('perkaras', 'perkaras.kategori_bagian_id', '=', 'akses.sakter_id')
              ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
              ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
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
              ->whereYear('date', '<', date('Y', strtotime('0 year')))
              ->where('akses.user_id', '=', Auth::user()->id)
              ->when(!empty($query_no_lp), function ($query) use ($query_no_lp) {
                $query->where('perkaras.no_lp', 'like', "%$query_no_lp%");
              })
              ->when(!empty($query_petugas), function ($query) use ($query_petugas) {
                  $query->where('perkaras.nama_petugas', 'like', "%$query_petugas%");
              })
              ->when(!empty($query_korban), function ($query) use ($query_korban) {
                  $query->where('korbans.nama', 'like', "%$query_korban%");
              })
              ->when(!empty($query_bukti), function ($query) use ($query_bukti) {
                  $query->where('korbans.barang_bukti', 'like', "%$query_bukti%");
              })
              ->when(!empty($query_kejadian), function ($query) use ($query_kejadian) {
                  $query->where('perkaras.date', $query_kejadian);
              })
              ->when(!empty($query_pidana), function ($query) use ($query_pidana) {
                  $query->where('jenis_pidanas.name', 'like', "%$query_pidana%");
              })
              ->when(!empty($query_status), function ($query) use ($query_status) {
                  $query->where('statuses.name', 'like', "%$query_status%");
              })
              ->when(!empty($query_daterange), function ($query) use ($original_date_from, $original_date_to) {
                  $query->whereBetween('perkaras.date', [$original_date_from, $original_date_to]);
              })
              ->paginate($perPage);

        return $perkaras;
    }

    public function backupData()
    {
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->select('groups.name AS group')
            ->first();
        $count_kasus =  25;
        // parameter filter
        // $start       = $request->get('start_date');
        // $end         = $request->get('end_date');
        // $date_range  = array($request->get('start_date'), $request->get('end_date'));
        // $no_lp       = $request->no_lp;
        // $satker      = $request->satker;
        // $petugas     = $request->petugas;
        // $korban      = $request->korban;
        // $bukti       = $request->bukti;
        // $pidana_id   = $request->pidana;
        // $status_id   = $request->status;

        /** param */
        $satker_param       = $request->satker;
        $jenis_kasus_param  = $request->jenis_kasus;
        $tahun_param        = $request->tahun;
        $search_bar         = $request->search;
        
        if($login->group!='Admin'){
            $perkaras = Akses::orderBy('perkaras.updated_at', 'desc')
                    ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                    ->leftJoin('users', 'users.id', '=', 'akses.user_id')
                    ->leftJoin('perkaras', 'perkaras.kategori_bagian_id', '=', 'akses.sakter_id')
                    ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
                    ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
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
                    ->whereYear('date', '<', date('Y', strtotime('0 year')))
                    ->where('akses.user_id', '=', Auth::user()->id)
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
                        ->paginate($count_kasus);
                    // ->when(!empty($request->no_lp), function ($query) use ($request) {
                    //     $query->where('perkaras.no_lp', 'like', "%{$request->no_lp}%");
                    // })
                    // ->when(!empty($request->satker), function ($query) use ($request) {
                    //     $query->where('kategori_bagians.name', 'like', "%{$request->satker}%");
                    // })
                    // ->when(!empty($request->petugas), function ($query) use ($request) {
                    //     $query->where('nama_petugas', 'like', "%{$request->petugas}%");
                    // })
                    // ->when(!empty($request->korban), function ($query) use ($request) {
                    //     $query->where('korbans.nama', 'like', "%{$request->korban}%");
                    // })
                    // ->when(!empty($request->bukti), function ($query) use ($request) {
                    //     $query->where('barang_bukti', 'like', "%{$request->bukti}%");
                    // })
                    // ->when(!empty($request->start_date && $request->end_date), function ($query) use ($date_range) {
                    //     $query->whereBetween('date', $date_range);
                    // })
                    // ->when(!empty($request->pidana), function ($query) use ($request) {
                    //     $query->where('jenis_pidana', $request->pidana);
                    // })
                    // ->when(!empty($request->status), function ($query) use ($request) {
                    //     $query->where('status_id', $request->status);
                    // })
                    // ->paginate($count_kasus);

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
                    ->whereYear('date', '<', date('Y', strtotime('0 year')))
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
                        ->paginate($count_kasus);
                    // ->when(!empty($request->no_lp), function ($query) use ($request) {
                    //     $query->where('perkaras.no_lp', 'like', "%{$request->no_lp}%");
                    // })
                    // ->when(!empty($request->satker), function ($query) use ($request) {
                    //     $query->where('kategori_bagians.name', 'like', "%{$request->satker}%");
                    // })
                    // ->when(!empty($request->petugas), function ($query) use ($request) {
                    //     $query->where('nama_petugas', 'like', "%{$request->petugas}%");
                    // })
                    // ->when(!empty($request->korban), function ($query) use ($request) {
                    //     $query->where('korbans.nama', 'like', "%{$request->korban}%");
                    // })
                    // ->when(!empty($request->bukti), function ($query) use ($request) {
                    //     $query->where('barang_bukti', 'like', "%{$request->bukti}%");
                    // })
                    // ->when(!empty($request->start_date && $request->end_date), function ($query) use ($date_range) {
                    //     $query->whereBetween('date', $date_range);
                    // })
                    // ->when(!empty($request->pidana), function ($query) use ($request) {
                    //     $query->where('jenis_pidana', $request->pidana);
                    // })
                    // ->when(!empty($request->status), function ($query) use ($request) {
                    //     $query->where('status_id', $request->status);
                    // })
                    // ->paginate($count_kasus);
        }
        
        return view('livewire.perkara.perkara-last-year-list');
    }
}
