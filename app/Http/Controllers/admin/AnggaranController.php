<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MasterAnggaran;
use App\KategoriBagian;
use App\Kategori;
use App\Anggaran;

class AnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_bar = $request->search;

        $anggarans = MasterAnggaran::select([
          'master_anggarans.id',
          'master_anggarans.total_anggaran',
          'kategori_bagians.name',
        ])->join('kategori_bagians', 'master_anggarans.satker_id', 'kategori_bagians.id')
          ->when(!empty($search_bar), function ($query) use ($search_bar) {
            $query->where('kategori_bagians.name', 'like', "%{$search_bar}%");
        })->paginate(25);

        return view('admin.anggaran.index', compact('search_bar', 'anggarans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // cek data jika sudah ada
        $anggarans = MasterAnggaran::get();
        if(!$anggarans->isEmpty()){
          foreach($anggarans as $key=>$anggaran){
            $satker_id[] = $anggaran->satker_id;
          }
        }else{
          $satker_id[] = '';
        }

        $satkers = KategoriBagian::where('kategori_id', '!=', 3)->whereNotIN('id', $satker_id)->get();
        return view('admin.anggaran.create', compact('satkers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create data
        $anggaran = new MasterAnggaran;
        $anggaran->satker_id        = $request->satker;
        $anggaran->total_anggaran   = $request->total_anggaran;
        $anggaran->save();

        return redirect()->route('anggaran.index')->with(['success' => 'Berhasil menambahkan data']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anggaran = MasterAnggaran::where('id', $id)->first();
        return view('admin.anggaran.edit', compact('anggaran'));
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
        $anggaran = MasterAnggaran::where('id', $id)->first();
        $anggaran->total_anggaran = $request->total_anggaran;
        $anggaran->save();

        return redirect()->route('anggaran.index')->with(['success' => 'Berhasil update data']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anggaran = MasterAnggaran::findOrFail($id);
        $anggaran->delete();

        return redirect()->route('anggaran.index')->with(['success' => 'Berhasil menghapus data']);
    }

    public function rekapAnggaran(Request $request)
    {
        $anggarans = KategoriBagian::select([
          'kategori_bagians.id',
          'kategori_bagians.name',
          ])->with([
              'masterAnggaran',
              'anggaran'
          ])->where('kategori_id', '!=', 3)
            ->get();

        // get data master anggaran
        $sum_master_anggaran = MasterAnggaran::sum('total_anggaran');
        $sum_anggaran        = Anggaran::sum('anggaran');
        $sisa                = $sum_master_anggaran - $sum_anggaran;

        return view('admin.anggaran.rekap', compact('anggarans', 'sum_master_anggaran', 'sum_anggaran', 'sisa'));
    }

}
