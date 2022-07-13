$(function () {
    $("#createCrateCodeCreationForm").validate({
        rules: {
            crate_code_id: {
                required: true,
            },
            sku_code_id: {
                required: true,
            },
            variant_id: {
                required: true,
            },
        },
        messages: {
            crate_code_id: {
                required: "Please select a crate code",
            },
            sku_code_id: {
                required: "Please select a sku code",
            },
            variant_id: {
                required: "Please select a sku code variant",
            },
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

$(document).ready(function() {
    $('.select2').select2();

    //  Search Crate Codes
    $('.crate_code_id_select2').select2({
        placeholder: 'Select an item',
        ajax: {
            type: "post",
            url: '/masters/crate-codes/get-empty-crate-codes-by-name/ajax',
            dataType: 'json',
            delay: 250,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function (data) {
                return {
                    name: data.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results:response
                };
            },
            cache: true
        }
    });

    $('#reservationdate').datetimepicker({
        format: 'DDMMYYYY'
    });
});
