@extends('layouts.app')

@section('title', 'Pallet Report')

@section('page-header', 'Pallet Report')

@section('breadcrumb')
<li class="breadcrumb-item active">Report</li>
<li class="breadcrumb-item active">Pallet Report</li>
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
                                                    <label for="email">Sku Codes</label>
                                                    <select name="sku_code_id" id="sku_code_id" class="form-control select2">
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
                                                    <select name="variant_id" id="variant_id" class="form-control select2">
                                                        <option value="">Select Variant</option>
                                                        @foreach ($data['variants'] as $variant)
                                                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Batch Date</label>
                                                    <input type="date" id="batch_date" name="batch_date" class="form-control">
                                                </div>
                                            </div>
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
                                        </div>
                                        <div class="row mb-3">
                                            <button type="button" class="btn btn-primary" id="filter">Search</button>
                                            <button type="button" class="btn btn-default ml-2" id="clear">Clear</button>
                                            <button type="button" class="btn btn-warning ml-2" id="getExcel" data-route="{{ route('pallet-report.getExcel') }}">Excel Export</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover mt-5" id="palletReportDatatble">
                        <thead>
                            <tr>
                                <th>Pallet Code</th>
                                <th>Sku Code</th>
                                <th>Variant Code</th>
                                <th>Weight</th>
                                <th>Batch</th>
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
<script src="{{asset('js/features/reports/pallet/index.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $(document).on('click', '#getExcel', function() {
            var formData = $('#palletFilter').serialize();

            window.location.href = $(this).attr('data-route') + "?" + formData;
        });
    });
    initPalletReportDataTable("{{ route('pallet-report.getPalletReport') }}");
</script>
@endsection
