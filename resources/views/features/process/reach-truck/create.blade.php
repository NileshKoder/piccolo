@extends('layouts.app')

@section('title', 'Reach Truck - Transfer Pallet')

@section('page-header', 'Reach Truck - Transfer Pallet')

@section('breadcrumb')
<li class="breadcrumb-item active">Reach Truck - Transfer Pallet</li>
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
                        Transfer Pallet
                    </h3>
                </div>
                <form action="{{ route('reach-trucks.store') }}" method="post" id="createReachTruckForm">
                    @csrf
                    <input type="hidden" id="type" value="{{ request()->get('type') }}">
                    @include('features.process.reach-truck.partials._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/process/reach-truck/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
