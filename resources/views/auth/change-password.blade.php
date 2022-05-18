@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Ubah Password</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Ubah Password</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="row">
      <div class="col-12">
          <div class="card card-primary">
              <div class="card-header">Ubah Password</div>

              <div class="card-body">
                  @if (session('error'))
                      <div class="alert alert-danger">
                          {{ session('error') }}
                      </div>
                  @endif
                      @if (session('success'))
                          <div class="alert alert-success">
                              {{ session('success') }}
                          </div>
                      @endif
                  <form class="form-horizontal" method="POST" action="{{URL::to('/changePassword')}}">
                      {{ csrf_field() }}

                      <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                          <label for="new-password" class="col-md-4 control-label">Password Sekarang</label>

                          <div class="col-md-6">
                              <input id="current-password" type="password" class="form-control" name="current-password" required>

                              @if ($errors->has('current-password'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('current-password') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                          <label for="new-password" class="col-md-4 control-label">Password Baru</label>

                          <div class="col-md-6">
                              <input id="new-password" type="password" class="form-control" name="new-password" required>

                              @if ($errors->has('new-password'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('new-password') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="new-password-confirm" class="col-md-4 control-label">Konfirmasi Password Baru</label>

                          <div class="col-md-6">
                              <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-4">
                              <button type="submit" class="btn btn-primary">
                                  Ubah Password
                              </button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection