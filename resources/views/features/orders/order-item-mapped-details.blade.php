@extends('layouts.app')

@section('title', 'Order Management')



@section('breadcrumb')
<li class="breadcrumb-item active">Order Management</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
        </div>
        <div class="col-md-12 mb-3">
            <h3>
                <i class="fas fa-chart-pie mr-1"></i>
                Mapped Pallet Details of Order #{{ $order->order_number }}
            </h3>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Item Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <b>Sku Code :</b>
                        </div>
                        <div class="col-md-6">
                            {{ $orderItem->skuCode->name }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Variant :</b>
                        </div>
                        <div class="col-md-6">
                            {{ $orderItem->variant->name }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Required Weight :</b>
                        </div>
                        <div class="col-md-6">
                            {{ $orderItem->required_weight }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Drop Location :</b>
                        </div>
                        <div class="col-md-6">
                            {{ $orderItem->location->abbr }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Pick Up Date :</b>
                        </div>
                        <div class="col-md-6">
                            {{ date('d-M-Y', strtotime($orderItem->pick_up_date)) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <b>Current Status :</b>
                        </div>
                        <div class="col-md-6">
                            {{ $orderItem->state }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Mapped Pallet Details
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Pallet</th>
                                <th>Weight</th>
                                <th>Mapped Weight</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItem->orderItemPallets as $orderItemPallet)
                            <tr>
                                <td>{{ $orderItemPallet->pallet->masterPallet->name }}</td>
                                <td>
                                    {{ $orderItemPallet->pallet->palletDetails->where('sku_code_id', $orderItem->sku_code_id)->where('variant_id',  $orderItem->variant_id)->sum('weight') }}
                                </td>
                                <td>
                                    {{ $orderItemPallet->weight }}
                                </td>
                                <!-- <td>
                                    @if(!$orderItemPallet->is_transfered)
                                    <a href="javascript:void(0);" data-order_item_pallet_id="{{ $orderItemPallet->id }}" class="text-danger remove-mapped-pallet">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    @endif
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/sweetalert.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.remove-mapped-pallet', function() {
            swal({
                    title: "Are you sure?",
                    text: "You want to unmapped this pallet? You won't revert this record?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((changeStatus) => {
                    $.ajax({
                        type: "post",
                        url: "{{ route('order.orderItem.unmappedPallet') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order_item_pallet_id: $(this).data('order_item_pallet_id')
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.code == 200) {
                                toastr.success('sku removed successfully');
                                window.location.reload();
                            }

                            if (response.code == 500) {
                                toastr.error('something went wrong!!');
                            }
                        }
                    });
                });
        })
    });
</script>
@endsection
