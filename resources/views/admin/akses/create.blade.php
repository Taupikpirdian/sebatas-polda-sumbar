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

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Create Akses User</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Akses User</a></li>
            </ol>
        </div>
    </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
        {{ Form::open(array('url' => '/akses/create', 'files' => true, 'method' => 'post')) }}
          <table class="table table-striped table-hover">

          <tr>
            <div class='form-group clearfix'>
              {{ Form::label("user_id", "User", ['class' => 'col-md-2 control-label']) }}
                <div class='col-md-4'>
                {{ Form::select('user_id', $users, null,['class' => 'form-control', 'required', 'value'=>'']) }}
                </div>
            </div> 
          </tr>

          <tr>
            <div class='form-group clearfix'>
              {{ Form::label("sakter_id", "Satker", ['class' => 'col-md-2 control-label']) }}
                <div class='col-md-4'>
                {{ Form::select('sakter_id', $satker, null,['class' => 'form-control', 'required', 'value'=>'']) }}
                </div>
            </div>  
          </tr>

          </table>

          <div class='form-group'>
            <div class='col-md-4 col-md-offset-2'>
              <button class='btn btn-primary' type='submit' name='save' id='save'><span class='glyphicon glyphicon-save'></span> Save</button>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      <!-- / Add New Post Form -->
    </div>
  </div>
</div>
@endsection