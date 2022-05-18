<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use File;
use Alert;
use App\Perkara;
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
use DB;

class PerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perkaras = Perkara::orderBy('created_at', 'desc')->paginate(25);
        $start_page= (($perkaras->currentPage()-1) * 25) + 1;
        return view('admin.perkara.list',compact('start_page'), array('perkaras'=>$perkaras));
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

        return View::make('admin.perkara.create', compact('satker_not_admin', 'satker', 'jenis_pidanas', 'type_narkoba'));
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
            'no_lp' => 'unique:perkaras,no_lp',
        ],
        [
            'no_lp.unique' => 'No LP sudah digunakan'
        ]
        );

        // cek kategori satker
        // $sakter_kategori = KategoriBagian::select('kategori_id')->where('id', $request->satker)->first();
        // if($sakter_kategori->kategori_id == 3){ // if kategori = 3
        //     // ceh sakter induk
        //     $turunan_satuan = TurunanSatuan::select('satker_id')->where('satker_turunan_id', $request->satker)->first();
        //     // cek data anggaran master satker turunan
        //     $checkMasterAnggaran = MasterAnggaran::where('satker_id', $turunan_satuan->satker_id)->first();
        //     if($checkMasterAnggaran == null){ // jika data belum ada
        //       // kirim dari bot telegram ke grup
        //       $createArray = [
        //       'user_id'    => Auth::user()->id,
        //       'satker_id'  => $turunan_satuan->satker_id,
        //       'status'     => 6, // error data master anggaran tidak ada
        //       ];
        //       messageToTelegram($createArray);
        //       return  Redirect::back()->withInput(Input::all())->with('flash-danger','Data anggaran untuk satker ini tidak tersedia, harap hubungi admin untuk memasukan data master anggaran.');
        //     }
        // }else{ // selain 3
        //   $checkMasterAnggaran = MasterAnggaran::where('satker_id', $request->satker)->first();
        //   if($checkMasterAnggaran == null){ // jika data belum ada
        //     // kirim dari bot telegram ke grup
        //     $createArray = [
        //     'user_id'    => Auth::user()->id,
        //     'satker_id'  => $request->satker,
        //     'status'     => 6, // error data master anggaran tidak ada
        //     ];
        //     messageToTelegram($createArray);
        //     return  Redirect::back()->withInput(Input::all())->with('flash-danger','Data anggaran untuk satker ini tidak tersedia, harap hubungi admin untuk memasukan data master anggaran.');
        //   }
        // }
        // cek jika anggaran sudah habis

        // get group user
        $user_group = UserGroup::leftjoin('groups', 'groups.id', '=', 'user_groups.group_id')
            ->select(
                'groups.name',
                'user_groups.group_id'
            )
            ->where('user_id', Auth::user()->id)
            ->first();
        // masuk table perkaras
        $perkara= new Perkara;
        $perkara->user_id             = Auth::user()->id;
        $perkara->no_lp               = Input::get('no_lp');
        $perkara->date_no_lp          = Input::get('date_no_lp');
        
        // Jika User Admin
        if($user_group->group_id == 1){
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $perkara->kategori_id         = $check_satuan->kategori_id;
            $perkara->kategori_bagian_id  = Input::get('satker');
            $perkara->divisi              = Input::get('divisi');
            if ($check_satuan->kategori_id == "1") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
    
            if ($check_satuan->kategori_id == "2") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
    
            if ($check_satuan->kategori_id == "3") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        // Jika User Polda
        }elseif ($user_group->group_id == 2) {
            // Cek divisi user polda
            $check_user = User::where('id', Auth::user()->id)->first();

            // Jika divisi Ditreskrimsus
            if($check_user->id == 2){
              $perkara->kategori_id         = 1;
              $perkara->kategori_bagian_id  = 179;
              $perkara->divisi              = Auth::user()->divisi;
              $perkara->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

            // Jika divisi Ditreskrimum
            if($check_user->id == 3){
              $perkara->kategori_id         = 1;
              $perkara->kategori_bagian_id  = 171;
              $perkara->divisi              = Auth::user()->divisi;
              $perkara->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            
            // Jika divisi Ditresnarkoba
            if($check_user->id == 4){
              $perkara->kategori_id         = 1;
              $perkara->kategori_bagian_id  = 1;
              $perkara->divisi              = Auth::user()->divisi;
              $perkara->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

        // Selain Polda dan Admin
        }else{
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $perkara->kategori_id         = $check_satuan->kategori_id;
            $perkara->kategori_bagian_id  = Input::get('satker');
            $perkara->divisi              = Auth::user()->divisi;
            // Untuk Polda
            if ($check_satuan->kategori_id == "1") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            // Untuk Polres
            if ($check_satuan->kategori_id == "2") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            // Untuk Polsek
            if ($check_satuan->kategori_id == "3") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        }

        $perkara->uraian              = Input::get('desc');
        $perkara->modus_operasi       = Input::get('modus');
        $perkara->nama_petugas        = Input::get('nama_petugas');
        $perkara->pangkat             = Input::get('pangkat');
        $perkara->no_tlp              = Input::get('no_tlp');
        $perkara->tkp                 = Input::get('tkp');
        $perkara->lat                 = Input::get('lat');
        $perkara->long                = Input::get('long');
        $perkara->date                = Input::get('date');
        $perkara->time                = Input::get('time');
        $perkara->status_id           = 1;
        $perkara->handle_bukti        = 0;
        $perkara->soft_delete_id      = 0;
        $perkara->jenis_pidana        = Input::get('jenis_pidana');
        $perkara->material            = Input::get('material');
        $perkara->qty                 = Input::get('qty');
        $perkara->anggaran            = Input::get('anggaran');

        // masuk table korban
        $saksi = implode(", ",$request->saksi);
        $arrays = $request->pelaku;
        // $array_chunk = array_chunk($arrays,5);
        // foreach($array_chunk as $array){
        //     $data = $array;
        // }
        $a = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($arrays), ENT_NOQUOTES));
        $b = (str_replace('{"','',$a));
        $c = (str_replace('"}','',$b));
        $d = (str_replace(',',', ',$c));
        $string_pelaku = (str_replace('"','',$d));
        // $pelaku = implode(", ",$request->pelaku);
        // $object = json_decode(json_encode($request->pelaku));
        // $pelaku = implode(", ",$request->pelaku);

        $korban= new Korban;
        $korban->no_lp               = Input::get('no_lp');
        $korban->nama                = Input::get('nama_korban');
        $korban->umur_korban         = Input::get('umur_korban');
        $korban->pendidikan_korban   = Input::get('pendidikan_korban');
        $korban->pekerjaan_korban    = Input::get('pekerjaan_korban');
        $korban->asal_korban         = Input::get('asal_korban');
        $korban->saksi               = $saksi;
        $korban->pelaku              = $string_pelaku;
        $korban->barang_bukti        = $request->barang_bukti;

        // if ($request->barang_bukti) {
        //     $image = $request->file('barang_bukti');
        //     $no_lp = str_replace(' ', '', $request->no_lp);
        //     $no_lp = strtolower($no_lp);
        //     $rand_name = rand(10000000, 99999999);
        //     $imageName = $no_lp.'_'.$rand_name.'.'.$image->getClientOriginalExtension();
        //     //thumbs
        //     $destinationPath = public_path('images/bukti/thumbs');
        //     if(!File::exists($destinationPath)){
        //         if(File::makeDirectory($destinationPath,0777,true)){
        //             throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
        //         }
        //     }
        //     $imageThumbs = Image::make($image->getRealPath());
        //     $imageThumbs->resize(800, 700);
        //     // $imageThumbs->resize(1928, 1080);
        //     $imageThumbs->save($destinationPath.'/'.$imageName);
        //     //original
        //     $destinationPath = public_path('images/bukti');
        //     $image = Image::make($image)->encode('jpg', 50);
        //     $image->save($destinationPath.'/'.$imageName);
        //     //save data image to db
        //     $korban->barang_bukti = $imageName;
        // }

        $perkara->save();
        // get id perkara
        $perkara_id = Perkara::select('perkaras.id')->where('no_lp', Input::get('no_lp'))->first();
        $korban->perkara_id  = $perkara_id->id;
        $korban->save();

        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'perkara_id' => $perkara_id->id,
          'status'     => 1,
        ];
        saveLog($createLog);
        // redirect
        return Redirect::action('admin\PerkaraController@thisYear')->with('flash-store','Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $perkara = Perkara::where('id', $id)->firstOrFail();
        return view('admin.perkara.show', compact('perkara'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perkara = Perkara::leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'perkaras.kategori_bagian_id')
                            ->leftJoin('jenis_pidanas', 'jenis_pidanas.id', '=', 'perkaras.jenis_pidana')
                            ->leftJoin('type_narkobas','type_narkobas.id','=','perkaras.material')
                            ->leftJoin('korbans', 'korbans.no_lp', '=', 'perkaras.no_lp')
                            ->select( 'perkaras.*',
                            'kategori_bagians.name as satuan',
                            'jenis_pidanas.name as pidana',
                            'korbans.nama as korban',
                            'korbans.saksi as saksi',
                            'korbans.pelaku as pelaku',
                            'type_narkobas.name as narkobas')
                            ->where('perkaras.id', $id)->firstOrFail();
        $status=Status::where('id', '!=', 1)->pluck('name', 'id');
        $status->prepend('=== Update Status ===', '');
        return view('admin.perkara.edit', compact('perkara', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $perkara        = Perkara::findOrFail($id);
        $year_perkara   = date('Y', strtotime($perkara->date));
        $year_now       = date('Y');

        // Validasi
        $validatedData = $request->validate([
            'dokumen' => 'required|file|mimes:pdf|max:5000',
        ],
        [
            'dokumen.mimes' => 'Upload Dokumen Berupa PDF dengan Ukuran Maksimal 5Mb',
            'dokumen.max' => 'Dokumen Terlau Besar, Ukuran Maksimal 5Mb'
        ]
        );
        
        $perkara->user_id                       = Auth::user()->id;
        $perkara->status_id                     = Input::get('status');
        $perkara->tanggal_surat_sprint_penyidik = Input::get('tanggal_surat_sprint_penyidik');
        $perkara->tgl_document                  = Input::get('tgl_doc');
        $perkara->keterangan                    = Input::get('keterangan');

        $file = $request->file('dokumen');
        $ext = $file->getClientOriginalExtension();
        $newName = rand(100000,1001238912).".".$ext;

        /** save to folder public */
        // $file->move('uploads/file',$newName);
        
        /** save to folder storage */
        Storage::disk('public')->putFileAs('file', $file, $newName);
        $perkara->document = $newName;

        $perkara->save();
        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'perkara_id' => $id,
          'status'     => 2,
        ];
        saveLog($createLog);
        // kondisi data update
        if($year_perkara == $year_now){
            return Redirect::action('admin\PerkaraController@thisYear', compact('perkara'))->with('flash-update','Data berhasil diubah.');
        }else{
            return Redirect::action('admin\PerkaraController@lastYear', compact('perkara'))->with('flash-update','Data berhasil diubah.');
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
        $perkara = Perkara::where('id', $id)->firstOrFail();
        $korban = Korban::where('no_lp', $perkara->no_lp)->firstOrFail();
        $korban->delete();
        $perkara->delete();
        return  Redirect::back()->with('flash-update','Data berhasil dihapus.');
    }

    public function lastYear()
    {
        // use livewire
        return view('admin.perkara.last_year');
    }

    public function thisYear()
    {
        // use livewire
        return view('admin.perkara.this_year');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateData($id)
    {
        $perkara = Perkara::where('id', $id)->firstOrFail();

        // jika tidak ada data korban
        $korban = Korban::where('no_lp', $perkara->no_lp)->first();
        if($korban == null){
            $korbanCreate = new Korban();
            $korbanCreate->no_lp                = $perkara->no_lp;
            $korbanCreate->nama                 = "";
            $korbanCreate->umur_korban          = "";
            $korbanCreate->pendidikan_korban    = "";
            $korbanCreate->pekerjaan_korban     = "";
            $korbanCreate->asal_korban          = "";
            $korbanCreate->saksi                = "";
            $korbanCreate->pelaku               = "";
            $korbanCreate->barang_bukti         = "";
            $korbanCreate->save();
        }

        $pelaku = $perkara->korban->pelaku;
        $jenis_pidanas=JenisPidana::pluck('name', 'id');
        $jenis_pidanas->prepend('=== Pilih Jenis Pidana ===', '');
        $type_narkoba=TypeNarkoba::pluck('name', 'id');
        $type_narkoba->prepend('=== Pilih Jenis Narkoba ===', '');
        // untuk admin
        $satker=KategoriBagian::pluck('name', 'id');
        $satker->prepend('=== Pilih Satuan Kerja (Satker) ===', '');
        // untuk bukan admin
        $satker_not_admin=Akses::leftJoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                ->where('akses.user_id', Auth::user()->id)
                ->pluck('kategori_bagians.name', 'kategori_bagians.id');

        $satker_not_admin->prepend('=== Pilih Satuan Kerja (Satker) ===', '');

        return view('admin.perkara.update', compact('pelaku', 'perkara', 'jenis_pidanas', 'satker', 'satker_not_admin', 'type_narkoba'));
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
            
        $perkara = Perkara::findOrFail($id);
            
        $year_perkara = date('Y', strtotime($perkara->date));
        $year_now = date('Y');
        $perkara->user_id             = Auth::user()->id;
        $perkara->date_no_lp          = Input::get('date_no_lp');
        
        // Jika User admin
        if($user_group->group_id == 1){
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $perkara->kategori_id         = $check_satuan->kategori_id;
            $perkara->kategori_bagian_id  = Input::get('satker');
            $perkara->divisi              = Input::get('divisi');
            if ($check_satuan->kategori_id == "1") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
    
            if ($check_satuan->kategori_id == "2") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
    
            if ($check_satuan->kategori_id == "3") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        // Jika user polda
        }elseif ($user_group->group_id == 2) {
            // Cek divisi user polda
            $check_user = User::where('id', Auth::user()->id)->first();

            // Jika divisi Ditreskrimsus
            if($check_user->id == 2){
              $perkara->kategori_id         = 1;
              $perkara->kategori_bagian_id  = 179;
              $perkara->divisi              = Auth::user()->divisi;
              $perkara->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

            // Jika divisi Ditreskrimum
            if($check_user->id == 3){
              $perkara->kategori_id         = 1;
              $perkara->kategori_bagian_id  = 171;
              $perkara->divisi              = Auth::user()->divisi;
              $perkara->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }
            
            // Jika divisi Ditresnarkoba
            if($check_user->id == 4){
              $perkara->kategori_id         = 1;
              $perkara->kategori_bagian_id  = 1;
              $perkara->divisi              = Auth::user()->divisi;
              $perkara->pin                 = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

        // Jika user selain admin dan polda
        }else{
            $check_satuan = KategoriBagian::where('id', $request->satker)->first();
            $perkara->kategori_id         = $check_satuan->kategori_id;
            $perkara->kategori_bagian_id  = Input::get('satker');
            $perkara->divisi              = Auth::user()->divisi;

            // Untuk Polda
            if ($check_satuan->kategori_id == "1") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
            }

            // Untuk Polres
            if ($check_satuan->kategori_id == "2") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            
            // Untuk Polsek
            if ($check_satuan->kategori_id == "3") {
                $perkara->pin         = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
            }
        }

        $perkara->uraian              = Input::get('desc');
        $perkara->modus_operasi       = Input::get('modus');
        $perkara->nama_petugas        = Input::get('nama_petugas');
        $perkara->pangkat             = Input::get('pangkat');
        $perkara->no_tlp              = Input::get('no_tlp');
        $perkara->tkp                 = Input::get('tkp');
        $perkara->lat                 = Input::get('lat');
        $perkara->long                = Input::get('long');
        $perkara->date                = Input::get('date');
        $perkara->time                = Input::get('time');
        // $perkara->status_id           = 1;
        $perkara->jenis_pidana        = Input::get('jenis_pidana');
        if($perkara->jenis_pidana == '32'){
            $perkara->material            = Input::get('material');
            $perkara->qty                 = Input::get('qty');
        }else{
            $perkara->material            = NULL;
            $perkara->qty                 = NULL;
        }
        $perkara->anggaran            = Input::get('anggaran');

        // masuk table korban
        $korban = Korban::where('no_lp', $perkara->no_lp)->first();
        $korban->nama                = Input::get('nama_korban');
        $korban->umur_korban         = Input::get('umur_korban');
        $korban->pendidikan_korban   = Input::get('pendidikan_korban');
        $korban->pekerjaan_korban    = Input::get('pekerjaan_korban');
        $korban->asal_korban         = Input::get('asal_korban');
        $korban->barang_bukti        = $request->barang_bukti;

        if($request->saksi){
            $saksi = implode(", ",$request->saksi);
            $korban->saksi           = $saksi;
        }
        
        if($request->pelaku){
            $arrays = $request->pelaku;
            $a = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($arrays), ENT_NOQUOTES));
            $b = (str_replace('{"','',$a));
            $c = (str_replace('"}','',$b));
            $d = (str_replace(',',', ',$c));
            $string_pelaku = (str_replace('"','',$d));
            $korban->pelaku          = $string_pelaku;
        }

        $perkara->save();
        $korban->save();
        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'perkara_id' => $id,
          'status'     => 3,
        ];
        saveLog($createLog);
        // kondisi data update
        if($year_perkara == $year_now){
            return Redirect::action('admin\PerkaraController@thisYear', compact('perkara'))->with('flash-update','Data berhasil diubah.');
        }else{
            return Redirect::action('admin\PerkaraController@lastYear', compact('perkara'))->with('flash-update','Data berhasil diubah.');
        }
    }

    public function tiket(Request $request, $id)
    {
        $check_tiket = Tiket::where('id', $id)->where('status', 0)->first();
        
        if($check_tiket == null){
            return  Redirect::back()->with('flash-destroy','Anda sudah pernah membuat tiket untuk no LP ini, tiket masih dalam progress, harap untuk menunggu.');
        }else{
            $tiket= new Tiket;
            $tiket->perkara_id = $id;
            $tiket->action     = $request->actionRequest;
            $tiket->reason     = $request->reason;
            $tiket->status     = 0;
            $tiket->save();
            return  Redirect::back()->with('flash-update','Tiket berhasil dibuat, Silahkan tunggu 1x24 Jam. Anda bisa mengecek status tiket di menu tiket.');
        }
    }

    public function updateAnggaran($id)
    {
        // data perkara
        $perkara = Perkara::where('id', $id)->firstOrFail();
        // history dana perkara
        $anggarans = Anggaran::orderBy('created_at', 'desc')->where('perkara_id', $id)->get();
        // anggaran yang sudah digunakan
        $sum_anggaran = Anggaran::where('perkara_id', $id)->sum('anggaran');

        return view('admin.perkara.update_anggaran', compact('perkara', 'anggarans', 'sum_anggaran'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeUpdateAnggaran(Request $request, $id)
    {
        $perkara = Perkara::select([
          'perkaras.id', 
          'perkaras.no_lp', 
          'perkaras.kategori_id',
          'perkaras.kategori_bagian_id',
          'perkaras.status_id',
          'perkaras.anggaran',
          'kategori_bagians.name',
        ])->join('kategori_bagians', 'perkaras.kategori_bagian_id', '=', 'kategori_bagians.id')
          ->where('perkaras.id', $id)
          ->first();

        // check if anggaran null or 0
        if($perkara->anggaran == null || $perkara->anggaran == 0){
            return  Redirect::back()->with('flash-danger','Data anggaran untuk '.$perkara->name.' masih 0 atau belum ada, harap cek kembali di menu edit perkara.');
        }

        $year_perkara   = date('Y', strtotime($perkara->date));
        $year_now       = date('Y');

        // save histori anggaran
        $anggaran = new Anggaran();

        // cek untuk data bukan polsek
        if($perkara->kategori_id != 3){
          // cek sudah ada data master atau belum
          $master_anggaran = MasterAnggaran::select('total_anggaran')->where('satker_id', $perkara->kategori_bagian_id)->first();
          if($master_anggaran == null){ // jika data tidak ada
            // kirim dari bot telegram ke grup
            $createArray = [
              'user_id'    => Auth::user()->id,
              'satker_id'  => $perkara->kategori_bagian_id,
              'status'     => 6, // error data master anggaran tidak ada
            ];
            messageToTelegram($createArray);
            return  Redirect::back()->with('flash-danger','Data master anggaran untuk '.$perkara->name.' belum tersedia, harap menghubungi Admin.');
          }
          // save kategori_id
          $anggaran->master_sakter_id   = $perkara->kategori_bagian_id;

        }else{
          // cek punya akun induk atau belum,
          /** data yang dibutuhkan */
          /** satker polsek */
          /** id polsek */
          $satker_turunan = TurunanSatuan::where('satker_turunan_id', $perkara->kategori_bagian_id)->first();
          if($satker_turunan == null){
            // kirim dari bot telegram ke grup
            $createArray = [
              'user_id'    => Auth::user()->id,
              'satker_id'  => $perkara->kategori_bagian_id,
              'status'     => 5, // error satker induk
            ];
            messageToTelegram($createArray);
            return  Redirect::back()->with('flash-danger','Data induk satker tidak tersedia, harap menghubungi Admin.');
          }
          // cek dana master ada atau tidak sesuai id satker induk
          $master_anggaran = MasterAnggaran::select('total_anggaran')->where('satker_id', $satker_turunan->satker_id)->first();
          // get nama satker induk
          $satker_induk = KategoriBagian::where('id', $satker_turunan->satker_id)->first();
          if($master_anggaran == null){ // jika data tidak ada
            // kirim dari bot telegram ke grup
            $createArray = [
              'user_id'    => Auth::user()->id,
              'satker_id'  => $perkara->kategori_bagian_id,
              'status'     => 6, // error data master anggaran tidak ada
            ];
            messageToTelegram($createArray);
            return  Redirect::back()->with('flash-danger','Data master anggaran untuk '.$satker_induk->name.' belum tersedia, harap menghubungi Admin.');
          }
          // save kategori_id
          $anggaran->master_sakter_id   = $satker_turunan->satker_id;
        }
        
        $anggaran->user_id      = Auth::user()->id;
        $anggaran->perkara_id   = $id;
        $anggaran->anggaran     = $request->anggaran;
        $anggaran->date         = $request->date;
        $anggaran->keterangan   = $request->keterangan;
        $anggaran->save();

        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'perkara_id' => $id,
          'status'     => 4,
        ];
        saveLog($createLog);
        // kondisi data update
        return  Redirect::back()->with('flash-update','Anggaran berhasil diupdate.');
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

        $perkara                                = Perkara::findOrFail($id);
        $perkara->tanggal_surat_sprint_penyidik = Input::get('tanggal_surat_sprint_penyidik');
        $perkara->tgl_document                  = Input::get('tgl_doc');
        $perkara->keterangan                    = Input::get('keterangan');

        $file = $request->file('dokumen');
        $ext = $file->getClientOriginalExtension();
        $newName = rand(100000,1001238912).".".$ext;

        /** save to folder storage */
        Storage::disk('public')->putFileAs('file', $file, $newName);
        $perkara->document = $newName;
        $perkara->save();
        // masukan ke log
        $createLog = [
          'user_id'    => Auth::user()->id,
          'perkara_id' => $id,
          'status'     => 2,
        ];
        saveLog($createLog);
        // kondisi data update
        return Redirect::back()->with('flash-update','Upload Berhasil.');
    }

    public function limpahPerkara($id)
    {
        /**
         * Rule
         * 
         * Satnarkoba <=> Ditresnarkoba
         * 
         * Satreskrim <=> Ditreskrimsus
         * Satreskrim <=> Ditreskrimum
         * 
         * Unit Reskrim <=> Satreskrim
         * 
         * Unit Reskrim <=> Ditreskrimsus
         * Unit Reskrim <=> Ditreskrimum
         * 
         */

        if(Auth::user()->divisi == "Satnarkoba"){
            $divisis = array(
                "Ditresnarkoba" => "Ditresnarkoba",
            );
        }elseif(Auth::user()->divisi == "Ditresnarkoba"){
            $divisis = array(
                "Satnarkoba"    => "Satnarkoba",
            );
        }elseif(Auth::user()->divisi == "Satreskrim"){
            $divisis = array(
                "Ditreskrimsus" => "Ditreskrimsus",
                "Ditreskrimum"  => "Ditreskrimum",
                "Unit Reskrim"   => "Unit Reskrim",
            );
        }elseif(Auth::user()->divisi == "Ditreskrimsus"){
            $divisis = array(
                "Satreskrim"    => "Satreskrim",
                "Unit Reskrim"   => "Unit Reskrim",
            );
        }elseif(Auth::user()->divisi == "Ditreskrimum"){
            $divisis = array(
                "Satreskrim"    => "Satreskrim",
                "Unit Reskrim"   => "Unit Reskrim",
            );
        }elseif(Auth::user()->divisi == "Unit Reskrim"){
            $divisis = array(
                "Ditreskrimsus" => "Ditreskrimsus",
                "Ditreskrimum"  => "Ditreskrimum",
                "Satreskrim"    => "Satreskrim",
            );
        }else{
            $divisis = array(
                "Ditreskrimsus" => "Ditreskrimsus",
                "Ditreskrimum"  => "Ditreskrimum",
                "Ditresnarkoba" => "Ditresnarkoba",
                "Satreskrim"    => "Satreskrim",
                "Satnarkoba"    => "Satnarkoba",
                "Unit Reskrim"   => "Unit Reskrim",
            );
        }

        $perkara = Perkara::where('id', $id)->firstOrFail();
        return view('admin.perkara.limpah', compact('perkara', 'divisis'));
    }

    public function getStates($id)
    {
        $users = DB::table("users")->where("divisi", $id)->pluck("name","id");
        return json_encode($users);
    }

    public function limpahPerkaraPost(Request $request, $id)
    {
        $perkara = Perkara::where('id', $id)->first();
        /**
         * ambil data user sesuai param name dan divisi
         */
        $user = User::where('name', $request->satker)->first();
        $akses = Akses::select([
            'akses.sakter_id',
            'kategori_bagians.kategori_id',
        ])->join('kategori_bagians', 'akses.sakter_id', '=', 'kategori_bagians.id')
          ->where('akses.user_id', $user->id)
          ->get();

        dd($akses);

        // update data
        $perkara->user_id               = $user->id;
        $perkara->kategori_id           = $akses->kategori_id;
        $perkara->kategori_bagian_id    = $akses->sakter_id;
        $perkara->is_limpah             = 1;
        $perkara->limpah_date           = date("Y-m-d");
        $perkara->save();

        $year_perkara   = date('Y', strtotime($perkara->date));
        $year_now       = date('Y');
        // kondisi data update
        if($year_perkara == $year_now){
            return Redirect::action('admin\PerkaraController@thisYear', compact('perkara'))->with('flash-update','Data berhasil dilimpahkan.');
        }else{
            return Redirect::action('admin\PerkaraController@lastYear', compact('perkara'))->with('flash-update','Data berhasil dilimpahkan.');
        }
    }
}
