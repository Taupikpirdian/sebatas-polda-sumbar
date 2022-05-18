@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4 class="mb-0">Aktivitas</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Aktifitas</a>
        </li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm mb-0 table-striped">
              <thead>
                <tr>
                  <th>No LP</th>
                  <th>Akun</th>
                  <th>Waktu</th>
                  <th>Aktivitas</th>
                </tr>
              </thead>
              <tbody id="customers">
              @foreach($logs as $key=>$log)
                <tr class="btn-reveal-trigger">
                  <td class="py-2">
                    <a href="#">{{ $log->no_lp }}</a>
                  </td>
                  <td class="py-2">{{ $log->name }}</td>
                  <td class="py-2">{{ $log->age_of_data }}</td>
                  @if($log->status == 1)
                  <td class="py-2"><span class="badge light badge-primary">Menambahkan data Crime</span></td>
                  @elseif($log->status == 2)
                  <td class="py-2"><span class="badge light badge-info">Update status Crime</span></td>
                  @elseif($log->status == 3)
                  <td class="py-2"><span class="badge light badge-danger">Edit data Crime</span></td>
                  @elseif($log->status == 4)
                  <td class="py-2"><span class="badge light badge-success">Update anggaran Crime</span></td>
                  @endif
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- paginate -->
        <div class="card-footer clearfix">
          <ul class="float-left">
            Showing {{ (($logs->currentpage()-1) * $logs->perPage())+1 }} 
            to {{ $logs->currentPage()*$logs->perPage() }} 
            of {{ $logs->total() }} entries
          </ul>
          <ul class="pagination pagination-sm m-0 float-right">
            {!! $logs->render() !!}
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
@endsection