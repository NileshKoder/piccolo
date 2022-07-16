<div class="card" id="createDiv">
    <div class="card-header bg-success">
        <h3 class="card-title">
            <i class="fas fa-plus mr-1"></i>
            Create Master Pallet
        </h3>
    </div>
    <form action="{{ route('master-pallets.store') }}" method="post" id="createMasterPalletForm">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Master Pallet No</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer clearfix">
            <button type="sumbit" class="btn btn-primary"><i class="fas fa-disk"></i> Submit</button>
        </div>
    </form>
</div>
