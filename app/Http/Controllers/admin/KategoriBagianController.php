<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use App\Kategori;
use App\KategoriBagian;
use App\Perkara;
use App\TurunanSatuan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class KategoriBagianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $count =  KategoriBagian::count();
        $kategori_bagians = KategoriBagian::orderBy('kategori_bagians.created_at', 'desc')
                                        ->leftJoin('kategoris','kategoris.id','kategori_bagians.kategori_id')
                                        ->leftJoin('turunan_satuans','turunan_satuans.satker_turunan_id','kategori_bagians.id')
                                        ->select('turunan_satuans.satker_id as turunan',
                                        'kategori_bagians.*',
                                        'kategori_bagians.name as name',
                                        'kategoris.name as kategori')
                                        ->paginate($count);

        // create array from kategori bagia
        $categories = KategoriBagian::select(['id', 'name'])->whereIn('kategori_id', [1, 2])->get();
        $kategori   = Array();
        foreach ($categories as $key => $categori) {
            $kategori[$categori->id] = $categori->name;
        }

        // dd($kategori);

        return view('admin.kategori_bagian.list', compact('kategori', 'kategori_bagians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('created_at', 'desc')->get();

        $satker=KategoriBagian::orderBy('name', 'asc')
                                ->where('kategori_id', 2)
                                ->get();

        return View::make('admin.kategori_bagian.create', compact('kategoris','satker'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kategori_bagian= new KategoriBagian;
        $kategori_bagian->kategori_id       = Input::get('kategori_id');
        $kategori_bagian->name              = Input::get('name');
        $kategori_bagian->save();

        if($request->kategori_id == 3){ 
            $satker = new TurunanSatuan;
            $satker->satker_id =  Input::get('satker');
            $satker->satker_turunan_id = $kategori_bagian->id;
            $satker->save();
        }

        return Redirect::action('admin\KategoriBagianController@index')->with('flash-store','Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kategori_bagian = KategoriBagian::where('id', $id)->firstOrFail();
        return view('admin.kategori_bagian.show', compact('kategori_bagian'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // kategori satker
        $kategori = Kategori::pluck('name', 'id');
        // satker
        $kategori_bagian = KategoriBagian::where('id', $id)->firstOrFail();
        if($kategori_bagian->kategori_id == 2){
          // satker induk jika polsek
          $satker_turunan = TurunanSatuan::where('satker_turunan_id', $id)->firstOrFail();
        }else{
          $satker_turunan = null;
        }
        // all satker polres
        $satker=KategoriBagian::orderBy('name', 'asc')->where('kategori_id', 2)->get();
        return view('admin.kategori_bagian.edit', compact('kategori', 'kategori_bagian', 'satker_turunan', 'satker'));
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
        $kategori_bagian = KategoriBagian::findOrFail($id);
        $kategori_bagian->kategori_id       = Input::get('kategori_id');
        $kategori_bagian->name              = Input::get('name');
        $kategori_bagian->save();

        if($request->kategori_id == 3){ 
            $satker = TurunanSatuan::where('satker_turunan_id', $id)->first();
            $satker->satker_id =  Input::get('satker');
            $satker->satker_turunan_id = $kategori_bagian->id;
            $satker->save();
        }

        return Redirect::action('admin\KategoriBagianController@index', compact('kategori_bagian'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = Perkara::where('kategori_bagian_id', $id)->first();
        
        if($check != null){
          return Redirect::back()->withErrors(['Tidak bisa menghapus data ini, karena sudah memiliki data perkara', 'The Message']);
        }else{
          $kategori_bagian = KategoriBagian::where('id', $id)->firstOrFail();
          $kategori_bagian->delete();
          return Redirect::action('admin\KategoriBagianController@index')->with('flash-destroy','Data berhasil dihapus.');
        }

    }
}
