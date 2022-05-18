<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\TurunanSatuan;
use App\Akses;
use App\KategoriBagian;

class LaporanController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $akses_user = Akses::select(['sakter_id'])->where('user_id', $user_id)->first();

        if($akses_user)
            $satkers = TurunanSatuan::with(['satkerTurunan'])->where('satker_id', $akses_user->sakter_id)->get();

        return view('laporan.list-polsek', compact('satkers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPerkara($id)
    {
        // use livewire
        $satker = KategoriBagian::where('id', $id)->first();
        return view('laporan.list-perkara', compact('id', 'satker'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
