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
                @foreach ($order->orderItems as $key => $ordeItem)
                <tr>
                    <input type="hidden" class="order_item_detail_id" name="order_item_details[{{ $key }}][order_item_id]" value="{{ $ordeItem->id }}">
                    <td>
                        <input type="hidden" class="selected_sku_id" name="order_item_details[{{ $key }}][sku_code_id]" value="{{ $ordeItem->sku_code_id }}">
                        <input type="text" class="form-control" value="{{ $ordeItem->skuCode->name }}" disabled>
                    </td>
                    <td>
                        @if(!empty($ordeItem->variant))
                        <input type="hidden" class="selected_variant_id" name="order_item_details[{{ $key }}][variant_id]" value="{{ $ordeItem->variant_id }}">
                        <input type="text" class="form-control" value="{{ $ordeItem->variant->name }}" disabled>
                        @else
                        <select name="order_item_details[{{ $key }}][variant_id]" class="from-control select2" required>
                            <option value="">Select Variant</option>
                            @foreach ($masterData['variants'] as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                            @endforeach
                        </select>
                        @endif
                    </td>
                    <td>
                        <input type="hidden" name="order_item_details[{{ $key }}][required_weight]" value="{{ $ordeItem->required_weight }}">
                        <input type="text" class="form-control" value="{{ $ordeItem->required_weight }}" disabled>
                    </td>
                    <td>
                        @if($ordeItem->pick_up_date != "1970-01-01")
                        <input type="hidden" name="order_item_details[{{ $key }}][pick_up_date]" value="{{ $ordeItem->pick_up_date }}">
                        <input type="text" class="form-control" value="{{ date('d/m/Y', strtotime($ordeItem->pick_up_date)) }}" disabled>
                        @else
                        <input type="date" name="order_item_details[{{ $key }}][pick_up_date]" class="form-control">
                        @endif
                    </td>
                    <td>
                        @if(!empty($ordeItem->location))
                        <input type="hidden" name="order_item_details[{{ $key }}][location_id]" class="weight" value="{{ $ordeItem->location_id }}">
                        <input type="text" class="form-control" value="{{ $ordeItem->location->abbr }}" disabled>
                        @else
                        <select name="order_item_details[{{ $key }}][   ]" class="from-control select2" required>
                            <option value="">Select Location</option>
                            @foreach ($masterData['locations'] as $location)
                            <option value="{{ $location->id }}">{{ $location->abbr }}</option>
                            @endforeach
                        </select>
                        @endif
                    </td>
                    <td>
                        <input type="text" name="order_item_details[{{ $key }}][state]" class="form-control" value="{{ $ordeItem->state }}" readonly>
                        <!-- <select name="order_item_details[{{ $key }}][state]" class="form-control state">
                            <option value="">Select State</option>
                            @foreach ($masterData['orderItemStates'] as $orderItemState)
                            <option value="{{ $orderItemState }}" @if($ordeItem->state == $orderItemState) selected @endif>{{ $orderItemState }}</option>
                            @endforeach
                        </select> -->
                    </td>
                    <td class="text-center">
                        @if($ordeItem->state == $masterData['orderItemCreate'])
                        <a href="javascript:void(0);" class="text-danger remove-sku">
                            <i class="fa fa-trash"></i>
                        </a>
                        @elseif ($ordeItem->state == $masterData['orderItemMapped'] || $ordeItem->state == $masterData['orderItemPartialMapped'] || $ordeItem->state == $masterData['orderItemTransffered'])
                        <a href="{{ route('orders.getOrderIteMappedDetails', [$ordeItem->order_id, $ordeItem->id]) }}" class="text-info">
                            <i class="fa fa-eye"></i>
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
