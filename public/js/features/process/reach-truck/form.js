$("#createReachTruckForm").validate({
    rules: {
        transfered_by: {
            required: true,
        },
        pallet_creation_id: {
            required: true,
        },
        from_locationable_type: {
            required: true,
        },
        from_locationable_id: {
            required: true,
        },
        to_locationable_type: {
            required: true,
        },
        to_locationable_id: {
            required: true,
        }
    },
    messages: {
        transfered_by: {
            required: "Please select reach truck",
        },
        pallet_creation_id: {
            required: "Please select pallet",
        },
        from_locationable_type: {
            required: "From location is required",
        },
        from_locationable_id: {
            required: "Please select from location",
        },
        to_locationable_type: {
            required: "To location is required",
        },
        to_locationable_id: {
            required: "Please select to location",
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

$('.select2').select2();

$(document).on('change', '#from_locationable_id', function(){
    $.ajax({
        type: "post",
        url: "/process/reach-truck/get-pallet-for-reach-truck/ajax",
        data: {
            from_locationable_type: $('#from_locationable_type').val(),
            from_locationable_id: $(this).val(),
            location_type: $('#type').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log(response)
            $('#pallet_creation_id').empty();
            $.each(response, function(index, value){
                $('#pallet_creation_id').append(`<option value="${value.id}">${value.pallet.name}</option>`);
            })
        },
        error: function(data) {
            console.log(data)
            toastr.error('something went wrong');
        }
    });
})
