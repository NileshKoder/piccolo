@extends('layouts.app')

@section('title', 'Fill New Pallet')

@section('page-header', 'Fill New Pallet')

@section('styles')

@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Pallet Management</li>
<li class="breadcrumb-item active">Fill New Pallet</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        All Pallets
                    </h3>
                </div>
                <form action="{{ route('pallets.store') }}" method="post" id="createFillPallets">
                    @csrf
                    <div class="card-body">
                        @include('features.process.pallet-management.partials._from')
                        <div class="row mb-2">
                            <div class="col-md-12 text-center">
                                <button type="button" id="add_sku" class="btn btn-warning"><i class="fas fa-plus"></i> Add Sku</button>
                            </div>
                        </div>
                        @include('features.process.pallet-management.partials._pallet-details-table')
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary submit_btn"><i class="fas fa-disk"></i> Submit</button>
                        <button type="submit" name="request_for_warehouse" id="request_for_warehouse_btn" value="true" class="btn btn-warning submit_btn"><i class="fas fa-disk"></i> Request for Warehouse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/process/pallet-management/form.js?v=1.1')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
</script>
@endsection
