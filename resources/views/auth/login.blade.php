@extends('layouts.login')

@section('content')
<div class="authincation h-100">
		<div class="container h-100">
				<div class="row justify-content-center h-100 align-items-center">
						<div class="col-md-6">
								<div class="authincation-content">
										<div class="row no-gutters">
												<div class="col-xl-12">
														<div class="auth-form">
																<h4 class="text-center mb-4">Analisis Pemetaan Kejahatan</h4>
																<div class="d-flex justify-content-center">
																	<img style="align: center; width: 50%" src="asset/images/maskot.png" alt="IMG">
																</div>
																<form method="POST" action="{{ route('login') }}">
								                {{ csrf_field() }}
																		<div class="form-group">
																				<label class="mb-1"><strong>Email</strong></label>
																				<input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
																				@if ($errors->has('email'))
																						<span class="help-block">
																								<strong>{{ $errors->first('email') }}</strong>
																						</span>
																				@endif
																		</div>

																		<div class="form-group">
																				<label class="mb-1"><strong>Password</strong></label>
																				<input type="password" class="form-control" name="password" placeholder="Password" required>
																				@if ($errors->has('password'))
																						<span class="help-block">
																								<strong>{{ $errors->first('password') }}</strong>
																						</span>
																				@endif
																		</div>

																		<div class="text-center">
																				<button type="submit" class="btn btn-primary btn-block">Masuk</button>
																		</div>
																</form>
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</div>

@endsection
