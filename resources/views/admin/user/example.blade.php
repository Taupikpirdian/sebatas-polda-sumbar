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
          <h1>User</h1>
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
            <h3 class="card-title">Menambahkan akun user</h3>
          </div>
          <!-- /.card-header -->
          {{ Form::open(array('url' => '/user/create', 'files' => true, 'method' => 'post')) }}
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Masukan nama" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukan email" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password" required>
              </div>
              <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Repeat Password" required>
              </div>
              <div class="form-group">
                <label for="">Status User</label>
                  <div class="help-block form-text with-errors form-control-feedback">
                    <select name="user_group_id" class="form-control">
                      @foreach($groups as $group)
                          <option value="{{ $group->id }}">{{ $group->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('user_group_id'))
                      <span class="help-block">
                        <strong>{{ $errors->first('user_status_id') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>

                <select id="test" name="form_select" onchange="showDiv(this)">
                   <option value="">Pilih Salah Satu</option>
                   <option value="1">Polda</option>
                   <option value="2">Polres</option>
                   <option value="3">Polsek</option>
                </select>

                <div id="hidden_div" style="display:none;">Hello 1</div>
                <div id="hidden_div2" style="display:none;">Hello 2</div>
                <div id="hidden_div3" style="display:none;">Hello 3</div>
            <!-- END KELOMPOK -->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
  function showDiv(select){
     if(select.value==1){
      document.getElementById('hidden_div').style.display = "block";
      document.getElementById('hidden_div2').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     } else if(select.value==2){
      document.getElementById('hidden_div2').style.display = "block";
      document.getElementById('hidden_div').style.display = "none";
      document.getElementById('hidden_div3').style.display = "none";
     } else if(select.value==3){
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