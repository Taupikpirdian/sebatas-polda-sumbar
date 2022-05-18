@extends('layouts.app')
@section('css')
<style>
  #perPage {
      width:90px;
  }
  #perPage option{
    width:90px;   
}
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>List Polsek Turunan</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">List Polsek Turunan</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-responsive-md">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Polsek</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($satkers as $i=>$data)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td> {{ $data->satkerTurunan->name }} </td>
                    <td>
                      <div class="dropdown">
                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                          <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href='{{URL::action("LaporanController@indexPerkara",array($data->satkerTurunan->id))}}'>List Perkara</a>
                        </div>
                      </div>
                    </td>
                </tr>
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

@endsection
