@extends('layouts.app')
@section('content')
  <!-- Content Header (Page header) -->
  <div class="container-fluid">
    <div class="row page-titles mx-0">
      <div class="col-sm-6 p-md-0">
          <div class="welcome-text">
              <h4>Update Anggaran Perkara</h4>
          </div>
      </div>
      <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">Update Anggaran Perkara</a></li>
          </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->

  <!-- Main content -->
  <section class="container-fluid">
    <div class="row page-titles mx-0">
      @if($perkara->status_id == 1)
        @if(Auth::user()->groups()->where("name", "!=", "Admin")->first())
        <div class="col-sm-12 p-md-0">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Update Anggaran Perkara</h3>
            </div>
            <!-- update-status -->
            {!! Form::model($perkara,['files'=>true,'method'=>'put','action'=>['admin\PerkaraController@storeUpdateAnggaran',$perkara->id]]) !!}
              <div class="card-body">

                <div class="form-group">
                  <label>Nomer LP</label>
                  <input type="text" class="form-control" id="no_lp" name="no_lp" placeholder="Masukan Nomer LP" value="{{ $perkara->no_lp }}" disabled="">
                </div>

                <div class="form-group">
                  <label>Nominal</label>
                  <input type="number" class="form-control" name="anggaran" placeholder="Masukan Nominal" required>
                </div>

                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="date" class="form-control" name="date" required>
                </div>

                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" class="form-control" name="keterangan" placeholder="Masukan Keterangan Penggunaan Dana" required>
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
        @endif
      @endif
      <div class="col-lg-12 p-md-0">
        <div class="card mt-3">
            <div class="card-header"> Data Anggaran <span class="float-right">
              <strong>Status Perkara:</strong> {{ $perkara->status->name }}</span> 
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th class="right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($anggarans as $key=>$anggaran)
                          <tr>
                            <td class="center">{{ $key + 1 }}</td>
                            <td class="left strong">{{ $anggaran->keterangan }}</td>
                            <td class="left strong">{{Carbon\Carbon::parse($anggaran->date)->formatLocalized('%d %B %Y')}}</td>
                            <td class="right">Rp.{{ number_format($anggaran->anggaran) }}</td>
                          </tr>
                        @empty
                        <td colspan="4">
                          <center>Data Kosong</center>
                        </td>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-sm-5"> </div>
                    <div class="col-lg-6 col-sm-5 ml-auto">
                      <table class="table table-clear">
                        <tbody>
                          <tr>
                            <td class="left"><strong>Subtotal</strong></td>
                            <td class="right">Rp.{{ number_format($sum_anggaran) }}</td>
                          </tr>
                          <tr>
                            <td class="left"><strong>Sisa Dana</strong></td>
                            @if($perkara->anggaran == null)
                            <td class="right">Rp.0</td>
                            @else
                            <td class="right"><strong>Rp.{{ number_format($perkara->anggaran - $sum_anggaran) }}</strong>
                            @endif
                          </tr>
                          <tr>
                            <td class="left"><strong>Total Dana</strong></td>
                            @if($perkara->anggaran == null)
                            <td class="right"><strong><code>Harap masukan data anggaran untuk kasus ini di menu edit perkara (Tidak boleh 0)</code></strong>
                            @else
                            <td class="right"><strong>Rp.{{ number_format($perkara->anggaran) }}</strong>
                            @endif
                          </tr>
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection

@section('js')

@endsection