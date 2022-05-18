<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use File;
use Alert;
use App\Lapor;
use App\Kategori;
use App\KategoriBagian;
use App\JenisPidana;
use App\UserGroup;
use App\Group;
use App\Korban;
use App\Status;
use App\Akses;
use App\User;
use App\Tiket;
use App\MasterAnggaran;
use App\TurunanSatuan;
use App\Anggaran;
use App\TypeNarkoba;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class LaporController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function thisYear(Request $request)
    {
        // untuk title blade
        $year_now  = date('Y');
        $title     = "Data Lapor Tahun ".$year_now;
        // untuk form search
        $is_open   = 1;

        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->select('groups.name AS group')
            ->first();
        $count_lapor =  25;

        /** param */
        $satker_param       = $request->satker;
        $jenis_kasus_param  = $request->jenis_kasus;
        $tahun_param        = $request->tahun;
        $search_bar         = $request->search;
        
        if($login->group!='Admin'){
            $lapors = Akses::orderBy('lapors.updated_at', 'desc')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->leftJoin('users', 'users.id', '=', 'akses.user_id')
                ->leftJoin('lapors', 'lapors.kategori_bagian_id', '=', 'akses.sakter_id')
                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
                ->select(
                    'lapors.id',
                    'lapors.no_stplp',
                    'kategori_bagians.name as satuan',
                    'lapors.nama_petugas',
                    'lapors.nama_pelapor',
                    'lapors.date',
                    'lapors.time'
                )
                ->whereYear('date', '=', date('Y', strtotime('0 year')))
                ->where('akses.user_id', '=', Auth::user()->id)
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('lapors.no_stplp', 'like', "%$search_bar%")
                        ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_petugas', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_pelapor', 'like', "%$search_bar%");
                    })
                    ->paginate($count_lapor);
        }else{
            $lapors = Lapor::orderBy('lapors.updated_at', 'desc')
                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'lapors.kategori_bagian_id')
                ->select(
                    'lapors.id',
                    'lapors.no_stplp',
                    'kategori_bagians.name as satuan',
                    'lapors.nama_petugas',
                    'lapors.nama_pelapor',
                    'lapors.date',
                    'lapors.time'
                )
                ->whereYear('date', '=', date('Y', strtotime('0 year')))
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('lapors.no_stplp', 'like', "%$search_bar%")
                        ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_petugas', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_pelapor', 'like', "%$search_bar%");
                    })
                    ->paginate($count_lapor);

        }

        return view('admin.lapor.index', compact(
            'satker_param',
            'jenis_kasus_param',
            'tahun_param',
            'search_bar',
            'lapors',
            'title',
            'is_open'
        ));
    }

    public function lastYear(Request $request)
    {
        // untuk title blade
        $year_now  = date('Y');
        $title     = "Data Lapor Tahun Kemarin";
        // untuk form search
        $is_open   = 0;

        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->select('groups.name AS group')
            ->first();
        $count_lapor =  25;

        /** param */
        $satker_param       = $request->satker;
        $jenis_kasus_param  = $request->jenis_kasus;
        $tahun_param        = $request->tahun;
        $search_bar         = $request->search;
        
        if($login->group!='Admin'){
            $lapors = Akses::orderBy('lapors.updated_at', 'desc')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->leftJoin('users', 'users.id', '=', 'akses.user_id')
                ->leftJoin('lapors', 'lapors.kategori_bagian_id', '=', 'akses.sakter_id')
                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
                ->select(
                    'lapors.id',
                    'lapors.no_stplp',
                    'kategori_bagians.name as satuan',
                    'lapors.nama_petugas',
                    'lapors.nama_pelapor',
                    'lapors.date',
                    'lapors.time'
                )
                ->whereYear('date', '<', date('Y', strtotime('0 year')))
                ->where('akses.user_id', '=', Auth::user()->id)
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('lapors.no_stplp', 'like', "%$search_bar%")
                        ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_petugas', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_pelapor', 'like', "%$search_bar%");
                    })
                    ->paginate($count_lapor);
        }else{
            $lapors = Lapor::orderBy('lapors.updated_at', 'desc')
                ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'lapors.jenis_pidana')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'lapors.kategori_bagian_id')
                ->select(
                    'lapors.id',
                    'lapors.no_stplp',
                    'kategori_bagians.name as satuan',
                    'lapors.nama_petugas',
                    'lapors.nama_pelapor',
                    'lapors.date',
                    'lapors.time'
                )
                ->whereYear('date', '<', date('Y', strtotime('0 year')))
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('lapors.no_stplp', 'like', "%$search_bar%")
                        ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_petugas', 'like', "%$search_bar%")
                        ->orWhere('lapors.nama_pelapor', 'like', "%$search_bar%");
                    })
                    ->paginate($count_lapor);

        }

        return view('admin.lapor.index', compact(
            'satker_param',
            'jenis_kasus_param',
            'tahun_param',
            'search_bar',
            'lapors',
            'title',
            'is_open'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis_pidanas=JenisPidana::pluck('name', 'id');
        $jenis_pidanas->prepend('=== Pilih Jenis Pidana ===', '');
        // untuk admin
        $satker=KategoriBagian::pluck('name', 'id');
        $satker->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        $type_narkoba=TypeNarkoba::pluck('name', 'id');
        $type_narkoba->prepend('=== Pilih Jenis Narkoba ===', '');
        // untuk bukan admin
        $satker_not_admin=Akses::leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->where('akses.user_id', Auth::user()->id)
                ->pluck('kategori_bagians.name', 'kategori_bagians.id');
        $satker_not_admin->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        return View::make('admin.lapor.create', compact('satker_not_admin', 'satker', 'jenis_pidanas', 'type_narkoba'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validatedData = $request->validate([
            'no_stplp' => 'unique:lapors,no_stplp',
        ],
        [
            'no_stplp.unique' => 'No STPLP sudah digunakan'
        ]
        );

        // get group user
        $user_group = UserGroup::leftjoin('groups', 'groups.id', '=', 'user_groups.group_id')
            ->select(
                'groups.name',
                'user_groups.group_id'
            )
            ->where('user_id', Auth::user()->id)
            ->first();
        // masuk table lapors
        $lapor= new Lapor;
        $lapor->user_id             = Auth::user()->id;
        $lapor->no_stplp            = Input::get('no_stplp');
        $lapor->date_no_stplp       = Input::get('date_no_stplp');
        
        // Jika User Admin
        if($user_group->group_id == 1){
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $lapor->kategori_id         = $check_satuan->kategori_id;
            $lapor->kategori_bagian_id  = Input::get('satker');
            $lapor->divisi              = Input::get('divisi');
            if ($check_satuan->kategori_id == "1") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
    
            if ($check_satuan->kategori_id == "2") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
    
            if ($check_satuan->kategori_id == "3") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        // Jika User Polda
        }elseif ($user_group->group_id == 2) {
            // Cek divisi user polda
            $check_user = User::where('id', Auth::user()->id)->first();

            // Jika divisi Ditreskrimsus
            if($check_user->id == 2){
              $lapor->kategori_id         = 1;
              $lapor->kategori_bagian_id  = 179;
              $lapor->divisi              = Auth::user()->divisi;
              $lapor->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

            // Jika divisi Ditreskrimum
            if($check_user->id == 3){
              $lapor->kategori_id         = 1;
              $lapor->kategori_bagian_id  = 171;
              $lapor->divisi              = Auth::user()->divisi;
              $lapor->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            
            // Jika divisi Ditresnarkoba
            if($check_user->id == 4){
              $lapor->kategori_id         = 1;
              $lapor->kategori_bagian_id  = 1;
              $lapor->divisi              = Auth::user()->divisi;
              $lapor->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

        // Selain Polda dan Admin
        }else{
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $lapor->kategori_id         = $check_satuan->kategori_id;
            $lapor->kategori_bagian_id  = Input::get('satker');
            $lapor->divisi              = Auth::user()->divisi;
            // Untuk Polda
            if ($check_satuan->kategori_id == "1") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            // Untuk Polres
            if ($check_satuan->kategori_id == "2") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            // Untuk Polsek
            if ($check_satuan->kategori_id == "3") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        }

        $lapor->uraian              = Input::get('uraian');
        $lapor->modus_operasi       = Input::get('modus_operasi');
        $lapor->nama_petugas        = Input::get('nama_petugas');
        $lapor->pangkat             = Input::get('pangkat');
        $lapor->no_tlp              = Input::get('no_tlp');
        $lapor->nama_pelapor        = Input::get('nama_pelapor');
        $lapor->umur_pelapor        = Input::get('umur_pelapor');
        $lapor->pendidikan_pelapor  = Input::get('pendidikan_pelapor');
        $lapor->pekerjaan_pelapor   = Input::get('pekerjaan_pelapor');
        $lapor->asal_pelapor        = Input::get('asal_pelapor');

        $saksi = implode(", ",$request->saksi);
        $lapor->saksi               = $saksi;

        $arrays = $request->terlapor;
      
        $a = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($arrays), ENT_NOQUOTES));
        $b = (str_replace('{"','',$a));
        $c = (str_replace('"}','',$b));
        $d = (str_replace(',',', ',$c));
        $string_terlapor = (str_replace('"','',$d));
        $lapor->terlapor            = $string_terlapor;

        $lapor->barang_bukti        = $request->barang_bukti;
        $lapor->tkp                 = Input::get('tkp');
        $lapor->lat                 = Input::get('lat');
        $lapor->long                = Input::get('long');
        $lapor->date                = Input::get('date');
        $lapor->time                = Input::get('time');
        $lapor->jenis_pidana        = Input::get('jenis_pidana');
        $lapor->anggaran            = Input::get('anggaran');
        $lapor->handle_bukti        = 0;
        $lapor->soft_delete_id      = 0;
        $lapor->save();

        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'lapor_id'   => $lapor->id,
          'status'     => 'create',
        ];
        saveLogLapor($createLog);

        $year_lapor               = date('Y', strtotime($lapor->date));
        $year_now                 = date('Y');
        // redirect
        if($year_lapor == $year_now){
            return Redirect::action('admin\LaporController@thisYear', compact('lapor'))->with('flash-store','Data berhasil ditambahkan.');
        }else{
            return Redirect::action('admin\LaporController@lastYear', compact('lapor'))->with('flash-store','Data berhasil ditambahkan.');
        }
    }

    public function updateData($id)
    {
        $lapor = Lapor::where('id', $id)->firstOrFail();
        $pelaku = $lapor->terlapor;
        $jenis_pidanas=JenisPidana::pluck('name', 'id');
        $jenis_pidanas->prepend('=== Pilih Jenis Pidana ===', '');
        // untuk admin
        $satker=KategoriBagian::pluck('name', 'id');
        $satker->prepend('=== Pilih Satuan Kerja (Satker) ===', '');
        // untuk bukan admin
        $satker_not_admin=Akses::leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->where('akses.user_id', Auth::user()->id)
                ->pluck('kategori_bagians.name', 'kategori_bagians.id');

        $satker_not_admin->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        return view('admin.lapor.update', compact('pelaku', 'lapor', 'jenis_pidanas', 'satker', 'satker_not_admin'));
    }

    public function updated(Request $request, $id)
    {
        // get group user
        $user_group = UserGroup::leftjoin('groups', 'groups.id', '=', 'user_groups.group_id')
        ->select(
            'groups.name',
            'user_groups.group_id'
            )
        ->where('user_id', Auth::user()->id)
        ->first();
            
        $lapor = Lapor::findOrFail($id);
        $lapor->user_id             = Auth::user()->id;
        $lapor->date_no_stplp       = Input::get('date_no_stplp');
        
        // Jika User admin
        if($user_group->group_id == 1){
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $lapor->kategori_id         = $check_satuan->kategori_id;
            $lapor->kategori_bagian_id  = Input::get('satker');
            $lapor->divisi              = Input::get('divisi');
            if ($check_satuan->kategori_id == "1") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
    
            if ($check_satuan->kategori_id == "2") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
    
            if ($check_satuan->kategori_id == "3") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        // Jika user polda
        }elseif ($user_group->group_id == 2) {
            // Cek divisi user polda
            $check_user = User::where('id', Auth::user()->id)->first();

            // Jika divisi Ditreskrimsus
            if($check_user->id == 2){
              $lapor->kategori_id         = 1;
              $lapor->kategori_bagian_id  = 179;
              $lapor->divisi              = Auth::user()->divisi;
              $lapor->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

            // Jika divisi Ditreskrimum
            if($check_user->id == 3){
              $lapor->kategori_id         = 1;
              $lapor->kategori_bagian_id  = 171;
              $lapor->divisi              = Auth::user()->divisi;
              $lapor->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            
            // Jika divisi Ditresnarkoba
            if($check_user->id == 4){
              $lapor->kategori_id         = 1;
              $lapor->kategori_bagian_id  = 1;
              $lapor->divisi              = Auth::user()->divisi;
              $lapor->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

        // Jika user selain admin dan polda
        }else{
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $lapor->kategori_id         = $check_satuan->kategori_id;
            $lapor->kategori_bagian_id  = Input::get('satker');
            $lapor->divisi              = Auth::user()->divisi;

            // Untuk Polda
            if ($check_satuan->kategori_id == "1") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

            // Untuk Polres
            if ($check_satuan->kategori_id == "2") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            
            // Untuk Polsek
            if ($check_satuan->kategori_id == "3") {
                $lapor->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        }

        $lapor->uraian              = Input::get('uraian');
        $lapor->modus_operasi       = Input::get('modus_operasi');
        $lapor->nama_petugas        = Input::get('nama_petugas');
        $lapor->pangkat             = Input::get('pangkat');
        $lapor->no_tlp              = Input::get('no_tlp');
        $lapor->nama_pelapor        = Input::get('nama_pelapor');
        $lapor->umur_pelapor        = Input::get('umur_pelapor');
        $lapor->pendidikan_pelapor  = Input::get('pendidikan_pelapor');
        $lapor->pekerjaan_pelapor   = Input::get('pekerjaan_pelapor');
        $lapor->asal_pelapor        = Input::get('asal_pelapor');

        if($request->saksi){
            $saksi = implode(", ",$request->saksi);
            $lapor->saksi           = $saksi;
        }

        if($request->pelaku){
            $arrays = $request->terlapor;
          
            $a = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($arrays), ENT_NOQUOTES));
            $b = (str_replace('{"','',$a));
            $c = (str_replace('"}','',$b));
            $d = (str_replace(',',', ',$c));
            $string_terlapor            = (str_replace('"','',$d));
            $lapor->terlapor            = $string_terlapor;
        }

        $lapor->barang_bukti        = $request->barang_bukti;
        $lapor->tkp                 = Input::get('tkp');
        $lapor->lat                 = Input::get('lat');
        $lapor->long                = Input::get('long');
        $lapor->date                = Input::get('date');
        $lapor->time                = Input::get('time');
        $lapor->jenis_pidana        = Input::get('jenis_pidana');
        $lapor->anggaran            = Input::get('anggaran');
        $lapor->handle_bukti        = 0;
        $lapor->soft_delete_id      = 0;
        $lapor->save();

        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'lapor_id'   => $id,
          'status'     => 'edit',
        ];
        saveLogLapor($createLog);
        // kondisi data update
        $year_lapor               = date('Y', strtotime($lapor->date));
        $year_now                 = date('Y');

        if($year_lapor == $year_now){
            return Redirect::action('admin\LaporController@thisYear', compact('lapor'))->with('flash-update','Data berhasil diubah.');
        }else{
            return Redirect::action('admin\LaporController@lastYear', compact('lapor'))->with('flash-update','Data berhasil diubah.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lapor = Lapor::where('id', $id)->firstOrFail();
        return view('admin.lapor.show', compact('lapor'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lapor = Lapor::where('id', $id)->firstOrFail();
        $lapor->delete();
        return  Redirect::back()->with('flash-destroy','Data berhasil dihapus.');
    }
}
