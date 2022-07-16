@extends('layouts.app')

@section('title', 'Variants')

@section('page-header', 'Variants')

@section('breadcrumb')
<li class="breadcrumb-item active">Variants</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-9">
            @include('features.masters.variant.partials._list')
        </div>
        <div class="col-md-3">
            @include('features.masters.variant.partials._create')
            @include('features.masters.variant.partials._edit')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/variant/index.js')}}"></script>
<script src="{{asset('js/features/masters/variant/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('variants.getVariants') }}");
</script>
@endsection
