@extends('layouts.app')

@section('title', 'Home')

@section('page-header', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $data['filled_pallets'] }} / {{ $data['unfilled_pallets'] }}</h3>
                    <p>Filled Pallets / Unfilled Pallets</p>
                </div>
                <div class="icon">
                    <!-- <i class="ion ion-bag"></i> -->
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $data['filled_warehouses'] }} / {{ $data['unfilled_warehouses'] }}</h3>
                    <p>Warehouse Locations Filled / Unfilled </p>
                </div>
                <div class="icon">
                    <!-- <i class="ion ion-bag"></i> -->
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $data['active_orders'] }}</h3>
                    <p>Total Active Orders</p>
                </div>
                <div class="icon">
                    <!-- <i class="ion ion-bag"></i> -->
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Order Stats
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.index') }}" target="_blank">
                            <button type="button" class="btn float-right ml-2">
                                <i class="fas fa-link text-primary"></i>
                            </button>
                        </a>
                        <button type="button" class="btn btn-tool refreshOrderStats" data-card-widget="card-refresh" data-source="#"
                                data-source-selector="#card-refresh-content" data-load-on-init="false" title="Refresh Order Stats"
                                data-order_stats_route="{{ route('home.order.stats') }}">
                            <i class="fas fa-sync-alt text-dark"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>State</th>
                            <th>Count</th>
                            <th>Oldest</th>
                        </tr>
                        </thead>
                        <tbody id="orderStatsTbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-inline-flex">
                        <i class="fas fa-chart-pie mr-1"></i>
                        <div style="width: 70mm">
                        Pickup Orders as on
                        </div>
                        <input type="date" id="pickUpDate" class="form-control" value="{{ date('Y-m-d') }}" data-order_stats_route="{{ route('home.order.get-order-by-pick-up-date') }}">
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.index') }}" target="_blank">
                            <button type="button" class="btn float-right ml-2">
                                <i class="fas fa-link text-primary"></i>
                            </button>
                        </a>
                        <button type="button" class="btn btn-tool refreshTodayPickupOrders" data-card-widget="card-refresh" data-source="#"
                                data-source-selector="#card-refresh-content" data-load-on-init="false" title="Refresh Today Pickup Orders"
                                data-order_stats_route="{{ route('home.order.get-order-by-pick-up-date') }}">
                            <i class="fas fa-sync-alt text-dark"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Location</th>
                            <th>Count</th>
                        </tr>
                        </thead>
                        <tbody id="pickUpOrdersTbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/features/home/home.js') }}"></script>

    <script>
        getOrderStats('{{ route('home.order.stats') }}');
        getOrdersByPickUpDate('{{ route('home.order.get-order-by-pick-up-date') }}');
    </script>
@endsection
