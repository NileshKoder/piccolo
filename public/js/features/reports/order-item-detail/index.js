var initOrderItemDetailDataTable = function(route) {
    var table =$('#OrderItemDetailsDatatble').DataTable({
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
                d.sku_code_id = $('#index_sku_code_id').val(),
                d.variant_id = $('#index_variant_id').val(),
                d.location_id = $('#index_location_id').val()
                d.state = $('#index_state').val()
            },
            error: function (e){
                console.log(e)
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
                name: 'variant.name'
            },
            {
                data: 'pick_up_date',
                name: 'pick_up_date'
            }
            ,
            {
                data: 'required_weight',
                name: 'required_weight'
            },
            {
                data: 'mapped_required_weight',
                name: 'mapped_required_weight'
            },
            {
                data: 'location.name',
                name: 'location.name'
            },
            {
                data: 'state',
                name: 'state'
            },
            {
                data: 'updated_at',
                name: 'updated_at'
            }
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
          }],
        order: [[1, 'desc']],
    });

    $('#searchOrderItemDetail').on('click', function () {
        table.draw();
    });

    $('#clearhOrderItemDetail').on('click', function () {
        $('#index_sku_code_id').val("").select2();
        $('#index_variant_id').val("").select2();
        $('#index_location_id').val("").select2();
        $('#index_state').val("").select2();
        table.draw();
    });
};
