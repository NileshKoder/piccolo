<div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Location</label>
                <select name="location_id" id="location_id" class="form-control select2">
                    <option value="">Select Locations</option>
                    @foreach ($data['locations'] as $location)
                        <option value="{{ $location->id }}" data-abbr="{{ $location->abbr }}" @if(!empty($pallet) && $pallet->masterPallet->last_locationable_type == "App\Features\Masters\Locations\Domains\Models\Location" && $pallet->masterPallet->last_locationable_id == $location->id) selected @endif>{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input type="hidden" id="max_weight">
                <label>Pallet</label>
                <select name="master_pallet_id" id="master_pallet_id" class="form-control select2">
                    <option value="">Select Master Pallet</option>
                    @foreach ($data['masterPallets'] as $masterPallet)
                        <option value="{{ $masterPallet->id }}" @if(!empty($pallet) && $pallet->master_pallet_id == $masterPallet->id) selected @endif>{{ $masterPallet->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <input type="hidden" id="max_weight">
                <label>Order Number</label>
                <select name="order_id" id="order_id" class="form-control select2">
                    <option value="">Select Order</option>
                    @foreach ($data['orders'] as $order)
                        <option value="{{ $order->id }}" @if(!empty($pallet) && $pallet->order_id == $order->id) selected @endif>{{ $order->order_number }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-md-12">
         <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Box Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @if(!empty($pallet) && $pallet->palletBoxDetails->count() > 0)
                @foreach($pallet->palletBoxDetails as $key => $palletBoxDetail)
                    <tr>
                        <input type="hidden" name="pallet_box_details[{{ $key }}][id]" value="{{ $palletBoxDetail->id }}">
                        <td>
                            <input type="text" name="pallet_box_details[{{ $key }}][box_name]" class="form-control box_name" placeholder="Enter Box Names" value="{{ $palletBoxDetail->box_name }}">
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="deleteBoxDetail text-danger">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <input type="hidden" name="pallet_box_details[0][id]" value="">
                        <td>
                            <input type="text" name="pallet_box_details[0][box_name]" class="form-control box_name" placeholder="Enter Box Names">
                        </td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <th colspan=2>
                    <button type="button" class="btn btn-warning" id="add_box_details"><i class="fa fa-plus"></i> Add</button>
                </th>
            </tfoot>
        </table>
    </div>
</div>
