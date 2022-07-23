@extends('layouts.app')

@section('title', 'Master Pallets')

@section('page-header', 'Master Pallets')

@section('breadcrumb')
<li class="breadcrumb-item active">Master Pallets</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-9">
            @include('features.masters.master-pallet.partials._list')
        </div>
        <div class="col-md-3">
            @include('features.masters.master-pallet.partials._create')
            @include('features.masters.master-pallet.partials._edit')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/master-pallet/index.js')}}"></script>
<script src="{{asset('js/features/masters/master-pallet/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('master-pallets.getMasterPallets') }}");
</script>
@endsection
