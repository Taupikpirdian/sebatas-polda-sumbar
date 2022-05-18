@extends('layouts.app')
@section('content')

<style>
.form-control:focus {
  color: #495057;
  background-color: #fff;
  border-color: #80bdff;
  outline: 0;
  //box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
</style>

<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Limpah Perkara</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Limpah</a>
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

                    <div class="accordion" id="accordionExample">
                      <div class="card">
                        <div class="card-header" id="headingThree">
                          <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              Lihat Detail Data Perkara
                            </button>
                          </h2>
                        </div>
                        <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
                          <div class="card-body">
                          <form>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">User Input</label>
                                <input type="text" class="form-control" value=" {{{$perkara->user->name}}}"  disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">No LP</label>
                                <input type="text" class="form-control" value=" {{{$perkara->no_lp}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Tanggal LP</label>
                                <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($perkara->date_no_lp)->formatLocalized('%d %B %Y')}}"  disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feLastName">Satuan</label>
                                <input type="text" class="form-control" value="{{{$perkara->satuan->name}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Satuan Kerja (Satker)</label>
                                <input type="text" class="form-control" value="{{{$perkara->satker->name}}}"  disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feLastName">Divisi</label>
                                <input type="text" class="form-control" value="{{{$perkara->divisi}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="feLastName">Uraian Kejadian</label>
                                <textarea class="form-control" rows="3" disabled="">{{{$perkara->uraian}}}</textarea>
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="feLastName">Modus Operasi</label>
                                <textarea class="form-control" rows="3" disabled="">{{{$perkara->modus_operasi}}}</textarea>
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Nama Petugas</label>
                                <input type="text" class="form-control" value="{{{$perkara->nama_petugas}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Pangkat</label>
                                <input type="text" class="form-control" value="{{{$perkara->pangkat}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">No Telepon</label>
                                <input type="text" class="form-control" value="{{{$perkara->no_tlp}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Jenis Pidana</label>
                                <input type="text" class="form-control" value="{{{$perkara->pidana->name}}}"  disabled> 
                              </div>
                            </div>

                            @if($perkara->jenis_pidana == '32')
                              <div class="form-row">
                              @if($perkara->jenis_narkoba)
                                <div class="form-group col-md-6">
                                  <label for="feLastName">Jenis Narkoba</label>
                                  <input type="text" class="form-control" value="{{{$perkara->jenis_narkoba->name}}}" disabled> 
                                </div>
                              @else
                                <div class="form-group col-md-6">
                                  <label for="feLastName">Jenis Narkoba</label>
                                  <input style="color: red" type="text" class="form-control" value="Belum ada data" disabled> 
                                </div>
                              @endif
                              @if($perkara->qty)
                                <div class="form-group col-md-6">
                                  <label for="feFirstName">Berat (gram)</label>
                                  <input type="text" class="form-control" value="{{{$perkara->qty}}}"  disabled> 
                                </div>
                              @else
                                <div class="form-group col-md-6">
                                  <label for="feFirstName">Berat (gram)</label>
                                  <input style="color: red" type="text" class="form-control" value="Belum ada data" disabled> 
                                </div>
                              @endif
                              </div>
                            @endif

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Tanggal Kejadian</label>
                                <input type="text" class="form-control" value=" {{Carbon\Carbon::parse($perkara->date)->formatLocalized('%d %B %Y')}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Waktu Kejadian</label>
                                <input type="text" class="form-control" value="{{{$perkara->time}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Nama Korban</label>
                                <input type="text" class="form-control" value="{{{$perkara->korban->nama}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feLastName">Umur Korban</label>
                                <input type="text" class="form-control" value="{{{$perkara->korban->umur_korban}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Pendidikan Korban</label>
                                <input type="text" class="form-control" value="{{{$perkara->korban->pendidikan_korban}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feLastName">Pekerjaan Korban</label>
                                <input type="text" class="form-control" value="{{{$perkara->korban->pekerjaan_korban}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Asal Korban</label>
                                <input type="text" class="form-control" value="{{{$perkara->korban->asal_korban}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Saksi</label>
                                <input type="text" class="form-control" value="{{{$perkara->korban->saksi}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="feLastName">Pelaku</label>
                                <textarea class="form-control" rows="3" disabled="">{{{$perkara->korban->pelaku}}}</textarea>
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="feLastName">Barang Bukti</label>
                                <textarea class="form-control" rows="3" disabled="">{{{$perkara->korban->barang_bukti}}}</textarea>
                              </div>
                            </div>

                          </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    {!! Form::model($perkara,['files'=>true,'method'=>'put','action'=>['admin\PerkaraController@limpahPerkaraPost',$perkara->id]]) !!}
                      <div class="accordion" id="accordionExample">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Limpah Perkara
                              </button>
                            </h2>
                          </div>
                          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                              <p style="color: red">Sebelum melakukan proses "LIMPAH PERKARA" harap periksa detail data perkara supaya tidak terjadi kesalahan, Terimakasih.</p>
                                <div class="form-group">
                                    <label for="divisi">Pilih Divisi:</label>
                                    <select name="divisi" class="form-control">
                                        <option value="">--- Pilih Divisi ---</option>
                                        @foreach ($divisis as $key => $divisi)
                                        <option value="{{ $key }}">{{ $divisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="satker">--- Pilih Satuan Kerja (Satker) ---</label>
                                    <select name="satker" id="satker" class="form-control">
                                        <option>--- Pilih Satuan Kerja ---</option>
                                    </select>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <a title="Kembali" style="margin-right:10px;" href="{{ url()->previous() }}" class="btn btn-sm btn-info float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>   Kembali</a>
                      <button type="submit" class="btn btn-primary">Submit</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
<script type="text/javascript">
  jQuery(document).ready(function ()
  {
    jQuery('select[name="divisi"]').on('change',function(){
        var divisi = jQuery(this).val();
        if(divisi)
        {
          jQuery.ajax({
              url : '/dropdown-list/limpah-perkara/' +divisi,
              type : "GET",
              dataType : "json",
              success:function(data)
              {
                console.log(data);
                jQuery('select[name="satker"]').empty();
                jQuery.each(data, function(key,value){
                    $('#satker').append('<option value="'+ value +'">'+ value +'</option>');
                });
              }
          });
        }
        else
        {
          $('select[name="satker"]').empty();
        }
    });
  });
</script>
@endsection