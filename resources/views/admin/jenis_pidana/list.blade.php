@extends('layouts.app')
@section('content')
<div class="container-fluid">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4>Jenis Pidana</h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a>
				</li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)">Jenis Pidana</a>
				</li>
			</ol>
		</div>
	</div>
	<!-- row -->
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="card">
				<div class="card-header"> <a title="Tambah data" style="float: left; margin-right: 10px" href="{{URL::to('/jenis-pidana/create')}}" class="btn btn-sm btn-info float-left"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="example3" class="table table-border" style="min-width: 845px">
							<thead>
								<tr>
                  <th style="width: 50px">No</th>
                  <th>Jenis Pidana</th>
                  <th style="width: 50px">Action</th>
                </tr>
							</thead>
							<tbody>
              @foreach($jenispidanas as $i=>$jenispidana)
              <tr>
                <td>{{ ($jenispidanas->currentpage()-1) * $jenispidanas->perpage() + $i + 1 }}</td>
                <td> {{ $jenispidana->name }} </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                      <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href='{{URL::action("admin\JenisPidanaController@edit",array($jenispidana->id))}}'>Edit</a>
                      <a class="dropdown-item" href="javascript:;" id="deleteForm" data-toggle="modal" onclick="deleteData({{$jenispidana->id}})" data-target="#myModal" method="post">Delete</a>
                    </div>
                  </div>
                </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                  <form action='{{URL::action("admin\JenisPidanaController@destroy",array($jenispidana->id))}}' id="deleteForm" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <h5 class="mt-0" style="align:center;">WARNING !!!</h5>
                        <p>Data Ini Akan Terhapus Secara Permanen! Apakah Anda Yakin ?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="formSubmit()">Delete</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Modal -->
                @endforeach
              </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">
  function deleteData(id)
  {
      var id = id;
      var url = '{{ route("jenispidana.destroy", ":id") }}';
      url = url.replace(':id', id);
      $("#deleteForm").attr('action', url);
  }

  function formSubmit()
  {
      $("#deleteForm").submit();
  }
</script>
@endsection