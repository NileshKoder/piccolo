@extends('layouts.app')

@section('title', 'Order Report')

@section('page-header', 'Order Report')

@section('breadcrumb')
    <li class="breadcrumb-item active">Report</li>
    <li class="breadcrumb-item active">Order Report</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('features.common.alerts')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Order Report
                        </h3>
                        <!-- <div class="card-tools">
                        <a href="#" data-href="{{ route('order-report.getExcel') }}">
                            <button type="button" class="btn btn-primary float-right">
                                <i class="fas fa-excel"></i> Excel Export
                            </button>
                        </a>
                    </div> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="accordion">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                            <i class="fa fa-search"></i> Search Orders
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <form id="orderFilter">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Order</label>
                                                        <select id="order_id" name="order_id" class="form-control select2">
                                                            <option value="">Select Order</option>
                                                            @foreach ($data['orders'] as $order)
                                                                <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Sku Codes</label>
                                                        <select id="sku_code_id" name="sku_code_id" class="form-control select2">
                                                            <option value="">Select Sku Code</option>
                                                            @foreach ($data['skuCodes'] as $skuCode)
                                                                <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Variant</label>
                                                        <select id="variant_id" name="variant_id" class="form-control select2">
                                                            <option value="">Select Variant</option>
                                                            @foreach ($data['variants'] as $variant)
                                                                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Pickup Date</label>
                                                        <input type="date" class="form-control" id="pickup_date" name="pickup_date">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Order Status</label>
                                                        <select id="order_state" name="order_state" class="form-control select2">
                                                            <option value="">Select State</option>
                                                            @foreach ($data['orderStates'] as $orderState)
                                                                <option value="{{ $orderState }}">{{ $orderState }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Order Items Status</label>
                                                        <select id="order_item_state" name="order_item_state" class="form-control select2">
                                                            <option value="">Select State</option>
                                                            @foreach ($data['orderItemStates'] as $orderItemState)
                                                                <option value="{{ $orderItemState }}">{{ $orderItemState }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-primary" id="filter">Search</button>
                                                <button type="button" class="btn btn-default" id="clear">Clear</button>
                                                <button type="button" class="btn btn-warning" id="getExcel" data-route="{{ route('order-report.getExcel') }}">Excel Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover mt-5" id="orderReportDataTable">
                            <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Sku Code</th>
                                <th>Variant Code</th>
                                <th>Weight</th>
                                <th>Mapped Weight</th>
                                <th>Pickup Date</th>
                                <th>Order Status</th>
                                <th>Order Item Status</th>
                                <th>Last Modified By</th>
                                <th>Last Modified At</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/features/reports/order/index.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $(document).on('click', '#getExcel', function() {
                var formData = $('#orderFilter').serialize();

                window.location.href = $(this).attr('data-route') + "?" + formData;
            });
        });
        initOrderReportDataTable("{{ route('order-report.getOrderReport') }}");
    </script>
@endsection
