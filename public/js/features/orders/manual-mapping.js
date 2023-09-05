$(document).ready(function () {
    $(document).on('change', '.pallet_detail_id', function() {
        let thisPalletWeight = $(this).data('weight')
        let mappedWeightInput = $('#mapped_weight_input').val();
        let remainingWeight = $('#remaining_weight').val();
        let newMappedWeight = 0;
        if($(this).is(':checked')) {
            newMappedWeight = parseInt(thisPalletWeight) + parseInt(mappedWeightInput);
        } else if(mappedWeightInput != 0) {
            newMappedWeight = parseInt(mappedWeightInput) - parseInt(thisPalletWeight);
        }
        $('#mapped_weight_input').val(newMappedWeight)
        $('#total_mapped_weight').text(newMappedWeight)

        if(newMappedWeight >= remainingWeight) {
            $('input[type=checkbox]').each(function(i,val) {
                if(!$(this).is(':checked')) {
                    $(this).prop('disabled', true);
                }
            })
        } else if(newMappedWeight < remainingWeight) {
            $('input[type=checkbox]').each(function(i,val) {
                if(!$(this).is(':checked')) {
                    $(this).prop('disabled', false);
                }
            })
        }

        if(parseInt(newMappedWeight) > 0) {
            $('#submitBtn').prop('disabled', false)
        } else {
            $('#submitBtn').prop('disabled', true)
        }
    })
});
