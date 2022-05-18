<table>
    <thead>
        <tr>
            @foreach($rekapitulasis as $data)
            <th>Rekapitulasi Data Kasus {{ $data->pidana }}</th>
            @break
            @endforeach
        </tr>
        <tr>
            <th>No</th>
            <th>Satker</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
    @php $no = 1 @endphp
    @foreach($rekapitulasis as $data)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $data->total }}</td>
        </tr>
    @endforeach
    </tbody>
</table>