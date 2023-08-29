var initPalletDataTable = function(route) {
    var palletTable = $('#palletDatatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searching: false,
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
                d.sku_code_id = $('#sku_code_id').val()
                d.variant_id = $('#variant_id').val()
                d.location_id = $('#location_id').val()
                d.order_id = $('#order_id').val()
            },
            error: function (e){
                console.log(e)
                toastr.error('something went wrong');
            }
        },
        columns: [
            {
                data: 'action',
                name: 'action',
                width: '8%'
            },
            {
                data: 'id',
                name: 'id',
                width: '8%'
            },
            {
                data: 'master_pallet.name',
                name: 'master_pallet.name',
            },
            {
                data: 'master_pallet.last_locationable.name',
                name: 'master_pallet.last_locationable.name',
            },
            {
                data: 'order.order_number',
                name: 'order.order_number',
            },
            {
                data: 'updater.name',
                name: 'updater.name',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
            },
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
          }],
        order: [[1, 'desc']],
    });

    $('#search_pallet').on('click', function () {
        palletTable.draw();
    });

    $('#clear_pallet').on('click', function () {
        $('#sku_code_id').val("").select2();
        $('#variant_id').val("").select2();
        $('#location_id').val("").select2();
        $('#order_id').val("").select2();

        palletTable.draw();
    });
};
