@extends('layouts.app')

@section('title', 'Create Warehouse')

@section('page-header', 'Create Warehouse')

@section('styles')

@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Warehouses</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Create Warehouse
                    </h3>
                </div>
                <form action="{{ route('warehouses.store') }}" method="post" id="createWarehouse">
                    @csrf
                    @include('features.masters.warehouse.partials._create')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/warehouse/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
@endsection
