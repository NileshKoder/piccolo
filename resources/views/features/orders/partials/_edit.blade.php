<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Order Number</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="{{ $order->order_number }}" placeholder="Enter Order Numer">
            </div>
        </div>
    </div>

    <h4 class="mt-2">Sku Details</h4>
    <hr>
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
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @foreach ($order->ordeItems as $key => $ordeItem)
                    <tr>
                        <input type="hidden" class="order_item_detail_id" name="order_item_details[{{ $key }}][order_item_id]" value="{{ $ordeItem->id }}">
                        <td>
                            <select name="order_item_details[{{ $key }}][sku_code_id]" class="form-control sku_code_id" disabled>
                                <option value="">Select Sku Code</option>
                                @foreach ($masterData['skuCodes'] as $skuCode)
                                <option value="{{ $skuCode->id }}" @if($ordeItem->sku_code_id == $skuCode->id) selected @endif>{{ $skuCode->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="order_item_details[{{ $key }}][variant_id]" class="form-control variant_id" disabled>
                                <option value="">Select Variant</option>
                                @foreach ($masterData['variants'] as $variant)
                                <option value="{{ $variant->id }}" @if($ordeItem->variant_id == $variant->id) selected @endif>{{ $variant->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="order_item_details[{{ $key }}][required_weight]" class="form-control required_weight" placeholder="Enter Required Weight" value="{{ $ordeItem->required_weight }}" readonly>
                        </td>
                        <td>
                            <input type="text" name="order_item_details[{{ $key }}][pick_up_date]" placeholder="Enter Pick Up Date" class="form-control pick_up_date" value="{{ date('d/m/Y', strtotime($ordeItem->pick_up_date)) }}" data-inputmask-alias="datetime" readonly data-inputmask-inputformat="dd-mm-yyyy" inputmode="numeric" data-mask>
                        </td>
                        <td>
                            <select name="order_item_details[{{ $key }}][location_id]" class="form-control location_id" disabled>
                                <option value="">Select Location</option>
                                @foreach ($masterData['locations'] as $location)
                                <option value="{{ $location->id }}" @if($ordeItem->location_id == $location->id) selected @endif>{{ $location->abbr }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="order_item_details[{{ $key }}][state]" class="form-control state">
                                <option value="">Select State</option>
                                @foreach ($masterData['orderItemStates'] as $orderItemState)
                                <option value="{{ $orderItemState }}" @if($ordeItem->state == $orderItemState) selected @endif>{{ $orderItemState }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0);" class="text-danger remove-sku d-none">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">
                            <button type="button" class="btn btn-info add-more-sku">
                                <i class="fa fa-plus"></i> Add More
                            </button>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="sumit" class="btn btn-info subnmitForm">
        <i class="fa fa-save"></i> Submit
    </button>
</div>
