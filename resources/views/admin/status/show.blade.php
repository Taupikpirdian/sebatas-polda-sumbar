@extends('admin.admin')

@section('content')
     <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
              <span class="text-uppercase page-subtitle"> <a href="{{URL::to('/user/index')}}"><i class="fa fa-dashboard"></i> Kembali</a></span>
                <h3 class="page-title">Data User</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Default Light Table -->
            <div class="row">
              <div class="col-lg-4">
                <div class="card card-small mb-4 pt-3">
                  <div class="card-header border-bottom text-center">
                    <div class="mb-3 mx-auto">
                      <!-- <img class="rounded-circle" src="{{URL::asset('images/siswa/thumbs/'.$user->foto)}}" alt="User Avatar" width="220" height="220"> </div> -->
                      <img class="rounded-circle" src="{{URL::asset('images/dummy/user.png')}}" alt="User Avatar" width="220" height="220"> </div>
                    <h4 class="mb-0">{{ $user->name }}</h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Data Lengkap</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Email</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$user->email}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Akses</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$user->group_name}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Password</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$user->password}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Tanggal Pembuatan Akun</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$user->created_at}}}" disabled> 
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- End Default Light Table -->
          </div>
@endsection