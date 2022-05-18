@extends('layouts.app')
@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Roles</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Roles</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <a href="{{URL::to('/roles/create')}}" class="btn btn-primary">Tambah Data</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
             @foreach($roles as $i=>$role)
		     	<tr>
		     	 <td>{{ ($roles->currentpage()-1) * $roles->perpage() + $i + 1 }}</td>
		         <td> {{ $role->name }} </td>
		         <td>
					<a class="btn btn-warning btn-sm" href='{{URL::action("admin\RoleController@edit",array($role->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
					<form class="btn btn-danger btn-sm" id="delete_role{{$role->id}}" onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')" action='{{URL::action("admin\RoleController@destroy",array($role->id))}}' method="POST">
						<input type="hidden" name="_method" value="delete">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<a href="#" onclick="document.getElementById('delete_role{{$role->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
					</form>
				</td>	         
		     	</tr>
			   @endforeach
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection