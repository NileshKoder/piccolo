@extends('layouts.app')

@section('title', 'Box Codes')

@section('page-header', 'Box Codes')

@section('breadcrumb')
<li class="breadcrumb-item active">Box Codes</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-9">
            @include('features.masters.box-code.partials._list')
        </div>
        <div class="col-md-3">
            @include('features.masters.box-code.partials._create')
            @include('features.masters.box-code.partials._edit-modal')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/box/index.js')}}"></script>
<script src="{{asset('js/features/masters/box/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('boxes.getBoxes') }}");
</script>
@endsection
