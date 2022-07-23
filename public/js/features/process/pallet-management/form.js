$(document).ready(function() {
    $('.select2').select2();

    $('#reservationdate').datetimepicker({
        format: 'DDMMYYYY'
    });

    getLocationShortName();
    calculateTotalWeight();

    $('#location_id').on('change', function() {
        getLocationShortName();
    })

    $('#add_sku').on('click', function() {
        checkTillWeight();

        let locationId = $('#location_id').val();
        let masterPalletId = $('#master_pallet_id').val();

        let skuCodeId = $('#sku_code_id').val();
        let skuCodeName = $('#sku_code_id').find(':selected').text();

        let varinatId = $('#variant_id').val();
        let varinatName = $('#variant_id').find(':selected').text();

        let weight = $('#weight').val();
        let batch = $('#batch_prefix').text() + $('#batch_date').val();

        if (
            locationId == '' || locationId == undefined ||
            masterPalletId == '' || masterPalletId == undefined ||
            skuCodeId == '' || skuCodeId == undefined ||
            varinatId == '' || varinatId == undefined ||
            weight == '' || weight == undefined ||
            batch == '' || batch == undefined
        ) {
            swal({
                title: "Please Select all mandetory fields!",
                icon: "warning"
            })
            return false;
        }
        let time = $.now();
        let tbodyHtml = `
            <tr>
                <input type="hidden" name="pallet_details[` + time + `][id]" value="">
                <td>
                    <input type="hidden" name="pallet_details[` + time + `][sku_code_id]" value="` + skuCodeId + `">
                    <input type="text" class="form-control" value="` + skuCodeName + `" disabled>
                </td>
                <td>
                    <input type="hidden" name="pallet_details[` + time + `][variant_id]" value="` + varinatId + `">
                    <input type="text" class="form-control" value="` + varinatName + `" disabled>
                </td>
                <td>
                    <input type="hidden" name="pallet_details[` + time + `][batch]" value="` + batch + `">
                    <input type="text" class="form-control" value="` + batch + `" disabled>
                </td>
                <td>
                    <input type="hidden" name="pallet_details[` + time + `][weight]" class="weight" value="` + weight + `">
                    <input type="text" class="form-control" value="` + weight + `" disabled>
                </td>
                <td>
                    <a href="javascript:void(0)" class="deleteSku text-danger">
                        <i class="fa fa-trash text-danger"></i>
                    </a>
                </td>
            </tr>
        `;

        $('#skuTableBody').append(tbodyHtml);

        calculateTotalWeight();
    })

    $(document).on('click', '.deleteSku', function() {
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
                    calculateTotalWeight();
                } else {
                    toastr.warning('Sku code is safe');
                }
            });
    })
});

function getLocationShortName() {
    let locationShortName = $('#location_id').find(':selected').data('abbr');
    $('#batch_prefix').empty();
    $('#batch_prefix').text(locationShortName);
}

function calculateTotalWeight() {
    let totalWeight = 0;
    $(document).find('.weight').each(function(index, value) {
        totalWeight += parseFloat($(this).val());
    });

    $('#total_weight').val(totalWeight);
}

function checkTillWeight() {
    let tilltotalWeight = $('#total_weight').val()
    let weight = $('#weight').val();
    let checkMaxWeight = parseFloat(tilltotalWeight) + parseFloat(weight);
    if (checkMaxWeight > 900) {
        swal({
            title: "You can not add more than 900 KG",
            icon: "warning"
        })
        return false;
    }
}
