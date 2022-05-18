@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                <p class="text-muted text-center">{{ Auth::user()->divisi }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Jumlah Input</b> <a class="float-right">{{ $count_kasus }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Kasus Selesai</b> <a class="float-right">{{ $count_kasus_selesai }}</a>
                  </li>
                  <li class="list-group-item">
                    @if($count_kasus > 1)
                    <b>Persentase Kasus Selesai</b> <a class="float-right">{{ $english_format_number }}%</a>
                    @else
                    <b>Persentase Kasus Selesai</b> <a class="float-right">0%</a>
                    @endif
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Aktifitas</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Ubah Password</a></li>
                  <li class="nav-item"><a class="nav-link" href="#grafik" data-toggle="tab">Grafik Kasus</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                  @foreach($aktifitas as $i=>$aktifitas)
                    <!-- Post -->
                    <div class="post">
                      <div class="user-block" style="position: relative; right: 50px">
                        <span class="username">
                          <a href="#">{{ Auth::user()->name }}</a>
                        </span>
                        <span class="description">Input Kasus Baru - {{ $aktifitas->updated_at->format("F j, Y, g:i a") }}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        {{ $aktifitas->uraian }}
                      </p>
                    </div>
                    <!-- /.post -->
                  @endforeach
                  </div>

                  <div class="tab-pane" id="settings">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form class="form-horizontal" method="POST" action="{{URL::to('/changePassword')}}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="col-md-4 control-label">Password Sekarang</label>

                                <div class="col-md-6">
                                    <input id="current-password" type="password" class="form-control" name="current-password" required>

                                    @if ($errors->has('current-password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('current-password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="col-md-4 control-label">Password Baru</label>

                                <div class="col-md-6">
                                    <input id="new-password" type="password" class="form-control" name="new-password" required>

                                    @if ($errors->has('new-password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('new-password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new-password-confirm" class="col-md-4 control-label">Konfirmasi Password Baru</label>

                                <div class="col-md-6">
                                    <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Ubah Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>

                  <div class="tab-pane" id="grafik">
                    <div class="card-body">
                        <!-- PIE CHART -->
                        <div class="card card-danger">
                          <div class="card-header">
                            <h3 class="card-title">Perbandingan Kasus Selesai dan Belum</h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                            </div>
                          </div>
                          <div class="card-body">
                            <canvas id="pieChartFirst" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- BAR CHART -->
                        <div class="card card-success">
                          <div class="card-header">
                            <h3 class="card-title">Rekapitulasi Kasus Tiap Bulan</h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="chart">
                              <canvas id="barChartFirst" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                          </div>
                          <!-- /.card-body -->
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
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('js')
<!-- page script -->
<script>
  $(function () {
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
          label               : 'Selesai',
          backgroundColor     : '#00a65a',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
                                  {{ $januari }}, 
                                  {{ $februari }}, 
                                  {{ $maret }}, 
                                  {{ $april }}, 
                                  {{ $mei }}, 
                                  {{ $juni }}, 
                                  {{ $juli }}, 
                                  {{ $agustus }}, 
                                  {{ $september }}, 
                                  {{ $oktober }}, 
                                  {{ $november }}, 
                                  {{ $desember }} 
                                ]
        },
        {
          label               : 'Belum Selesai',
          backgroundColor     : '#DC3545',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [
                                  {{ $belum_januari }}, 
                                  {{ $belum_februari }}, 
                                  {{ $belum_maret }}, 
                                  {{ $belum_april }}, 
                                  {{ $belum_mei }}, 
                                  {{ $belum_juni }}, 
                                  {{ $belum_juli }}, 
                                  {{ $belum_agustus }}, 
                                  {{ $belum_september }}, 
                                  {{ $belum_oktober }}, 
                                  {{ $belum_november }}, 
                                  {{ $belum_desember }}
                                ]
        },
      ]
    }

    //-------------
    //- DONUT Data -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutData        = {
      labels: [
          'Belum Selesai',
          'Kasus Selesai', 
      ],
      datasets: [
        {
          data: [
                  {{ $count_kasus - $count_kasus_selesai }},
                  {{ $count_kasus_selesai }}
                ],
          backgroundColor : ['#DC3545', '#00a65a'],
        }
      ]
    }

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChartFirst').get(0).getContext('2d')
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
    var barChartCanvas = $('#barChartFirst').get(0).getContext('2d')
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

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>
@endsection
