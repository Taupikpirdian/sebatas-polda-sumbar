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

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Create Satuan Kerja (Satker)</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Satuan Kerja (Satker)</a></li>
            </ol>
        </div>
    </div>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Menambahkan Satuan Kerja (Satker)</h3>
          </div>
          <!-- /.card-header -->
          {{ Form::open(array('url' => '/kategori-bagian/create', 'files' => true, 'method' => 'post')) }}
            <div class="card-body">
              <div class="form-group">
                <label for="">Satuan</label>
                  <div class="help-block form-text with-errors form-control-feedback">
                    <select name="kategori_id" class="form-control">
                    @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                        @endforeach
                      </select>
                      @if ($errors->has('kategori_id'))
                        <span class="help-block">
                          <strong>{{ $errors->first('kategori_id') }}</strong>
                        </span>
                      @endif
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="name"> Nama Satuan Kerja (Satker)</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Satuan Kerja (Satker)" required>
              </div>

              <div class="form-group box" id="Polsek">
                <label for="">Satuan Kerja Induk</label>
                  <div class="help-block form-text with-errors form-control-feedback">
                    <select name="satker" class="form-control selectpicker" data-live-search="true">
                      @foreach($satker as $satkers)
                          <option value="{{ $satkers->id }}">{{ $satkers->name}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('kategori_id'))
                      <span class="help-block">
                        <strong>{{ $errors->first('kategori_id') }}</strong>
                      </span>
                    @endif
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
  $(document).ready(function(){
      $("select").change(function(){
          $( "select option:selected").each(function(){
              if($(this).attr("value")=="1"){
                  $(".box").hide();
                  $("#Polda").show();
              }
              if($(this).attr("value")=="2"){
                  $(".box").hide();
                  $("#Polres").show();
              }
              if($(this).attr("value")=="3"){
                  $(".box").hide();
                  $("#Polsek").show();
              }
          });
      }).change();
  });
</script>
@endsection