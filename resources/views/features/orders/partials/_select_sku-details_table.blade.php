<div class="row ">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Sku</th>
                <th>Variant</th>
                <th>Required Weight</th>
                <th>Date</th>
                <th>Drop Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="tbody">
            @if(!empty($order))
                @foreach ($order->orderItems as $key => $orderItem)
                    <tr>
                        <input type="hidden" class="order_item_detail_id"
                               name="order_item_details[{{ $key }}][order_item_id]" value="{{ $orderItem->id }}">
                        <td>
                            <input type="hidden" class="selected_sku_id"
                                   name="order_item_details[{{ $key }}][sku_code_id]"
                                   value="{{ $orderItem->sku_code_id }}">
                            <input type="text" class="form-control" value="{{ $orderItem->skuCode->name }}" disabled>
                        </td>
                        <td>
                            @if(!empty($orderItem->variant))
                                <input type="hidden" class="selected_variant_id"
                                       name="order_item_details[{{ $key }}][variant_id]"
                                       value="{{ $orderItem->variant_id }}">
                                <input type="text" class="form-control" value="{{ $orderItem->variant->name }}"
                                       disabled>
                            @else
                                <select name="order_item_details[{{ $key }}][variant_id]" class="from-control select2"
                                        required>
                                    <option value="">Select Variant</option>
                                    @foreach ($masterData['variants'] as $variant)
                                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </td>
                        <td>
                            <input type="hidden" name="order_item_details[{{ $key }}][required_weight]"
                                   value="{{ $orderItem->required_weight }}">
                            <input type="text" class="form-control" value="{{ $orderItem->required_weight }}" disabled>
                        </td>
                        <td>
                            @if($orderItem->pick_up_date != "1970-01-01")
                                <input type="hidden" name="order_item_details[{{ $key }}][pick_up_date]"
                                       value="{{ $orderItem->pick_up_date }}">
                                <input type="text" class="form-control"
                                       value="{{ date('d/m/Y', strtotime($orderItem->pick_up_date)) }}" disabled>
                            @else
                                <input type="date" name="order_item_details[{{ $key }}][pick_up_date]"
                                       class="form-control">
                            @endif
                        </td>
                        <td>
                            @if(!empty($orderItem->location))
                                <input type="hidden" name="order_item_details[{{ $key }}][location_id]" class="weight"
                                       value="{{ $orderItem->location_id }}">
                                <input type="text" class="form-control" value="{{ $orderItem->location->abbr }}"
                                       disabled>
                            @else
                                <select name="order_item_details[{{ $key }}][location_id]" class="from-control select2"
                                        required>
                                    <option value="">Select Location</option>
                                    @foreach ($masterData['locations'] as $location)
                                        <option value="{{ $location->id }}">{{ $location->abbr }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </td>
                        <td>
                            <input type="text" name="order_item_details[{{ $key }}][state]" class="form-control"
                                   value="{{ $orderItem->state }}" readonly>
                        </td>
                        <td class="text-center">
                            @if($orderItem->state == $masterData['orderItemCreate'])
                                <a href="javascript:void(0);" class="text-danger remove-sku">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @elseif (in_array($orderItem->state, $masterData['showOrderItemDetailsState']))
                                <a href="{{ route('orders.getOrderIteMappedDetails', [$orderItem->order_id, $orderItem->id]) }}"
                                   class="text-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif

                            @if($orderItem->isItemHasAllDetails() && $order->state != "CANCELLED" &&
                                ($orderItem->state == "CREATED" || $orderItem->state == "PARTIAL_MAPPED" || $orderItem->state == "MAPPED") &&
                                $orderItem->pick_up_date != date('Y-m-d'))
                                <a href="{{ route('order.orderItem.manual-mapping', [$order->id, $orderItem->id]) }}" class="ml-2"
                                    title="Manual Mapping">
                                    <i class="fa fa-check-square text-warning"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
