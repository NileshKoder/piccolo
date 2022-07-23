$(function () {
    $("#createUser").validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
            },
            role: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
            },
            password_confirmation: {
                required: "Please provide a confirm password",
                minlength: "Your password must be at least 8 characters long",
            },
            role: {
                required: "Please select role of user",
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

    $("#editUser").validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                minlength: 8,
            },
            password_confirmation: {
                minlength: 8,
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address",
            },
            password: {
                minlength: "Your password must be at least 8 characters long",
            },
            password_confirmation: {
                minlength: "Your password must be at least 8 characters long",
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
