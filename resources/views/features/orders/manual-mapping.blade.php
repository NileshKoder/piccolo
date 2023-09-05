@php use App\Features\Masters\Warehouses\Domains\Models\Warehouse; @endphp
@extends('layouts.app')

@section('title', 'Manual Mapping | Order Management')

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
                    <i class="fas fa-receipt mr-1"></i> Mapping Pallet Details of Order #{{ $order->order_number }}
                </h3>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-record-vinyl mr-1"></i>
                            Item Details
                        </h3>
                    </div>
                    <div class="card-body text-lg">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Sku Code :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $orderItem->skuCode->name }}
                            </div>
                            <div class="col-md-6">
                                <b>Variant :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $orderItem->variant->name }}
                            </div>

                            <div class="col-md-6">
                                <b>Pick Up Date :</b>
                            </div>
                            <div class="col-md-6">
                                {{ date('d-M-Y', strtotime($orderItem->pick_up_date)) }}
                            </div>
                            <div class="col-md-6">
                                <b>Drop Location :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $orderItem->location->abbr }}
                            </div>
                            <div class="col-md-6">
                                <b>Required Weight :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $orderItem->required_weight }}
                            </div>
                            <div class="col-md-6">
                                <b>Mapped Weight :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $orderItem->orderItemPalletDetails->sum('mapped_weight') }}
                            </div>
                            <div class="col-md-6">
                                <b>Remaining Weight :</b>
                            </div>
                            <div class="col-md-6">
                                @if(($orderItem->required_weight -  $orderItem->orderItemPalletDetails->sum('mapped_weight')) < 0)
                                    0
                                @else
                                    {{ $orderItem->required_weight -  $orderItem->orderItemPalletDetails->sum('mapped_weight') }}
                                @endif
                            </div>
                            <div class="col-md-6">
                                <b>Transferred Weight :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $orderItem->orderItemPalletDetails->sum('mapped_weight') - $data['nonTransferredfMappedWeight'] }}
                            </div>
                            <div class="col-md-6">
                                <b>Non-Transferred Weight :</b>
                            </div>
                            <div class="col-md-6">
                                {{ $data['nonTransferredfMappedWeight'] }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">
                            <i class="fas fa-pallet mr-1"></i>
                            Pallets at Warehouse
                        </h3>
                    </div>
                    <form action="{{ route('order.orderItem.store-manual-mapping', [$order->id, $orderItem->id]) }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="remaining_weight" value="{{ $orderItem->required_weight -  $data['transferredfMappedWeight']}}">
                                    <input type="hidden" id="non_transferred_mapped_weight" value="{{ $data['nonTransferredfMappedWeight'] }}">
                                    <input type="hidden" id="mapped_weight_input" value="0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Pallet</th>
                                                <th>Batch</th>
                                                <th>Sku Code</th>
                                                <th>Variant</th>
                                                <th>Current Location</th>
                                                <th>Weight</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            @php($counter = 0)
                                            @foreach($data['palletDetails'] as $palletDetail)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="order_item_pallets[{{ $counter }}][order_item_pallet_id]">
                                                        <input type="hidden" name="order_item_pallets[{{ $counter }}][is_updated]" value="true">
                                                        <input type="checkbox" name="order_item_pallets[{{ $counter }}][pallet_detail_id]"
                                                               class="pallet_detail_id" value="{{ $palletDetail->id }}"
                                                                data-weight="{{ $palletDetail->weight }}">
                                                    </td>
                                                    <td>{{ $palletDetail->pallet->masterPallet->name }}</td>
                                                    <td>{{ $palletDetail->batch }}</td>
                                                    <td>{{ $palletDetail->skuCode->name }}</td>
                                                    <td>{{ $palletDetail->variant->name }}</td>
                                                    <td>{{ $palletDetail->pallet->masterPallet->last_locationable->name }}</td>
                                                    <td>{{ $palletDetail->weight }}</td>
                                                </tr>
                                                @php($counter++)
                                            @endforeach
                                            @if($orderItem->orderItemPallets->count() > 0)
                                                <tr>
                                                    <td colspan="7" class="text-center font-weight-bold"> Mapped But Non Transferred Pallets</td>
                                                </tr>
                                                @php($counter++)
                                                @foreach($orderItem->orderItemPallets->sortByDesc('id') as $orderItemPallet)
                                                    @if($orderItemPallet->pallet->masterPallet->last_locationable_type == Warehouse::class)
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="order_item_pallets[{{ $counter }}][order_item_pallet_id]" value="{{ $orderItemPallet->id }}">
                                                                <input type="hidden" name="order_item_pallets[{{ $counter }}][is_updated]" value="true">
                                                                <input type="checkbox" name="order_item_pallets[{{ $counter }}][pallet_detail_id]"
                                                                       class="pallet_detail_id" value="{{ $orderItemPallet->pallet_detail_id }}"
                                                                       data-weight="{{ $orderItemPallet->palletDetail->weight }}" checked>
                                                            </td>
                                                            <td>{{ $orderItemPallet->palletDetail->pallet->masterPallet->name }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->batch }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->skuCode->name }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->variant->name }}</td>
                                                            <td>{{ $orderItemPallet->pallet->masterPallet->last_locationable->name }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->weight }}</td>
                                                        </tr>
                                                    @endif
                                                    @php($counter++)
                                                @endforeach
                                                <tr>
                                                    <td colspan="7" class="text-center font-weight-bold"> Mapped & Transferred Pallets</td>
                                                </tr>
                                                @php($counter++)
                                                @foreach($orderItem->orderItemPallets->sortByDesc('id') as $orderItemPallet)
                                                    @if($orderItemPallet->pallet->masterPallet->last_locationable_type != Warehouse::class)
                                                        <tr>
                                                            <input type="hidden" name="order_item_pallets[{{ $counter }}][order_item_pallet_id]" value="{{ $orderItemPallet->id }}">
                                                            <input type="hidden" name="order_item_pallets[{{ $counter }}][is_updated]" value="false">
                                                            <td></td>
                                                            <td>{{ $orderItemPallet->palletDetail->pallet->masterPallet->name }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->batch }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->skuCode->name }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->variant->name }}</td>
                                                            <td>{{ $orderItemPallet->pallet->masterPallet->last_locationable->name }}</td>
                                                            <td>{{ $orderItemPallet->palletDetail->weight }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" class="text-right">
                                                </th>
                                                <th>
                                                    <span id="total_mapped_weight">{{ $data['nonTransferredfMappedWeight'] }}</span> / {{ $orderItem->required_weight -  $orderItem->orderItemPalletDetails->sum('mapped_weight') + $data['nonTransferredfMappedWeight']}}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                <i class="fa fa-save"></i>  Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/features/orders/manual-mapping.js')}}"></script>
@endsection
