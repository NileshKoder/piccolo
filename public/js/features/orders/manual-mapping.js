$(document).ready(function () {
    $(document).on('change', '.pallet_detail_id', function() {
        let thisPalletWeight = $(this).data('weight')

        let requiredWeight = $('#required_weight').val();

        let totalMappedWeight = parseInt($('#total_mapped_weight_input').val());


        if($(this).is(':checked')) {
            totalMappedWeight = parseInt(totalMappedWeight) + parseInt(thisPalletWeight);
        } else if(totalMappedWeight !== 0) {
            totalMappedWeight = parseInt(totalMappedWeight) - parseInt(thisPalletWeight);
        }

        $('#total_mapped_weight_input').val(totalMappedWeight)
        $('#total_mapped_weight').text(totalMappedWeight)

        if(totalMappedWeight >= requiredWeight) {
            $('input[type=checkbox]').each(function(i,val) {
                if(!$(this).is(':checked')) {
                    $(this).prop('disabled', true);
                }
            })
        } else if(totalMappedWeight < requiredWeight) {
            $('input[type=checkbox]').each(function(i,val) {
                if(!$(this).is(':checked')) {
                    $(this).prop('disabled', false);
                }
            })
        }

        if(parseInt(totalMappedWeight) > 0) {
            $('#submitBtn').prop('disabled', false)
        } else {
            $('#submitBtn').prop('disabled', true)
        }
    })
});
