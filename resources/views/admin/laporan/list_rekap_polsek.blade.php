@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Rekapitulasi Kasus Polsek</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Rekapitulasi Kasus Polsek</li>
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
            <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">Daftar Polsek</a></li>
            <li class="nav-item"><a class="nav-link" href="#grafik" data-toggle="tab">Grafik</a></li>
          </ul>
        </div><!-- /.card-header -->
        <div class="card-header">
          {!! Form::open(['method'=>'GET','url'=>'/search-rekapitulasi-polsek','role'=>'search'])  !!}
            <div class="input-group" style="float: left; width: 300px; margin-right: 10px">
              <input type="text" class="form-control float-left" name="search" placeholder="Polsek ...">
            </div>
            <button title="Pencarian" style="float: left; margin-right: 5px" class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          {!! Form::close() !!}
        </div>
        <div class="card-body">
          <!-- tab-content -->
          <div class="tab-content">
            <!-- tab-pane -->
            <div class="active tab-pane" id="data">
              <div class="col-md-12 table-responsive">
                <table class="table table-bordered" style="font-size:14px">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Polsek</th>
                      <th style="width: 70px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $i=>$polsek)
                        <tr>
                        <td>{{ $i + 1 }}</td>
                        <td> {{ $polsek->name }} </td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fa fa-bars fa-xs" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a target="_blank" class="dropdown-item" href='{{URL::action("admin\LaporanController@rekapPolsek",array($polsek->id))}}'>Detail Perkara</a>
                            </div>
                          </div>
                        </td>   
                        </tr>
                    @endforeach
                  </tbody>
                </table>
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