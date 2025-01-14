@extends('layouts.app')

@section('title', 'Order Management')

@section('page-header', 'Order Management')

@section('breadcrumb')
<li class="breadcrumb-item active">Order Management</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-12">
            <div class="card" id="createDiv">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Create Order Management
                    </h3>
                </div>
                <form action="{{ route('orders.store') }}" method="post" id="createOrderCreationForm">
                    @csrf
                    <input type="hidden" id="order_screen" value="create">
                    @include('features.orders.partials._create')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/orders/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
@endsection
