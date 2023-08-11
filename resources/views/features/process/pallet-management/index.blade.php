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
                        <a href="{{ route('pallets.create.box-deatils') }}">
                            <button type="button" class="btn btn-primary float-right">
                                <i class="fas fa-plus"></i> Fill New Pallet With Box Details
                            </button>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="palletDatatble">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Id</th>
                                <th>Pallet Code</th>
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
<script src="{{asset('js/features/process/pallet-management/index.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('pallets.getAllPallets') }}");
</script>
@endsection
