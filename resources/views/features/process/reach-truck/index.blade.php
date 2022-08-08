@extends('layouts.app')

@section('title', 'Reach Truck')

@section('page-header', 'Reach Truck')

@section('breadcrumb')
<li class="breadcrumb-item active">Reach Truck</li>
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
                        Reach Truck Details
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('reach-trucks.create') }}" target="_blank">
                            <button type="button" class="btn btn-primary float-right">
                                <i class="fas fa-plus"></i> Transfer Pallet
                            </button>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="reachTruckDatatble">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Id</th>
                                <th>Pallet</th>
                                <th>From Location</th>
                                <th>To Location</th>
                                <th>Is Transfer</th>
                                <th>Transfer By</th>
                                <th>Transfer At</th>
                                <th>Created By</th>
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
<script src="{{asset('js/features/process/reach-truck/index.js?v=1.0')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initReachTruckDataTable("{{ route('reach-trucks.getRechTrucks') }}");
</script>
@endsection
