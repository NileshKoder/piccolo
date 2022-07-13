var initWareHouseDataTable = function(route) {
    $('#VariantsDatatble').DataTable({
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
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
          }],
        order: [[1, 'asc']],
    });
};

$(document).ready(function () {
    $(document).on('click', '.deleteVariant', function(){
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
                    url: "variants/"+id,
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
                            toastr.success('Variant has been deleted');
                            initWareHouseDataTable("variants/get-variants");
                        }
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('Variant variant is safe');;
            }
          });
    })
});
