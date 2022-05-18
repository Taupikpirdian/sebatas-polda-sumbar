@extends('layouts.app')
@section('content')
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Data Semua Perkara </h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{URL::to('/total-crime')}}">Data Semua Perkara</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- row -->
  @livewire('perkara.perkara-total-list')
</div>
@endsection
@section('js')
@endsection