<div id="accordion">
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title w-100">
                <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                    <i class="fa fa-search"></i> Search Orders
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label>Sku</label>
                        <select id="sku_code_id" class="form-control select2">
                            <option value="">Select Sku Code</option>
                            @foreach ($data['skuCodes'] as $skuCode)
                                <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="email">Variant</label>
                        <select id="variant_id" class="form-control select2">
                            <option value="">Select Variant</option>
                            @foreach ($data['variants'] as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Location </label>
                        <select name="location_id" id="location_id" class="form-control select2">
                            <option value="">Select Locations</option>
                            @foreach ($data['locations'] as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label>States </label>
                        <select name="state" id="state" class="form-control select2">
                            <option value="">Select State</option>
                            @foreach ($data['states'] as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3">
                        <label>PickUp Date </label>
                        <input type="date" name="pickup_date" id="pickup_date" class="form-control">
                    </div>
                    <div class="col-3 mt-4">
                        <button type="button" id="search_order" class="btn btn-success">Submit</button>
                        <button type="button" id="clear_order" class="btn btn-default ml-1" >Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
