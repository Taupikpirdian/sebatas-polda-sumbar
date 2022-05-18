<h4>KEPOLISIAN REPUBLIK INDONESIA</h4>
<h4>DAERAH SUMATERA BARAT</h4>
<h4>STAFF PRIBADI PIMPINAN</h4>
<br>
<h4>REKAP KEAKTIFAN PENGINPUTAN DATA KRIMINALITAS PADA APLIKASI MAPPING CRIME</h4>
<table class="table table-bordered" style="font-size:14px;">
  <thead >                  
    <tr>
      <th rowspan="2" style="width: 10px; text-align: center;">#</th>
      <th rowspan="2" style="width: 50px; text-align: center;">SATKER/SATWIL</th>
      <th colspan="4" style="width: 50px; text-align: center;">JUMLAH LAPORAN POLISI</th>
    </tr>
    <tr>
      <th style="width: 20px; text-align: center;">TERJADI</th>
      <th style="width: 20px; text-align: center;">SUDAH INPUT (APLIKASI MAPPING CRIME)</th>
      <th style="width: 20px; text-align: center;">BELUM INPUT (APLIKASI MAPPING CRIME)</th>
      <th style="width: 20px; text-align: center;">PERSENTASE SELESAI (APLIKASI MAPPING CRIME)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($rekap as $i=>$grouping)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $grouping->name }}</td>
        <td><span class="badge bg-danger">{{ $grouping->total }}</span></td>
      @if($grouping->array == 3)
        <td><span class="badge bg-danger">0</span></td>
        <td><span class="badge bg-danger">{{ $grouping->total }}</span></td>
      @else
        <td><span class="badge bg-danger">{{ $grouping->kasus_selesai }}</span></td>
        <td><span class="badge bg-danger">{{ ($grouping->total - $grouping->kasus_selesai) }}</span></td>
      @endif
        <td><span class="badge bg-danger">{{ $grouping->percent_success }}%</span></td>
      </tr>
    @endforeach
  </tbody>
</table>