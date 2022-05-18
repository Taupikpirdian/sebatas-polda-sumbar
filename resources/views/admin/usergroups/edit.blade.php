@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>User Group</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit User Group</a></li>
            </ol>
        </div>
    </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
        {!! Form::model($user_group,['method'=>'put','action'=>['admin\UserGroupController@update',$user_group->id]]) !!}
        <table class="table table-striped table-hover">

          <tr>
            <div class='form-group clearfix'>
              {{ Form::label("user_id", "User", ['class' => 'col-md-2 control-label']) }}
                <div class='col-md-4'>
                  {{ Form::select('user_id', $users, null,['class' => 'form-control required']) }}
                </div>
            </div> 
          </tr>

          <tr>
            <div class='form-group clearfix'>
              {{ Form::label("group_id", "Group", ['class' => 'col-md-2 control-label']) }}
                <div class='col-md-4'>
                  {{ Form::select('group_id', $groups, null,['class' => 'form-control required']) }}
                </div>
            </div> 
          </tr>

          </table>

          <div class='form-group'>
            <div class='col-md-4'>
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