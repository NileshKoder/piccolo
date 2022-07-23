@extends('layouts.app')

@section('title', 'Warehouse')

@section('page-header', 'Warehouse')

@section('breadcrumb')
<li class="breadcrumb-item active">Warehouse</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-12">
            @include('features.masters.warehouse.partials._list')
        </div>
    </div>
</div>
@include('features.masters.warehouse.edit')
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/warehouse/index.js')}}"></script>
<script src="{{asset('js/features/masters/warehouse/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/jquery-barcode.js') }}"></script>
<script>
    initWareHouseDataTable("{{ route('warehouses.getWarehouses') }}");
</script>
@endsection
