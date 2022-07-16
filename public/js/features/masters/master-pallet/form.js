$(function () {
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            return this.optional(element) || regexp.test(value);
        },
        "Please check your input."
    );

    $("#createMasterPalletForm").validate({
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

    $("#editMasterPalletForm").validate({
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
                required: "create code id missing",
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

    $(document).on('click','.editMasterPallet', function () {
        $('#editMasterPalletForm').attr('action', 'master-pallets/'+$(this).data('id'))
        $('#id').val($(this).data('id'))
        $('#editName').val($(this).data('name'))
        $('select#editType').val($(this).data('type'))
        $('#createDiv').fadeOut();
        $('#editDiv').removeClass('d-none');
        $('#editDiv').fadeIn();

    });

    $(document).mouseup(function (e) {
        var container = $("#editDiv");
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            $('#editMasterPalletForm').attr('action', '#')
            $('#id').val('')
            $('#editName').val('')
            $('#createDiv').fadeIn();
            $('#editDiv').addClass('d-none');
            $('#editDiv').fadeOut();
        }
    });
});
