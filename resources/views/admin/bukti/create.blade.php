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
          <h1>Kategori</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Menambahkan Bukti Lain</h3>
          </div>
          <!-- /.card-header -->
          {{ Form::open(array('url' => '/bukti-lain/create', 'files' => true, 'method' => 'post')) }}
            <div class="card-body">
              <div class="form-group">
                <label for="name">No LP</label>
                  <!-- <select class="form-control select2" style="width: 100%;">
                    <option selected="selected">Alabama</option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                  </select> -->
                {{ Form::select('perkara_id', $no_lp, 'null',['class' => 'form-control select2', 'required', 'value'=>'']) }}
              </div>

              <div class="form-group">
                <label>No Rangka</label>
                <input type="text" class="form-control" name="no_rangka" placeholder="Masukan no rangka" required>
              </div>

              <div class="form-group">
                <label>No Mesin</label>
                <input type="text" class="form-control" name="no_mesin" placeholder="Masukan no mesin" required>
              </div>

              <div class="form-group">
                <label for="name">Jenis Kendaraan</label>
                <div class="input-group">
                  <select class="form-control" name="kode_kendaraan" required>
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="R2" @if(old('kode_kendaraan') == 'R2') selected @endif>R2</option>
                     <option value="R4" @if(old('kode_kendaraan') == 'R4') selected @endif>R4</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
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
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script>
@endsection