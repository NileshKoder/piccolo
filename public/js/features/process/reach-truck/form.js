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
                $('#reach_truck_id').append(`<option value="${value.id}" data-to_loaction_id="${value.to_locationable_id}">${value.pallet.master_pallet.name}</option>`);
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

    if($('#from_locationable_type').val() == "App\\Features\\Masters\\Warehouses\\Domains\\Models\\Warehouse") {
        let toLocationId = $('#reach_truck_id option:selected').data('to_loaction_id');
        $('#to_locationable_id').val(toLocationId).trigger('change')
    }
})


$(document).on("mouseover", ".to_locationable_id_div", function() {
    if ($('#to_locationable_id').val() == 1 || $('#from_locationable_type').val() == "App\\Features\\Masters\\Warehouses\\Domains\\Models\\Warehouse") {
        $(this).find('select').prop('disabled', true);
    }
});

$(document).on("mouseleave", ".to_locationable_id_div", function() {
    if ($('#to_locationable_id').val() == 1 || $('#from_locationable_type').val() == "App\\Features\\Masters\\Warehouses\\Domains\\Models\\Warehouse") {
        $(this).find('select').removeAttr('disabled');
    }
});

$(document).ready(function () {
    // Initialize the validation
    $("#createReachTruckForm").validate({
        rules: {
            transfered_by: {
                required: true
            },
            from_locationable_id: {
                required: true,
            },
            reach_truck_id: {
                required: true,
            },
            to_locationable_id: {
                required: true,
            }
        },
        messages: {
            transfered_by: {
                required: "Please Select Reach Truck User",
            },
            from_locationable_id: {
                required: "Choose Pick Up Location",
            },
            reach_truck_id: {
                required: "Select Pallet",
            },
            to_locationable_id: {
                required: "Choose Drop Location",
            }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
});
