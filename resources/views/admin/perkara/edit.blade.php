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
  <div class="container-fluid">
    <div class="row page-titles mx-0">
      <div class="col-sm-6 p-md-0">
          <div class="welcome-text">
              <h4>Update Status Perkara</h4>
          </div>
      </div>
      <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Status Perkara</a></li>
          </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->

  <!-- Main content -->
  <section class="container-fluid">
    <div class="row page-titles mx-0">
      <div class="col-sm-12 p-md-0">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            @error('dokumen')
            <div id="alert-danger" class="alert alert-danger alert-dismissible fade show" style="position: absolute; right: 0px">
              <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
              <strong>Error! {{ $message }}</strong> {{Session::get('flash-danger')}}.
              <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
              </button>
            </div>
            @enderror
            <h3 class="card-title">Update Status Perkara Divisi @if(Auth::check()){{ Auth::user()->divisi }}@endif</h3>
          </div>
          <!-- update-status -->
          {!! Form::model($perkara,['files'=>true,'method'=>'put','action'=>['admin\PerkaraController@update',$perkara->id]]) !!}
            <div class="card-body">

              <div class="form-group">
                <label for="name">Nomer LP</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->no_lp }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Satker</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->satuan }}" disabled="">
              </div>

              <div class="form-group">
                <label>Uraian Singkat Kejadian</label>
                <textarea class="form-control" rows="3" name="desc" placeholder="Masukan Uraian Singkat Kejadian" disabled>{{ $perkara->uraian }}</textarea>
              </div>

              <div class="form-group">
                <label for="name">Nama Petugas</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->nama_petugas }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Pangkat</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->pangkat }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Jenis Pidana</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->pidana }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Jenis Narkoba</label>
                <input type="text" class="form-control" name="material" value="{{ $perkara->narkobas }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Berat (gram)</label>
                <input type="text" class="form-control" name="qty" value="{{ $perkara->qty }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Nama Korban</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->korban }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Saksi</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->saksi }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Pelaku</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->pelaku }}" disabled="">
              </div>

              <div class="form-group">
                <label for="name">Tanggal Surat Sprint Penyidik</label>
                <input type="date" class="form-control" id="tanggal_surat_sprint_penyidik" name="tanggal_surat_sprint_penyidik" placeholder="Masukan Tanggal" value="{{old('tanggal_surat_sprint_penyidik')}}"  required>
              </div>
              
              <div class="form-group">
                <label>Status</label>
                {{ Form::select('status', $status, null,['class' => 'form-control', 'required' => 'required']) }}
              </div>

              <div class="form-group">
                <label for="name">Dokumen</label>
                <input type="file" class="form-control" id="dokumen" name="dokumen" placeholder="Masukan Dokumen Update Status Perkara" required>
                <p style="color: red; font-size:13">Note: Hanya File PDF dengan ukuran maksimal 5 Mb</p>
                
              </div>

              <div class="form-group">
                <label for="name">Tanggal Dokumen</label>
                <input type="date" class="form-control" id="tgl_doc" name="tgl_doc" placeholder="Masukan Tanggal Update Status Perkara" value="{{old('tgl_doc')}}"  required>
              </div>

              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="3" name="keterangan" placeholder="Masukan Keterangan Update Status Perkara" required>{{Request::old('keterangan')}}</textarea>
              </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a title="Kembali" href="{{ url()->previous() }}" style="margin-right:20px;" class="btn btn-sm btn-info float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>   Kembali</a>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          {!! Form::close() !!}

        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection
@section('js')
<script>
setTimeout(function() {
    $('#alert-danger').fadeOut('fast');
}, 7000);

setTimeout(function() {
    $('#alert-warning').fadeOut('fast');
}, 7000);
</script>
@endsection