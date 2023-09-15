var initSkuReportDataTable = function(route) {
    var table = $('#skuReportDataTable').DataTable({
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
                d.variant_id = $('#variant_id').val()
            },
            error: function (e){
                console.log(e)
                toastr.error('something went wrong');
            }
        },
        columns: [
            {
                data: 'sku_code',
                name: 'sku_code',
            },
            {
                data: 'variant',
                name: 'variant',
            },
            {
                data: 'total_weight',
                name: 'total_weight',
            },
            {
                data: 'total_weight_in_wh',
                name: 'total_weight_in_wh',
            },
            {
                data: 'total_weight_in_line',
                name: 'total_weight_in_line',
            },
            {
                data: 'total_weight_in_location',
                name: 'total_weight_in_location',
            },
            {
                data: 'total_mapped_weight',
                name: 'total_mapped_weight',
            },
            {
                data: 'total_unmapped_weight',
                name: 'total_unmapped_weight',
            },
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all",
            "orderable": false
        }],
    });

    $('#filter').on('click', function () {
        if($('#sku_code_id').val() === '' || $('#sku_code_id').val() === undefined) {
            toastr.warning("Please select SKU Code")
        }
        table.draw();
    });

    $('#clear').on('click', function () {
        $('#sku_code_id').val("").select2();
        $('#variant_id').val("").select2();

        table.draw();
    });
};
