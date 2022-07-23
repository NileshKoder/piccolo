<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="row">Row</label>
                <input type="text" class="form-control" id="row" placeholder="Enter Row">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="column">Column</label>
                <input type="text" class="form-control" id="column" placeholder="Please Enter Max Column Number">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="height">Height</label>
                <input type="text" class="form-control" id="height" placeholder="Please Enter Max Height Number">
            </div>
        </div>
    </div>
    <div class="row" align="center">
        <button type="button" class="btn btn-primary" id="createWarehouses">Crate Warehouse Names</button>
    </div>
    <div class="row mt-2">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Warehouse Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="warehousesTbody">
                <tr>

                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer clearfix d-none" id="submitButton">
    <button type="sumbit" class="btn btn-primary"><i class="fas fa-disk"></i> Submit</button>
</div>
