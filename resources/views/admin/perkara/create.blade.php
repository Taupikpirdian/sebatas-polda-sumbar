@extends('layouts.app')
@section('content')
  <!-- Content Header (Page header) -->
  <div class="container-fluid">
    <div class="row page-titles mx-0">
      <div class="col-sm-6 p-md-0">
          <div class="welcome-text">
              <h4>Tambah Perkara</h4>
          </div>
      </div>
      <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Perkara</a></li>
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
            <h3 class="card-title">Menambahkan Perkara Divisi @if(Auth::check()){{ Auth::user()->divisi }}@endif</h3>
          </div>
          <!-- /.card-header -->
          {{ Form::open(array('url' => '/perkara/create', 'files' => true, 'method' => 'post')) }}
            <div class="card-body">

              <div class="form-group">
                <label for="name">Nomer LP</label>
                <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{old('no_lp')}}" required>
                @error('no_lp')
                  <div class="col-md-5 input-group mb-1" style="color:red">
                      {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-group">
                <label for="date">Tanggal Nomer LP</label>
                <input type="date" class="form-control" id="date_no_lp" name="date_no_lp" value="{{old('date_no_lp')}}" required>
              </div>

              @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
              <div class="form-group">
                <label>Satker</label>
                {{ Form::select('satker', $satker, null,['class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true']) }}
                <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
              </div>

              <div class="form-group">
                <label>Divisi</label>
                <div class="input-group">
                  <select class="form-control selectpicker" name="divisi" data-live-search="true" required>
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Ditreskrimsus" @if(old('divisi') == 'Ditreskrimsus') selected @endif>Ditreskrimsus</option>
                     <option value="Ditreskrimum" @if(old('divisi') == 'Ditreskrimum') selected @endif>Ditreskrimum</option>
                     <option value="Ditresnarkoba" @if(old('divisi') == 'Ditresnarkoba') selected @endif>Ditresnarkoba</option>
                     <option value="Satreskrim" @if(old('divisi') == 'Satreskrim') selected @endif>Satreskrim</option>
                     <option value="Satnarkoba" @if(old('divisi') == 'Satnarkoba') selected @endif>Satnarkoba</option>
                     <option value="Unit Reskrim" @if(old('divisi') == 'Unit Reskrim') selected @endif>Unit Reskrim</option>
                  </select>
                </div>
              </div>
              @endif

              @if(Auth::user()->groups()->where("name", "=", "Polres")->first())
              <div class="form-group">
                <label>Satker</label>
                {{ Form::select('satker', $satker_not_admin, null,['class' => 'form-control selectpicker ', 'required' => 'required', 'data-live-search' => 'true']) }}
                <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
              </div>
              @endif

              @if(Auth::user()->groups()->where("name", "=", "Polsek")->first())
              <div class="form-group">
                <label>Satker</label>
                {{ Form::select('satker', $satker_not_admin, null,['class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true']) }}
                <p style="color: red; font-size:13">Note: Jika satuan kerja yang anda maksud tidak tersedia, harap untuk menghubungi admin</p>
              </div>
              @endif

              <div class="form-group">
                <label>Uraian Singkat Kejadian</label>
                <textarea class="form-control" rows="3" name="desc" placeholder="Masukan Uraian Singkat Kejadian" required>{{Request::old('desc')}}</textarea>
              </div>

              <div class="form-group">
                <label>Modus Operasi</label>
                <textarea class="form-control" rows="3" name="modus" placeholder="Masukan Modus Operasi" required>{{Request::old('modus')}}</textarea>
              </div>

              <div class="form-group">
                <label for="nama_petugas">Nama Petugas</label>
                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" placeholder="Masukan Nama Petugas" value="{{old('nama_petugas')}}" required>
              </div>

              <div class="form-group">
                <label for="pangkat">Pangkat dan NRP</label>
                <input type="text" class="form-control" id="pangkat" name="pangkat" placeholder="Masukan Pangkat dan NRP" value="{{old('pangkat')}}" required>
              </div>

              <div class="form-group">
                <label for="no_tlp">No Telphone</label>
                <input type="text" class="form-control" id="no_tlp" name="no_tlp" placeholder="Masukan No Telphone Petugas" value="{{old('no_tlp')}}" required>
              </div>

              <div class="form-group">
                <label for="nama_korban">Korban</label>
                <input type="text" class="form-control" id="nama_korban" name="nama_korban" placeholder="Masukan Nama Korban" value="{{old('nama_korban')}}">
                <input type="number" class="form-control" id="umur_korban" name="umur_korban" placeholder="Masukan Umur Korban" value="{{old('umur_korban')}}">
                <select class="form-control" name="pendidikan_korban">
                     <option value="">=== Pilih Salah Satu Pendidikan ===</option>
                     <option value="SD/Sederajat" @if(old('pendidikan_korban') == 'SD/Sederajat') selected @endif>SD/Sederajat</option>
                     <option value="SMP/Sederajat" @if(old('pendidikan_korban') == 'SMP/Sederajat') selected @endif>SMP/Sederajat</option>
                     <option value="SMA/SMK/Sederajat" @if(old('pendidikan_korban') == 'SMA/SMK/Sederajat') selected @endif>SMA/SMK/Sederajat</option>
                     <option value="D3/Sarjana Muda/Sederajat" @if(old('pendidikan_korban') == 'D3/Sarjana Muda/Sederajat') selected @endif>D3/Sarjana Muda/Sederajat</option>
                     <option value="S1/Sarjana" @if(old('pendidikan_korban') == 'S1/Sarjana') selected @endif>S1/Sarjana</option>
                     <option value="S2/Master" @if(old('pendidikan_korban') == 'S2/Master') selected @endif>S2/Master</option>
                     <option value="S3/Doktor" @if(old('pendidikan_korban') == 'S3/Doktor') selected @endif>S3/Doktor</option>
                     <option value="Lainnya" @if(old('pendidikan_korban') == 'Lainnya') selected @endif>Lainnya</option>
                </select>
                <select class="form-control" name="pekerjaan_korban">
                     <option value="">=== Pilih Salah Satu Pekerjaan ===</option>
                     <option value="Pegawai Negeri Sipil" @if(old('pekerjaan_korban') == 'Pegawai Negeri Sipil') selected @endif>1. Pegawai Negeri Sipil</option>
                     <option value="Karyawan Swasta" @if(old('pekerjaan_korban') == 'Karyawan Swasta') selected @endif>2. Karyawan Swasta</option>
                     <option value="Mahasiswa / Pelajar" @if(old('pekerjaan_korban') == 'Mahasiswa / Pelajar') selected @endif>3. Mahasiswa / Pelajar</option>
                     <option value="TNI" @if(old('pekerjaan_korban') == 'TNI') selected @endif>4. TNI</option>
                     <option value="Polri" @if(old('pekerjaan_korban') == 'Polri') selected @endif>5. Polri</option>
                     <option value="Pengangguran" @if(old('pekerjaan_korban') == 'Pengangguran') selected @endif>6. Pengangguran</option>
                     <option value="Lain - lain" @if(old('pekerjaan_korban') == 'Lain - lain') selected @endif>7. Lain - lain</option>
                </select>
                <input type="text" class="form-control" id="asal_korban" name="asal_korban" placeholder="Masukan Tempat Asal Korban" value="{{old('asal_korban')}}">
              </div>

              <div class="form-group input_fields_container_part">
                  <label for="saksi">Saksi</label>
                  <input type="text" class="form-control" name="saksi[]" placeholder="Masukan Nama Saksi">
                  <div class="btn btn-sm btn-default add_more_button">Tambah Saksi</div>
              </div>

              <div class="form-group input_fields_container_part_2">
                  <label for="saksi">Pelaku</label>
                  <input type="text" class="form-control" name="pelaku[][Nama]" placeholder="Masukan Nama Pelaku" required>
                  <input type="number" class="form-control" name="pelaku[][Umur]" placeholder="Masukan Umur" required>
                  <select class="form-control" name="pelaku[][Pendidikan]" required>
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
                  <select class="form-control" name="pelaku[][Pekerjaan]" required>
                     <option value="">=== Pilih Salah Satu Pekerjaan ===</option>
                     <option value="Pegawai Negeri Sipil">1. Pegawai Negeri Sipil</option>
                     <option value="Karyawan Swasta">2. Karyawan Swasta</option>
                     <option value="Mahasiswa / Pelajar">3. Mahasiswa / Pelajar</option>
                     <option value="TNI">4. TNI</option>
                     <option value="Polri">5. Polri</option>
                     <option value="Pengangguran">6. Pengangguran</option>
                     <option value="Lain - lain">7. Lain - lain</option>
                  </select>

                  <input type="text" class="form-control" name="pelaku[][Asal]" placeholder="Masukan Tempat Asal" required>
                  <div class="btn btn-sm btn-default add_more_button_2">Tambah Pelaku</div>
              </div>

              <div class="form-group">
                <label for="name">Barang Bukti</label>
                <input type="text" class="form-control" id="barang_bukti" name="barang_bukti" placeholder="Masukan Barang Bukti" value="{{old('barang_bukti')}}" required>
                @error('barang_bukti')
                  <div class="col-md-5 input-group mb-1" style="color:red">
                      {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-group">
                <label>Klasifikasi TKP</label>
                <div class="input-group">
                  <select class="form-control" name="tkp" required>
                     <option value="">=== Pilih Salah Satu ===</option>
                     <option value="Rumah / Pemukiman" @if(old('tkp') == 'Rumah / Pemukiman') selected @endif>1. Rumah / Pemukiman</option>
                     <option value="Perkantoran" @if(old('tkp') == 'Perkantoran') selected @endif>2. Perkantoran</option>
                     <option value="Pasar / Pertokoan" @if(old('tkp') == 'Pasar / Pertokoan') selected @endif>3. Pasar / Pertokoan</option>
                     <option value="Restoran" @if(old('tkp') == 'Restoran') selected @endif>4. Restoran</option>
                     <option value="Sekolah / Universitas" @if(old('tkp') == 'Sekolah') selected @endif>5. Sekolah / Universitas</option>
                     <option value="Tempat Ibadah" @if(old('tkp') == 'Tempat Ibadah') selected @endif>6. Tempat Ibadah</option>
                     <option value="Lain - lain" @if(old('tkp') == 'Lain - lain') selected @endif>7. Lain - lain</option>
                  </select>
                </div>
              </div>

              <div id="map_create" style="width:100%;height:380px;"></div>
              <p style="color: red; font-size:13">Note: Klik lokasi yang diinginkan pada maps untuk mendapatkan titik koordinat (Akan secara otomatis terisi pada form lat dan long)</p>

              <div class="form-group">
                <label for="lat">Latitude</label>
                <input type="text" class="form-control" id="lat" placeholder="Masukan Latitude" value="{{old('lat')}}" disabled>
              </div>

              <div class="form-group">
                <label for="long">Longitude</label>
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
                <label for="date">Tanggal Kejadian</label>
                <input type="date" class="form-control" id="date" name="date" placeholder="Masukan Longitude" value="{{old('date')}}" required>
              </div>

              <div class="form-group">
                <label>Waktu Kejadian</label>
                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                  <input type="text" class="form-control" id="time" name="time" placeholder="Masukan Waktu Kejadian" value="{{old('time')}}" autocomplete="off" required> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                </div>
              </div>
              
              <div class="form-group">
                <label>Jenis Tindak Pidana</label>
                {{ Form::select('jenis_pidana', $jenis_pidanas, null,['class' => 'form-control selectpicker', 'id' => 'jenis_pidana', 'required' => 'required', 'data-live-search' => 'true']) }}
              </div>

              <div class="form-group" id="jenis_narkoba">
                <label>Jenis Narkoba</label>
                {{ Form::select('material', $type_narkoba, null,['class' => 'form-control selectpicker', 'data-live-search' => 'true']) }}
              </div>

              <div class="form-group" id="qty">
                <label>Berat (gram)</label>
                <input type="number" class="form-control" name="qty" placeholder="Masukan Berat Narkoba" value="{{old('qty')}}">
              </div>

              <div class="form-group">
                <label>Anggaran Perkara</label>
                <input type="number" class="form-control" id="anggaran" name="anggaran" placeholder="Masukan Nomimal Dana Perkara" value="{{old('anggaran')}}" required>
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
    var max_fields_limit      = 8; //set limit for maximum input fields
    var x = 1; //initialize counter for text box
    $('#qty').hide();
    $('#jenis_narkoba').hide();
    $('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
        e.preventDefault();
        if(x < max_fields_limit){ //check conditions
            x++; //counter increment
            $('.input_fields_container_part').append('<div><input type="text" class="form-control" name="saksi[]" placeholder="Masukan Nama Saksi Lain" required><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
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
            $('.input_fields_container_part_2').append('<div><input type="text" class="form-control" name="pelaku[][Nama]" placeholder="Masukan Nama Pelaku Lain" required><input type="number" class="form-control" name="pelaku[][Umur]" placeholder="Masukan Umur Pelaku Lain" required><select class="form-control" name="pelaku[][Pendidikan]" required><option value="">=== Pilih Salah Satu Pendidikan ===</option><option value="SD/Sederajat">SD/Sederajat</option><option value="SMP/Sederajat">SMP/Sederajat</option><option value="SMA/SMK/Sederajat">SMA/SMK/Sederajat</option><option value="D3/Sarjana Muda/Sederajat">D3/Sarjana Muda/Sederajat</option><option value="S1/Sarjana">S1/Sarjana</option><option value="S2/Master">S2/Master</option><option value="S3/Doktor">S3/Doktor</option><option value="Lainnya">Lainnya</option></select><select class="form-control" name="pelaku[][Pekerjaan]" required><option value="">=== Pilih Salah Satu Pekerjaan ===</option><option value="Pegawai Negeri Sipil">1. Pegawai Negeri Sipil</option><option value="Karyawan Swasta">2. Karyawan Swasta</option><option value="Mahasiswa / Pelajar">3. Mahasiswa / Pelajar</option><option value="TNI">4. TNI</option><option value="Polri">5. Polri</option><option value="Pengangguran">6. Pengangguran</option><option value="Lain - lain">7. Lain - lain</option></select><input type="text" class="form-control" name="pelaku[][Asal]" placeholder="Masukan Tempat Asal Pelaku Lain" required><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
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