@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Rekap Anggaran</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Data Anggaran</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-responsive-md">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Satker</th>
                  <th>Total Anggaran</th>
                  <th>Sisa Anggaran</th>
                  <th>Anggaran Terpakai</th>
                </tr>
              </thead>
              <tbody>
                @foreach($anggarans as $i=>$anggaran)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $anggaran->name }}</td>
                  @if($anggaran->masterAnggaran != null)
                  <td>Rp.{{ number_format($anggaran->masterAnggaran->total_anggaran) }}</td>
                  @else
                  <td>null</td>
                  @endif
                  @if(!$anggaran->anggaran->isEmpty())
                  <td>Rp.{{ number_format(($anggaran->masterAnggaran->total_anggaran) - ($anggaran->anggaran->sum('anggaran')))}}</td>
                  @else
                  <td>null</td>
                  @endif
                  @if(!$anggaran->anggaran->isEmpty())
                  <td>Rp.{{ number_format($anggaran->anggaran->sum('anggaran'))}}</td>
                  @else
                  <td>null</td>
                  @endif
                </tr>
                @endforeach
                <tr>
	                <td>#</td>
	                <td style="text-align: center"><b>TOTAL</b></td>
                  <td style="font-size: 17px"><span class="badge light badge-success">Rp. {{ number_format($sum_master_anggaran) }}</span></td>
                  <td style="font-size: 17px"><span class="badge light badge-danger">Rp. {{ number_format($sisa) }}</span></td>
                  <td style="font-size: 17px">Rp. {{ number_format($sum_anggaran) }}</td>
	              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
  </div>
</div>
@endsection
