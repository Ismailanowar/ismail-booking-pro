jQuery(document).ready(function($){

    function updateStatus(id, actionType) {

        $.post(ibp_admin_ajax.ajax_url, {
            action: 'ibp_update_status',
            security: ibp_admin_ajax.nonce,
            booking_id: id,
            status_action: actionType
        }, function(response){

            if(response.success) {

                if(actionType === 'delete') {
                    $('#booking-row-' + id).fadeOut();
                } else {
                    var newStatus = response.data.new_status.toLowerCase();
var badgeClass = 'ibp-badge ibp-pending';

if(newStatus === 'approved') {
    badgeClass = 'ibp-badge ibp-approved';
}

if(newStatus === 'cancelled') {
    badgeClass = 'ibp-badge ibp-cancelled';
}

$('#booking-row-' + id + ' .ibp-status').html(
    '<span class="' + badgeClass + '">' +
    newStatus.charAt(0).toUpperCase() + newStatus.slice(1) +
    '</span>'
);
                }

            } else {
                alert('Error!');
            }

        });
    }

    $('.ibp-approve').click(function(){
        updateStatus($(this).data('id'), 'approve');
    });

    $('.ibp-cancel').click(function(){
        updateStatus($(this).data('id'), 'cancel');
    });

    $('.ibp-delete').click(function(){
        if(confirm('Are you sure?')) {
            updateStatus($(this).data('id'), 'delete');
        }
    });

});