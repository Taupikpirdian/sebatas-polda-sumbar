@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Detail Pengaduan Masyarakat</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <div class="profile-tab">
            <div class="custom-tab-1">
              <div class="tab-content">
                <div id="profile-settings" class="tab-pane fade active show">
                  <div class="pt-3">
                    <div class="settings-form">
                      <form>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">User Input</label>
                            <input type="text" class="form-control" value=" {{{$lapor->user->name}}}"  disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">No STPLP</label>
                            <input type="text" class="form-control" value=" {{{$lapor->no_stplp}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Tanggal LP</label>
                            <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($lapor->date_no_lp)->formatLocalized('%d %B %Y')}}"  disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Satuan</label>
                            <input type="text" class="form-control" value="{{{$lapor->satuan->name}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Satuan Kerja (Satker)</label>
                            <input type="text" class="form-control" value="{{{$lapor->satker->name}}}"  disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Divisi</label>
                            <input type="text" class="form-control" value="{{{$lapor->divisi}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Uraian Kejadian</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$lapor->uraian}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Modus Operasi</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$lapor->modus_operasi}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Nama Petugas</label>
                            <input type="text" class="form-control" value="{{{$lapor->nama_petugas}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">Pangkat</label>
                            <input type="text" class="form-control" value="{{{$lapor->pangkat}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">No Telepon</label>
                            <input type="text" class="form-control" value="{{{$lapor->no_tlp}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">Jenis Pidana</label>
                            <input type="text" class="form-control" value="{{{$lapor->pidana->name}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Tanggal Kejadian</label>
                            <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($lapor->date)->formatLocalized('%d %B %Y')}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">Waktu Kejadian</label>
                            <input type="text" class="form-control" value="{{{$lapor->time}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Nama Pelapor</label>
                            <input type="text" class="form-control" value="{{{$lapor->nama_pelapor}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Umur Pelapor</label>
                            <input type="text" class="form-control" value="{{{$lapor->umur_pelapor}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Pendidikan Pelapor</label>
                            <input type="text" class="form-control" value="{{{$lapor->pendidikan_pelapor}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Pekerjaan Pelapor</label>
                            <input type="text" class="form-control" value="{{{$lapor->pekerjaan_pelapor}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Asal Pelapor</label>
                            <input type="text" class="form-control" value="{{{$lapor->asal_pelapor}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">Saksi</label>
                            <input type="text" class="form-control" value="{{{$lapor->saksi}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Terlapor</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$lapor->terlapor}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Barang Bukti</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$lapor->barang_bukti}}}</textarea>
                          </div>
                        </div>

                      </form>
                      <a title="Kembali" href="{{ url()->previous() }}" class="btn btn-sm btn-info float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>   Kembali</a>
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
@endsection