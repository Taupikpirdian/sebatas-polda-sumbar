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
                <h4>Menambahkan akun user</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Create Akun User</a></li>
            </ol>
        </div>
    </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
        {{ Form::open(array('url' => '/user/create', 'files' => true, 'method' => 'post')) }}
          <table class="table table-striped table-hover">

          <tr>
            <div class='form-group clearfix'>
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Masukan nama" required>
            </div> 
          </tr>

          <tr>
            <div class='form-group clearfix'>
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukan email" required>
            </div> 
          </tr>

          <tr>
            <div class='form-group clearfix'>
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password" required>
            </div> 
          </tr>

          <tr>
            <div class='form-group clearfix'>
                <label for="password">Confirm Password</label>
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Repeat Password" required>
            </div> 
          </tr>

          <tr>
            <div class='form-group clearfix'>
                <label for="">Status User</label>
                <select class="form-control" id="test" name="user_group_id" onchange="showDiv(this)">
                     <option value="">=== Pilih Salah Satu ===</option>
                      @foreach($groups as $group)
                          <option value="{{ $group->id }}">{{ $group->name }}</option>
                      @endforeach
                </select>
            </div> 
          </tr>

          <div class="form-group" id="hidden_div" style="display:none;">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control" name="divisi_polda">
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Ditreskrimsus">Ditreskrimsus</option>
                     <option value="Ditreskrimum">Ditreskrimum</option>
                     <option value="Ditresnarkoba">Ditresnarkoba</option>
                  </select>
                </div>
              </div>

              <div class="form-group" id="hidden_div2" style="display:none;">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control" name="divisi_polres">
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Satreskrim">Satreskrim</option>
                     <option value="Satnarkoba">Satnarkoba</option>
                  </select>
                </div>
              </div>

              <div class="form-group" id="hidden_div3" style="display:none;">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control" name="divisi_polsek">
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Unit Reskrim">Unit Reskrim</option>
                  </select>
                </div>
              </div>


          </table>

          <div class='form-group'>
            <div class='col-md-4 '>
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
@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
  function showDiv(select){
     if(select.value==2){
      document.getElementById('hidden_div').style.display = "block";
      document.getElementById('hidden_div2').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     } else if(select.value==3){
      document.getElementById('hidden_div2').style.display = "block";
      document.getElementById('hidden_div').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     } else if(select.value==4){
      document.getElementById('hidden_div3').style.display = "block";
      document.getElementById('hidden_div').style.display = "none";
      document.getElementById('hidden_div2').style.display = "none";
     }else{
      document.getElementById('hidden_div').style.display = "none";
      document.getElementById('hidden_div2').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     }
  } 
</script>
@endsection