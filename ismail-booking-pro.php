<?php
/*
Plugin Name: Ismail Booking Pro
Description: Advanced Booking System (MVC + AJAX + Admin Dashboard)
Version: 1.0
Author: Md Ismail
*/

if (!defined('ABSPATH')) exit;

define('IBP_PATH', plugin_dir_path(__FILE__));
define('IBP_URL', plugin_dir_url(__FILE__));

require_once IBP_PATH . 'core/class-activator.php';
require_once IBP_PATH . 'database/class-db.php';
require_once IBP_PATH . 'admin/class-admin-menu.php';
require_once IBP_PATH . 'public/class-public-form.php';
require_once IBP_PATH . 'public/class-ajax-handler.php';
require_once  IBP_PATH . 'admin/class-admin-bookings-page.php';

register_activation_hook(__FILE__, ['IBP_Activator', 'activate']);

add_action('admin_init', 'ibp_handle_booking_status_update');

function ibp_handle_booking_status_update() {

    if (!isset($_GET['action'], $_GET['booking_id'])) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'ibp_bookings';

    $booking_id = intval($_GET['booking_id']);
    $action     = sanitize_text_field($_GET['action']);

    if ($action === 'approve') {
        $wpdb->update(
            $table_name,
            array('status' => 'Approved'),
            array('id' => $booking_id)
        );
    }

    if ($action === 'reject') {
        $wpdb->update(
            $table_name,
            array('status' => 'Rejected'),
            array('id' => $booking_id)
        );
    }

    wp_safe_redirect(admin_url('admin.php?page=ibp-bookings'));
    exit;
}