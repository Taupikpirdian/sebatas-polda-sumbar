@extends('layouts.app')
@section('css')
<style>
.select2-selection__rendered {
  line-height: 35px !important;
}

.select2-selection {
  height: 35px !important;
}
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Tambah Anggaran</h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Anggaran</a></li>
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
        <!-- /.card-header -->
        <form method="post" action="{{ route('anggaran.store') }}" accept-charset="UTF-8">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label>Satker</label>
              <select class="form-control" name="satker" id="satker_on_anggaran" required>
                <option value="">Pilih Satker</option>
                @foreach($satkers as $key=>$satker)
                <option value="{{ $satker->id }}">{{ $satker->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Total Anggaran</label>
              <input type="number" class="form-control" name="total_anggaran" required>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</div>
@endsection

@section('js')
<script type="text/javascript">
	$("#satker_on_anggaran").select2();
</script>
@endsection
