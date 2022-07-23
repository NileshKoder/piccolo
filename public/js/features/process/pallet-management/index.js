var initWareHouseDataTable = function(route) {
    $('#palletDatatble').DataTable({
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
        order: [[1, 'asc']],
    });
};
