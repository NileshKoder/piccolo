$(document).ready(function() {
    $('.select2').select2();

    $(document).on('change','#order_id', function() {
        let selectedOrderNumber = getSelectedOrderNumber();

        $('#tbody tr').each(function (i, v) {
            let inputValue = $(this).find('.box_name').val();
            if(inputValue !== '') {
                let extraBoxName = inputValue.split('-')[1];
                $(this).find('.box_name').val(selectedOrderNumber + '-' + extraBoxName);
            } else {
                $(this).find('.box_name').val(selectedOrderNumber + '-');
            }
        })
    })

    $('#add_box_details').on('click', function() {
        let time = $.now();
        let selectedOrderNumber = getSelectedOrderNumber();
        let tbodyHtml = `
            <tr>
                <input type="hidden" name="pallet_box_details[` + time + `][id]" value="">
                <td>
                   <input type="text" name="pallet_box_details[` + time + `][box_name]" class="form-control box_name" placeholder="Enter Box Names" value="${selectedOrderNumber}-">
                </td>
                <td>
                    <a href="javascript:void(0)" class="deleteBoxDetail text-danger">
                        <i class="fa fa-trash text-danger"></i>
                    </a>
                </td>
            </tr>
        `;

        $('#tbody').append(tbodyHtml);
    })

    $(document).on('click', '.deleteBoxDetail', function() {
        swal({
            title: "Are you sure?",
            text: "You won't revert this record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((changeStatus) => {
                if (changeStatus) {
                    $(this).parent().parent().remove();
                } else {
                    toastr.warning('Box Detail is safe');
                }
            });
    })
})

function getSelectedOrderNumber()
{
    return $('#order_id').find(":selected").text();
}
