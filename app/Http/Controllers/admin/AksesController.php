<?php

namespace App\Http\Controllers\Admin;

use Auth;
use View;
use Alert;
use Validator;
use App\Akses; 
use App\User; 
use App\KategoriBagian; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class AksesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count_akses =  Akses::count();

        $akseses = Akses::leftjoin('kategori_bagians', 'kategori_bagians.id', '=', 'akses.sakter_id')
                    ->leftjoin('users', 'users.id', '=', 'akses.user_id')
                    ->orderBy('akses.created_at', 'desc')
                    ->select(
                            'kategori_bagians.name as satuan',
                            'users.name as akun',
                            'users.email',
                            'akses.*'
                            )
                    ->paginate($count_akses);
        return view('admin.akses.list', compact('akseses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users=User::pluck('email', 'id');
        $users->prepend('Pilih User', '');
        $satker=KategoriBagian::pluck('name', 'id');
        $satker->prepend('Pilih Satuan Kerja', '');
        return View::make('admin.akses.create', compact('users', 'satker'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $akses = new Akses;
        $akses->user_id           = Input::get('user_id');
        $akses->sakter_id         = Input::get('sakter_id');
        $akses->save();
        
        return Redirect::action('admin\AksesController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $users=User::pluck('email', 'id');
        $users->prepend('Pilih User', '');
        $satker=KategoriBagian::pluck('name', 'id');
        $satker->prepend('Pilih Satuan Kerja', '');
        $akses = Akses::findOrFail($id);

        return View::make('admin.akses.edit', compact('akses', 'users', 'satker'));
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
        $akses = Akses::findOrFail($id);
        $akses->user_id           = Input::get('user_id');
        $akses->sakter_id         = Input::get('sakter_id');
        $akses->save();
        
        return Redirect::action('admin\AksesController@index')->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $akses = Akses::findOrFail($id);
        $akses->delete();

        return Redirect::action('admin\AksesController@index')->with('flash-destroy','The user has been deleted.');
    }
}
