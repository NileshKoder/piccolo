var initReachTruckDataTable = function(route) {
    var table =$('#ReachTruckDatatble').DataTable({
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
                data: 'id',
                name: 'id',
            },
            {
                data: 'transfer_by.name',
                name: 'transfer_by.name',
            },
            {
                data: 'pallet_creation.pallet.name',
                name: 'pallet_creation.pallet.name',
            },
            {
                data: 'from_locationable.name',
                name: 'from_locationable.name'
            },
            {
                data: 'to_locationable.name',
                name: 'to_locationable.name'
            },
            {
                data: 'created_at',
                name: 'created_at'
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

$(document).ready(function () {
    $('.select2').select2();

    $(document).on('click', '.deleteReachTruck', function(){
        let id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "You won't revert this record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((changeStatus) => {
            if (changeStatus) {
                $.ajax({
                    type: "post",
                    url: "/process/reach-truck/"+id,
                    data: {
                        "_method": 'DELETE',
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if(response.status == 500) {
                            toastr.error(response.error);
                        } else {
                            toastr.success('Reach Truck has been deleted');
                            initReachTruckDataTable("/process/reach-truck/get-reach-truck/ajax");
                        }
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('Reach Truck is safe');;
            }
          });
    })
});
