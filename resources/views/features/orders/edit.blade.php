@extends('layouts.app')

@section('title', 'Edit - Order Management')

@section('page-header', 'Edit - Order Management')

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
                        Edit Order Management
                    </h3>
                </div>
                <form action="{{ route('orders.update', $order->id) }}" method="post" id="editOrderCreationForm">
                    @csrf
                    @method("PUT")
                    @include('features.orders.partials._edit')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/process/order/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $('#editOrderCreationForm').submit(function() {
            $('select').removeAttr('disabled');
        });
    });
</script>

@endsection
