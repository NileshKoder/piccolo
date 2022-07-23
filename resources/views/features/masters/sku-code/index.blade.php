@extends('layouts.app')

@section('title', 'Sku Codes')

@section('page-header', 'Sku Codes')

@section('breadcrumb')
<li class="breadcrumb-item active">Sku Codes</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-9">
            @include('features.masters.sku-code.partials._list')
        </div>
        <div class="col-md-3">
            @include('features.masters.sku-code.partials._create')
            @include('features.masters.sku-code.partials._edit')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/sku-code/index.js')}}"></script>
<script src="{{asset('js/features/masters/sku-code/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('sku-codes.getSkuCodes') }}");
</script>
@endsection
