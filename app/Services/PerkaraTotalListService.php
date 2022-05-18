<?php

namespace App\Services;

use Auth;
use App\Akses;
use App\Perkara;
use Illuminate\Http\Request;

class PerkaraTotalListService
{
    public function showAdmin($perPage, $param, $param_mount)
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
            // ->where('status_id', '=', 1)
            ->when(!empty($param_mount), function ($query) use ($param_mount) {
                $query->where('date_no_lp', '<=', $param_mount);
            })
            ->paginate($perPage);

        return $perkaras;
    }

    public function showNotAdmin($perPage, $param, $param_mount)
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
            // ->where('status_id', '=', 1)
            ->when(!empty($param_mount), function ($query) use ($param_mount) {
                $query->where('date_no_lp', '<=', $param_mount);
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
            // ->where('status_id', '=', 1)
            ->when(!empty($param_mount), function ($query) use ($param_mount) {
                $query->where('date_no_lp', '<=', $param_mount);
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
            ->paginate(25);
        }
        
        return view('livewire.perkara.perkara-last-year-list');
    }
}
