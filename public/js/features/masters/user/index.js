var initWareHouseDataTable = function(route) {
    $('#userDatatble').DataTable({
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
                data: 'name',
                name: 'name',
            },
            {
                data: 'email',
                name: 'email',
            },
            {
                data: 'role',
                name: 'role',
            },
            {
                data: 'state',
                name: 'state',
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
    $(document).on('click', '.changeState', function(){
        let userId = $(this).data('id');
        let currentState = $(this).data('current-state');
        swal({
            title: "Are you sure?",
            text: "You want change status of this user?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((changeState) => {
            if (changeState) {
                $.ajax({
                    type: "post",
                    url: "users/change-user-state/"+userId+"/ajax",
                    data: {
                        currentState: currentState
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success('User state has been updated');
                        initWareHouseDataTable("users/get-users/ajax");
                    },
                    error: function(data) {
                        console.log(data)
                        toastr.error('something went wrong');
                    }
                });
            } else {
                toastr.warning('User state is not updated');;
            }
          });
    })
});
