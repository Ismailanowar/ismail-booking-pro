<?php
class IBP_Ajax_Handler {

    public function __construct() {
        add_action('wp_ajax_ibp_submit_booking', [$this, 'handle']);
        add_action('wp_ajax_nopriv_ibp_submit_booking', [$this, 'handle']);
    }

    public function handle() {

        check_ajax_referer('ibp_nonce', 'security');

        global $wpdb;
        $table = $wpdb->prefix . 'ibp_bookings';

        $wpdb->insert($table, [
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'booking_date' => sanitize_text_field($_POST['booking_date']),
            'message' => sanitize_textarea_field($_POST['message']),
            'status' => 'Pending'
        ]);

        wp_send_json_success("Booking Submitted!");
    }
}

new IBP_Ajax_Handler();