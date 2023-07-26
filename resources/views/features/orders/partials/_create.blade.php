<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Order Number</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="{{ old('order_number') }}" placeholder="Enter Order Numer">
            </div>
        </div>
    </div>
    <hr>
    <h4 class="mt-2">Sku Details</h4>
    @include('features.orders.partials._select_sku')
    <h4 class="mt-2">Preview</h4>
    <hr>
    @include('features.orders.partials._select_sku-details_table')
</div>
<div class="card-footer">
    <button type="sumit" class="btn btn-info">
        <i class="fa fa-save"></i> Submit
    </button>
</div>
