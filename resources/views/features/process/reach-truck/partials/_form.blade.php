<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Transfer By <span class="text-danger">*</span></label>
                <select name="transfered_by" id="transfered_by" class="form-control select2">
                    <option value="">Select Reach Truck</option>
                    @foreach ($data['reachTruckUsers'] as $reachTruckUser)
                    <option value="{{ $reachTruckUser->id }}" @if(!empty($reachTruck) && $reachTruck->transfered_by == $reachTruckUser->id) selected @endif>{{ $reachTruckUser->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <input type="hidden" name="from_locationable_type" id="from_locationable_type" value="{{ $data['fromLocationType'] }}">
                <label for="name">Pick Up Location <span class="text-danger">*</span></label>
                <select name="from_locationable_id" id="from_locationable_id" class="form-control select2" @if(!empty($reachTruck)) disabled @endif>
                    <option value="">Select Location</option>
                    @foreach ($data['fromLocations'] as $fromLocation)
                    <option value="{{ $fromLocation->id }}" @if(!empty($reachTruck) && $reachTruck->from_locationable_id == $fromLocation->id) selected @endif>{{ $fromLocation->abbr ?? $fromLocation->name  }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Pallet <span class="text-danger">*</span></label>
                <select name="reach_truck_id" id="reach_truck_id" class="form-control select2" @if(!empty($reachTruck)) disabled @endif>
                    <option value="">Select Pallet</option>
                    @foreach ($data['reachTrucks'] as $nonTransferredReachTruck)
                    <option value="{{ $nonTransferredReachTruck->id }}" @if($nonTransferredReachTruck->id == $reachTruck->id) selected @endif>{{ $nonTransferredReachTruck->pallet->masterPallet->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4 to_locationable_id_div">
            <div class="form-group">
                <input type="hidden" name="to_locationable_type" value="{{ $data['toLocationType'] }}">
                <label for="name">Drop Location <span class="text-danger">*</span></label>
                <select name="to_locationable_id" id="to_locationable_id" class="form-control select2">
                    <option value="">Select Location</option>
                    @foreach ($data['toLocations'] as $toLocation)
                    <option value="{{ $toLocation->id }}" @if(!empty($reachTruck) && $reachTruck->to_locationable_id == $toLocation->id) selected @endif>{{ $toLocation->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="card-footer clearfix">
    <button type="sumbit" class="btn btn-primary"><i class="fas fa-disk"></i> Submit</button>
</div>
