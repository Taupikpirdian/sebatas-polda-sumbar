@extends('admin.admin')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Edit Data User</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
		{!! Form::model($user,['method'=>'put', 'files'=> 'true', 'action'=>['admin\UserController@update',$user->id]]) !!}
          <table class="table table-striped table-hover">

          <tr>
            <div class="input-group mb-2">
              <div class="button button-white" style="width:15%">Nama</div>
              <input class="form-control" placeholder="Masukan Nama" name="name" value="{{{$user->name}}}">
              <div class="input-group-append">
              </div>
            </div>
          </tr>

		  <tr>
            <div class="input-group mb-2">
              <div class="button button-white" style="width:15%">Email</div>
              <input class="form-control" placeholder="Masukan Email" name="email" value="{{{$user->email}}}">
              <div class="input-group-append">
              </div>
            </div>
          </tr>

		  <tr>
            <div class="input-group mb-2">
              <div class="button button-white" style="width:15%">Akses</div>
              {{ Form::select('user_group_id', $groups, $user->group_id,['class' => 'form-control required', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          </table>

          <div class='form-group'>
            <div class='col-md-4 px-0'>
              <button class='button button-primary' type='submit' name='save' id='save'><span class='glyphicon glyphicon-save'></span> Save</button>
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
<script>
  $(function() {
    $(".datepicker4").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-80:+0',
    dateFormat: "yy-mm-dd"
    });
    $(".datepicker2").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-80:+0',
    dateFormat: "yy-mm-dd"
    });
    $(".datepicker3").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-80:+0',
    dateFormat: "yy-mm-dd"
    });
  });
</script>
@endsection