@extends('layouts.app')

@section('css')
<style>
.select2-selection__rendered {
  line-height: 32px !important;
}

.select2-selection {
  height: 34px !important;
}
</style>
@endsection

@section('content')

<div class="container-fluid">
  <div class="row">
    <div class="col-xl-9 col-xxl-12">
      <div class="row">
        <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
          <div class="card overflow-hidden">
            <div class="card-body pb-0 px-4 pt-4">
              <div class="row">
                <div class="col">
                  <h5 class="mb-1">{{ $count_kasus }}</h5>  <span class="text-success">Aduan Masyarakat Total</span>
                </div>
              </div>
            </div>
            <div class="chart-wrapper">
              <canvas id="areaChart_2" class="chartjs-render-monitor" height="90"></canvas>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-xxl-6 col-lg-6 col-sm-6">
          <div class="card bg-primary overflow-hidden">
            <div class="card-body pb-0 px-4 pt-4">
              <div class="row">
                <div class="col text-white">
                  <h5 class="text-white mb-1">{{ $count_kasus_this_y }}</h5>  <span>Aduan Masyarakat Total Tahun 2021</span>
                </div>
              </div>
            </div>
            <div class="chart-wrapper px-2">
              <canvas id="chart_widget_2" height="100"></canvas>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-xxl-4 col-lg-12 col-md-12">
          <div class="card">
            <div class="card-header border-0 pb-0">
              <h4 class="card-title">Aktivitas</h4>
            </div>
            <div class="card-body">
              <div id="DZ_W_TimeLine1" class="widget-timeline dz-scroll style-1" style="height:250px;">
                <ul class="timeline">
                @foreach($logs as $key=>$log)
                @if($log->status == 5)
                <!-- kode = 1 -->
                  <li>
                    <div class="timeline-badge primary"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Menambahkan Data Aduan</h6> 
                      <p class="mb-0">{{ $log->no_stplp }}</p>
                    </a>
                  </li>
                @elseif($log->status == 6)
                <!-- kode = 2 -->
                  <li>
                    <div class="timeline-badge info"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Update Data Aduan</h6>
                      <p class="mb-0">{{ $log->no_stplp }}</p>
                    </a>
                  </li>
                @elseif($log->status == 7)
                <!-- kode = 3 -->
                  <li>
                    <div class="timeline-badge danger"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Edit Data Aduan</h6> 
                      <p class="mb-0">{{ $log->no_stplp }}</p>
                    </a>
                  </li>
                @elseif($log->status == 8)
                <!-- kode = 4 -->
                  <li>
                    <div class="timeline-badge success"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Update Anggaran Aduan</h6> 
                      <p class="mb-0">{{ $log->no_stplp }}</p>
                    </a>
                  </li>
                @endif
                @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-8 col-xxl-8 col-lg-12 col-md-12">
          <div class="card">
            <div class="card-header border-0 pb-0">
              <h4 class="card-title">Data Aduan Masyarakat Terbaru</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-responsive-sm mb-0">
                  <thead>
                    <tr>
                      <th><strong>No STPLP</strong>
                      </th>
                      <th><strong>SATKER</strong>
                      </th>
                      <th><strong>JENIS</strong>
                      </th>
                      <th><strong>WAKTU KEJADIAN</strong>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($lapors as $key=>$lapor)
                    <tr>
                      <td><b>{{ $lapor->no_stplp }}</b></td>
                      <td>{{ $lapor->satuan }}</td>
                      <td>{{ $lapor->pidana }}</td>
                      <td>{{ \Carbon\Carbon::parse($lapor->date)->format('j F, Y') }}</td>
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

    <div class="col-xl-3 col-xxl-6 col-lg-12 col-md-12">
      <div class="card bg-primary text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Aduan Masyarakat <label style="font-size: 12px; color: #72A0FE">berdasarkan jenis Aduan Masyarakat</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus as $key=>$top)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top->name }}</h5>  <small class="d-block">{{ $top->total }} Aduan Masyarakat</small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <canvas id="lineChart_3Kk"></canvas>
      </div>
    </div>
    <!-- for admin -->
    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
    <div class="col-xl-3 col-xxl-6 col-lg-12 col-md-12">
      <div class="card bg-primary text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Aduan Masyarakat <label style="font-size: 12px; color: #72A0FE">berdasarkan satker</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polda</h5>  
                    @foreach($top_kasus_satker_polda as $key=>$top_polda)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polda->name }} <label style="font-size: 12px; color: #72A0FE">({{$top_polda->total}} Aduan Masyarakat)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polres</h5>
                    @foreach($top_kasus_satker_polres as $key=>$top_polres)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polres->name }} <label style="font-size: 12px; color: #72A0FE">({{$top_polres->total}} Aduan Masyarakat)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polsek</h5>
                    @foreach($top_kasus_satker_polsek as $key=>$top_polsek)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polsek->name }} <label style="font-size: 12px; color: #72A0FE">({{$top_polsek->total}} Aduan Masyarakat)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <canvas id="lineChart_3Kk_1"></canvas>
      </div>
    </div>

    <!-- <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-primary text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Crime Index <label style="font-size: 12px; color: #72A0FE">berdasarkan divisi</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus_satker_bagian as $key=>$top_bagian)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top_bagian->name }}</h5>  <small class="d-block">{{ $top_bagian->total }} Crime</small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <canvas id="lineChart_3Kk_2"></canvas>
      </div>
    </div> -->
    <!-- for admin end -->
    @endif
    <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12">
      <!-- filter -->
      <div class="card">
        <div class="card-header">
            <h5 class="card-title">Lihat data Aduan Masyarakat</h5>
        </div>
        {!! Form::open(['method'=>'GET','url'=>'/lihat-data-dumas','role'=>'search'])  !!}
        <div class="card-body">
            <div class="basic-form">
                <div class="row">
                    <div class="col-sm-6" style="margin-bottom: 12px">
                      <select name="satker" id="satker">
                          <option value="">Pilih SATKER</option>
                        @foreach($kategori_bagians as $i=>$satker)
                          <option value="{{ $satker->id }}">{{ $satker->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-sm-6 mt-2 mt-sm-0" style="margin-bottom: 12px">
                      <select name="jenis_kasus" id="jenis_kasus">
                          <option value="">Pilih Jenis Tindak Pidana</option>
                        @foreach($jenispidanas as $i=>$pidana)
                          <option value="{{ $pidana->id }}">{{ $pidana->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-sm-6 mt-2 mt-sm-0" style="margin-bottom: 12px">
                      <select name="tahun" id="year-select2" required>
                          <option value="">Pilih Tahun</option>
                          <option value="2015">2015</option>
                          <option value="2016">2016</option>
                          <option value="2017">2017</option>
                          <option value="2018">2018</option>
                          <option value="2019">2019</option>
                          <option value="2020">2020</option>
                          <option value="2021">2021</option>
                          <option value="2022">2022</option>
                          <option value="2023">2023</option>
                          <option value="2024">2024</option>
                          <option value="2025">2025</option>
                          <option value="2026">2026</option>
                          <option value="2027">2027</option>
                          <option value="2028">2028</option>
                          <option value="2029">2029</option>
                          <option value="2030">2030</option>
                      </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-sm-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-primary">Lihat</button>
        </div>
        {!! Form::close() !!}
      </div>
      <!-- end filter -->
    </div>

    @if($is_open == true)
    <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12">
      <!-- filter -->
      <div class="card">
        <div class="card-header">
            <h5 class="card-title">Pencarian untuk</h5>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <div class="row">
                    <div class="col-sm-4" style="margin-bottom: 12px">
                        SATKER: <b>@if($satker_param){{ $satker_fr_param->name }} @else - @endif </b>
                    </div>
                    <div class="col-sm-4" style="margin-bottom: 12px">
                        Jenis Pidana: <b>@if($jenis_kasus_param){{ $jenis_pidana_fr_param->name }} @else - @endif </b>
                    </div>
                    <div class="col-sm-4" style="margin-bottom: 12px">
                        Tahun: <b>{{ $tahun_param }}</b>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <!-- end filter -->
    </div>

    <!-- <div class="col-xl-4 col-lg-6 col-sm-6">
      <div class="widget-stat card">
        <div class="card-body p-4">
          <div class="media ai-icon">	
            <span class="mr-3">
              <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
              </svg>
            </span>
            <div class="media-body">
              <p class="mb-1">Resiko Penduduk Terkena Tindak Pidana</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-sm-6">
      <div class="widget-stat card">
        <div class="card-body p-4">
          <div class="media ai-icon">	
            <span class="mr-3">
              <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
              </svg>
            </span>
            <div class="media-body">
              <p class="mb-1">Selang Waktu Terjadi Kejahatan (menit)</p>
            </div>
          </div>
        </div>
      </div>
    -->
   
    <!-- </div> -->
    <!-- <div class="col-xl-4 col-lg-6 col-sm-6">
      <div class="widget-stat card">
        <div class="card-body p-4">
          <h4 class="card-title">Perkembangan Jumlah Kejahatan</h4>
        </div>
      </div>
    </div> -->
    <!-- <div class="col-xl-4 col-lg-6 col-sm-6">
      <div class="widget-stat card">
        <div class="card-body p-4">
          <h4 class="card-title">Penyelesaian Perkara</h4>
        </div>
      </div>
    </div> -->

    <!-- Filter Card -->
    <div class="col-xl-3 col-xxl-6 col-lg-12 col-md-12">
      <div class="card bg-success text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Aduan Masyarakat Index <label style="font-size: 12px; color: #EBEEF6">berdasarkan jenis Kejahatan</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus_filter as $key=>$top_filter)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top_filter->name }}</h5>  <small class="d-block">{{ $top_filter->total }} Kejahatan</small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <canvas id="lineChart_3Kk_2"></canvas>
      </div>
    </div>
    <!-- for admin -->
    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
    <div class="col-xl-3 col-xxl-6 col-lg-12 col-md-12">
      <div class="card bg-success text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Aduan Masyarakat Index <label style="font-size: 12px; color: #EBEEF6">berdasarkan satker</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polda</h5>  
                    @foreach($top_kasus_satker_polda_filter as $key=>$top_polda_filter)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polda_filter->name }} <label style="font-size: 12px; color: #EBEEF6">({{$top_polda_filter->total}} Kejahatan)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polres</h5>
                    @foreach($top_kasus_satker_polres_filter as $key=>$top_polres_filter)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polres_filter->name }} <label style="font-size: 12px; color: #EBEEF6">({{$top_polres_filter->total}} Kejahatan)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polsek</h5>
                    @foreach($top_kasus_satker_polsek_filter as $key=>$top_polsek_filter)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polsek_filter->name }} <label style="font-size: 12px; color: #EBEEF6">({{$top_polsek_filter->total}} Kejahatan)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="chart-wrapper">
            <div id="chart_widget_5"></div>
        </div>
        <!-- <canvas id="lineChart_3Kk_1"></canvas> -->
      </div>
    </div>
    @endif

    <div class="col-xl-12 col-xxl-12 col-lg-12 col-md-12">
      <!-- maps -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Peta Persebaran Aduan Provinsi Sumatera Barat Tahun {{ $tahun_param }}</h5>
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
          </div>
          <!-- /.row -->
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $count_kasus_f_map }}</h5>
                <span style="text-decoration:none;" class="description-text">Total Aduan Masyarakat</span>
              </div>
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div>
      </div>
      <!-- end maps -->
    </div>
    
    <div class="col-xl-12 col-lg-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Data Aduan Masyarakat Tahun {{ $tahun_param }}</h4>
        </div>
        <div class="card-body">
          <canvas id="barChart_1"></canvas>
        </div>
      </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Data Aduan Masyarakat <label style="font-size: 12px; color: #72A0FE">berdasarkan waktu</label></h4>
        </div>
        <div class="card-body">
          <canvas id="lineChart_1"></canvas>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>

@endsection
@section('js')
<script>
//#chart_widget_2
if(jQuery('#chart_widget_2').length > 0 ){
	
  const chart_widget_2 = document.getElementById("chart_widget_2").getContext('2d');
  //generate gradient
  const chart_widget_2gradientStroke = chart_widget_2.createLinearGradient(0, 0, 0, 250);
  chart_widget_2gradientStroke.addColorStop(0, "#a0bfff");
  chart_widget_2gradientStroke.addColorStop(1, "#a0bfff");

  // chart_widget_2.attr('height', '100');

  new Chart(chart_widget_2, {
    type: 'bar',
    data: {
      defaultFontFamily: 'Poppins',
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      datasets: [
        {
          label: "Jumlah Aduan Masyarakat",
          data: [
            {{ $jan_f_diagram_ty }},
            {{ $feb_f_diagram_ty }},
            {{ $mar_f_diagram_ty }},
            {{ $apr_f_diagram_ty }},
            {{ $mei_f_diagram_ty }},
            {{ $jun_f_diagram_ty }},
            {{ $jul_f_diagram_ty }},
            {{ $aug_f_diagram_ty }},
            {{ $sep_f_diagram_ty }},
            {{ $oct_f_diagram_ty }},
            {{ $nov_f_diagram_ty }},
            {{ $des_f_diagram_ty }}
          ],
          borderColor: chart_widget_2gradientStroke,
          borderWidth: "0",
          backgroundColor: chart_widget_2gradientStroke, 
          hoverBackgroundColor: chart_widget_2gradientStroke
        }
      ]
    },
    options: {
        legend: false,
        responsive: true, 
        maintainAspectRatio: false,  
        scales: {
            yAxes: [{
                display: false, 
                ticks: {
                    beginAtZero: true, 
                    display: false, 
                    max: 100, 
                    min: 0, 
                    stepSize: 10
                }, 
                gridLines: {
                    display: false, 
                    drawBorder: false
                }
            }],
            xAxes: [{
                display: false, 
                barPercentage: 0.3, 
                gridLines: {
                    display: false, 
                    drawBorder: false
                }, 
                ticks: {
                    display: false
                }
            }]
        }
    }
  });
}

// select2
$("#satker").select2();
$("#jenis_kasus").select2();
$("#year-select2").select2();
</script>

@if($is_open == true)
<!-- google maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYiwleTNi8Ww0Un6Jna9LuQyWGvdFYEcI&callback=initMap"
async defer></script>

<script>
  function initMap() {
    var options = {
        zoom:8,
        center:{lat:-0.528119,lng:100.538150}
    }

    var locations = [
      @foreach($petas as $i=>$peta)
        @if($peta != null)
        [new google.maps.LatLng(
          {{$peta->lat}}, 
          {{$peta->long}}), 
          '{{$peta->divisi}}', 
          'No STPLP: {{ $peta->no_stplp }}, <a target="_blank" href="{{URL::to('/lapor/show/'.$peta->id)}}">Detail</a>',
          '{{$peta->pin}}'],
        @endif
      @endforeach
    ];

    var map = new google.maps.Map(document.getElementById('map'), options);

    var infowindow = new google.maps.InfoWindow();

    for (var i = 0; i < locations.length; i++) {
      var marker = new google.maps.Marker({
        position: locations[i][0],
        map: map,
        title: locations[i][1],
        icon: locations[i][3],
      });

      // Register a click event listener on the marker to display the corresponding infowindow content
      google.maps.event.addListener(marker, 'click', (function(marker, i) {

        return function() {
          infowindow.setContent(locations[i][2]);
          infowindow.open(map, marker);
        }

      })(marker, i));
    }
  }

  //basic bar chart
  if(jQuery('#barChart_1').length > 0 ){
    const barChart_1 = document.getElementById("barChart_1").getContext('2d');
    
    barChart_1.height = 100;

    new Chart(barChart_1, {
      type: 'bar',
      data: {
          defaultFontFamily: 'Poppins',
          labels: [
            "Jan", 
            "Feb", 
            "Mar", 
            "Apr", 
            "May", 
            "Jun", 
            "Jul", 
            "Aug", 
            "Sep", 
            "Oct", 
            "Nov", 
            "Des"
          ],
          datasets: [
            {
              label: "Aduan Masyarakat Selesai",
              data: [
                {{ $jan_f_diagram_done }}, 
                {{ $feb_f_diagram_done }}, 
                {{ $mar_f_diagram_done }}, 
                {{ $apr_f_diagram_done }}, 
                {{ $mei_f_diagram_done }}, 
                {{ $jun_f_diagram_done }}, 
                {{ $jul_f_diagram_done }}, 
                {{ $aug_f_diagram_done }}, 
                {{ $sep_f_diagram_done }}, 
                {{ $oct_f_diagram_done }}, 
                {{ $nov_f_diagram_done }}, 
                {{ $des_f_diagram_done }},
              ],
              borderColor: 'rgba(58, 122, 254, 1)',
              borderWidth: "0",
              backgroundColor: 'rgba(58, 122, 254, 1)'
            },
          ]
      },
      options: {
        legend: false, 
        scales: {
          yAxes: [{
              ticks: {
                  beginAtZero: true
              }
          }],
          xAxes: [{
              // Change here
              barPercentage: 0.5
          }]
        }
      }
    });
  }

  // chart time
  // variable
  var time_session_1 = {{ $data_time[0] }};
  var time_session_2 = {{ $data_time[1] }};
  var time_session_3 = {{ $data_time[2] }};
  var time_session_4 = {{ $data_time[3] }};
  var time_session_5 = {{ $data_time[4] }};
  var time_session_6 = {{ $data_time[5] }};
  var time_session_7 = {{ $data_time[6] }};
  var time_session_8 = {{ $data_time[7] }};
  var min            = {{ $min }};
  var max            = {{ $max }};
  var range          = {{ $range }};

  if(jQuery('#lineChart_1').length > 0){
    let draw = Chart.controllers.line.__super__.draw; //draw shadow
    
    //basic line chart
    const lineChart_1 = document.getElementById("lineChart_1").getContext('2d');

    Chart.controllers.line = Chart.controllers.line.extend({
      draw: function () {
        draw.apply(this, arguments);
        let nk = this.chart.chart.ctx;
        let _stroke = nk.stroke;
        nk.stroke = function () {
          nk.save();
          nk.shadowColor = 'rgba(255, 0, 0, .2)';
          nk.shadowBlur = 10;
          nk.shadowOffsetX = 0;
          nk.shadowOffsetY = 10;
          _stroke.apply(this, arguments)
          nk.restore();
        }
      }
    });
    
    lineChart_1.height = 100;

    new Chart(lineChart_1, {
      type: 'line',
      data: {
        defaultFontFamily: 'Poppins',
        labels: [
          "00.00-03.00", 
          "03.01-06.00", 
          "06.01-09.00", 
          "09.01-12.00", 
          "12.01-15.00", 
          "15.01-18.00", 
          "18.01-21.00",
          "21.01-23.59"
        ],
        datasets: [
          {
            label: "Jumlah Aduan Masyarakat",
            data: [
              time_session_1, 
              time_session_2, 
              time_session_3, 
              time_session_4, 
              time_session_5, 
              time_session_6, 
              time_session_7, 
              time_session_8, 
            ],
            borderColor: 'rgba(56, 164, 248, 1)',
            borderWidth: "2",
            backgroundColor: 'transparent',  
            pointBackgroundColor: 'rgba(56, 164, 248, 1)'
          }
        ]
      },
      options: {
        legend: false, 
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true, 
              max: max, 
              min: min, 
              stepSize: range, 
              padding: 10
            }
          }],
          xAxes: [{
            ticks: {
              padding: 5
            }
          }]
        }
      }
    });
  }
</script>
@endif

<!-- First Chart Admin-->
@if(Auth::user()->groups()->where("name", "=", "Admin")->first())
<script>

</script>
@endif

<!-- First Chart Bukan Admin-->
@if(Auth::user()->groups()->where("name", "!=", "Admin")->first())
<script>

</script>
@endif
@endsection