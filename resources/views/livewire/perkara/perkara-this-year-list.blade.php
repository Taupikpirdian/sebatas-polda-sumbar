<div>
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-2">
            <a title="Tambah data perkara" href="{{URL::to('/perkara/create')}}" class="btn btn-sm btn-info float-left"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data</a>
          </div>
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
                @if($role_group == 'Admin')
                <th><input wire:model='query_satker' type="text" class="form-control" aria-describedby="button-addon2"></th>
                @endif
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
                @if($role_group == 'Admin')
                <th>Satker</th>
                @endif
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
            @forelse($perkaras as $i=>$perkara)
              <tr>
                  <td>{{ ($perkaras->currentpage()-1) * $perkaras->perpage() + $i + 1 }}</td>
                  <td> {{ $perkara->no_lp }} </td>
                  @if($role_group == 'Admin')
                  <td> {{ $perkara->satuan }} </td>
                  @endif
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
                    @if($perkara->status_id != 1)
                    @else
                    <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@edit",array($perkara->id))}}'>Update Status Perkara</a>
                    <!-- masih progress -->
                    <!-- <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@limpahPerkara",array($perkara->id))}}'>Limpah Perkara</a> -->
                    @endif
                    <a target="_blank" class="dropdown-item" href='{{URL::action("admin\PerkaraController@show",array($perkara->id))}}'>Detail Perkara</a>
                    @if(Auth::user()->hasAnyRole('Edit Perkara'))
                    <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@updateData",array($perkara->id))}}'>Edit Perkara</a>
                    @endif
                    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
                    <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@updateAnggaran",array($perkara->id))}}'>Lihat Anggaran</a>
                    @else
                    <a class="dropdown-item" href='{{URL::action("admin\PerkaraController@updateAnggaran",array($perkara->id))}}'>Update Anggaran</a>
                    @endif
                    @if(Auth::user()->hasAnyRole('Delete Perkara'))
                    <a class="dropdown-item" href="javascript:;" id="deleteForm" data-toggle="modal" onclick="deleteData({{$perkara->id}})" data-target="#DeleteModal" method="post">Hapus Perkara</a>
                    @endif
                    @if(Auth::user()->groups()->where("name", "!=", "Admin")->first())
                    <!-- <a class="dropdown-item" href='javascript:;' data-toggle="modal" onclick="requestForm({{$perkara->id}})" data-target="#RequestForm" method="post">Hapus atau Edit Perkara</a> -->
                    @endif
                    </div>
                  </div>
                </td>
              </tr>
              <!-- Modal -->
              <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <form action='{{URL::action("admin\PerkaraController@destroy",array($perkara->id))}}' id="deleteForm" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-center" id="exampleModalLabel">Hapus Data</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <p class="text-center">Data Ini Akan Terhapus Secara Permanen! Apakah Anda Yakin ?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button>
                      <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Ya, Hapus</button>
                    </div>
                  </div>
                </div>
              </div>
            @empty
                <td colspan="9" style="text-align: center">
                  Data Kosong
                </td>
            @endforelse
            </tbody>
          </table>
        </div>
        <br>
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

  function deleteData(id)
  {
      var id = id;
      var url = '{{ route("perkara.destroy", ":id") }}';
      url = url.replace(':id', id);
      $("#deleteForm").attr('action', url);
  }

  function formSubmit()
  {
      $("#deleteForm").submit();
  }
</script>
@endsection