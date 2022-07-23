<div class="modal fade" id="editWarehouseModal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Warehouse</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="#" method="post" id="editWarehouseForm">
                @csrf
                {{ method_field('PUT')}}
                <input type="hidden" name="id" edit="editId">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Warehouse Name</label>
                            <input type="text" class="form-control" name="name" id="editName" placeholder="Please Enter Name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
