$("#warehouseCrateForm").validate({
    rules: {
        warehouse_id: {
            required: true,
        }
    },
    messages: {
        warehouse_id: {
            required: "Please select warehouse location",
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

var warehouseCrateInput = $('input[name^="warehouse_crate"]');
var warehouseCrateSelect = $('select[name^="warehouse_crate"]');

warehouseCrateInput.filter('input[name$="[crate_code]"]').each(function() {
    $(this).rules("add", {
        required: true,
        messages: {
            required: "Plese enter crate code"
        }
    });
});

warehouseCrateSelect.filter('select[name$="[sku_code_id]"]').each(function() {
    $(this).rules("add", {
        required: true,
        messages: {
            required : 'Please select sku code',
        }
    });
});

warehouseCrateSelect.filter('select[name$="[qty]"]').each(function() {
    $(this).rules("add", {
        required: true,
        messages: {
            required : 'Please select qty',
        }
    });
});
