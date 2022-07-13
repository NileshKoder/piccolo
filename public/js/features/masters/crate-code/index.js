var initWareHouseDataTable = function(route) {
    $('#crateCodesDatatble').DataTable({
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
                d.type = $('#type').val()
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
                data: 'name',
                name: 'name',
            },
            {
                data: 'is_empty',
                name: 'is_empty'
            },
            // {
            //     data: 'type',
            //     name: 'type'
            // },
            {
                data: 'state',
                name: 'state'
            }
        ],
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
          }],
        order: [[1, 'asc']],
    });

};

$(document).ready(function () {
    $(document).on('click', '.deleteCrateCode', function(){
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
                    url: "crate-codes/"+id,
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
                            toastr.success('Crate Code has been deleted');
                            initWareHouseDataTable("crate-codes/get-crate-codes/ajax");
                        }
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('Crate code is safe');;
            }
          });
    })

    $(document).on('click', '.changeCrateCodeState', function(){
        let id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "You want to change status of this crate?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((changeStatus) => {
            if (changeStatus) {
                $.ajax({
                    type: "post",
                    url: `/masters/crate-codes/change-crate-codes-state/${id}/ajax`,
                    data: {
                        "state": $(this).data('state'),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if(response.status == 500) {
                            toastr.error(response.error);
                        } else {
                            toastr.success('Crate Code statis has been changed successfully');
                            initWareHouseDataTable("crate-codes/get-crate-codes/ajax");
                        }
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('Crate code status is not updated');
            }
          });
    })
});
