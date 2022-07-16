<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            All Crate Codes
        </h3>
        <div class="card-tools">
            <a href="{{ route('warehouses.create') }}" target="_blank">
                <button type="button" class="btn btn-success float-right">
                    <i class="fas fa-plus"></i> Add Warehouse
                </button>
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table class="table table-bordered table-hover" id="warehousesDatatble">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Is Empty</th>
                    <th>Type</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
