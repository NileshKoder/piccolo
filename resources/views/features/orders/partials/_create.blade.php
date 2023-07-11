<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Order Number</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="{{ old('order_number') }}" placeholder="Enter Order Numer">
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr>
                        <td>
                            <select name="order_item_details[0][sku_code_id]" id="sku_code_id" class="form-control sku_code_id">
                                <option value="">Select Sku Code</option>
                                @foreach ($masterData['skuCodes'] as $skuCode)
                                <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="order_item_details[0][variant_id]" id="variant_id" class="form-control variant_id">
                                <option value="">Select Variant</option>
                                @foreach ($masterData['variants'] as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="order_item_details[0][required_weight]" id="required_weight" class="form-control required_weight" placeholder="Enter Required Weight" value="">
                        </td>
                        <td>
                            <input type="text" name="order_item_details[0][pick_up_date]" placeholder="Enter Pick Up Date" class="form-control pick_up_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" inputmode="numeric" data-mask value="{{ date('d-m-Y') }}">
                        </td>
                        <td>
                            <select name="order_item_details[0][location_id]" id="location_id" class="form-control location_id">
                                <option value="">Select Location</option>
                                @foreach ($masterData['locations'] as $location)
                                <option value="{{ $location->id }}" @if(!empty($crateCodeCreation) && $crateCodeCreation->location_id == $location->id) selected @endif>{{ $location->abbr }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0);" class="text-danger remove-sku">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
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
    <button type="sumit" class="btn btn-info">
        <i class="fa fa-save"></i> Submit
    </button>
</div>
