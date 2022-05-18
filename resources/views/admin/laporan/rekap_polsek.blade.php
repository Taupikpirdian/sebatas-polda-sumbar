@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Rekapitulasi Kasus {{ $satuan->name }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Rekapitulasi Kasus {{ $satuan->name }}</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">Data Unit Reskrim</a></li>
            <li class="nav-item"><a class="nav-link" href="#grafik" data-toggle="tab">Grafik</a></li>
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <!-- tab-content -->
          <div class="tab-content">
            <!-- tab-pane -->
            <div class="active tab-pane" id="data">
              <div class="col-md-12 table-responsive">
                <table class="table table-bordered" style="font-size:14px">
                <thead>
                    {!! Form::open(['method'=>'GET','url'=>'/filter-polsek/'.$id,'role'=>'search'])  !!}
                    <tr style="border: 0">
                      <th style="width: 10px"></th>
                      <th><input type="text" class="form-control float-left" name="no_lp" value="{{ $no_lp }}"></th>
                      <th><input type="text" class="form-control float-left" name="petugas" value="{{ $petugas }}"></th>
                      <th><input type="text" class="form-control float-left" name="korban" value="{{ $korban }}"></th>
                      <th><input type="text" class="form-control float-left" name="bukti" value="{{ $bukti }}"></th>
                      <th>
                        <div class="input-group" style="float: left; margin-right: 10px">
                          <input type="text" class="form-control float-left" id="reservation2">
                        </div>
                        <input type="hidden" name="start_date" id="start_date" />
                        <input type="hidden" name="end_date" id="end_date" />
                      </th>
                      <th>
                        <select name="pidana" class="form-control">
                          <option value="">Pilih Jenis Pidana</option>
                          @foreach($jenispidanas as $i=>$pidana)
                          <option value="{{ $pidana->id }}" @if($pidana->id == $pidana_id) selected @endif>{{ $pidana->name }}</option>
                          @endforeach
                        </select>
                      </th>
                      <th>
                        <select name="status" class="form-control">
                          <<option value="">Pilih Status Pidana</option>
                          @foreach($statuses as $i=>$status)
                          <option value="{{ $status->id }}" @if($status->id == $status_id) selected @endif>{{ $status->name }}</option>
                          @endforeach
                        </select>
                      </th>
                      <th style="width: 70px"><button title="Pencarian" style="float: left; margin-right: 5px" class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button></th>
                    </tr>
                  {!! Form::close() !!}
                  </thead>               
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>No LP</th>
                      <th>Nama Petugas</th>
                      <th>Nama Korban</th>
                      <th>Barang Bukti</th>
                      <th>Waktu Kejadian</th>
                      <th>Jenis Pidana</th>
                      <th>Status</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  <tbody>
                  @foreach($data_unit_reskrim as $i=>$perkara)
                    <tr>
                      <td>{{ ($data_unit_reskrim->currentpage()-1) * $data_unit_reskrim->perpage() + $i + 1 }}</td>
                      <td> {{ $perkara->no_lp }} </td>
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
                          <button class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars fa-xs" aria-hidden="true"></i>
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a target="_blank" class="dropdown-item" href='{{URL::action("admin\PerkaraController@show",array($perkara->id))}}'>Detail Perkara</a>
                          </div>
                        </div>
                      </td>   
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  {!! $data_unit_reskrim->appends(Request::all())->links(); !!}
                </ul>
              </div>
            </div>

            <div class="tab-pane" id="grafik">
               <div class="card-body">
                  <!-- PIE CHART Polres -->
                  <div class="row">
                    <div class="col-md-12">

                      <div class="card card-danger">
                        <div class="card-header">
                          <h3 class="card-title">Kasus Unit Reskrim</h3>

                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="pieChartUnitreskrim" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>

                    </div>

                  </div>
                  <!-- /.card -->

              </div>
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
@endsection
@section('js')
<!-- page script -->
<script>
  $(function () {
    //-------------
    //- DONUT CHART Unitreskrim-
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var unitreskrim        = {
      labels: [
          'Belum Selesai',
          'Kasus Selesai', 
      ],
      datasets: [
        {
          data: [
                  {{ $pie_unit_reskrim_belum  }},
                  {{ $pie_unit_reskrim }}
                ],
          backgroundColor : ['#DC3545', '#00a65a'],
        }
      ]
    }

    //-------------
    //- PIE CHART Satreskrim -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChartUnitreskrim').get(0).getContext('2d')
    var pieData        = unitreskrim;
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