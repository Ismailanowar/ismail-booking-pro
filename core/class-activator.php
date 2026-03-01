<?php
class IBP_Activator {

    public static function activate() {

        global $wpdb;
        $table = $wpdb->prefix . 'ibp_bookings';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(200) NOT NULL,
            booking_date DATE NOT NULL,
            message TEXT,
            status VARCHAR(50) DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}