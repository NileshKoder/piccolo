var initWareHouseDataTable = function(route) {
    $('#warehousesDatatble').DataTable({
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
            },
            {
                data: 'id',
                name: 'id',
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'is_empty',
                name: 'is_empty'
            },
            {
                data: 'type',
                name: 'type'
            },

        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
          }],
        order: [[1, 'asc']],
    });

    $(document).on('click', '.deleteWarehouse', function(){
        let id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "You want to delete this warehouse?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((changeStatus) => {
            if (changeStatus) {
                $.ajax({
                    type: "post",
                    url: `/masters/warehouses/${id}`,
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
                            toastr.success('Warehouse deleted successfully');
                            initWareHouseDataTable("warehouses/get-warehouses/ajax");
                        }
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('Warehouse is safe');
            }
          });
    })
};

$(document).ready(function () {
    $(document).on('click', '.editWarehouse', function(){
        $('#editName').val($(this).data('name'));
        $('#editId').val($(this).data('id'));
        $('#editWarehouseForm').attr('action',  $(this).data('href'));
    })
});
