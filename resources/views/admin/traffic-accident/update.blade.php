@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Edit Data Laka Lantas</h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Data Laka Lantas</a></li>
      </ol>
    </div>
  </div>
</div><!-- /.container-fluid -->

<!-- Main content -->
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-12 p-md-0">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Edit Data Laka Lantas</h3>
        </div>
        <!-- update-status -->
        {!! Form::model($traffic_accident,['files'=>true,'method'=>'put','action'=>['admin\LakaLantasController@updated',$traffic_accident->id]]) !!}
          <div class="card-body">

            <div class="form-group">
              <label for="name">Nomer LP</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer STPLP" value="{{$traffic_accident->no_lp}}">
              @error('no_lp')
                <div class="col-md-5 input-group mb-1" style="color:red">
                    {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="date">Tanggal Nomer LP</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="date" class="form-control" id="date_no_lp" name="date_no_lp" value="{{$traffic_accident->date_no_lp}}" required>
            </div>

            @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
            <div class="form-group">
              <label>Satker</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              {{ Form::select('satker', $satker, $traffic_accident->kategori_bagian_id,['class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true']) }}
              <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
            </div>

            <div class="form-group">
              <label>Divisi</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <div class="input-group">
                <select class="form-control selectpicker" name="divisi" data-live-search="true" required>
                    <option value="">=== Pilih Salah Satu ===</option>
                    <option value="Ditlantas" @if($traffic_accident->divisi == 'Ditlantas') selected @endif>Ditlantas</option>
                    <option value="Satlantas" @if($traffic_accident->divisi == 'Satlantas') selected @endif>Satlantas</option>
                </select>
              </div>
            </div>
            @else
            <div class="form-group">
              <label>Satker</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              {{ Form::select('satker', $satker, $traffic_accident->kategori_bagian_id,['class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true']) }}
              <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
            </div>
            @endif

            <div class="form-group">
              <label>Uraian Singkat Kejadian</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <textarea class="form-control" rows="3" name="uraian" placeholder="Masukan Uraian Singkat Kejadian">{{ $traffic_accident->uraian }}</textarea>
            </div>

            <div class="form-group">
              <label>Kerugian Material</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <textarea class="form-control" rows="3" name="kerugian_material" placeholder="Masukan Kerugian Material">{{ $traffic_accident->kerugian_material }}</textarea>
            </div>

            <div class="form-group">
              <label for="nama_petugas">Nama Petugas</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" placeholder="Masukan Nama Petugas" value="{{ $traffic_accident->nama_petugas }}" required>
            </div>

            <div class="form-group">
              <label for="pangkat">Pangkat dan NRP</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="pangkat" name="pangkat" placeholder="Masukan Pangkat dan NRP" value="{{ $traffic_accident->pangkat }}" required>
            </div>

            <div class="form-group">
              <label for="no_tlp">No Telphone</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="no_tlp" name="no_tlp" placeholder="Masukan No Telphone Petugas" value="{{ $traffic_accident->no_tlp }}" required>
            </div>

            <div class="form-group input_fields_container_part_2">
                <label for="saksi">Korban</label>
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
                <p style="color: red; font-size:13">Note: Klik tombol "Tambah Korban" jika ingin mengubah data</p>
                @empty
                <p>Tidak ada data korban</p>
                <p style="color: red; font-size:13">Note: Klik tombol "Tambah Korban" jika ingin menambahkan data</p>
                @endforelse
                <button class="btn btn-warning add_more_button_2" style="margin-bottom: 6px" title="Tambah data korban"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Korban</button>
            </div>

            <div>
              <label for="saksi">Saksi</label>
              @if($traffic_accident->saksi)
              <input type="text" class="form-control" value="{{{$traffic_accident->saksi}}}" disabled>
              @else
              <p>Tidak ada data saksi</p>
              @endif 
              <p style="color: red; font-size:13">Note: Klik tombol "Tambah Saksi" jika ingin mengubah data</p>
            </div>

            <div class="form-group input_fields_container_part">
                <div class="btn btn-warning add_more_button" style="margin-bottom: 6px" title="Tambah data saksi"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Saksi</div>
            </div>

            <div class="form-group">
              <label for="name">Barang Bukti</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="barang_bukti" name="barang_bukti" placeholder="Masukan Barang Bukti" value="{{{$traffic_accident->barang_bukti}}}" required>
              @error('barang_bukti')
                <div class="col-md-5 input-group mb-1" style="color:red">
                    {{ $message }}
                </div>
              @enderror
            </div>

            <br>
            <br>

            <div id="map_create" style="width:100%;height:380px;"></div>
            <p style="color: red; font-size:13">Note: Klik lokasi yang diinginkan pada maps untuk mendapatkan titik koordinat (Akan secara otomatis terisi pada form lat dan long)</p>

            <div class="form-group">
              <label for="lat">Latitude</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="lat" placeholder="Masukan Latitude" value="{{ $traffic_accident->lat }}" disabled>
            </div>

            <div class="form-group">
              <label for="long">Longitude</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="text" class="form-control" id="long" placeholder="Masukan Longitude" value="{{ $traffic_accident->long }}" disabled>
            </div>

            <div class="form-group" style="display: none;">
              <label for="lat">Latitude</label>
              <input type="text" class="form-control" name="lat" id="latInput" placeholder="Masukan Latitude" value="{{ $traffic_accident->lat }}" required>
            </div>

            <div class="form-group" style="display: none;">
              <label for="long">Longitude</label>
              <input type="text" class="form-control" name="long" id="longInput" placeholder="Masukan Longitude" value="{{ $traffic_accident->long }}" required>
            </div>

            <div class="form-group">
              <label for="date">Tanggal Kecelakaan</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <input type="date" class="form-control" id="date" name="date" placeholder="Masukan Tanggal" value="{{ $traffic_accident->date }}" required>
            </div>

            <div class="form-group">
              <label>Waktu Kecelakaan</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                <input type="text" class="form-control" id="time" name="time" placeholder="Masukan Waktu Kecelakaan" value="{{ $traffic_accident->time }}" required> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
              </div>
            </div>

            <div class="form-group">
              <label>Faktor Kecelakaan</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              {{ Form::select('faktor_id', $faktors, null,['class' => 'form-control', 'required' => 'required']) }}
            </div>

            <div class="form-group">
              <label>Klasifikasi Kecelakaan</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
              {{ Form::select('klasifikasi_id', $klasifikasi, null,['class' => 'form-control', 'required' => 'required']) }}
            </div>

            @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
              @if($traffic_accident->status_id != '1')
              <div class="form-group">
                <label>Status</label>
                {{ Form::select('status', $status, $traffic_accident->status_id,['class' => 'form-control selectpicker', 'required' => 'required']) }}
              </div>
              @endif
            @endif
            <!-- /.card-body -->
            <div class="card-footer">
              <a title="Kembali" href="{{ url()->previous() }}" style="margin-right:20px;" class="btn btn-sm btn-info float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>   Kembali</a>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        {!! Form::close() !!}

      </div>
    </div>
    <!-- /.col -->
  </div>
</div>
  <!-- /.row -->
</section>
@endsection
@section('js')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYiwleTNi8Ww0Un6Jna9LuQyWGvdFYEcI&callback=initMapStore"
async defer></script>
<script>
function initMapStore(){
    // Map options
    var options = {
        zoom:8,
        center:{lat:-0.528119,lng:100.538150}
    }

    // New Map
    var peta = new 
    google.maps.Map(document.getElementById('map_create'), options);

    google.maps.event.addListener(peta, 'click', function( event ){
      // alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() );
      
      var lat = function() {
        return event.latLng.lat();    
      };
      var long = function() {
        return event.latLng.lng();    
      };
      document.getElementById('lat').value = lat();
      document.getElementById('long').value = long();
      document.getElementById('latInput').value = lat();
      document.getElementById('longInput').value = long();

    });
}
</script>

<script>
$(document).ready(function() {
    var max_fields_limit      = 20; //set limit for maximum input fields
    var x = 1; //initialize counter for text box
    $('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
        e.preventDefault();
        if(x < max_fields_limit){ //check conditions
            x++; //counter increment
            $('.input_fields_container_part').append('<div><input type="text" class="form-control" name="saksi[]" placeholder="Masukan Nama Saksi Lain" required><a href="#" title="Hapus data saksi" class="btn btn-danger remove_field" style="margin-top:6px; margin-bottom:6px"><i class="fa fa-trash" aria-hidden="true"></i></a></div>'); //add input field
        }
    });  
    $('.input_fields_container_part').on("click",".remove_field", function(e){ //user click on remove text links
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});

$(document).ready(function() {
    var max_fields_limit      = 8; //set limit for maximum input fields
    var x = 1; //initialize counter for text box
    $('.add_more_button_2').click(function(e){ //click event on add more fields button having class add_more_button
        e.preventDefault();
        if(x < max_fields_limit){ //check conditions
            x++; //counter increment
            $('.input_fields_container_part_2').append('<div><input type="text" class="form-control" name="korban[][nama]" placeholder="Masukan Nama Korban Lain" required><input type="number" class="form-control" name="korban[][umur]" placeholder="Masukan Umur Korban Lain" required><select class="form-control" name="korban[][pendidikan]" required><option value="">=== Pilih Salah Satu Pendidikan ===</option><option value="SD/Sederajat">SD/Sederajat</option><option value="SMP/Sederajat">SMP/Sederajat</option><option value="SMA/SMK/Sederajat">SMA/SMK/Sederajat</option><option value="D3/Sarjana Muda/Sederajat">D3/Sarjana Muda/Sederajat</option><option value="S1/Sarjana">S1/Sarjana</option><option value="S2/Master">S2/Master</option><option value="S3/Doktor">S3/Doktor</option><option value="Lainnya">Lainnya</option></select><select class="form-control" name="korban[][pekerjaan]" required><option value="">=== Pilih Salah Satu Pekerjaan ===</option><option value="Pegawai Negeri Sipil">1. Pegawai Negeri Sipil</option><option value="Karyawan Swasta">2. Karyawan Swasta</option><option value="Mahasiswa / Pelajar">3. Mahasiswa / Pelajar</option><option value="TNI">4. TNI</option><option value="Polri">5. Polri</option><option value="Pengangguran">6. Pengangguran</option><option value="Lain - lain">7. Lain - lain</option></select><input type="text" class="form-control" name="korban[][asal]" placeholder="Masukan Tempat Asal Korban Lain" required><select class="form-control" name="korban[][kondisi]" required><option value="">=== Pilih Kondisi Korban Lain ===</option><option value="1">1. Meninggal</option><option value="2">2. Luka Berat</option><option value="3">3. Luka Ringan</option></select><a href="#" title="Hapus data korban" class="btn btn-danger remove_field" style="margin-top: 6px; margin-bottom: 6px"><i class="fa fa-trash" aria-hidden="true"></i></a></div>'); //add input field
        }
    });  
    $('.input_fields_container_part_2').on("click",".remove_field", function(e){ //user click on remove text links
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>
@endsection