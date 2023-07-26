$(document).ready(function () {
    $('.select2').select2();
    $('[data-mask]').inputmask()

    // $(document).on('click','.add-more-sku',function(){
    //     let time = $.now();
    //     let firstChild = $('#tbody').find('tr:first').clone();

    //     firstChild.find('.sku_code_id').attr('name', `order_item_details[${time}][sku_code_id]`).val("").trigger('change').removeAttr('disabled');
    //     firstChild.find('.variant_id').attr('name', `order_item_details[${time}][variant_id]`).val("").trigger('change').removeAttr('disabled');
    //     firstChild.find('.required_weight').attr('name', `order_item_details[${time}][required_weight]`).removeAttr('readonly').val("");
    //     firstChild.find('.pick_up_date').attr('name', `order_item_details[${time}][pick_up_date]`).removeAttr('readonly').val("");
    //     firstChild.find('.location_id').attr('name', `order_item_details[${time}][location_id]`).val("").trigger('change').removeAttr('disabled');
    //     firstChild.find('.order_item_detail_id').attr('name', `order_item_details[${time}][order_item_detail_id]`).val("").trigger('change').removeAttr('disabled');
    //     firstChild.find('.state').attr('name', `order_item_details[${time}][state]`).val("").trigger('change').removeAttr('disabled');
    //     firstChild.find('.order_item_detail_id').val("");
    //     firstChild.find('.remove-sku').removeClass('d-none');
    //     firstChild.find('[data-mask]').inputmask();

    //     $('#tbody').append(`
    //         <tr>
    //             ${firstChild.html()}
    //         </tr>
    //     `);
    // })

    $(document).on('click','.add-more-sku',function(){
        let skuCodeId = $('#sku_code_id').val();
        let skuCodeName = $('#sku_code_id').find(':selected').text();

        let varinatId = $('#variant_id').val();
        let varinatName = $('#variant_id').find(':selected').text();

        let dropLocationId = $('#location_id').val();
        let dropLocationName = $('#location_id').find(':selected').text();

        let requiredWeight = $('#required_weight').val();
        let pickUpDate = $('#pick_up_date').val();


        if (
            dropLocationId == '' || dropLocationId == undefined ||
            skuCodeId == '' || skuCodeId == undefined ||
            varinatId == '' || varinatId == undefined ||
            requiredWeight == '' || requiredWeight == undefined ||
            pickUpDate == '' || pickUpDate == undefined
        ) {
            swal({
                title: "Please Select all mandetory fields!",
                icon: "warning"
            })
            return false;
        }

        let isAlreadyExists = false
        $('#tbody tr').each(function (index, value) {
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
                <input type="hidden" name="order_item_details[` + time + `][id]" value="">
                <td>
                    <input type="hidden" class="selected_sku_id" name="order_item_details[` + time + `][sku_code_id]" value="` + skuCodeId + `">
                    <input type="text" class="form-control" value="` + skuCodeName + `" disabled>
                </td>
                <td>
                    <input type="hidden" class="selected_variant_id" name="order_item_details[` + time + `][variant_id]" value="` + varinatId + `">
                    <input type="text" class="form-control" value="` + varinatName + `" disabled>
                </td>
                <td>
                    <input type="hidden" name="order_item_details[` + time + `][required_weight]" value="` + requiredWeight + `">
                    <input type="text" class="form-control" value="` + requiredWeight + `" disabled>
                </td>
                <td>
                    <input type="hidden" name="order_item_details[` + time + `][pick_up_date]" value="` + pickUpDate + `">
                    <input type="text" class="form-control" value="` + pickUpDate + `" disabled>
                </td>
                <td>
                    <input type="hidden" name="order_item_details[` + time + `][location_id]" class="weight" value="` + dropLocationId + `">
                    <input type="text" class="form-control" value="` + dropLocationName + `" disabled>
                </td>
                <td class="text-center">
                    <a href="javascript:void(0);" class="text-danger remove-sku">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        `;

        $('#tbody').append(tbodyHtml);
    });

    $(document).on('click', '.remove-sku', function() {
        if($('.remove-sku').length > 1) {
        swal({
            title: "Are you sure?",
            text: "You won't revert this record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((changeStatus) => {
            if (changeStatus) {
                $(this).closest('tr').remove();
                toastr.success('sku removed successfully');
            } else {
                toastr.warning('Sku code is safe');
            }
        });
    } else {
            toastr.error('Select atleast one sku');
        }
    })

    // $(document).on('change', '.sku_code_id', function() {
    //     let closetDiv = $(this).closest('.childDiv').find('.filledCarteCode');
    //     $.ajax({
    //         type: "post",
    //         url: "/process/crate-code-creations/get-pallet-filled-crate-code-creations-by-sku-code/"+$(this).val()+"/ajax",
    //         data: {},
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function (response) {
    //             closetDiv.empty();
    //             $.each(response, function(index, value){
    //                 closetDiv.append(`
    //                     <tr>
    //                         <td>
    //                             <input type="checkbox" name="checked[]" value="${value.id}" class="checkedCrateCodes" data-weight="${value.weight}">
    //                         </td>
    //                         <td>${value.variant.name}</td>
    //                         <td>${value.batch_no}</td>
    //                         <td>${value.weight}</td>
    //                         <td>${value.crate_code.name}</td>
    //                     <tr>
    //                 `)
    //             });
    //         },
    //         error: function(data) {
    //             console.log(data)
    //             toastr.error('something went wrong');
    //         }
    //     });
    // });

    // $(document).on('change', '.checkedCrateCodes', function(){
    //     let checkedQty = 0;
    //     $(this).closest('.childDiv').find('.checkedCrateCodes').each(function() {
    //         if($(this).is(":checked")) {
    //             checkedQty += $(this).data('weight')
    //         }
    //     })

    //     $(this).closest('.childDiv').find('.checked_qty').val(checkedQty);

    //     let requiredQty = $(this).closest('.childDiv').find('.required_qty').val();
    //     let balancedQty = requiredQty - checkedQty

    //     $(this).closest('.childDiv').find('.balanced_qty').val(balancedQty);
    // })

    $('.date').datetimepicker({
        format: 'DD-MM-YYYY'
    });
});
