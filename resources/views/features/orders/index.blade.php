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
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Order Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.create') }}">
                            <button type="button" class="btn btn-success float-right">
                                <i class="fas fa-plus"></i> Add Order
                            </button>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @include('features.orders.partials._search')
                    @include('features.orders.partials._list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/orders/index.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('orders.getOrders') }}");

    $(document).ready(function() {
        $(document).on('click', '.updateState', function() {
            let url = $(this).data('update_state_route');

            swal({
                    title: "Are you sure?",
                    text: "You want to change status? You won't revert this record?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((changeStatus) => {
                    if(changeStatus) {
                        $.ajax({
                            type: "post",
                            url: url,
                            data: {
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                console.log(response);
                                if (response.code == 200) {
                                    toastr.success('Order Status Changed Successfully');
                                    initWareHouseDataTable("{{ route('orders.getOrders') }}");
                                }

                                if (response.code == 500) {
                                    toastr.error('something went wrong!!');
                                }
                            }
                        });
                    } else {
                        toastr.warning('Order Status : Not Changed!!');
                    }
                });
        })
    });

    $(document).ready(function() {
        $(document).on('click', '.updateStateAsComplete', function() {
            let url = $(this).data('update_state_route');

            swal({
                    title: "Are you sure?",
                    text: "You want to change status? You won't revert this record?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((changeStatus) => {
                    if(changeStatus) {
                        $.ajax({
                            type: "post",
                            url: url,
                            data: {
                                _token: "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                console.log(response);
                                if (response.code == 200) {
                                    toastr.success('Order Status Changed Successfully');
                                    initWareHouseDataTable("{{ route('orders.getOrders') }}");
                                }

                                if (response.code == 500) {
                                    toastr.error('something went wrong!!');
                                }
                            }
                        });
                    } else {
                        toastr.warning('Order Status : not changed!!');
                    }
                });
        })
    });
</script>
@endsection
