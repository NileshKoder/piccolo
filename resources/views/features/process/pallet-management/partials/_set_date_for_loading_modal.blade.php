<div class="modal fade" id="set_date_for_loading_modal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Set Date For Transfer To Loading </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('pallet.set-date-for-transfer-at-loading') }}" method="post">
                @csrf
                <input type="hidden" name="id" id="set_date_for_loading_pallet_id">
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4 class="">For Pallet # <span id="set_date_for_loading_pallet_name"></span></h4>
                        <h5 class="">Last Set Date : <span id="last_set_date"></span></h5>
                        <div class="form-group">
                            <label for="name">Date</label>
                            <input type="date" class="form-control" name="transfer_date" id="transfer_date" placeholder="Select a date">
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
