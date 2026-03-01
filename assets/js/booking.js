jQuery(document).ready(function($){

    $('#ibp-booking-form').submit(function(e){
        e.preventDefault();

        var data = {
            action: 'ibp_submit_booking',
            security: ibp_ajax.nonce,
            name: $('input[name="name"]').val(),
            email: $('input[name="email"]').val(),
            booking_date: $('input[name="booking_date"]').val(),
            message: $('textarea[name="message"]').val()
        };

        $.post(ibp_ajax.ajax_url, data, function(response){
            $('#ibp-response').html(response.data);
        });
    });

});