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
              <h4>Edit akun user</h4>
          </div>
      </div>
      <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Akun User</a></li>
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
            <h3 class="card-title">Edit akun user</h3>
          </div>
          <!-- /.card-header -->
          {!! Form::model($user,['method'=>'put', 'autocomplete' => 'off', 'files'=> 'true', 'action'=>['admin\UserController@update',$user->id]]) !!}
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control" name="name" placeholder="Masukan nama" value="{{{$user->name}}}" autocomplete="off" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" placeholder="Masukan email" value="{{{$user->email}}}" autocomplete="off" required>
              </div>
              <div class="form-group input_fields_container_part">
                  <div class="btn btn-sm btn-primary add_more_button">Ubah Password</div><br>
              </div>
              <div class="form-group">
                <label for="">Lembaga</label>
                  {{ Form::select('user_group_id', $groups, $user->group_id,['onchange'=> 'showDiv(this)','class' => 'form-control required', 'required' => 'required']) }}
                </div>

              <div class="form-group" id="hidden_div" style="display:block;">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control" name="divisi_polda">
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Ditreskrimsus" @if($user->divisi == 'Ditreskrimsus') selected @endif>Ditreskrimsus</option>
                     <option value="Ditreskrimum" @if($user->divisi == 'Ditreskrimum') selected @endif>Ditreskrimum</option>
                     <option value="Ditresnarkoba" @if($user->divisi == 'Ditresnarkoba') selected @endif>Ditresnarkoba</option>
                  </select>
                </div>
              </div>

              <div class="form-group" id="hidden_div2" style="display:none;">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control" name="divisi_polres">
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Satreskrim" @if($user->divisi == 'Satreskrim') selected @endif>Satreskrim</option>
                     <option value="Satnarkoba" @if($user->divisi == 'Satnarkoba') selected @endif>Satnarkoba</option>
                     <option value="Unit Reskrim" @if($user->divisi == 'Unit Reskrim') selected @endif>Unit Reskrim</option>
                  </select>
                </div>
              </div>

              <div class="form-group" id="hidden_div3" style="display:none;">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control" name="divisi_polsek">
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Unit Reskrim" @if($user->divisi == 'Unit Reskrim') selected @endif>Unit Reskrim</option>
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

  $(document).ready(function() {
    var max_fields_limit      = 2; //set limit for maximum input fields
    var x = 1; //initialize counter for text box
    $('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
        e.preventDefault();
        if(x < max_fields_limit){ //check conditions
            x++; //counter increment
            $('.input_fields_container_part').append('<div><label>Password</label><input type="password" class="form-control" name="password" autocomplete="new-password"><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
        }
    });  
    $('.input_fields_container_part').on("click",".remove_field", function(e){ //user click on remove text links
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    if({{ $user->group_id }} == 2){
      document.getElementById('hidden_div').style.display = "block";
      document.getElementById('hidden_div2').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     } else if({{ $user->group_id }} == 3){
      document.getElementById('hidden_div2').style.display = "block";
      document.getElementById('hidden_div').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     } else{
      document.getElementById('hidden_div').style.display = "none";
      document.getElementById('hidden_div2').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     }

  });
</script>
@endsection