@extends('layouts.app')

@section('title', 'Home')

@section('page-header', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-6">
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
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h6>{{ $data['most_sku_used']?->skuCode?->name ?? '' }}</h6>
                    <h6> {{ $data['most_sku_used']?->count ?? 0 }}</h6>
                    <p>Most Sku Used</p>
                </div>
                <div class="icon">
                    <!-- <i class="ion ion-bag"></i> -->
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0 / 0</h3>
                    <p>Warehouse Locations Filled / Unfilled </p>
                </div>
                <div class="icon">
                    <!-- <i class="ion ion-bag"></i> -->
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Total Active Orders</p>
                </div>
                <div class="icon">
                    <!-- <i class="ion ion-bag"></i> -->
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
