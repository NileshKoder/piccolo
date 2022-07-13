$(document).ready(function() {
    $('.select2').select2();

    $('#addCrateCodeCreation').on('click', function(){
        if($('#crateCodeCreationTbody').find('tr').length <= 44) {
            let appendData = $('#crateCodeCreationTbody').find('tr').first();
            $('#crateCodeCreationTbody').append(
                "<tr>"+appendData.html()+"</tr>"
            );
        } else {
            swal({
                title: "You can not add more than 45 crate code in single pallet",
                text: "",
                icon: "warning",
            });
        }
    })

    $(document).on('click', '.deleteCrateCodeCreation', function() {
        swal({
            title: "Are you sure?",
            text: "You won't revert this record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((changeStatus) => {
            if (changeStatus) {
                $(this).closest('tr').remove();
            } else {
                toastr.warning('Crate code is safe');;
            }
        });
    })
});

$("#createPalletCreationForm").validate({
    rules: {
        location_id: {
            required: true,
        },
        pallet_id: {
            required: true,
        }
    },
    messages: {
        location_id: {
            required: "Please select location location",
        },
        pallet_id: {
            required: "Please select pa location",
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
