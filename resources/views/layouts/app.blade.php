<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">

  <title>Dashboard Aplikasi Sebaran Data Kriminalitas</title>
  <!-- Style -->
  @include('includes.style')
	@livewireStyles
	@yield('css')
  <!-- / Style -->
</head>
<body>
	<div id="preloader">
		<div class="sk-three-bounce">
			<div class="sk-child sk-bounce1"></div>
			<div class="sk-child sk-bounce2"></div>
			<div class="sk-child sk-bounce3"></div>
		</div>
	</div>

	<!-- Modal -->
  <div class="modal fade" id="modal-default">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">PERHATIAN!</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Apakah anda akan mengakses data periode LP sebelum tahun {{ $now->year }}?</p>
              </div>
              <div class="modal-footer">
									<a href="{{URL::to('/perkara/last-year')}}" class="btn btn-danger light">Ya, sebelum tahun {{ $now->year }}</a>
									<a href="{{URL::to('/perkara/this-year')}}" class="btn btn-primary">Tidak, tahun ini</a>
              </div>
          </div>
      </div>
  </div>

	<!-- Modal Lapor -->
  <div class="modal fade" id="modal-lapor">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">PERHATIAN!</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Apakah anda akan mengakses data Lapor sebelum tahun {{ $now->year }}?</p>
              </div>
              <div class="modal-footer">
									<a href="{{URL::to('/lapor/last-year')}}" class="btn btn-danger light">Ya, sebelum tahun {{ $now->year }}</a>
									<a href="{{URL::to('/lapor/this-year')}}" class="btn btn-primary">Tidak, tahun ini</a>
              </div>
          </div>
      </div>
  </div>

	<!-- Modal Laka Lantas -->
  <div class="modal fade" id="modal-laka-lantas">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">PERHATIAN!</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Apakah anda akan mengakses data Laka Lantas sebelum tahun {{ $now->year }}?</p>
              </div>
              <div class="modal-footer">
									<a href="{{URL::to('/laka-lantas/last-year')}}" class="btn btn-danger light">Ya, sebelum tahun {{ $now->year }}</a>
									<a href="{{URL::to('/laka-lantas/this-year')}}" class="btn btn-primary">Tidak, tahun ini</a>
              </div>
          </div>
      </div>
  </div>

	<!-- Modal Polda -->
	<div class="modal fade" id="modal-polda">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">PERHATIAN!</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                  </button>
              </div>
			        {!! Form::open(['method'=>'GET','url'=>'/rekapitulasi-polda','role'=>'search'])  !!}
              <div class="modal-body">
                  <p>Pilih Divisi Polda</p>
									<div class="col-sm-12" style="margin-bottom: 12px">
										<select name="divisi" id="divisi" required>
											<option value="">Pilih Divisi</option>
											<option value="1">Ditresnarkoba</option>
											<option value="179">Ditreskrimsus</option>
											<option value="171">Ditreskrimum</option>
										</select>
									</div>
              </div>
              <div class="modal-footer">
				          <button type="submit" class="btn btn-success light">Lihat</button>
									<a href="#" class="btn btn-primary" data-dismiss="modal">Cancel</a>
              </div>
			        {!! Form::close() !!}
          </div>
      </div>
  </div>

	<!-- Modal Polres -->
	<div class="modal fade" id="modal-polres">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">PERHATIAN!</h5>
                  <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                  </button>
              </div>
			        {!! Form::open(['method'=>'GET','url'=>'/lihat-polres','role'=>'search'])  !!}
              <div class="modal-body">
								<div class="col-sm-12">
									<label>Pilih Polres</label>
									<select name="polres" id="polres" required>
										<option value="">Pilih Polres</option>
										@foreach($polres_list as $key=>$polres)
										<option value="{{ $polres->id }}">{{ $polres->name }}</option>
										@endforeach
									</select>
								</div>
								<hr>
								<div class="col-sm-12">
									<label>Pilih Divisi Polres</label>
									<select name="divisi" id="divisi2" required>
										<option value="">Pilih Divisi</option>
										<option value="Satnarkoba">Satnarkoba</option>
										<option value="Satreskrim">Satreskrim</option>
									</select>
								</div>
              </div>
              <div class="modal-footer">
				          <button type="submit" class="btn btn-success light">Lihat</button>
									<a href="#" class="btn btn-primary" data-dismiss="modal">Cancel</a>
              </div>
			        {!! Form::close() !!}
          </div>
      </div>
  </div>

	<div id="main-wrapper">
		<div class="nav-header">
			@if(Auth::user()->groups()->where("name", "=", "Admin")->first() || Auth::user()->divisi == 'Ditreskrimsus' || Auth::user()->divisi == 'Ditreskrimum' || Auth::user()->divisi == 'Ditresnarkoba' || Auth::user()->divisi == 'Satreskrim' || Auth::user()->divisi == 'Satnarkoba' || Auth::user()->divisi == 'Unit Reskrim')
			<a href="{{URL::to('/')}}" class="brand-logo">
				<img class="logo-abbr" src="{{asset('asset/images/polda_sumbar1.png')}}" alt="">
				<img class="logo-compact" src="{{asset('asset/images/logo-text.png')}}" alt="">
				<img class="brand-title" src="{{asset('asset/images/logo-text.png')}}" alt="">
			</a>
			@elseif(Auth::user()->groups()->where("name", "=", "Admin")->first() || Auth::user()->divisi == 'Ditlantas' || Auth::user()->divisi == 'Satlantas')
			<a href="{{URL::to('/dashboard/laka-lantas')}}" class="brand-logo">
				<img class="logo-abbr" src="{{asset('asset/images/polda_sumbar1.png')}}" alt="">
				<img class="logo-compact" src="{{asset('asset/images/logo-text.png')}}" alt="">
				<img class="brand-title" src="{{asset('asset/images/logo-text.png')}}" alt="">
			</a>
			@endif
			<div class="nav-control">
				<div class="hamburger"> <span class="line"></span><span class="line"></span><span class="line"></span>
				</div>
			</div>
		</div>

    <!-- Main Sidebar Container -->
    @include('includes.sidebar')
    <!-- / Main Sidebar Container -->

		<!-- Navbar -->
    @include('includes.navbar')
    <!-- /.navbar -->

		<div class="content-body">
			<!-- content -->
			<!-- Alert here -->
			@if(Session::has('flash-success'))
			<div id="alert-success" class="alert alert-success alert-dismissible fade show" style="position: absolute; right: 0px">
				<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
				<strong>Berhasil!</strong> {{Session::get('flash-success')}}.
				<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
				</button>
			</div>
			@endif
			@if(Session::has('flash-update'))
			<div id="alert-update" class="alert alert-info alert-dismissible fade show" style="position: absolute; right: 0px; margin-top:5px; width: 40%">
				<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
				<strong>Berhasil!</strong> {{Session::get('flash-update')}}.
				<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
					<span><i class="mdi mdi-close"></i></span>
				</button>
			</div>
			@endif
			@if(Session::has('flash-danger'))
			<div id="alert-danger" class="alert alert-danger alert-dismissible fade show" style="position: absolute; right: 0px">
				<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
				<strong>Error!</strong> {{Session::get('flash-danger')}}.
				<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
				</button>
			</div>
			@endif
			@error('dokumen')
				<div id="alert-danger" class="alert alert-danger alert-dismissible fade show" style="position: absolute; right: 0px">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
					<strong>Error!</strong> {{ $message }}.
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
					</button>
				</div>
			@enderror
			<!-- Alert end -->
      @yield('content')
      <!-- /.content -->
		</div>
    
		<div class="footer">
			<div class="copyright">
				<p>Copyright © Designed &amp; Developed by AgoraDev 2020</p>
			</div>
		</div>
	</div>
  <!-- Script -->
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>

	@livewireScripts
  @include('includes.script')
  <!-- / Script -->
  @include('sweet::alert')
	<!-- bagian sidebar -->
	<script>
	setTimeout(function() {
			$('#alert-success').fadeOut('fast');
	}, 7000);

	setTimeout(function() {
			$('#alert-update').fadeOut('fast');
	}, 7000);

	setTimeout(function() {
			$('#alert-danger').fadeOut('fast');
	}, 7000);
	</script>
	<script>
	// select2
	$("#divisi").select2();
	$("#divisi2").select2();
	$("#polres").select2();

	if(jQuery('#ShareProfit').length > 0 ){
		//doughut chart
		const ShareProfit = document.getElementById("ShareProfit").getContext('2d');
		// ShareProfit.height = 100;
		new Chart(ShareProfit, {
			type: 'doughnut',
			data: {
				defaultFontFamily: 'Poppins',
				datasets: [{
					data: [10, 25, 20],
					borderWidth: 3, 
					borderColor: "rgba(255, 243, 224, 1)",
					backgroundColor: [
						"rgba(58, 122, 254, 1)",
						"rgba(255, 159, 0, 1)",
						"rgba(41, 200, 112, 1)"
					],
					hoverBackgroundColor: [
						"rgba(58, 122, 254, 0.9)",
						"rgba(255, 159, 0, .9)",
						"rgba(41, 200, 112, .9)"
					]

				}],
				
			},
			options: {
				weight: 1,	
				 cutoutPercentage: 65,
				responsive: true,
				maintainAspectRatio: false
			}
		});
	}
	</script>
</body>
</html>
