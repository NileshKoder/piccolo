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
            <label for="email">Sku Codes</label>
            <select id="sku_code_id" class="form-control select2">
                <option value="">Select Sku Code</option>
                @foreach ($data['skuCodes'] as $skuCode)
                <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="email">Variant</label>
            <select id="variant_id" class="form-control select2">
                <option value="">Select Variant</option>
                @foreach ($data['variants'] as $variant)
                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="password">Weight</label>
            <input type="number" class="form-control" id="weight" placeholder="Enter Weight">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="password">Batch</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="batch_prefix">#</span>
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ date('dmY') }}" id="batch_date">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
