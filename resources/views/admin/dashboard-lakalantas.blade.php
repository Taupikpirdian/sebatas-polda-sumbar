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
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
          <div class="card overflow-hidden">
            <div class="card-body pb-0 px-4 pt-4">
              <div class="row">
                <div class="col">
                  <h5 class="mb-1">{{ $count_kasus }}</h5>  <span class="text-success">Total Kecelakaan lalu-lintas</span><br>
                  <a href="{{URL::to('/total-laka-lantas')}}">Lihat Detail</a>
                </div>
              </div>
            </div>
            <div class="chart-wrapper">
              <canvas id="areaChart_2" class="chartjs-render-monitor" height="90"></canvas>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
          <div class="card bg-success	overflow-hidden">
            <div class="card-body pb-0 px-4 pt-4">
              <div class="row">
                <div class="col">
                  <h5 class="text-white mb-1">{{ $count_kasus_lama }}</h5><span class="text-white">Kecelakaan lalu-lintas Tunggakan</span><br>
                  <a href="{{URL::to('/laka-lantas-lama')}}">Lihat Detail</a>
                </div>
              </div>
            </div>
            <div class="chart-wrapper" style="width:100%"> <span class="peity-line" data-width="100%">6,2,8,4,3,8,4,3,6,5,9,2</span>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
          <div class="card bg-primary overflow-hidden">
            <div class="card-body pb-0 px-4 pt-4">
              <div class="row">
                <div class="col text-white">
                  <h5 class="text-white mb-1">{{ $count_kasus_this_y }}</h5>  <span>Total Kecelakaan lalu-lintas Tahun 2021</span>
                  <a href="{{URL::to('/laka-lantas/this-year')}}" class="text-danger">Lihat Detail</a>
                </div>
              </div>
            </div>
            <div class="chart-wrapper px-2">
              <canvas id="chart_widget_2" height="100"></canvas>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
          <div class="card overflow-hidden">
            <div class="card-body px-4 py-4">
              <h5 class="mb-3">{{ $count_kasus }} <small class="text-primary">Kecelakaan lalu-lintas</small></h5>
              <div class="chart-point">
                <div class="check-point-area">
                  <canvas id="pie_chart_2"></canvas>
                </div>
                <ul class="chart-point-list">
                  <li><i class="fa fa-circle text-success mr-1"></i> {{ number_format($percent_done, 2) }}% Selesai</li>
                  <li><i class="fa fa-circle text-danger mr-1"></i> {{ number_format($percent_progress, 2) }}% Progress</li>
                </ul>
              </div>
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
                @if($log->status == 9)
                <!-- kode = 1 -->
                  <li>
                    <div class="timeline-badge primary"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Menambahkan data Kecelakaan lalu-lintas</h6> 
                      <p class="mb-0">{{ $log->no_lp }}</p>
                    </a>
                  </li>
                @elseif($log->status == 10)
                <!-- kode = 2 -->
                  <li>
                    <div class="timeline-badge info"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Update Data Kecelakaan lalu-lintas</h6>
                      <p class="mb-0">{{ $log->no_lp }}</p>
                    </a>
                  </li>
                @elseif($log->status == 11)
                <!-- kode = 3 -->
                  <li>
                    <div class="timeline-badge danger"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Edit Data Kecelakaan lalu-lintas</h6> 
                      <p class="mb-0">{{ $log->no_lp }}</p>
                    </a>
                  </li>
                @elseif($log->status == 12)
                <!-- kode = 4 -->
                  <li>
                    <div class="timeline-badge success"></div>
                    <a class="timeline-panel text-muted" href="#">{{ $log->name }}<span>{{ $log->age_of_data }}</span>
                      <h6 class="mb-0">Update anggaran Kecelakaan lalu-lintas</h6> 
                      <p class="mb-0">{{ $log->no_lp }}</p>
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
              <h4 class="card-title">Data Kecelakaan lalu-lintas Terbaru</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-responsive-sm mb-0">
                  <thead>
                    <tr>
                      <th><strong>No LP</strong>
                      </th>
                      <th><strong>SATKER</strong>
                      </th>
                      <th><strong>NAMA PETUGAS</strong>
                      </th>
                      <th><strong>WAKTU KEJADIAN</strong>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($traffic_accidents as $key=>$traffic_accident)
                    <tr>
                      <td><b>{{ $traffic_accident->no_lp }}</b></td>
                      <td>{{ $traffic_accident->satuan }}</td>
                      <td>{{ $traffic_accident->nama_petugas }}</td>
                      <td>{{Carbon\Carbon::parse($traffic_accident->date)->formatLocalized('%d %B %Y')}}, {{ $traffic_accident->time }}</td>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-sm-6">
          <div class="widget-stat card">
            <div class="card-body p-4">
              <div class="media ai-icon">	
                <span class="mr-3">
                      <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                      </svg>
                </span>
                <div class="media-body">
                  <p class="mb-1">Jumlah Korban Meninggal</p>
                  <h4 class="mb-0">{{$count_kasus_meninggal}}</h4>
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
                    <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <div class="media-body">
                  <p class="mb-1">Jumlah Korban Luka Berat</p>
                  <h4 class="mb-0">{{$count_kasus_sedang}}</h4>
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
                    <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <div class="media-body">
                  <p class="mb-1">Jumlah Korban Luka Ringan</p>
                  <h4 class="mb-0">{{$count_kasus_ringan}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- for admin -->
    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
    <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-primary text-white">
        <div class="card-header pb-0 border-0">
        <h4 class="card-title text-white">Kecelakaan lalu-lintas <label style="font-size: 12px; color: #72A0FE">berdasarkan Klasifikasi Kecelakaan</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus_klasifikasi as $key=>$top_klasifikasi)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top_klasifikasi->name }}</h5>  <small class="d-block">{{ $top_klasifikasi->total }} Kecelakaan Lalu Lintas</small>
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

    <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-primary text-white">
        <div class="card-header pb-0 border-0">
        <h4 class="card-title text-white">Kecelakaan lalu-lintas <label style="font-size: 12px; color: #72A0FE">berdasarkan Faktor Kecelakaan</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus_faktor as $key=>$top_faktor)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top_faktor->name }}</h5>  <small class="d-block">{{ $top_faktor->total }} Kecelakaan Lalu Lintas</small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <canvas id="lineChart_3Kk_1"></canvas>
      </div>
    </div>

    <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-primary text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Kecelakaan lalu-lintas <label style="font-size: 12px; color: #72A0FE">berdasarkan satker</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polda</h5>  
                    @foreach($top_kasus_satker_polda as $key=>$top_polda)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polda->name }} <label style="font-size: 12px; color: #72A0FE">({{$top_polda->total}} Kecelakaan lalu-lintas)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polres</h5>
                    @foreach($top_kasus_satker_polres as $key=>$top_polres)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polres->name }} <label style="font-size: 12px; color: #72A0FE">({{$top_polres->total}} Kecelakaan lalu-lintas)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polsek</h5>
                    @foreach($top_kasus_satker_polsek as $key=>$top_polsek)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polsek->name }} <label style="font-size: 12px; color: #72A0FE">({{$top_polsek->total}} Kecelakaan lalu-lintas)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <canvas id="lineChart_3Kk"></canvas>
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
            <h5 class="card-title">Lihat data Kecelakaan lalu-lintas</h5>
        </div>
        {!! Form::open(['method'=>'GET','url'=>'/lihat-data-laka','role'=>'search'])  !!}
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
                    <div class="col-sm-6" style="margin-bottom: 12px">
                        SATKER: <b>@if($satker_param){{ $satker_fr_param->name }} @else - @endif </b>
                    </div>
                    <div class="col-sm-6" style="margin-bottom: 12px">
                        Tahun: <b>{{ $tahun_param }}</b>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <!-- end filter -->
    </div>

    <div class="col-xl-4 col-lg-6 col-sm-6">
      <div class="widget-stat card">
        <div class="card-body p-4">
          <div class="media ai-icon">	
            <span class="mr-3">
                  <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
            </span>
            <div class="media-body">
              <p class="mb-1">Jumlah Korban Meninggal</p>
              <h4 class="mb-0">{{$count_kasus_meninggal_filter}}</h4>
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
                <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </span>
            <div class="media-body">
              <p class="mb-1">Jumlah Korban Luka Berat</p>
              <h4 class="mb-0">{{$count_kasus_sedang_filter}}</h4>
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
                <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </span>
            <div class="media-body">
              <p class="mb-1">Jumlah Korban Luka Ringan</p>
              <h4 class="mb-0">{{$count_kasus_ringan_filter}}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Card -->
    <!-- for admin -->
    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
    <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-success text-white">
        <div class="card-header pb-0 border-0">
        <h4 class="card-title text-white">Kecelakaan lalu-lintas <label style="font-size: 12px; color: #EBEEF6">berdasarkan Klasifikasi Kecelakaan</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus_klasifikasi_filter as $key=>$top_klasifikasi_filter)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top_klasifikasi_filter->name }}</h5>  <small class="d-block">{{ $top_klasifikasi_filter->total }} Kecelakaan Lalu Lintas</small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="chart-wrapper">
            <div id="chart_widget_1"></div>
        </div>
        <!-- <canvas id="lineChart_3Kk_2"></canvas> -->
      </div>
    </div>

    <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-success text-white">
        <div class="card-header pb-0 border-0">
        <h4 class="card-title text-white">Kecelakaan lalu-lintas <label style="font-size: 12px; color: #EBEEF6">berdasarkan Faktor Kecelakaan</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              @foreach($top_kasus_faktor_filter as $key=>$top_faktor_filter)
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">{{ $top_faktor_filter->name }}</h5>  <small class="d-block">{{ $top_faktor_filter->total }} Kecelakaan Lalu Lintas</small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="chart-wrapper">
            <div id="chart_widget_5"></div>
        </div>
        <!-- <canvas id="lineChart_3Kk_1"></canvas> -->
      </div>
    </div>

    <div class="col-xl-3 col-xxl-4 col-lg-12 col-md-12">
      <div class="card bg-success text-white">
        <div class="card-header pb-0 border-0">
          <h4 class="card-title text-white">Kecelakaan lalu-lintas Index <label style="font-size: 12px; color: #EBEEF6">berdasarkan satker</label></h4>
        </div>
        <div class="card-body">
          <div class="widget-media">
            <ul class="timeline">
              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polda</h5>  
                    @foreach($top_kasus_satker_polda_filter as $key=>$top_polda_filter)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polda_filter->name }} <label style="font-size: 12px; color: #EBEEF6">({{$top_polda_filter->total}} Kecelakaan lalu-lintas)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polres</h5>
                    @foreach($top_kasus_satker_polres_filter as $key=>$top_polres_filter)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polres_filter->name }} <label style="font-size: 12px; color: #EBEEF6">({{$top_polres_filter->total}} Kecelakaan lalu-lintas)</label></small>
                    @endforeach
                  </div>
                </div>
              </li>

              <li>
                <div class="timeline-panel">
                  <div class="media-body">
                    <h5 style="font-size: 14px" class="mb-1 text-white">Polsek</h5>
                    @foreach($top_kasus_satker_polsek_filter as $key=>$top_polsek_filter)
                    <small class="d-block">{{ $key + 1 }}. {{ $top_polsek_filter->name }} <label style="font-size: 12px; color: #EBEEF6">({{$top_polsek_filter->total}} Kecelakaan lalu-lintas)</label></small>
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
          <h5 class="card-title">Peta Persebaran Kecelakaan lalu-lintas Provinsi Sumatera Barat Tahun {{ $tahun_param }}</h5>
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
                <span style="text-decoration:none;" class="description-text">Total Kecelakaan lalu-lintas</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $count_kasus_selesai_f_map }}</h5>
                <a style="text-decoration:none;" href="{{URL::to('/laka-lantas/done?satker='.$satker_param.'&tahun='.$tahun_param)}}" target="_blank"><span class="description-text">Kecelakaan lalu-lintas Selesai </span><br><span style="font-size: 12px; color: #72A0FE;text-decoration:none;">klik here for detail</span></a>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $count_kasus_belum_f_map }}</h5>
                <a style="text-decoration:none;" href="{{URL::to('/laka-lantas/progress?satker='.$satker_param.'&tahun='.$tahun_param)}}" target="_blank"><span class="description-text">Kecelakaan lalu-lintas Progress </span><br><span style="font-size: 12px; color: #72A0FE;text-decoration:none;">klik here for detail</span></a>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-6">
              <div class="description-block">
                @if($count_kasus > 1)
                <h5 class="description-header">{{ number_format($percent_done_f_map) }}%</h5>
                @else
                <h5 class="description-header">0%</h5>
                @endif
                <span style="text-decoration:none;" class="description-text">Persentase Kecelakaan lalu-lintas Selesai</span>
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
          <h4 class="card-title">Kecelakaan lalu-lintas Data Tahun {{ $tahun_param }}</h4>
        </div>
        <div class="card-body">
          <canvas id="barChart_1"></canvas>
        </div>
      </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Kecelakaan lalu-lintas Data <label style="font-size: 12px; color: #72A0FE">berdasarkan waktu</label></h4>
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
          label: "Jumlah Kecelakaan lalu-lintas",
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

//pie chart total Kejahatan
if(jQuery('#pie_chart_2').length > 0 ){
  //pie chart
  const pie_chart = document.getElementById("pie_chart_2").getContext('2d');
  // pie_chart.height = 100;
  new Chart(pie_chart, {
    type: 'pie',
    data: {
      defaultFontFamily: 'Poppins',
      datasets: [{
          data: [{{ $count_kasus_selesai }}, {{ $count_kasus_belum }}],
          borderWidth: 0, 
          backgroundColor: [
              "rgb(41,200,112)",
              "rgb(242,87,103)"
          ],
          hoverBackgroundColor: [
              "rgb(41,200,112)",
              "rgb(242,87,103)"
          ]

      }],
      labels: [
          "done",
          "progress"
      ]
    },
    options: {
      responsive: true, 
      legend: false, 
      maintainAspectRatio: false
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
              label: "Kecelakaan lalu-lintas Selesai",
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
            label: "Jumlah Kecelakaan lalu-lintas",
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