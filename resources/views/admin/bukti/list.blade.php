@extends('layouts.app')
@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Bukti Lain</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Bukti Lain</li>
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
          <div class="card-header">
            <a title="Tambah data bukti lain" style="float: left; margin-right: 10px;" href="{{URL::to('/bukti-lain/create')}}" class="btn btn-primary">Tambah Data Bukti Lain</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <!-- Filter -->
              <thead>
              {!! Form::open(['method'=>'GET','url'=>'/bukti-lain-search-user','role'=>'search'])  !!}
                <tr style="border: 0">
                  <th style="width: 10px"></th>
                  <th><input type="text" class="form-control float-left" name="no_lp" value="{{ $no_lp }}"></th>
                  <th><input type="text" class="form-control float-left" name="satker" value="{{ $satker }}"></th>
                  <th><input type="text" class="form-control float-left" name="jenis_pidana" value="{{ $jenis_pidana }}"></th>
                  <th><input type="text" class="form-control float-left" name="no_rangka" value="{{ $no_rangka }}"></th>
                  <th><input type="text" class="form-control float-left" name="no_mesin" value="{{ $no_mesin }}"></th>
                  <th><input type="text" class="form-control float-left" name="jenis_kendaraan" value="{{ $jenis_kendaraan }}"></th>
                  <th>
                    <select name="status_no_rangka" class="form-control" disabled>
                      <option value="">Pilih Status</option>
                      <option value="0" @if('0' == $status_no_rangka) selected @endif>Tidak Tersedia</option>
                      <option value="1" @if('1' == $status_no_rangka) selected @endif>Tersedia</option>
                    </select>
                  </th>
                  <th style="width: 70px"><button title="Pencarian" style="float: left; margin-right: 5px" class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button></th>
                </tr>
              {!! Form::close() !!}
              </thead>
              <thead>
              <tr>
                <th>No</th>
                <th>No Lp</th>
                <th>Satker</th>
                <th>Jenis Pidana</th>
                <th>No Rangka</th>
                <th>No Mesin</th>
                <th>Jenis Kendaraan</th>
                <th>Status No Rangka</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
             @foreach($buktis as $i=>$data)
              <tr>
                <td>{{ ($buktis->currentpage()-1) * $buktis->perpage() + $i + 1 }}</td>
                <td> {{ $data->no_lp }} </td>
                <td> {{ $data->satuan }} </td>
                <td> {{ $data->pidana }} </td>
                <td> {{ $data->no_rangka }} </td>
                <td> {{ $data->no_mesin }} </td>
                <td> {{ $data->kode_kendaraan }} </td>
                @if($data->status_no_rangka == 0)
                <td><span class="badge badge-info">Tidak Tersedia</span></td>
                @endif
                @if($data->status_no_rangka == 1)
                <td><span class="badge badge-success">Tersedia</span></td>
                @endif
                @if($data->status_no_rangka == 3)
                <td><span class="badge badge-info">Tidak Tersedia</span></td>
                @endif
                <td>
                  <div class="btn-group dropleft">
                    <button class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-bars fa-xs" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a target="_blank" class="dropdown-item" href='{{URL::action("admin\BuktiLainController@show",array($data->perkara_id))}}'>Detail Perkata</a>
                      <a class="dropdown-item" href="javascript:;" id="deleteForm" data-toggle="modal" onclick="deleteData({{$data->id}})" data-target="#DeleteModal" method="post">Hapus Bukti Lain</a>
                    </div>
                  </div>
                </td>   
              </tr>
              <!-- Modal -->
              <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <form action='{{URL::action("admin\BuktiLainController@destroy",array($data->id))}}' id="deleteForm" method="post">
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
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
              {!! $buktis->appends(Request::all())->links(); !!}
            </ul>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection

@section('js')
<script type="text/javascript">
  function deleteData(id)
  {
      var id = id;
      var url = '{{ route("bukti.destroy", ":id") }}';
      url = url.replace(':id', id);
      $("#deleteForm").attr('action', url);
  }

  function formSubmit()
  {
      $("#deleteForm").submit();
  }
</script>
@endsection