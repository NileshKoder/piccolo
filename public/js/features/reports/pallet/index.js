var initPalletReportDataTable = function(route) {
    var table = $('#palletReportDatatble').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        destroy: true,
        'fnCreatedRow': function (nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'row_' + iDataIndex); // or whatever you choose to set as the id
        },
        ajax: {
            url: route,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: function (d) {
                d.sku_code_id = $('#sku_code_id').val(),
                d.variant_id = $('#variant_id').val(),
                d.order_id = $('#order_id').val(),
                d.batch_date = $('#batch_date').val(),
                d.master_palle_id = $('#master_pallet_id').val()
            },
            error: function (e){
                console.log(e)
                toastr.error('something went wrong');
            }
        },
        columns: [
            {
                data: 'pallet.master_pallet.name',
                name: 'pallet.master_pallet.name',
            },
            {
                data: 'sku_code.name',
                name: 'sku_code.name',
            },
            {
                data: 'variant.name',
                name: 'variant.name',
            },
            {
                data: 'weight',
                name: 'weight',
            },
            {
                data: 'batch',
                name: 'batch',
            },
            {
                data: 'pallet.reach_truck.from_locationable.name',
                name: 'pallet.reach_truck.from_locationable.name',
            },
            {
                data: 'pallet.master_pallet.last_locationable.name',
                name: 'pallet.master_pallet.last_locationable.name',
            },
            {
                data: 'order_item_pallet.order_item.order.order_number',
                name: 'order_item_pallet.order_item.order.order_number',
            },
            {
                data: 'pallet.updater.name',
                name: 'pallet.updater.name',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
            },
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all",
            "orderable": false
        }],
    });

    $('#filter').on('click', function () {
        table.draw();
    });

    $('#clear').on('click', function () {
        $('#sku_code_id').val("").select2();
        $('#variant_id').val("").select2();
        $('#master_pallet_id').val("").select2();
        $('#order_id').val("").select2();
        $('#batch_date').val("");
        table.draw();
    });
};
