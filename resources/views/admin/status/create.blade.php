@extends('admin.admin')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Input Data User</h3>
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

          <div class="row">
            <div class='col-md-6'>
              <div class="form-group">
                <label for=""> Nama</label>
                <input class="form-control" placeholder="Nama" name="name" required>
                @if ($errors->has('name'))
                  <div class="help-block form-text with-errors form-control-feedback">
                    <ul class="list-unstyled text-danger">
                      <li>{{ $errors->first('name') }}</li>
                    </ul>
                  </div>
                @endif
              </div>
            </div>
            <div class='col-md-6'>
              <div class="form-group">
                <label for=""> Password</label>
                <input class="form-control" placeholder="password" name="password" type="password" required>
                @if ($errors->has('password'))
                  <div class="help-block form-text with-errors form-control-feedback">
                    <ul class="list-unstyled text-danger">
                      <li>{{ $errors->first('password') }}</li>
                    </ul>
                  </div>
                @endif
              </div>
            </div>
            <div class='col-md-6'>
              <div class="form-group">
                <label for="">Confirm Password</label>
                <div class="help-block form-text with-errors form-control-feedback">
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
              </div>
            </div>
            <div class='col-md-6'>
               <div class="form-group">
                  <label for=""> Email</label>
                  <input class="form-control" placeholder="Nama" name="email" required>
                  @if ($errors->has('email'))
                    <div class="help-block form-text with-errors form-control-feedback">
                      <ul class="list-unstyled text-danger">
                        <li>{{ $errors->first('email') }}</li>
                      </ul>
                    </div>
                  @endif
                </div>
            </div>
            <div class='col-md-6'>
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
            </div>
          </div>

          </table>

          <div class='form-group'>
            <div class='col-md-4 md-2 px-0'>
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