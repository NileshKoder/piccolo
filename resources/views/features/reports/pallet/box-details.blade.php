@extends('layouts.app')

@section('title', 'Box Pallet Report')

@section('page-header', 'Box Pallet Report')

@section('breadcrumb')
    <li class="breadcrumb-item active">Report</li>
    <li class="breadcrumb-item active">Box Pallet Report</li>
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
                            Pallet Report
                        </h3>
                        <!-- <div class="card-tools">
                        <a href="#" data-href="{{ route('pallet-report.getExcel') }}">
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
                                            <i class="fa fa-search"></i> Search Pallets
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <form id="palletFilter">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Master Pallets</label>
                                                        <select name="master_pallet_id" id="master_pallet_id" class="form-control select2">
                                                            <option value="">Select Master Pallet</option>
                                                            @foreach ($data['masterPallets'] as $masterPallet)
                                                                <option value="{{ $masterPallet->id }}" @if(!empty($pallet) && $pallet->master_pallet_id == $masterPallet->id) selected @endif>{{ $masterPallet->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Order</label>
                                                        <select name="order_id" id="order_id" class="form-control select2">
                                                            <option value="">Select Order</option>
                                                            @foreach ($data['orders'] as $order)
                                                                <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <button type="button" class="btn btn-primary" id="filter">Search</button>
                                                <button type="button" class="btn btn-default" id="clear">Clear</button>
                                                <button type="button" class="btn btn-warning ml-2" id="getExcel" data-route="{{ route('box-pallet-report.getExcel') }}">Excel Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover mt-5" id="boxPalletReportDataTable">
                            <thead>
                            <tr>
                                <th>Pallet Code</th>
                                <th>Box Name</th>
                                <th>Last PickUp Location</th>
                                <th>Current Location</th>
                                <th>Mapped Order</th>
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
    <script src="{{asset('js/features/reports/pallet/box-pallet-index.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $(document).on('click', '#getExcel', function() {
                var formData = $('#palletFilter').serialize();

                window.location.href = $(this).attr('data-route') + "?" + formData;
            });

        });
        initBoxPalletReportDataTable("{{ route('box-pallet-report.getBoxPalletReport') }}");
    </script>
@endsection
