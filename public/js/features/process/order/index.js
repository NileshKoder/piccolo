var initWareHouseDataTable = function(route) {
    var table =$('#OrderDatatble').DataTable({
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
            data: function (d) {},
            error: function (e){
                console.log(e)
            }
        },
        columns: [
            {
                data: 'action',
                name: 'action',
                width: 10
            },
            {
                data: 'id',
                name: 'id',
            },
            {
                data: 'order_number',
                name: 'order_number',
            },
            {
                data: 'creator.name',
                name: 'creator.name'
            },
            {
                data: 'updator.name',
                name: 'updator.name'
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

    // $('#searchPalletCreation').on('click', function () {
    //     table.draw();
    // });

    // $('#clearPalletCreation').on('click', function () {
    //     $('#pallet_id').val("").select2();
    //     $('#crate_code_creation_id').val("").select2();
    //     $('#location_id').val("").select2();
    //     table.draw();
    // });
};
