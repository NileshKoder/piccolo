@extends('layouts.app')

@section('title', 'Pallet Management')

@section('page-header', 'Pallet Management')

@section('breadcrumb')
<li class="breadcrumb-item active">Pallet Management</li>
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
                        All Pallets
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('pallets.create') }}">
                            <button type="button" class="btn btn-primary float-right ml-2">
                                <i class="fas fa-plus"></i> Fill New Pallet With Sku Details
                            </button>
                        </a>
                        <a href="{{ route('pallets.create.box-details') }}">
                            <button type="button" class="btn btn-warning float-right">
                                <i class="fas fa-plus"></i> Fill New Pallet With Box Details
                            </button>
                        </a>
                    </div>
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
                                    <div class="row">
                                        <div class="col-3">
                                            <label>Pallet</label>
                                            <select id="master_pallet_id" class="form-control select2">
                                                <option value="">Select Pallet</option>
                                                @foreach ($data['masterPallets'] as $masterPallet)
                                                    <option value="{{ $masterPallet->id }}">{{ $masterPallet->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label>Sku</label>
                                            <select id="sku_code_id" class="form-control select2">
                                                <option value="">Select Sku Code</option>
                                                @foreach ($data['skuCodes'] as $skuCode)
                                                    <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label for="email">Variant</label>
                                            <select id="variant_id" class="form-control select2">
                                                <option value="">Select Variant</option>
                                                @foreach ($data['variants'] as $variant)
                                                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label>Location </label>
                                            <select name="location_id" id="location_id" class="form-control select2">
                                                <option value="">Select Locations</option>
                                                @foreach ($data['locations'] as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-3">
                                            <label>Mapped Orders </label>
                                            <select name="order_id" id="order_id" class="form-control select2">
                                                <option value="">Select Order</option>
                                                @foreach ($data['orders'] as $order)
                                                    <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 mt-4">
                                            <button type="button" id="search_pallet" class="btn btn-success">Submit</button>
                                            <button type="button" id="clear_pallet" class="btn btn-default ml-1" >Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover" id="palletDatatable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Id</th>
                                <th>Pallet Code</th>
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
    @include('features.process.pallet-management.partials._set_date_for_loading_modal')
@endsection

@section('scripts')
<script src="{{asset('js/features/process/pallet-management/index.js?v=0.4')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initPalletDataTable("{{ route('pallets.getAllPallets') }}");

    $(document).ready(function () {
        $('.select2').select2();

        $(document).on('click', '.setDateForLoading', function () {
            let palletId = $(this).data('pallet_id');
            let palletName = $(this).data('pallet-name');
            let loadingTransferDate = $(this).data('loading_transfer_date');

            $('#set_date_for_loading_pallet_id').val(palletId)
            $('#set_date_for_loading_pallet_name').text(palletName)
            $('#last_set_date').text(loadingTransferDate)

            $('#set_date_for_loading_modal').modal('show')
        })
    })
</script>
@endsection
