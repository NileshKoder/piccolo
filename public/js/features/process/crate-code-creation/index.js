var initWareHouseDataTable = function(route) {
    var table = $('#CrateCodeCreationsDatatble').DataTable({
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
                data: 'crate_code.name',
                name: 'crate_code.name',
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
                name: 'weight'
            },
            {
                data: 'batch_no',
                name: 'batch_no'
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

    $('#searchCrateCodeCreation').on('click', function () {
        table.draw();
    });

    $('#clearhCrateCodeCreation').on('click', function () {
        $('#index_sku_code_id').val("").select2();
        $('#index_variant_id').val("").select2();
        $('#index_location_id').val("").select2();
        table.draw();
    });
};

$(document).ready(function () {
    $(document).on('click', '.deleteCrateCodeCreation', function(){
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
                    url: "/process/crate-code-creations/"+id,
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
                            toastr.success('Crate Code Creation has been deleted');
                            initWareHouseDataTable("/process/crate-code-creations/get-crate-code-creations/ajax");
                        }
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('Crate code creation is safe');;
            }
          });
    })
});
