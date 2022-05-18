<h4>KEPOLISIAN REPUBLIK INDONESIA</h4>
<h4>DAERAH SUMATERA BARAT</h4>
<h4>STAFF PRIBADI PIMPINAN</h4>
<br>
<h4>REKAP KEAKTIFAN PENGINPUTAN DATA BUKTI PADA APLIKASI MAPPING CRIME</h4>
<table class="table table-bordered" style="font-size:14px;">
  <thead >                  
    <tr>
      <th style="width: 10px; text-align: center;">#</th>
      <th style="width: 30px; text-align: center;">NO LP</th>
      <th style="width: 30px; text-align: center;">SATKER/SATWIL</th>
      <th style="width: 30px; text-align: center;">JENIS PIDANA</th>
      <th style="width: 30px; text-align: center;">NO RANGKA</th>
      <th style="width: 30px; text-align: center;">NO MESIN</th>
      <th style="width: 30px; text-align: center;">JENIS KENDARAAN</th>
    </tr>
  </thead>
  <tbody>
    @foreach($buktis as $i=>$bukti)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $bukti->no_lp }}</td>
        <td>{{ $bukti->satuan }}</td>
        <td>{{ $bukti->pidana }}</td>
        <td>{{ $bukti->no_rangka }}</td>
        <td>{{ $bukti->no_mesin }}</td>
        <td>{{ $bukti->kode_kendaraan }}</td>
      </tr>
    @endforeach
  </tbody>
</table>