@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <!-- Info boxes -->
  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sistem Data Kriminal</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  <!-- /.content-header -->
  <!-- /.row -->

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Filter Data</h5>
          <br>
          <hr>
            {!! Form::open(['method'=>'GET','url'=>'/filter-admin','role'=>'search'])  !!}
            <div class="form-row">

              <div class="form-group col-md-3">
                <select id="my-select-satker" name="satker" class="form-control">
                  <option value="">Pilih Satuan Kerja</option>
            	    @foreach($kategori_bagians as $i=>$satker)
                  <option value="{{ $satker->id }}">{{ $satker->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group col-md-3">
                <select id="my-select-pidana" name="pidana" class="form-control">
                  <option value="">Pilih Jenis Pidana</option>
                  @foreach($jenispidanas as $i=>$pidana)
                  <option value="{{ $pidana->id }}">{{ $pidana->name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- <div class="input-group" style="float: left; width: 230px; margin-right: 10px">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="text" class="form-control float-left" id="reservation">
              </div>
              <input type="hidden" name="start_date" id="start_date" />
              <input type="hidden" name="end_date" id="end_date" /> -->

              <div class="form-group col-md-1">
                <button title="Filter" type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>
            {!! Form::close() !!}
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Peta Persebaran Kriminal Provinsi Sumatera Barat</h5>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="chart">
                <!-- Sales Chart Canvas -->
                <div id="map" style="width:100%;height:380px;"></div>
                <!-- <canvas id="salesChart" height="180" style="height: 180px;"></canvas> -->
              </div>
              <!-- /.chart-responsive -->
            </div>
            <!-- /.col -->
            <!-- <div class="col-md-4">
              <p class="text-center">
                <strong>Progress Kasus di Sumatera Barat</strong>
              </p>

              <div class="progress-group">
                Polda
                <span class="float-right"><b>{{ $success_perkara_polda }}</b>/{{ $perkara_polda }}</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-primary" style="width: {{ $persentase_polda }}%"></div>
                </div>
              </div>

              <div class="progress-group">
                Polres
                <span class="float-right"><b>{{ $success_perkara_polres }}</b>/{{ $perkara_polres }}</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-danger" style="width: {{ $persentase_polres }}%"></div>
                </div>
              </div>

              <div class="progress-group">
                Polsek
                <span class="float-right"><b>{{ $success_perkara_polsek }}</b>/{{ $perkara_polsek }}</span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-warning" style="width: {{ $persentase_polsek }}%"></div>
                </div>
              </div>
              <div style="text-align: center; background-color: ; width: 300px; border: 15px solid hsl(0, 100%, 30%); padding: 50px; margin: 20px;">
                <h5><b>User Login</b></h5>
                {{ $numberOfUsers}}
              </div>

            </div> -->
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $count_kasus }}</h5>
                <span class="description-text">TOTAL KASUS</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $count_kasus_selesai }}</h5>
                <span class="description-text">KASUS SELESAI</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $count_kasus_belum }}</h5>
                <span class="description-text">KASUS BELUM SELESAI</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block">
                @if($count_kasus > 1)
                <h5 class="description-header">{{ ($count_kasus_selesai/$count_kasus)*100 }}%</h5>
                @else
                <h5 class="description-header">0%</h5>
                @endif
                <span class="description-text">PERSENTASE KASUS SELESAI</span>
              </div>
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- PIE CHART -->
  <div class="card card-danger">
    <div class="card-header">
      <h3 class="card-title">Diagram Pai Jumlah Perkara Di Provinsi Sumatera Barat Tahun {{ $now->year }}</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  <!-- BAR CHART -->
  <div class="card card-success">
    <div class="card-header">
      <h3 class="card-title">Diagram Batang Jumlah Perkara Di Provinsi Sumatera Barat Tahun {{ $now->year }}</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="chart">
        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
    </div>
    <!-- /.card-body -->
  </div>

<!-- AREA CHART -->
  <div class="card card-primary" style="display: none;">
    <div class="card-header">
      <h3 class="card-title">Area Chart</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="chart">
        <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  <!-- DONUT CHART -->
  <div class="card card-danger" style="display: none;">
    <div class="card-header">
      <h3 class="card-title">Donut Chart</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <div class="col-md-12">
      <!-- TABLE: LATEST ORDERS -->
      <div class="card">
        <div class="card-header border-transparent">
          <h3 class="card-title">Rekapitulasi Jumlah Kasus <span class="badge badge-info"></span></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table class="table table-bordered">
            <thead>                  
              <tr>
                <th style="width: 10px">#</th>
                <th>Satker</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
            @foreach($perkaras_grouping as $i=>$perkara)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $perkara->name }}</td>
                <td><span class="badge bg-danger">{{ $perkara->total }}</span></td>
              </tr>
            @endforeach
              <tr>
                <td>#</td>
                <td style="text-align: center"><b>TOTAL</b></td>
                <td><span class="badge bg-info">{{ $count_kasus }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <a style="margin-right: 10px" href="{{URL::to('/perkara/create')}}" class="btn btn-sm btn-info float-left">Tambah Kasus</a>
          {!! Form::open(['method'=>'GET','url'=>'/grouping/export_excel','role'=>'search'])  !!}
            <input type="hidden" name="satker_selected" value="{{ $satker_param }}" />
            <input type="hidden" name="pidana_selected" value="{{ $pidana_param }}" />
            <button title="Download Data" type="submit" class="btn btn-sm btn-info float-left">Download Data Rekapitulasi</button>
          {!! Form::close() !!}
          <a href="#" data-toggle="modal" data-target="#modal-default" class="btn btn-sm btn-secondary float-right">Update Kasus</a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

@endsection
@section('js')
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : [
                  'January', 
                  'February', 
                  'March', 
                  'April', 
                  'May', 
                  'June', 
                  'July', 
                  'August', 
                  'September', 
                  'October', 
                  'November', 
                  'December'
                ],
      datasets: [
        {
          label               : 'Polres',
          backgroundColor     : 'rgb(220,53,69)',
          borderColor         : 'rgb(52,58,64)',
          pointRadius         : false,
          pointColor          : 'rgb(52,58,64)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgb(52,58,64)',
          data                : [
                                  {{$polres_januari}}, 
                                  {{$polres_februari}}, 
                                  {{$polres_maret}}, 
                                  {{$polres_april}}, 
                                  {{$polres_mei}}, 
                                  {{$polres_juni}}, 
                                  {{$polres_juli}},
                                  {{$polres_agustus}},
                                  {{$polres_september}},
                                  {{$polres_oktober}},
                                  {{$polres_november}},
                                  {{$polres_desember}}
                                ]
        },
        {
          label               : 'Polda',
          backgroundColor     : 'rgb(0,123,255)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
                                  {{$polda_januari}}, 
                                  {{$polda_februari}}, 
                                  {{$polda_maret}}, 
                                  {{$polda_april}}, 
                                  {{$polda_mei}}, 
                                  {{$polda_juni}}, 
                                  {{$polda_juli}},
                                  {{$polda_agustus}},
                                  {{$polda_september}},
                                  {{$polda_oktober}},
                                  {{$polda_november}},
                                  {{$polda_desember}}
                                ]
        },
        {
          label               : 'Polsek',
          backgroundColor     : 'rgb(255,193,7)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [
                                  {{$polsek_januari}}, 
                                  {{$polsek_februari}}, 
                                  {{$polsek_maret}}, 
                                  {{$polsek_april}}, 
                                  {{$polsek_mei}}, 
                                  {{$polsek_juni}}, 
                                  {{$polsek_juli}},
                                  {{$polsek_agustus}},
                                  {{$polsek_september}},
                                  {{$polsek_oktober}},
                                  {{$polsek_november}},
                                  {{$polsek_desember}}
                                ]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    var areaChart       = new Chart(areaChartCanvas, { 
      type: 'line',
      data: areaChartData, 
      options: areaChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Polda', 
          'Polres', 
          'Polsek',
      ],
      datasets: [
        {
          data: [ {{ $pai_polda }}, {{ $pai_polres }}, {{ $pai_polsek }} ],
          backgroundColor : ['#007BFF', '#DC3545', '#FFC107'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
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

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

  })


  $(document).ready(function(){

    document.getElementById('my-select-satker').addEventListener('change', function() {
      document.getElementById('satker-selected').value = this.value;
    });

    document.getElementById('my-select-pidana').addEventListener('change', function() {
      document.getElementById('pidana-selected').value = this.value;
    });

  });

</script>
@endsection