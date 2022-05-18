@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Update Status Kecelakaan</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Status Kecelakaan</a>
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
                      {!! Form::model($traffic_accident,['files'=>true,'method'=>'put','action'=>['admin\LakaLantasController@updateStatus',$traffic_accident->id]]) !!}
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">User Input</label>
                            <input type="text" class="form-control" value=" {{{$traffic_accident->user->name}}}"  disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">No LP</label>
                            <input type="text" class="form-control" value=" {{{$traffic_accident->no_lp}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Tanggal LP</label>
                            <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($traffic_accident->date_no_lp)->formatLocalized('%d %B %Y')}}"  disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Satuan</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->satuan->name}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Satuan Kerja (Satker)</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->satker->name}}}"  disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Divisi</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->divisi}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Uraian Kejadian</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$traffic_accident->uraian}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Kerugian Material</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$traffic_accident->kerugian_material}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Nama Petugas</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->nama_petugas}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">Pangkat</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->pangkat}}}"  disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">No Telepon</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->no_tlp}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Tanggal Kejadian</label>
                            <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($traffic_accident->date)->formatLocalized('%d %B %Y')}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feFirstName">Waktu Kejadian</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->time}}}"  disabled> 
                          </div>
                        </div>
                        <br>
                        @foreach($traffic_accident->korbans as $key=>$korban)
                        <h6>Data Korban {{ $key + 1 }}</h6>
                        <hr>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Nama Korban</label>
                            <input type="text" class="form-control" value="{{{$korban->nama}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Umur Korban</label>
                            <input type="text" class="form-control" value="{{{$korban->umur}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feLastName">Pendidikan Korban</label>
                            <input type="text" class="form-control" value="{{{$korban->pendidikan}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Pekerjaan Korban</label>
                            <input type="text" class="form-control" value="{{{$korban->pekerjaan}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Asal Korban</label>
                            <input type="text" class="form-control" value="{{{$korban->asal}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Kondisi Korban</label>
                            <input type="text" class="form-control" value="{{{$korban->kondisi->name}}}" disabled> 
                          </div>
                        </div>
                        <br>
                        @endforeach

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feFirstName">Saksi</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$traffic_accident->saksi}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Barang Bukti</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$traffic_accident->barang_bukti}}}</textarea>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Faktor Kecelakaan</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->faktor->name}}}" disabled> 
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Klasifikasi Kecelakaan</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->klasifikasi->name}}}" disabled> 
                          </div>
                        </div>

                        <!-- update status kecelakaan -->
                        <div class="form-group">
                          <label for="name">Tanggal Surat Sprint Penyidik</label>
                          <input type="date" class="form-control" id="tanggal_surat_sprint_penyidik" name="tanggal_surat_sprint_penyidik" placeholder="Masukan Tanggal" value="{{old('tanggal_surat_sprint_penyidik')}}"  required>
                        </div>
                        
                        <div class="form-group">
                          <label>Status</label>
                          {{ Form::select('status', $status, null,['class' => 'form-control', 'required' => 'required']) }}
                        </div>

                        <div class="form-group">
                          <label for="name">Dokumen</label>
                          <input type="file" class="form-control" id="dokumen" name="dokumen" placeholder="Masukan Dokumen Update Status Perkara">
                          <p style="color: red; font-size:13">Note: Hanya File PDF dengan ukuran maksimal 5 Mb</p>
                          
                        </div>

                        <div class="form-group">
                          <label for="name">Tanggal Dokumen</label>
                          <input type="date" class="form-control" id="tgl_doc" name="tgl_doc" placeholder="Masukan Tanggal Update Status Perkara" value="{{old('tgl_doc')}}"  required>
                        </div>

                        <div class="form-group">
                          <label>Keterangan</label>
                          <textarea class="form-control" rows="3" name="keterangan" placeholder="Masukan Keterangan Update Status Perkara" required>{{Request::old('keterangan')}}</textarea>
                        </div>

                        <div class="card-footer">
                          <a title="Kembali" href="{{ url()->previous() }}" style="margin-right:20px;" class="btn btn-sm btn-info float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>   Kembali</a>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      {!! Form::close() !!}
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