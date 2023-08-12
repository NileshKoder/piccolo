@extends('layouts.app')

@section('title', 'Fill New Pallet With Box Details')

@section('page-header', 'Fill New Pallet Box')

@section('styles')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">Pallet Management</li>
    <li class="breadcrumb-item active">Fill New Pallet Box</li>
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
                    <form action="{{ route('pallets.update.box-details', $pallet->id) }}" method="post" id="createFillPallets">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @include('features.process.pallet-management.partials._pallet-box-details-form')
                        </div>
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary submit_btn"><i class="fas fa-disk"></i> Submit</button>
                            <button type="submit" name="request_for_warehouse" id="request_for_warehouse_btn" value="true" class="btn btn-warning submit_btn"><i class="fas fa-disk"></i> Request for Warehouse</button>
                            <button type="submit" name="request_for_loading" id="request_for_loading_btn" value="true" class="btn btn-secondary submit_btn"><i class="fas fa-disk"></i> Request for Loading</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/features/process/pallet-management/box-form.js?v=1.0')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script>
    </script>
@endsection
