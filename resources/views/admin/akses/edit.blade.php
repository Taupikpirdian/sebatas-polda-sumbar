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
                <h4>Edit Akses User</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Akses User</a></li>
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
            <h3 class="card-title">Edit Akses User</h3>
          </div>
          <!-- /.card-header -->
          {!! Form::model($akses,['files'=>true,'method'=>'put','action'=>['admin\AksesController@update',$akses->id]]) !!}
            <div class="card-body">
              <div class="form-group">
                <label>Akun</label>
                {{ Form::select('user_id', $users, null,['class' => 'form-control', 'required', 'value'=>'']) }}
              </div>
              <div class="form-group">
                <label>Satuan Kerja (Satker)</label>
                {{ Form::select('sakter_id', $satker, null,['class' => 'form-control', 'required', 'value'=>'']) }}
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