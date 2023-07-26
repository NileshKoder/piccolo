<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">Sku Code</label>
            <select id="sku_code_id" class="form-control sku_code_id select2">
                <option value="">Select Sku Code</option>
                @foreach ($masterData['skuCodes'] as $skuCode)
                <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">Variant</label>
            <select id="variant_id" class="form-control variant_id select2">
                <option value="">Select Variant</option>
                @foreach ($masterData['variants'] as $variant)
                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">Required Weight</label>
            <input type="text" id="required_weight" class="form-control required_weight" placeholder="Enter Required Weight" value="">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">Drop Location</label>
            <select id="location_id" class="form-control location_id select2">
                <option value="">Select Location</option>
                @foreach ($masterData['locations'] as $location)
                <option value="{{ $location->id }}" @if(!empty($crateCodeCreation) && $crateCodeCreation->location_id == $location->id) selected @endif>{{ $location->abbr }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="name">Pick Up Date</label>
            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input pick_up_date" id="pick_up_date" data-target="#reservationdate" value="{{ date('d-m-Y') }}">
                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-12 text-center">
        <button type="button" id="add_sku" class="btn btn-warning add-more-sku"><i class="fas fa-plus"></i> Add Sku</button>
    </div>
</div>
