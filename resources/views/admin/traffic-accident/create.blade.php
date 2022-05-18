@extends('layouts.app')
@section('content')
  <!-- Content Header (Page header) -->
  <div class="container-fluid">
    <div class="row page-titles mx-0">
      <div class="col-sm-6 p-md-0">
          <div class="welcome-text">
              <h4>Tambah Laka Lantas</h4>
          </div>
      </div>
      <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Laka Lantas</a></li>
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
            <h3 class="card-title">Menambahkan Laka Lantas Divisi @if(Auth::check()){{ Auth::user()->divisi }}@endif</h3>
          </div>
          <!-- /.card-header -->
          {{ Form::open(array('url' => '/laka-lantas/create', 'files' => true, 'method' => 'post')) }}
            <div class="card-body">

              <div class="form-group">
                <label for="name">Nomor LP</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{old('no_lp')}}" required>
                @error('no_lp')
                  <div class="col-md-5 input-group mb-1" style="color:red">
                      {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-group">
                <label for="date">Tanggal Nomor LP</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="date" class="form-control" id="date_no_lp" name="date_no_lp" value="{{old('date_no_lp')}}" required>
              </div>

              @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
              <div class="form-group">
                <label>Satker</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                {{ Form::select('satker', $satker, null,['class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true']) }}
                <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
              </div>

              <div class="form-group">
                <label>Divisi</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <div class="input-group">
                  <select class="form-control selectpicker" name="divisi" data-live-search="true" required>
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Ditlantas" @if(old('divisi') == 'Ditlantas') selected @endif>Ditlantas</option>
                     <option value="Satlantas" @if(old('divisi') == 'Satlantas') selected @endif>Satlantas</option>
                  </select>
                </div>
              </div>
              @else
              <div class="form-group">
                <label>Satker</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                {{ Form::select('satker', $satker, null,['class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true']) }}
                <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
              </div>
              @endif

              <div class="form-group">
                <label>Uraian Singkat Kejadian</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <textarea class="form-control" rows="3" name="uraian" placeholder="Masukan Uraian Singkat Kejadian" required>{{Request::old('uraian')}}</textarea>
              </div>

              <div class="form-group">
                <label>Kerugian Material</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <textarea class="form-control" rows="3" name="kerugian_material" placeholder="Masukan Kerugian Material" required>{{Request::old('kerugian_material')}}</textarea>
              </div>

              <div class="form-group">
                <label for="nama_petugas">Nama Petugas</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" placeholder="Masukan Nama Petugas" value="{{old('nama_petugas')}}" required>
              </div>

              <div class="form-group">
                <label for="pangkat">Pangkat dan NRP</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="pangkat" name="pangkat" placeholder="Masukan Pangkat dan NRP" value="{{old('pangkat')}}" required>
              </div>

              <div class="form-group">
                <label for="no_tlp">No Telphone</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="no_tlp" name="no_tlp" placeholder="Masukan No Telphone Petugas" value="{{old('no_tlp')}}" required>
              </div>

              <div class="form-group input_fields_container_part_2">
                  <label for="saksi">Korban</label>
                  <br>
                  <!-- <input type="text" class="form-control" name="korban[][nama]" placeholder="Masukan Nama Korban">
                  <input type="number" class="form-control" name="korban[][umur]" placeholder="Masukan Umur Korban">
                  <select class="form-control" name="korban[][pendidikan]">
                     <option value="">=== Pilih Salah Satu Pendidikan ===</option>
                     <option value="SD/Sederajat">SD/Sederajat</option>
                     <option value="SMP/Sederajat">SMP/Sederajat</option>
                     <option value="SMA/SMK/Sederajat">SMA/SMK/Sederajat</option>
                     <option value="D3/Sarjana Muda/Sederajat">D3/Sarjana Muda/Sederajat</option>
                     <option value="S1/Sarjana">S1/Sarjana</option>
                     <option value="S2/Master">S2/Master</option>
                     <option value="S3/Doktor">S3/Doktor</option>
                     <option value="Lainnya">Lainnya</option>
                  </select>
                  <select class="form-control" name="korban[][pekerjaan]">
                     <option value="">=== Pilih Salah Satu Pekerjaan ===</option>
                     <option value="Pegawai Negeri Sipil">1. Pegawai Negeri Sipil</option>
                     <option value="Karyawan Swasta">2. Karyawan Swasta</option>
                     <option value="Mahasiswa / Pelajar">3. Mahasiswa / Pelajar</option>
                     <option value="TNI">4. TNI</option>
                     <option value="Polri">5. Polri</option>
                     <option value="Pengangguran">6. Pengangguran</option>
                     <option value="Lain - lain">7. Lain - lain</option>
                  </select>
                  <input type="text" class="form-control" name="korban[][asal]" placeholder="Masukan Tempat Asal">
                  <select class="form-control" name="korban[][kondisi]">
                     <option value="">=== Pilih Kondisi Korban ===</option>
                     <option value="1">1. Meninggal</option>
                     <option value="2">2. Luka Berat</option>
                     <option value="3">3. Luka Ringan</option>
                  </select> -->
                  <button class="btn btn-warning add_more_button_2" style="margin-bottom: 6px" title="Tambah data korban"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Korban</button>
              </div>

              <div class="form-group input_fields_container_part">
                  <label for="saksi">Saksi</label>
                  <br>
                  <!-- <input type="text" class="form-control" name="saksi[]" placeholder="Masukan Nama Saksi"> -->
                  <div class="btn btn-warning add_more_button" style="margin-bottom: 6px" title="Tambah data saksi"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Saksi</div>
              </div>

              <div class="form-group">
                <label for="name">Barang Bukti</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="barang_bukti" name="barang_bukti" placeholder="Masukan Barang Bukti" value="{{old('barang_bukti')}}" required>
                @error('barang_bukti')
                  <div class="col-md-5 input-group mb-1" style="color:red">
                      {{ $message }}
                  </div>
                @enderror
              </div>

              <div id="map_create" style="width:100%;height:380px;"></div>
              <p style="color: red; font-size:13">Note: Klik lokasi yang diinginkan pada maps untuk mendapatkan titik koordinat (Akan secara otomatis terisi pada form lat dan long)</p>

              <div class="form-group">
                <label for="lat">Latitude</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="lat" placeholder="Masukan Latitude" value="{{old('lat')}}" disabled>
              </div>

              <div class="form-group">
                <label for="long">Longitude</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="text" class="form-control" id="long" placeholder="Masukan Longitude" value="{{old('long')}}" disabled>
              </div>

              <div class="form-group" style="display: none;">
                <label for="lat">Latitude</label>
                <input type="text" class="form-control" name="lat" id="latInput" placeholder="Masukan Latitude" value="{{old('lat')}}" required>
              </div>

              <div class="form-group" style="display: none;">
                <label for="long">Longitude</label>
                <input type="text" class="form-control" name="long" id="longInput" placeholder="Masukan Longitude" value="{{old('long')}}" required>
              </div>

              <div class="form-group">
                <label for="date">Tanggal Kecelakaan</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <input type="date" class="form-control" id="date" name="date" placeholder="Masukan Longitude" value="{{old('date')}}" required>
              </div>

              <div class="form-group">
                <label>Waktu Kecelakaan</label><label style="color: red; font-size:13; margin-left: 2px">*</label>
                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                  <input type="text" class="form-control" id="time" name="time" placeholder="Masukan Waktu Lapor" value="{{old('time')}}" autocomplete="off" required> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
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
              
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <a title="Kembali" href="{{ url()->previous() }}" style="margin-right:20px;" class="btn btn-sm btn-info float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i>   Kembali</a>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
@endsection
@section('js')
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
    $('#qty').hide();
    $('#jenis_narkoba').hide();
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

function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#blah')
          .attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
}

</script>

<script>
setTimeout(function() {
    $('#alert-danger').fadeOut('fast');
}, 7000);

setTimeout(function() {
    $('#alert-warning').fadeOut('fast');
}, 7000);

$("#jenis_pidana").change(function() {
  if ($(this).val() == "32") {
    console.log($(this).val());
    $('#jenis_narkoba').show();
    $('#qty').show();
  } else {
    $('#jenis_narkoba').hide();
    $('#qty').hide();
  }
});
</script>
@endsection