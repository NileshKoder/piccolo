var initWareHouseDataTable = function(route) {
    var table = $('#OrderDatatble').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        destroy: true,
        'fnCreatedRow': function(nRow, aData, iDataIndex) {
            $(nRow).attr('id', 'row_' + iDataIndex); // or whatever you choose to set as the id
        },
        ajax: {
            url: route,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: function(d) {
                d.sku_code_id = $('#sku_code_id').val()
                d.variant_id = $('#variant_id').val()
                d.location_id = $('#location_id').val()
                d.state = $('#state').val()
                d.pickup_date = $('#pickup_date').val()
            },
            error: function(e) {
                console.log(e)
                toastr.error('something went wrong');
            }
        },
        columns: [
            { data: 'action', name: 'action', width: 10},
            { data: 'id', name: 'id'},
            { data: 'order_number', name: 'order_number'},
            { data: 'state', name: 'state'},
            { data: 'creator.name', name: 'creator.name'},
            { data: 'updator.name', name: 'updator.name'},
            { data: 'updated_at', name: 'updated_at'}
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
        order: [[1, 'desc']],
    });

    $('#search_order').on('click', function () {
        table.draw();
    });

    $('#clear_order').on('click', function () {
        $('#sku_code_id').val("").select2();
        $('#variant_id').val("").select2();
        $('#location_id').val("").select2();
        $('#state').val("").select2();
        $('#pickup_date').val("");
        table.draw();
    });
};
