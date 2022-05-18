<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use App\JenisPidana;
use App\Perkara;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class JenisPidanaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $count =  JenisPidana::count();
        $jenispidanas = JenisPidana::orderBy('created_at', 'desc')->paginate($count);
        return view('admin.jenis_pidana.list',array('jenispidanas'=>$jenispidanas));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('admin.jenis_pidana.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jenispidana= new JenisPidana;
        $jenispidana->name              = Input::get('name');
        $jenispidana->save();
        return Redirect::action('admin\JenisPidanaController@index')->with('flash-store','Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jenispidana = JenisPidana::where('id', $id)->firstOrFail();
        return view('admin\JenisPidanaController@index', compact('jenispidana'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jenispidana = JenisPidana::where('id', $id)->firstOrFail();
        return view('admin.jenis_pidana.edit', compact('jenispidana'));
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
        $jenispidana = JenisPidana::findOrFail($id);
        $jenispidana->name              = Input::get('name');
        $jenispidana->save();
        return Redirect::action('admin\JenisPidanaController@index', compact('jenispidana'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = Perkara::where('jenis_pidana', $id)->first();

        if($check != null){
            return Redirect::back()->withErrors(['Tidak bisa menghapus data ini, karena sudah memiliki data User Groups', 'The Message']);
          }else{
            $jenispidana = JenisPidana::where('id', $id)->firstOrFail();
            $jenispidana->delete();
            return Redirect::action('admin\JenisPidanaController@index')->with('flash-destroy','Data berhasil dihapus.');
          }

        // $jenispidana = JenisPidana::where('id', $id)->firstOrFail();
        // $jenispidana->delete();
        // return Redirect::action('admin\JenisPidanaController@index')->with('flash-destroy','Data berhasil dihapus.');
    }
}
