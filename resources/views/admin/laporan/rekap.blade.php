@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Rekapitulasi Kasus</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Rekapitulasi Kasus</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          {!! Form::open(['method'=>'GET','url'=>'/grouping/export_excel','role'=>'search'])  !!}
            <button title="Download Data" type="submit" class="btn btn-sm btn-info float-left">Download Data Rekapitulasi</button>
          {!! Form::close() !!}
          {!! Form::open(['method'=>'GET','url'=>'/search-rekapitulasi','role'=>'search'])  !!}
            <div class="input-group" style="float: left; width: 300px; margin-right: 10px">
              <input type="text" class="form-control float-left" name="search" placeholder="Satuan Kerja .....">
            </div>
            <button title="Pencarian" style="float: left; margin-right: 5px" class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          {!! Form::close() !!}
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-responsive-md">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Satuan Kerja</th>
                  <th style="width: 200px">Kasus Selesai</th>
                  <th style="width: 200px">Kasus Belum Selesai</th>
                  <th style="width: 200px">Jumlah Kasus</th>
                  <th style="width: 200px">Persentase Selesai</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rekap as $i=>$grouping)
		              <tr>
		                <td>{{ $i + 1 }}</td>
                    <td>{{ $grouping->name }}</td>
                    @if($grouping->array == 3)
                    <td style="font-size: 17px"><span class="badge light badge-success">0</span></td>
                    <td style="font-size: 17px"><span class="badge light badge-danger">{{ $grouping->total }}</span></td>
                    @else
                    <td style="font-size: 17px"><span class="badge light badge-success">{{ $grouping->kasus_selesai }}</span></td>
                    <td style="font-size: 17px"><span class="badge light badge-danger">{{ ($grouping->total - $grouping->kasus_selesai) }}</span></td>
                    @endif
		                <td style="font-size: 17px">{{ $grouping->total }}</td>
                    <td style="font-size: 17px">{{ $grouping->percent_success }}%</td>
		              </tr>
		            @endforeach
                <tr>
	                <td>#</td>
	                <td style="text-align: center"><b>TOTAL</b></td>
                  <td style="font-size: 17px"><span class="badge light badge-success">{{ $count_kasus_selesai }}</span></td>
                  <td style="font-size: 17px"><span class="badge light badge-danger">{{ $count_kasus_belum_selesai }}</span></td>
	                <td>{{ $count_kasus }}</td>
                  <td style="background-color: #343A40"><span class="badge bg-info"></span></td>
	              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection