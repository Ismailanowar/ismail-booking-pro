<?php
if (!defined('ABSPATH')) exit;

class IBP_Admin_Menu {

    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue($hook) {

        if ($hook !== 'toplevel_page_ibp-bookings') {
            return;
        }

        wp_enqueue_script(
            'ibp-admin-js',
            IBP_URL . 'assets/js/admin.js',
            ['jquery'],
            null,
            true
        );

        wp_enqueue_style(
            'ibp-admin-css',
            IBP_URL . 'assets/css/admin.css',
            [],
            null
        );

        wp_localize_script('ibp-admin-js', 'ibp_admin_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ibp_admin_nonce')
        ]);
    }
}

new IBP_Admin_Menu();