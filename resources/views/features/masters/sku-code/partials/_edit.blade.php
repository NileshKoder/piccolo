<div class="card d-none" id="editDiv">
    <div class="card-header bg-warning">
        <h3 class="card-title">
            <i class="fas fa-edit mr-1"></i>
            Edit Sku Code
        </h3>
    </div>
    <form action="#" method="post" id="editSkuCodeForm">
        @csrf
        {{ method_field('PUT')}}
        <div class="card-body">
            <input type="hidden" name="id" id="id">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Sku Code</label>
                        <input type="text" name="name" class="form-control" id="editName" placeholder="Enter Name">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer clearfix">
            <button type="sumbit" class="btn btn-primary"><i class="fas fa-disk"></i> Submit</button>
        </div>
    </form>
</div>
