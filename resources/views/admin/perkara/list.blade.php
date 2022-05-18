@extends('layouts.app')

@section('content')
@if ($message = Session::get('flash-store'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif

@if ($message = Session::get('flash-update'))
  <div class="alert alert-info alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif

@if ($message = Session::get('flash-destroy'))
  <div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Semua Perkara</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Semua Perkara</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>No</th>
                <th>No LP</th>
                <th>Satker</th>
                <th>Nama Petugas</th>
                <th>Nama Korban</th>
                <th>Barang Bukti</th>
                <th>Waktu Kejadian</th>
                <th>Jenis Pidana</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
             @foreach($perkaras as $i=>$perkara)
              <tr>
                <td>{{ ($perkaras->currentpage()-1) * $perkaras->perpage() + $i + 1 }}</td>
                <td> {{ $perkara->no_lp }} </td>
                <td> {{ $perkara->kategori_bagian_id }} </td>
                <td> {{ $perkara->nama_petugas }} </td>
                <td> {{ $perkara->korban->nama }} </td>
                <td> {!! str_limit($perkara->korban->barang_bukti, 25) !!} <a title="{{ $perkara->korban->barang_bukti }}" href="#">[...]</a></td>
          <!--       <td><a target="_blank" href="http://www.google.com/maps/place/{{ $perkara->lat }},{{ $perkara->long }}"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td> -->
                <td> {{Carbon\Carbon::parse($perkara->date)->formatLocalized('%d %B %Y')}}, {{ $perkara->time }}</td>
                <td> {{ $perkara->jenis_pidana }} </td>
                @if($perkara->status_id == 1)
                <td><span class="badge badge-info">{{ $perkara->status->name }}</span></td>
                @else
                <td><span class="badge badge-success">{{ $perkara->status->name }}</span></td>
                @endif
                <td>
                  @if($perkara->status_id != 1)
                  @else
                  <a class="btn btn-warning btn-sm" href='{{URL::action("admin\PerkaraController@edit",array($perkara->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
                  @endif
                  <a class="btn btn-info btn-sm" href='{{URL::action("admin\PerkaraController@show",array($perkara->id))}}'><i class="fa fa-eye fa-xs"></i></a>
          <!--         <form class="btn btn-danger btn-sm" id="perkara{{$perkara->id}}" action='{{URL::action("admin\PerkaraController@destroy",array($perkara->id))}}' method="POST">
                      <input type="hidden" name="_method" value="delete">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <a href="#" onclick="document.getElementById('perkara{{$perkara->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
                  </form> -->
                </td>   
              </tr>
              @endforeach
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection