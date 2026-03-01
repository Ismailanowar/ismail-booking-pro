<?php

class IBP_Public_Form {

    public function __construct() {
        add_shortcode('ibp_booking_form', [$this, 'render_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue() {
        wp_enqueue_script('ibp-js', IBP_URL . 'assets/js/booking.js', ['jquery'], null, true);
        wp_localize_script('ibp-js', 'ibp_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ibp_nonce')
        ]);
    }

    public function render_form() {
        ob_start();
        include IBP_PATH . 'public/views/booking-form.php';
        return ob_get_clean();
    }
}

new IBP_Public_Form();