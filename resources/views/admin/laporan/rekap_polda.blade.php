@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
          <div class="card-header">
              <h4 class="card-title">Rekapitulasi Kasus Polda {{ $satker_fr_param->name }}</h4>
          </div>
          <div class="card-body">
              <!-- Nav tabs -->
              <div class="default-tab">
                  <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#home"><i class="la la-home mr-2"></i> Data Kasus</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#profile"><i class="la la-pie-chart mr-2"></i> Chart</a>
                      </li>
                  </ul>
                  <div class="tab-content">
                      <div class="tab-pane fade show active" id="home" role="tabpanel">
                          <div class="pt-4">
                              <div class="card">
                                <div class="card-header">
                                    <div class="input-group" style="float: right; width: 300px; margin-right: 10px">
                                    </div>
                                  {!! Form::open(['method'=>'GET','url'=>'/filter-polda','role'=>'search'])  !!}
                                    <div class="input-group" style="float: left; width: 300px; margin-right: 10px">
                                      <input type="text" class="form-control float-left" name="search" placeholder="Search no LP, korban dan tersangka ...">
                                      <input type="text" class="form-control" name="divisi" value="{{ $divisi }}" style="display: none">
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
                                          <th>No LP</th>
                                          <th>Divisi</th>
                                          <th>Nama Petugas</th>
                                          <th>Nama Korban</th>
                                          <th>Barang Bukti</th>
                                          <th>Waktu Kejadian</th>
                                          <th>Jenis Pidana</th>
                                          <th>Status</th>
                                          <th style="width: 70px">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($data as $i=>$perkara)
                                          <tr>
                                            <td>{{ ($data->currentpage()-1) * $data->perpage() + $i + 1 }}</td>
                                            <td> {{ $perkara->no_lp }} </td>
                                            <td> {{ $perkara->divisi }} </td>
                                            <td> {{ $perkara->nama_petugas }} </td>
                                            <td> {{ $perkara->nama }} </td>
                                            <td> {!! str_limit($perkara->barang_bukti, 25) !!} <a title="{{ $perkara->barang_bukti }}" href="#">[...]</a></td>
                                            <td> {{Carbon\Carbon::parse($perkara->date)->formatLocalized('%d %B %Y')}}, {{ $perkara->time }}</td>
                                            <td> {{ $perkara->pidana }} </td>
                                            @if($perkara->status_id == 1)
                                            <td><span class="badge badge-info">{{ $perkara->status }}</span></td>
                                            @else
                                            <td><span class="badge badge-success">{{ $perkara->status }}</span></td>
                                            @endif
                                            <td>
                                              <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                  <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                  <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@show",array($perkara->id))}}'>Detail</a>
                                                </div>
                                              </div>
                                            </td>
                                          </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                  <ul class="float-left">
                                    Showing {{ (($data->currentpage()-1) * $data->perPage())+1 }} 
                                    to {{ $data->currentPage()*$data->perPage() }} 
                                    of {{ $data->total() }} entries
                                  </ul>
                                  <ul class="pagination pagination-sm m-0 float-right">
                                    {!! $data->appends(Request::all())->links(); !!}
                                  </ul>
                                </div>
                              </div>
                          </div>
                      </div>

                      <div class="tab-pane fade" id="profile">
                        <div class="pt-4">
                          <div class="col-md-12">
                            <div class="card card-primary">
                              <div class="card-body">
                                <canvas id="pieChartDitreskrimsus" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  </div>
</div>
@endsection
@section('js')
<!-- page script -->
<script>
  $(function () {
    //-------------
    //- DONUT CHART Ditreskrimsus-
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var ditreskrimsus        = {
      labels: [
          'Kasus Selesai', 
          'Belum Selesai',
      ],
      datasets: [
        {
          data: [
                  {{ $pie_polda }},
                  {{ $pie_polda_belum }}
                ],
          backgroundColor : ['#00a65a', '#DC3545'],
        }
      ]
    }

    //-------------
    //- PIE CHART Ditreskrimsus -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChartDitreskrimsus').get(0).getContext('2d')
    var pieData        = ditreskrimsus;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions      
    })

  })
</script>
@endsection