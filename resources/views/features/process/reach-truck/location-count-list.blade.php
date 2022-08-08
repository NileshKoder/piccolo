@extends('layouts.app')

@section('title', 'Reach Truck - Location Count')

@section('page-header', 'Reach Truck - Location Count')

@section('breadcrumb')
<li class="breadcrumb-item">Reach Truck</li>
<li class="breadcrumb-item active">Reach Truck - Location Count</li>
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
                        Reach Truck - Location Count
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @include('features.process.reach-truck.partials._location-count')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
