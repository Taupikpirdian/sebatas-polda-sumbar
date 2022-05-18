@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Polsek di bawah {{ $satuan->name }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Polsek di bawah {{ $satuan->name }}</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">Data Kasus</a></li>
            <li class="nav-item"><a class="nav-link" href="#grouping" data-toggle="tab">Data Angka</a></li>
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <!-- tab-content -->
          <div class="tab-content">
            <!-- tab-pane -->
            <div class="active tab-pane" id="data">
              <div class="col-md-12 table-responsive">
                <table class="table table-bordered" style="font-size:14px">
                  <thead>
                    {!! Form::open(['method'=>'GET','url'=>'/filter-satker-bawah-polres/'.$id.'/sumbar','role'=>'search'])  !!}
                    <tr style="border: 0">
                      <th style="width: 10px"></th>
                      <th><input type="text" class="form-control float-left" name="no_lp" value="{{ $no_lp }}"></th>
                      <th>
                        <select name="satuan" class="form-control">
                          <option value="">Pilih Satker</option>
                          @foreach($satuanBawah as $i=>$loopSatuanBawah)
                          <option value="{{ $loopSatuanBawah->satker_turunan_id }}" @if($loopSatuanBawah->satker_turunan_id == $satker) selected @endif>{{ $loopSatuanBawah->satuan }}</option>
                          @endforeach
                        </select>
                      </th>
                      <th><input type="text" class="form-control float-left" name="petugas" value="{{ $petugas }}"></th>
                      <th><input type="text" class="form-control float-left" name="korban" value="{{ $korban }}"></th>
                      <th><input type="text" class="form-control float-left" name="bukti" value="{{ $bukti }}"></th>
                      <th>
                        <div class="input-group" style="float: left; margin-right: 10px">
                          <input type="text" class="form-control float-left" id="reservation2">
                        </div>
                        <input type="hidden" name="start_date" id="start_date" />
                        <input type="hidden" name="end_date" id="end_date" />
                      </th>
                      <th>
                        <select name="pidana" class="form-control">
                          <option value="">Pilih Jenis Pidana</option>
                          @foreach($jenispidanas as $i=>$pidana)
                          <option value="{{ $pidana->id }}" @if($pidana->id == $pidana_id) selected @endif>{{ $pidana->name }}</option>
                          @endforeach
                        </select>
                      </th>
                      <th>
                        <select name="status" class="form-control">
                          <<option value="">Pilih Status Pidana</option>
                          @foreach($statuses as $i=>$status)
                          <option value="{{ $status->id }}" @if($status->id == $status_id) selected @endif>{{ $status->name }}</option>
                          @endforeach
                        </select>
                      </th>
                      <th style="width: 70px"><button title="Pencarian" style="float: left; margin-right: 5px" class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button></th>
                    </tr>
                  {!! Form::close() !!}
                  </thead> 
                  <thead>                 
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>No LP</th>
                    <th>Satuan Kerja</th>
                    <th>Nama Petugas</th>
                    <th>Nama Korban</th>
                    <th>Barang Bukti</th>
                    <th>Waktu Kejadian</th>
                    <th>Jenis Pidana</th>
                    <th>Status</th>
                    <th style="width: 70px">Action</th>
                  </tr>
                  <tbody>
                    @foreach($data as $i=>$perkara)
                    <tr>
                      <td>{{ ($data->currentpage()-1) * $data->perpage() + $i + 1 }}</td>
                      <td> {{ $perkara->no_lp }} </td>
                      <td> {{ $perkara->satuan }} </td>
                      <td> {{ $perkara->nama_petugas }} </td>
                      <td> {{ $perkara->nama }} </td>
                      <td> {!! str_limit($perkara->barang_bukti, 25) !!} <a title="{{ $perkara->barang_bukti }}" href="#">[...]</a></td>
                      <td> {{Carbon\Carbon::parse($perkara->date)->formatLocalized('%d %B %Y')}}, {{ $perkara->time }}</td>
                      <td> {{ $perkara->pidana }} </td>
                      @if($perkara->status_id == 1)
                      <td><span class="badge badge-info">{{ $perkara->status }}</span></td>
                      @else
                      <td><span class="badge badge-success">{{ $perkara->status }}</span></td>
                      @endif
                      <td>
                        <div class="dropdown">
                          <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars fa-xs" aria-hidden="true"></i>
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a target="_blank" class="dropdown-item" href='{{URL::action("admin\PerkaraController@show",array($perkara->id))}}'>Detail Perkara</a>
                          </div>
                        </div>
                      </td>   
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  {!! $data->appends(Request::all())->links(); !!}
                </ul>
              </div>
            </div>

            <div class="tab-pane" id="grouping">
              <div class="col-md-12 table-responsive">
                <table class="table table-bordered" style="font-size:14px">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Satuan Kerja</th>
                      <th>Kasus Selesai</th>
                      <th>Kasus Belum Selesai</th>
                      <th>Jumlah Kasus</th>
                      <th>Persentase Selesai</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($grouping as $i=>$data)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ $data->name }}</td>
                    @if($data->array == 3)
                      <td><span class="badge bg-danger">0</span></td>
                      <td><span class="badge bg-danger">{{ $data->total }}</span></td>
                    @else
                      <td><span class="badge bg-danger">{{ $data->kasus_selesai }}</span></td>
                      <td><span class="badge bg-danger">{{ ($data->total - $data->kasus_selesai) }}</span></td>
                    @endif
                      <td><span class="badge bg-danger">{{ $data->total }}</span></td>
                      <td><span class="badge bg-danger">{{ $data->percent_success }}%</span></td>
                    </tr>
                  @endforeach
                  <tr>
                    <td>#</td>
                    <td style="text-align: center"><b>TOTAL</b></td>
                    <td><span class="badge bg-info">{{ $count_kasus_selesai }}</span></td>
                    <td><span class="badge bg-info">{{ $count_kasus_belum_selesai }}</span></td>
                    <td><span class="badge bg-info">{{ $count_kasus }}</span></td>
                    <td style="background-color: #343A40"><span class="badge bg-info"></span></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
@endsection
@section('js')
<!-- page script -->

@endsection