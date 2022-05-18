@extends('layouts.app')
@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Kategori</h1>
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
        <div class="card">
          <div class="card-header">
            <a href="{{URL::to('/kategori/create')}}" class="btn btn-primary">Tambah Data</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th style="width:5%;">No</th>
                <th>Nama Kategori</th>
                <th style="width:10%;">Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach($kategoris as $i=>$kategori)
              <tr>
                <td>{{ ($kategoris->currentpage()-1) * $kategoris->perpage() + $i + 1 }}</td>
                <td> {{ $kategori->name }} </td>
                <td>
                  <a class="btn btn-warning btn-sm" href='{{URL::action("admin\KategoriController@edit",array($kategori->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
                  
                  <form class="btn btn-danger btn-sm" id="kategori{{$kategori->id}}" action='{{URL::action("admin\KategoriController@destroy",array($kategori->id))}}' method="POST">
                      <input type="hidden" name="_method" value="delete">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <a href="#" onclick="document.getElementById('kategori{{$kategori->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
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

