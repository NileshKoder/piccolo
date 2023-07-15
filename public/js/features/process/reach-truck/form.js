$(document).on('change', '#from_locationable_id', function() {
    $.ajax({
        type: "post",
        url: "/process/reach-trucks/get-pallet-for-reach-truck/ajax",
        data: {
            from_locationable_type: $('#from_locationable_type').val(),
            from_locationable_id: $(this).val(),
            location_type: $('#type').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response)
            $('#reach_truck_id').empty();
            $('#reach_truck_id').append(`<option value="">Select Pallet</option>`);
            $.each(response, function(index, value) {
                $('#reach_truck_id').append(`<option value="${value.id}">${value.pallet.master_pallet.name}</option>`);
            })
        },
        error: function(data) {
            console.log(data)
            toastr.error('something went wrong');
        }
    });
})


$(document).on('change', "#reach_truck_id", function() {
    let selectedPallet = $(this).find('option:selected').text()
    if (selectedPallet.charAt(0) == "C") {
        $('#to_locationable_id').val(1).trigger('change')
    }
})


$(document).on("mouseover", ".to_locationable_id_div", function() {
    if ($('#to_locationable_id').val() == 1) {
        $(this).find('select').prop('disabled', true);
    }
});

$(document).on("mouseleave", ".to_locationable_id_div", function() {
    if ($('#to_locationable_id').val() == 1) {
        $(this).find('select').removeAttr('disabled');
    }
});
