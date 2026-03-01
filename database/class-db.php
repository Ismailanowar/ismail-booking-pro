<?php
class IBP_DB {

    public static function table_name() {
        global $wpdb;
        return $wpdb->prefix . 'ibp_bookings';
    }

}