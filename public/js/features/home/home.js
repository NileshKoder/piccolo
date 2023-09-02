$(document).ready(function() {
    $('.refreshOrderStats').on('click', function () {
        getOrderStats($(this).data('order_stats_route'))
    })

    $('.refreshTodayPickupOrders').on('click', function () {
        getOrdersByPickUpDate($(this).data('order_stats_route'))
    })

    $('#pickUpDate').on('change', function () {
        getOrdersByPickUpDate($(this).data('order_stats_route'))
    })
})

function getOrderStats(route)
{
    $.ajax({
        method: 'post',
        url: route,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response){
            console.log(response.data)
            $('#orderStatsTbody').empty();
            let orderStatsHtml = '';
            $.each(response.data, function (i, value){
                orderStatsHtml += `
                    <tr>
                        <td>
                            <a href="/orders-management/orders?state=`+ value.state +`" target="_blank">
                                ` + value.state + `
                            </a>
                        </td>
                        <td>`+value.count+`</td>
                        <td>`+value.oldest+`</td>
                    </tr>
                `;
            });

            $('#orderStatsTbody').append(orderStatsHtml);
        },
        error: function (error) {
            console.log(error.responseJSON.message)
            toastr.error('something went wrong on Order state \n Exception Message : ' + error.responseJSON.message);
        }
    })
}

function getOrdersByPickUpDate(route)
{
    let pickUpDate = $('#pickUpDate').val()

    $.ajax({
        method: 'post',
        url: route,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            pickUpDate : pickUpDate,
        },
        success: function (response){
            console.log(response.data)
            $('#pickUpOrdersTbody').empty();
            let ordersByPickUpDate = '';
            $.each(response.data, function (i, value){
                ordersByPickUpDate += `
                    <tr>
                        <td>
                            <a href="/orders-management/orders?location_id=`+ value.location_id +`&pickup_date=`+ value.pickup_date +`" target="_blank">
                                ` + value.location_name + `
                            </a>
                        </td>
                        <td>`+value.count+`</td>
                    </tr>
                `;
            });

            $('#pickUpOrdersTbody').append(ordersByPickUpDate);
        },
        error: function (error) {
            console.log(error.responseJSON.message)
            toastr.error('something went wrong on Orders by Pickup Date \n Exception Message : ' + error.responseJSON.message);
        }
    })
}

