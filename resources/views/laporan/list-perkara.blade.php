@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="container-fluid">
  <div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
      <div class="welcome-text">
        <h4>Data Perkara {{ $satker->name }}</h4>
      </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Dashboard</a>
        </li>
        <li class="breadcrumb-item"><a href="{{URL::to('/polsek/list-satker')}}">List Polsek Turunan</a>
        </li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Data Perkara {{ $satker->name }}</a>
        </li>
      </ol>
    </div>
  </div>
  <!-- livewire here -->
  @livewire('report.perkara-polsek-list', compact('id'))
  
</div>
@endsection