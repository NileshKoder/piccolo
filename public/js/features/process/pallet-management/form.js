$(document).ready(function() {
    $('.select2').select2();

    $('#reservationdate').datetimepicker({
        format: 'DDMMYYYY'
    });

    getLocationShortName();
    calculateTotalWeight();
    updateMaxWight();

    $('#location_id').on('change', function() {
        getLocationShortName();
    })

    $('#master_pallet_id').on('change', function() {
        updateMaxWight();
    })

    $('#add_sku').on('click', function() {
        let masterPalletId = $('#master_pallet_id').val();
        let maxWeight = $('#max_weight').val();
        if(parseInt($('#weight').val()) < 10 || parseInt($('#weight').val()) > maxWeight) {
            swal({
                title: `Weight should be greater than 10KG and less than ${maxWeight} KG`,
                icon: "warning"
            })

            return false;
        }


        let checkWeight = checkTillWeight();

        if(!checkWeight) {
            swal({
                title: `You can not add more than ${maxWeight} KG`,
                icon: "warning"
            })

            return false;
        }

        let locationId = $('#location_id').val();

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

        let isAlreadyExists = false
        $('#skuTableBody tr').each(function (index, value) {
            let selectedSkuId = $(this).find('.selected_sku_id').val();
            let selectedVariantId = $(this).find('.selected_variant_id').val();

            if(skuCodeId == selectedSkuId && varinatId == selectedVariantId) {
                isAlreadyExists = true;
                return;
            }
        });

        if(isAlreadyExists) {
            swal({
                title: "Sku and varinat already exists!",
                icon: "error"
            })
            return false;
        }

        let time = $.now();
        let tbodyHtml = `
            <tr>
                <input type="hidden" name="pallet_details[` + time + `][id]" value="">
                <td>
                    <input type="hidden" class="selected_sku_id" name="pallet_details[` + time + `][sku_code_id]" value="` + skuCodeId + `">
                    <input type="text" class="form-control" value="` + skuCodeName + `" disabled>
                </td>
                <td>
                    <input type="hidden" class="selected_variant_id" name="pallet_details[` + time + `][variant_id]" value="` + varinatId + `">
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
    let maxWeight = $('#max_weight').val()

    if (checkMaxWeight > maxWeight) {
        return false;
    }

    return true;
}

function updateMaxWight() {
    let selectedPallet = $('#master_pallet_id option:selected').text();
    let maxWeight = 0;
    if(selectedPallet.charAt(0) == 'P') {
        maxWeight = 900;
    } else if(selectedPallet.charAt(0) == 'C') {
        maxWeight = 1500;
    } else {
        maxWeight = 900;
    }
    $('#max_weight').val(maxWeight);

    if(!checkTillWeight()) {
        swal({
            title: `You can not add more than ${maxWeight} KG`,
            icon: "warning"
        })

        return false;
    }
}
