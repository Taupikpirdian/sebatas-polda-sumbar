@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Detail Laka Lantas</h4>
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
  <!-- Modal -->
  <div class="modal fade" id="modal-upload-pdf">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Dokumen Update Status Kecelakaan!</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            {!! Form::model($traffic_accident,['files'=>true,'method'=>'put','action'=>['admin\LakaLantasController@uploadPdf',$traffic_accident->id]]) !!}
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Tanggal Surat Sprint Penyidik</label>
                <input type="date" class="form-control" id="tanggal_surat_sprint_penyidik" name="tanggal_surat_sprint_penyidik" placeholder="Masukan Tanggal" value="{{old('tanggal_surat_sprint_penyidik')}}"  required>
              </div>
              <div class="form-group">
                <label for="name">Dokumen</label>
                <input type="file" class="form-control" id="dokumen" name="dokumen" placeholder="Masukan Dokumen Update Status Kecelakaan" required>
                <p style="color: red; font-size:13">Note: Hanya File PDF dengan ukuran maksimal 5 Mb</p>
              </div>
              <div class="form-group">
                <label for="name">Tanggal Dokumen</label>
                <input type="date" class="form-control" id="tgl_doc" name="tgl_doc" placeholder="Masukan Tanggal Update Status Kecelakaan" value="{{old('tgl_doc')}}"  required>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="3" name="keterangan" placeholder="Masukan Keterangan Update Status Kecelakaan" required>{{Request::old('keterangan')}}</textarea>
              </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
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
                        @forelse($traffic_accident->korbans as $key=>$korban)
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
                        @empty
                        <h6>Data Korban</h6>
                        <p>Tidak ada data korban</p>
                        @endforelse

                        @if($traffic_accident->saksi)
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feFirstName">Data Saksi</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$traffic_accident->saksi}}}</textarea>
                          </div>
                        </div>
                        @else
                        <h6>Data Saksi</h6>
                        <p>Tidak ada data saksi</p>
                        @endif

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

                        <!-- update bukti -->
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Tanggal Surat Sprint Penyidik</label>
                            @if($traffic_accident->tanggal_surat_sprint_penyidik != null)
                            <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($traffic_accident->tanggal_surat_sprint_penyidik)->formatLocalized('%d %B %Y')}}"  disabled> 
                            @else
                            <input type="text" class="form-control" value="-"  disabled> 
                            @endif
                          </div>

                          <div class="form-group col-md-6">
                            <label for="feLastName">Status</label>
                            <input type="text" class="form-control" value="{{{$traffic_accident->status->name}}}" disabled> 
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="feFirstName">Tanggal Update Status</label>
                            @if($traffic_accident->tgl_document != null)
                            <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($traffic_accident->tgl_document)->formatLocalized('%d %B %Y')}}"  disabled> 
                            @else
                            <input type="text" class="form-control" value="-"  disabled> 
                            @endif
                          </div>

                          <div class="form-group @if($traffic_accident->status->name == "Progress") col-md-6 @elseif($traffic_accident->document == null) col-md-6 @else col-md-4 @endif">
                            <label for="feLastName">Dokumen Update Status</label>
                            @if($traffic_accident->status->name == "Progress")
                            <a class="form-control" style=" color: red" href="#">Dokumen belum ada <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                            @else
                              @if($traffic_accident->document)
                              <a class="form-control" target="_blank" style=" color: #00AFEF" href="{{route('show-pdf-laka-lantas', $traffic_accident->id)}}">View Dokumen <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                              @else
                              <a class="form-control" style="color: #00AFEF" href="#" data-toggle="modal" data-target="#modal-upload-pdf" class="ai-icon" aria-expanded="false"><label style="color: red">Tidak ada dokumen</label>, Upload Dokumen?</a>
                              @endif
                            @endif
                          </div>

                          @if($traffic_accident->status->name == "Progress")
                          @else
                            @if($traffic_accident->document)
                            <div class="form-group col-md-2">
                              <label for="feLastName">Ubah PDF</label>
                              <a class="form-control" style="color: #00AFEF" href="#" data-toggle="modal" data-target="#modal-upload-pdf" class="ai-icon" aria-expanded="false"><i class="fa fa-upload" aria-hidden="true"></i></a>
                            </div>
                            @endif
                          @endif
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="feLastName">Keterangan Update Status</label>
                            <textarea class="form-control" rows="3" disabled="">{{{$traffic_accident->keterangan}}}</textarea>
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