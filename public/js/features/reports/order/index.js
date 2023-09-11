var initOrderReportDataTable = function(route) {
    var table = $('#orderReportDataTable').DataTable({
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
                d.order_state = $('#order_state').val(),
                d.order_item_state = $('#order_item_state').val(),
                d.pickup_date = $('#pickup_date').val()
            },
            error: function (e){
                console.log(e)
                toastr.error('something went wrong');
            }
        },
        columns: [
            {
                data: 'order.order_number',
                name: 'order.order_number',
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
                data: 'required_weight',
                name: 'required_weight',
            },
            {
                data: 'mapped_weight',
                name: 'mapped_weight',
            },
            {
                data: 'pick_up_date',
                name: 'pick_up_date',
            },
            {
                data: 'order.state',
                name: 'order.state',
            },
            {
                data: 'state',
                name: 'state',
            },
            {
                data: 'order.updator.name',
                name: 'order.updator.name',
            },
            {
                data: 'order.updated_at',
                name: 'order.updated_at',
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
        $('#order_id').val("").select2();
        $('#order_state').val("").select2();
        $('#order_item_state').val("");
        $('#pickup_date').val("");
        table.draw();
    });
};
