@extends('layouts.app')

@section('title', 'Order Management')

@section('page-header', 'Order Management')

@section('breadcrumb')
<li class="breadcrumb-item active">Order Management</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Order Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.create') }}">
                            <button type="button" class="btn btn-success float-right">
                                <i class="fas fa-plus"></i> Add Order
                            </button>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @include('features.orders.partials._search')
                    @include('features.orders.partials._list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/orders/index.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('orders.getOrders') }}");
</script>
@endsection
