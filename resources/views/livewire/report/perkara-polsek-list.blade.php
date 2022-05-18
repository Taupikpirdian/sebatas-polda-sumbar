<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-2">
            <select wire:model='perPage' class="form-control" id="perPage">
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
          </div>

          <div class="col-lg-3" style="justify-content: left; width: 100px">
              <input type="text" wire:model='query_daterange' class="form-control" id="query_daterange" placeholder="Pilih Range Tanggal">
          </div>
        </div>
        <hr>
        <div class="table-responsive">
          <table class="table table-responsive-md">
            <thead>
              <tr>
                <th style="width: 10px"></th>
                <th><input wire:model='query_no_lp' type="text" class="form-control" aria-describedby="button-addon2"></th>
                <th><input wire:model='query_petugas' type="text" class="form-control" aria-describedby="button-addon2"></th>
                <th><input wire:model='query_korban' type="text" class="form-control" aria-describedby="button-addon2"></th>
                <th><input wire:model='query_bukti' type="text" class="form-control" aria-describedby="button-addon2"></th>
                <th><input wire:model='query_kejadian' type="date" class="form-control" aria-describedby="button-addon2"></th>
                <th><input wire:model='query_pidana' type="text" class="form-control" aria-describedby="button-addon2"></th>
                <th><input wire:model='query_status' type="text" class="form-control" aria-describedby="button-addon2"></th>
                <th style="width: 10px"></th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>No LP</th>
                <th>Nama Petugas</th>
                <th>Nama Korban</th>
                <th>Barang Bukti</th>
                <th>Waktu Kejadian</th>
                <th>Jenis Pidana</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            @forelse ($perkaras as $i=>$perkara)
              <tr>
                <td>{{ ($perkaras->currentpage()-1) * $perkaras->perpage() + $i + 1 }}</td>
                <td> {{ $perkara->no_lp }} </td>
                <td> {{ $perkara->nama_petugas }} </td>
                <td> {{ $perkara->nama }} </td>
                <td> {!! str_limit($perkara->barang_bukti, 25) !!} <a title="{{ $perkara->barang_bukti }}" href="#">[...]</a></td>
                <td> {{Carbon\Carbon::parse($perkara->date)->formatLocalized('%d %B %Y')}}, {{ $perkara->time }}</td>
                <td> {{ $perkara->pidana }} </td>
                @if($perkara->status_id == 1)
                <td><span class="badge badge-info">{{ $perkara->status }}</span></td>
                @else
                <td><span class="badge badge-success">{{ $perkara->status }}</span></td>
                @endif
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                      <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@show",array($perkara->id))}}'>Detail Perkara</a>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <td colspan="9" style="text-align: center">
                Data Kosong
              </td>
            @endforelse
            </tbody>
          </table>
        </div>
        <div class="col-md-12">
          {{ $perkaras->links() }}
        </div>
        <p class="col-sm-12" style="text-align: left;"> 
          {{ $paginate_content }}
        </p>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
@section('js')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // date range
      $('#query_daterange').daterangepicker({
        locale: {
          format: 'YYYY/MM/DD'
        },
        singleDatePicker: false,
      })
      $('#query_daterange').on('change', function (e) {
        @this.set('query_daterange', e.target.value);
      });
  });
</script>
@endsection