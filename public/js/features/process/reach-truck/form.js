$(document).on('change', '#from_locationable_id', function(){
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
        success: function (response) {
            console.log(response)
            $('#reach_truck_id').empty();
            $.each(response, function(index, value){
                $('#reach_truck_id').append(`<option value="${value.id}">${value.pallet.master_pallet.name}</option>`);
            })
        },
        error: function(data) {
            console.log(data)
            toastr.error('something went wrong');
        }
    });
})
