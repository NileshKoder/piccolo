var initReachTruckDataTable = function(route) {
    var table =$('#WarehousePalletkDatatble').DataTable({
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
                // d.pallet_id = $('#pallet_id').val(),
                // d.crate_code_creation_id = $('#crate_code_creation_id').val(),
                // d.location_id = $('#location_id').val()
            },
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
                data: 'to_locationable.name',
                name: 'to_locationable.name'
            },
            {
                data: 'pallet_creation.pallet.name',
                name: 'pallet_creation.pallet.name',
            },
            {
                data: 'pallet_creation_count',
                name: 'pallet_creation_count'
            }
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
          }],
        order: [[1, 'desc']],
    });

    $('#searchPalletCreation').on('click', function () {
        table.draw();
    });

    $('#clearPalletCreation').on('click', function () {
        $('#pallet_id').val("").select2();
        $('#crate_code_creation_id').val("").select2();
        $('#location_id').val("").select2();
        table.draw();
    });
};
