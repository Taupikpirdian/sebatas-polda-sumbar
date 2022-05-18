<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use File;
use Alert;
use App\TrafficAccident;
use App\AccidentVictimt;
use App\Kategori;
use App\KategoriBagian;
use App\JenisPidana;
use App\UserGroup;
use App\Group;
use App\Korban;
use App\Status;
use App\Akses;
use App\User;
use App\MasterAnggaran;
use App\TurunanSatuan;
use App\Anggaran;
use App\FaktorKecelakaan;
use App\KlasfikasiKecelakaan;
use App\KondisiKorban;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class LakaLantasController extends Controller
{
    public function thisYear(Request $request)
    {
        // untuk title blade
        $year_now  = date('Y');
        $title     = "Data Laka Lantas Tahun ".$year_now;
        // untuk form search
        $is_open   = 1;

        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->select('groups.name AS group')
            ->first();
        $count_data =  25;

        /** param */
        $satker_param       = $request->satker;
        $tahun_param        = $request->tahun;
        $search_bar         = $request->search;
        
        if($login->group!='Admin'){
            $traffic_accidents = TrafficAccident::orderBy('traffic_accidents.date_no_lp', 'desc')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'traffic_accidents.kategori_bagian_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'traffic_accidents.status_id')
                ->select([
                    'traffic_accidents.id',
                    'traffic_accidents.no_lp',
                    'traffic_accidents.date_no_lp',
                    'kategori_bagians.name as satuan',
                    'traffic_accidents.nama_petugas',
                    // 'traffic_accidents.nama_pelapor',
                    'traffic_accidents.date',
                    'traffic_accidents.time',
                    'traffic_accidents.status_id',
                    'statuses.name as status',
                ])
                ->whereYear('date', '=', date('Y', strtotime('0 year')))
                ->where('traffic_accidents.user_id', '=', Auth::user()->id)
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('traffic_accidents.no_lp', 'like', "%$search_bar%")
                          ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                          ->orWhere('traffic_accidents.nama_petugas', 'like', "%$search_bar%");
                    })
                    ->paginate($count_data);
        }else{
            $traffic_accidents = TrafficAccident::orderBy('traffic_accidents.date_no_lp', 'desc')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'traffic_accidents.kategori_bagian_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'traffic_accidents.status_id')
                ->select([
                    'traffic_accidents.id',
                    'traffic_accidents.no_lp',
                    'traffic_accidents.date_no_lp',
                    'kategori_bagians.name as satuan',
                    'traffic_accidents.nama_petugas',
                    // 'traffic_accidents.nama_pelapor',
                    'traffic_accidents.date',
                    'traffic_accidents.time',
                    'traffic_accidents.status_id',
                    'statuses.name as status',
                ])
                ->whereYear('date', '=', date('Y', strtotime('0 year')))
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('traffic_accidents.no_lp', 'like', "%$search_bar%")
                          ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                          ->orWhere('traffic_accidents.nama_petugas', 'like', "%$search_bar%");
                    })
                    ->paginate($count_data);

        }


        return view('admin.traffic-accident.index', compact(
            'satker_param',
            'tahun_param',
            'search_bar',
            'traffic_accidents',
            'title',
            'is_open'
        ));
    }

    public function lastYear(Request $request)
    {
        // untuk title blade
        $year_now  = date('Y');
        $title     = "Data Laka Lantas Tahun Kemarin";
        // untuk form search
        $is_open   = 0;

        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
            ->where('user_groups.user_id', Auth::id())
            ->select('groups.name AS group')
            ->first();
        $count_data =  25;

        /** param */
        $satker_param       = $request->satker;
        $tahun_param        = $request->tahun;
        $search_bar         = $request->search;
        
        if($login->group!='Admin'){
            $traffic_accidents = TrafficAccident::orderBy('traffic_accidents.date_no_lp', 'desc')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'traffic_accidents.kategori_bagian_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'traffic_accidents.status_id')
                ->select(
                    'traffic_accidents.id',
                    'traffic_accidents.no_lp',
                    'traffic_accidents.date_no_lp',
                    'kategori_bagians.name as satuan',
                    'traffic_accidents.nama_petugas',
                    // 'traffic_accidents.nama_pelapor',
                    'traffic_accidents.date',
                    'traffic_accidents.time',
                    'traffic_accidents.status_id',
                    'statuses.name as status',
                )
                ->whereYear('date', '<', date('Y', strtotime('0 year')))
                ->where('traffic_accidents.user_id', '=', Auth::user()->id)
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('traffic_accidents.no_lp', 'like', "%$search_bar%")
                        ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                        ->orWhere('traffic_accidents.nama_petugas', 'like', "%$search_bar%");
                    })
                    ->paginate($count_data);

        }else{
            $traffic_accidents = TrafficAccident::orderBy('traffic_accidents.date_no_lp', 'desc')
                ->leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'traffic_accidents.kategori_bagian_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'traffic_accidents.status_id')
                ->select(
                    'traffic_accidents.id',
                    'traffic_accidents.no_lp',
                    'traffic_accidents.date_no_lp',
                    'kategori_bagians.name as satuan',
                    'traffic_accidents.nama_petugas',
                    // 'traffic_accidents.nama_pelapor',
                    'traffic_accidents.date',
                    'traffic_accidents.time',
                    'traffic_accidents.status_id',
                    'statuses.name as status',
                )
                ->whereYear('date', '<', date('Y', strtotime('0 year')))
                ->when(!empty($satker_param), function ($query) use ($satker_param) {
                    $query->where('kategori_bagian_id', $satker_param);
                    })
                    ->when(!empty($tahun_param), function ($query) use ($tahun_param) {
                    $query->whereYear('date', $tahun_param);
                    })
                    ->where(function ($query) use ($search_bar) {
                    $query->where('traffic_accidents.no_lp', 'like', "%$search_bar%")
                        ->orWhere('kategori_bagians.name', 'like', "%$search_bar%")
                        ->orWhere('traffic_accidents.nama_petugas', 'like', "%$search_bar%");
                    })
                    ->paginate($count_data);

        }

        return view('admin.traffic-accident.index', compact(
            'satker_param',
            'tahun_param',
            'search_bar',
            'traffic_accidents',
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
        // untuk admin
        $satker=KategoriBagian::where('kategori_id', '!=', '3')->pluck('name', 'id');
        $satker->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        // untuk bukan admin
        $satker_not_admin=Akses::leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->where('akses.user_id', Auth::user()->id)
                ->pluck('kategori_bagians.name', 'kategori_bagians.id');
        $satker_not_admin->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        $faktors=FaktorKecelakaan::pluck('name', 'id');
        $faktors->prepend('=== Pilih Faktor Kecelakaan ===', '');

        $klasifikasi=KlasfikasiKecelakaan::pluck('name', 'id');
        $klasifikasi->prepend('=== Pilih Klasifikasi Kecelakaan ===', '');

        return View::make('admin.traffic-accident.create', compact('satker_not_admin', 'satker', 'faktors', 'klasifikasi'));
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
            'no_lp' => 'unique:traffic_accidents,no_lp',
        ],
        [
            'no_lp.unique' => 'No Lp sudah digunakan'
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
        $traffic_accident= new TrafficAccident;
        $traffic_accident->user_id             = Auth::user()->id;
        $traffic_accident->no_lp               = Input::get('no_lp');
        $traffic_accident->date_no_lp          = Input::get('date_no_lp');
        
        // Jika User Admin
        if($user_group->group_id == 1){
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $traffic_accident->kategori_id         = $check_satuan->kategori_id;
            $traffic_accident->kategori_bagian_id  = Input::get('satker');
            $traffic_accident->divisi              = Input::get('divisi');
            if ($check_satuan->kategori_id == "1") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
    
            if ($check_satuan->kategori_id == "2") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
    
            if ($check_satuan->kategori_id == "3") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        // Jika User Selain Admin
        }else{
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $traffic_accident->kategori_id         = $check_satuan->kategori_id;
            $traffic_accident->kategori_bagian_id  = Input::get('satker');
            $traffic_accident->divisi              = Auth::user()->divisi;
            // Untuk Polda
            if ($check_satuan->kategori_id == "1") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            // Untuk Polres
            if ($check_satuan->kategori_id == "2") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            // Untuk Polsek
            if ($check_satuan->kategori_id == "3") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        }

        $traffic_accident->uraian              = Input::get('uraian');
        $traffic_accident->kerugian_material   = Input::get('kerugian_material');
        $traffic_accident->nama_petugas        = Input::get('nama_petugas');
        $traffic_accident->pangkat             = Input::get('pangkat');
        $traffic_accident->no_tlp              = Input::get('no_tlp');
        // jika saksi kosong
        if($request->saksi != null){
            $saksi = implode(", ",$request->saksi);
            $traffic_accident->saksi               = $saksi;
        }
        $traffic_accident->barang_bukti        = $request->barang_bukti;
        $traffic_accident->tkp                 = Input::get('tkp');
        $traffic_accident->lat                 = Input::get('lat');
        $traffic_accident->long                = Input::get('long');
        $traffic_accident->date                = Input::get('date');
        $traffic_accident->time                = Input::get('time');
        $traffic_accident->handle_bukti        = 0;
        $traffic_accident->soft_delete_id      = 0;
        $traffic_accident->status_id           = 1; // in progress status
        $traffic_accident->faktor_id           = Input::get('faktor_id');
        $traffic_accident->klasifikasi_id      = Input::get('klasifikasi_id');
        $traffic_accident->save();
        
        $array_korban                          = $request->korban;
        if($array_korban != null){ // jika ada korban
            $array_chunk                           = array_chunk($array_korban,6); // sesuai jumlah data
            foreach($array_chunk as $korban){
                $accident_victimt = new AccidentVictimt();
                $accident_victimt->traffic_accident_id  = $traffic_accident->id;
                $accident_victimt->nama                 = $korban[0]['nama'];
                $accident_victimt->umur                 = $korban[1]['umur'];
                $accident_victimt->pendidikan           = $korban[2]['pendidikan'];
                $accident_victimt->pekerjaan            = $korban[3]['pekerjaan'];
                $accident_victimt->asal                 = $korban[4]['asal'];
                $accident_victimt->kondisi_id           = $korban[5]['kondisi'];
                $accident_victimt->save();
            }
        }
        // masukan ke log
        $createLogLakaLantas = [
          'user_id'                 => Auth::user()->id,
          'traffic_accident_id'     => $traffic_accident->id,
          'status'                  => 'create',
        ];
        saveLogLakaLantas($createLogLakaLantas);

        $year_lapor               = date('Y', strtotime($traffic_accident->date));
        $year_now                 = date('Y');
        // redirect
        if($year_lapor == $year_now){
            return Redirect::action('admin\LakaLantasController@thisYear', compact('traffic_accident'))->with('flash-store','Data berhasil ditambahkan.');
        }else{
            return Redirect::action('admin\LakaLantasController@lastYear', compact('traffic_accident'))->with('flash-store','Data berhasil ditambahkan.');
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
        $traffic_accident = TrafficAccident::with([
            'korbans', 
        ])->where('id', $id)
          ->firstOrFail();

        return view('admin.traffic-accident.show', compact('traffic_accident'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateData($id)
    {
        $traffic_accident = TrafficAccident::with([
            'korbans', 
            'korbans.kondisi', 
        ])->where('id', $id)
          ->firstOrFail();

        // untuk admin
        $satker=KategoriBagian::where('kategori_id', '!=', '3')->pluck('name', 'id');
        $satker->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        // untuk bukan admin
        $satker_not_admin=Akses::leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->where('akses.user_id', Auth::user()->id)
                ->pluck('kategori_bagians.name', 'kategori_bagians.id');
        $satker_not_admin->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        $faktors=FaktorKecelakaan::pluck('name', 'id');
        $faktors->prepend('=== Pilih Faktor Kecelakaan ===', '');

        $klasifikasi=KlasfikasiKecelakaan::pluck('name', 'id');
        $klasifikasi->prepend('=== Pilih Klasifikasi Kecelakaan ===', '');

        $status=Status::where('id', '!=', 4)->where('id', '!=', 5)->pluck('name', 'id');
        $status->prepend('=== Update Status ===', '');

        return view('admin.traffic-accident.update', compact('traffic_accident', 'satker', 'satker_not_admin', 'faktors', 'klasifikasi', 'status'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        // masuk table lapors
        $traffic_accident  = TrafficAccident::where('id', $id)->firstOrFail();

        // validasi manual jika no LP diganti
        if($traffic_accident->no_lp != $request->no_lp){
            // Validasi
            $validatedData = $request->validate([
                'no_lp' => 'unique:traffic_accidents,no_lp',
            ],
            [
                'no_lp.unique' => 'No "'.$request->no_lp.'" Lp sudah digunakan'
            ]
            );

            $traffic_accident->no_lp               = Input::get('no_lp');
        }

        $traffic_accident->no_lp               = Input::get('no_lp');
        $traffic_accident->date_no_lp          = Input::get('date_no_lp');
        
        // Jika User Admin
        if($user_group->group_id == 1){
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $traffic_accident->kategori_id         = $check_satuan->kategori_id;
            $traffic_accident->kategori_bagian_id  = Input::get('satker');
            $traffic_accident->divisi              = Input::get('divisi');
            if ($check_satuan->kategori_id == "1") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
    
            if ($check_satuan->kategori_id == "2") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
    
            if ($check_satuan->kategori_id == "3") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        // Jika User Selain Admin
        }else{
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $traffic_accident->kategori_id         = $check_satuan->kategori_id;
            $traffic_accident->kategori_bagian_id  = Input::get('satker');
            $traffic_accident->divisi              = Auth::user()->divisi;
            // Untuk Polda
            if ($check_satuan->kategori_id == "1") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            // Untuk Polres
            if ($check_satuan->kategori_id == "2") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            // Untuk Polsek
            if ($check_satuan->kategori_id == "3") {
                $traffic_accident->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        }

        $traffic_accident->uraian              = Input::get('uraian');
        $traffic_accident->kerugian_material   = Input::get('kerugian_material');
        $traffic_accident->nama_petugas        = Input::get('nama_petugas');
        $traffic_accident->pangkat             = Input::get('pangkat');
        $traffic_accident->no_tlp              = Input::get('no_tlp');
        if($request->saksi != null){
            $saksi = implode(", ",$request->saksi);
            $traffic_accident->saksi               = $saksi;
        }
        $traffic_accident->barang_bukti        = $request->barang_bukti;
        $traffic_accident->tkp                 = Input::get('tkp');
        $traffic_accident->lat                 = Input::get('lat');
        $traffic_accident->long                = Input::get('long');
        $traffic_accident->date                = Input::get('date');
        $traffic_accident->time                = Input::get('time');
        // $traffic_accident->anggaran            = Input::get('anggaran');
        $traffic_accident->handle_bukti        = 0;
        $traffic_accident->soft_delete_id      = 0;
        $traffic_accident->faktor_id           = Input::get('faktor_id');
        $traffic_accident->klasifikasi_id      = Input::get('klasifikasi_id');
        if($request->status != null){
            $traffic_accident->status_id       = Input::get('status');
        }
        $traffic_accident->save();

        if($request->korban != null){
            $f_delete_accident_victimt = AccidentVictimt::where('traffic_accident_id', $traffic_accident->id)->get();
            foreach($f_delete_accident_victimt as $f_delete_accident){
                $f_delete_accident->delete();
            }
            $array_korban                          = $request->korban;
            $array_chunk                           = array_chunk($array_korban,6);

            foreach($array_chunk as $korban){
                $accident_victimt = new AccidentVictimt();
                $accident_victimt->traffic_accident_id  = $traffic_accident->id;
                $accident_victimt->nama                 = $korban[0]['nama'];
                $accident_victimt->umur                 = $korban[1]['umur'];
                $accident_victimt->pendidikan           = $korban[2]['pendidikan'];
                $accident_victimt->pekerjaan            = $korban[3]['pekerjaan'];
                $accident_victimt->asal                 = $korban[4]['asal'];
                $accident_victimt->kondisi_id           = $korban[5]['kondisi'];
                $accident_victimt->save();
            }
        }
        // masukan ke log
        $createLogLakaLantas = [
          'user_id'                 => Auth::user()->id,
          'traffic_accident_id'     => $traffic_accident->id,
          'status'                  => 'edit',
        ];
        saveLogLakaLantas($createLogLakaLantas);

        $year_lapor               = date('Y', strtotime($traffic_accident->date));
        $year_now                 = date('Y');
        // redirect
        if($year_lapor == $year_now){
            return Redirect::action('admin\LakaLantasController@thisYear', compact('traffic_accident'))->with('flash-store','Data berhasil diubah.');
        }else{
            return Redirect::action('admin\LakaLantasController@lastYear', compact('traffic_accident'))->with('flash-store','Data berhasil diubah.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $traffic_accident  = TrafficAccident::where('id', $id)->firstOrFail();
        $accident_victimts = AccidentVictimt::where('traffic_accident_id', $traffic_accident->id)->get();
        foreach($accident_victimts as $accident_victimt){
            $accident_victimt->delete();
        }
        $traffic_accident->delete();
        return  Redirect::back()->with('flash-destroy','Data berhasil dihapus.');
    }

    public function indexUpdateStatus($id){
        $status=Status::where('id', '!=', 1)->where('id', '!=', 4)->where('id', '!=', 5)->pluck('name', 'id');
        $status->prepend('=== Update Status ===', '');

        $traffic_accident = TrafficAccident::with([
            'korbans', 
        ])->where('id', $id)
          ->firstOrFail();

        return view('admin.traffic-accident.update-status', compact('status', 'traffic_accident'));
    }

    public function updateStatus(Request $request, $id)
    {
        $traffic_accident        = TrafficAccident::findOrFail($id);
        $year_traffic_accident   = date('Y', strtotime($traffic_accident->date));
        $year_now                = date('Y');

        // Validasi
        $validatedData = $request->validate([
            'dokumen' => 'file|mimes:pdf|max:5000',
        ],
        [
            'dokumen.mimes' => 'Upload Dokumen Berupa PDF dengan Ukuran Maksimal 5Mb',
            'dokumen.max' => 'Dokumen Terlau Besar, Ukuran Maksimal 5Mb'
        ]
        );
        
        $traffic_accident->user_id                       = Auth::user()->id;
        $traffic_accident->status_id                     = Input::get('status');
        $traffic_accident->tanggal_surat_sprint_penyidik = Input::get('tanggal_surat_sprint_penyidik');
        $traffic_accident->tgl_document                  = Input::get('tgl_doc');
        $traffic_accident->keterangan                    = Input::get('keterangan');

        $file = $request->file('dokumen');
        if($file){
            $ext = $file->getClientOriginalExtension();
            $newName = rand(100000,1001238912).".".$ext;
    
            /** save to folder public */
            // $file->move('uploads/file',$newName);
            
            /** save to folder storage */
            Storage::disk('public')->putFileAs('file', $file, $newName);
            $traffic_accident->document = $newName;
        }

        $traffic_accident->save();
        // masukan ke log
        $createLogLakaLantas = [
          'user_id'                 => Auth::user()->id,
          'traffic_accident_id'     => $traffic_accident->id,
          'status'                  => 'update-status',
        ];
        saveLogLakaLantas($createLogLakaLantas);
        // kondisi data update
        $year_lapor               = date('Y', strtotime($traffic_accident->date));
        $year_now                 = date('Y');
        // redirect
        if($year_lapor == $year_now){
            return Redirect::action('admin\LakaLantasController@thisYear', compact('traffic_accident'))->with('flash-store','Data berhasil ditambahkan.');
        }else{
            return Redirect::action('admin\LakaLantasController@lastYear', compact('traffic_accident'))->with('flash-store','Data berhasil ditambahkan.');
        }
    }

    public function uploadPdf(Request $request, $id)
    {
        // Validasi
        $validatedData = $request->validate([
            'dokumen' => 'required|file|mimes:pdf|max:5000',
        ],
        [
            'dokumen.mimes' => 'Upload Dokumen Berupa PDF dengan Ukuran Maksimal 5Mb',
            'dokumen.max' => 'Dokumen Terlau Besar, Ukuran Maksimal 5Mb'
        ]
        );

        $traffic_accident                                = TrafficAccident::findOrFail($id);
        $traffic_accident->tanggal_surat_sprint_penyidik = Input::get('tanggal_surat_sprint_penyidik');
        $traffic_accident->tgl_document                  = Input::get('tgl_doc');
        $traffic_accident->keterangan                    = Input::get('keterangan');

        $file = $request->file('dokumen');
        if($file){
            $ext = $file->getClientOriginalExtension();
            $newName = rand(100000,1001238912).".".$ext;
    
            /** save to folder storage */
            Storage::disk('public')->putFileAs('file', $file, $newName);
            $traffic_accident->document = $newName;
        }
        $traffic_accident->save();
        // masukan ke log
        $createLogLakaLantas = [
          'user_id'                 => Auth::user()->id,
          'traffic_accident_id'     => $traffic_accident->id,
          'status'                  => 'update-status',
        ];
        saveLogLakaLantas($createLogLakaLantas);
        // kondisi data update
        return Redirect::back()->with('flash-update','Upload Berhasil.');
    }
}
