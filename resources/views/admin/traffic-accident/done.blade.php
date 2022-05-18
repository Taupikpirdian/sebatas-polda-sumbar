@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Kecelakaan Lalu-Lintas Selesai</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Kecelakaan Lalu-Lintas Selesai</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <a title="Tambah data perkara" href="{{URL::to('/laka-lantas/create')}}" class="btn btn-sm btn-info float-left"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data</a>
          {!! Form::open(['method'=>'GET','url'=>'/laka-lantas/done','role'=>'search'])  !!}
            <div class="input-group" style="float: left; width: 300px; margin-right: 10px">
              <input type="text" class="form-control float-left" name="search" value="{{ $search_bar }}" placeholder="Search no LP, korban dan tersangka ...">
              <input type="text" class="form-control float-left" name="satker" value="{{ $satker_param }}" style="display: none">
              <input type="text" class="form-control float-left" name="tahun" value="{{ $tahun_param }}" style="display: none">
            </div>
            <button title="Pencarian" style="float: left; margin-right: 5px" class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          {!! Form::close() !!}
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-responsive-md">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>No Lp</th>
                  <th>Tanggal No Lp</th>
                  <th>Satker</th>
                  <th>Nama Petugas</th>
                  <!-- <th>Nama Pelapor</th> -->
                  <th>Waktu Kejadian</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @foreach($traffic_accidents as $i=>$traffic_accident)
                <tr>
                    <td>{{ ($traffic_accidents->currentpage()-1) * $traffic_accidents->perpage() + $i + 1 }}</td>
                    <td> {{ $traffic_accident->no_lp }} </td>
                    <td> {{Carbon\Carbon::parse($traffic_accident->date_no_lp)->formatLocalized('%d %B %Y')}}</td>
                    <td> {{ $traffic_accident->satuan }} </td>
                    <td> {{ $traffic_accident->nama_petugas }} </td>
                    <td> {{Carbon\Carbon::parse($traffic_accident->date)->formatLocalized('%d %B %Y')}}, {{ $traffic_accident->time }}</td>
                    @if($traffic_accident->status_id == 1)
                    <td><span class="badge badge-info">{{ $traffic_accident->status }}</span></td>
                    @else
                    <td><span class="badge badge-success">{{ $traffic_accident->status }}</span></td>
                    @endif
                    <td>
                      <div class="dropdown">
                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                          <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                        </button>
                        <div class="dropdown-menu">
                        @if($traffic_accident->status_id != 1)
                        @else
                        <a class="dropdown-item" href='{{URL::action("admin\LakaLantasController@indexUpdateStatus",array($traffic_accident->id))}}'>Update Status</a>
                        @endif
                        <a target="_blank" class="dropdown-item" href='{{URL::action("admin\LakaLantasController@show",array($traffic_accident->id))}}'>Detail</a>
                        <a class="dropdown-item" href='{{URL::action("admin\LakaLantasController@updateData",array($traffic_accident->id))}}'>Edit</a>
                        @if(Auth::user()->hasAnyRole('Delete Perkara'))
                        <a class="dropdown-item" href="javascript:;" id="deleteForm" data-toggle="modal" onclick="deleteData({{$traffic_accident->id}})" data-target="#DeleteModal" method="post">Hapus</a>
                        @endif
                        </div>
                      </div>
                    </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                  <form action='{{URL::action("admin\LakaLantasController@destroy",array($traffic_accident->id))}}' id="deleteForm" method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Hapus Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <p class="text-center">Data Ini Akan Terhapus Secara Permanen! Apakah Anda Yakin ?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Ya, Hapus</button>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <ul class="float-left">
          Showing {{ (($traffic_accidents->currentpage()-1) * $traffic_accidents->perPage())+1 }} 
            to {{ $traffic_accidents->currentPage()*$traffic_accidents->perPage() }} 
            of {{ $traffic_accidents->total() }} entries
          </ul>
          <ul class="pagination pagination-sm m-0 float-right">
            {!! $traffic_accidents->render() !!}
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  function deleteData(id)
  {
      var id = id;
      var url = '{{ route("laka-lantas.destroy", ":id") }}';
      url = url.replace(':id', id);
      $("#deleteForm").attr('action', url);
  }

  function formSubmit()
  {
      $("#deleteForm").submit();
  }
</script>
@endsection