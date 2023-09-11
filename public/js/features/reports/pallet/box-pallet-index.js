var initBoxPalletReportDataTable = function(route) {
    var table = $('#boxPalletReportDataTable').DataTable({
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
                d.order_id = $('#order_id').val(),
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
                data: 'box_name',
                name: 'box_name',
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
                data: 'pallet.order.order_number',
                name: 'pallet.order.order_number',
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
        $('#master_pallet_id').val("").select2();
        $('#order_id').val("").select2();
        table.draw();
    });
};
