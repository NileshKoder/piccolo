$(document).ready(function () {
    $('#createWarehouses').on("click", function (){
        let row = $('#row').val();
        let column = $('#column').val();
        let height = $('#height').val();

        if(row == '') {
            alert('Please Enter Row Name');
            return false;
        }

        if(column == '') {
            alert('Please Enter Max Column Number');
            return false;
        }

        if(height == '') {
            alert('Please Enter Max Height Number');
            return false;
        }

        let html = '';
        for (let columnIndex = 1; columnIndex <= column; columnIndex++) {
            for (let heightIndex = 1; heightIndex <= height; heightIndex++) {
                html += `
                    <tr>
                        <td>
                            <input type="text" name="warehouse[][name]" class="form-control" value="${row}${columnIndex}.${heightIndex}" readonly>
                        </td>
                        <td>
                            <a href='javascript:void(0)' title='Delete Warehouse Name' class='deleteWarehouseName'>
                                <i class='fas fa-trash text-danger'></i>
                            </a>
                        </td>
                    <tr>
                `;
            }
        }

        $('#warehousesTbody').append(html);

        $('#submitButton').removeClass('d-none');
    });

    $(document).on('click', '.deleteWarehouseName', function(){
        swal({
            title: "Are you sure?",
            text: "You want to change status of this crate?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((changeStatus) => {
            if (changeStatus) {
                $(this).closest('tr').remove()
            } else {
                toastr.warning('Crate code status is not updated');
            }
        });
    })
});
