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
            "targets": "_all"
          }],
        order: [[1, 'asc']],
    });

    $('#filter').on('click', function () {
        table.draw();
    });

    $('#clear').on('click', function () {
        $('#sku_code_id').val("").select2();
        $('#variant_id').val("").select2();
        $('#master_pallet_id').val("").select2();
        table.draw();
    });
};
