$(function () {
    $("#createVarientForm").validate({
        rules: {
            name: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
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

    $("#editVariantForm").validate({
        rules: {
            name: {
                required: true,
            },
            id: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            id: {
                required: "sku code variant id missing",
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

    $(document).on('click','.editVariant', function () {
        $('#editVariantForm').attr('action', '/masters/variants/'+$(this).data('id'))
        $('#id').val($(this).data('id'))
        $('#editName').val($(this).data('name'))
        $('#createDiv').fadeOut();
        $('#editDiv').removeClass('d-none');
        $('#editDiv').fadeIn();

    });

    $(document).mouseup(function (e) {
        var container = $("#editDiv");
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            $('#editVariantForm').attr('action', '#')
            $('#id').val('')
            $('#editName').val('')
            $('#createDiv').fadeIn();
            $('#editDiv').addClass('d-none');
            $('#editDiv').fadeOut();
        }
    });
});
