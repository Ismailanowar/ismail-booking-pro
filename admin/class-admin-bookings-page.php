<?php
if (!defined('ABSPATH')) exit;

class IBP_Admin_Bookings_Page {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
    }

    public function add_menu() {
        add_menu_page(
            'Bookings',
            'Bookings',
            'manage_options',
            'ibp-bookings',
            [$this, 'render_page'],
            'dashicons-calendar',
            26
        );
    }

    public function render_page() {

        global $wpdb;
        $table = $wpdb->prefix . 'ibp_bookings';

        /* =========================
           DELETE BOOKING
        ========================= */
        if (isset($_GET['delete_id'])) {

            $delete_id = intval($_GET['delete_id']);
            $wpdb->delete($table, ['id' => $delete_id]);

            echo '<div class="updated"><p>Booking deleted successfully.</p></div>';
        }

        /* =========================
           UPDATE BOOKING
        ========================= */
        if (isset($_POST['update_booking'])) {

            $booking_id = intval($_POST['booking_id']);

            $wpdb->update(
                $table,
                [
                    'name'  => sanitize_text_field($_POST['name']),
                    'email' => sanitize_email($_POST['email']),
                    'booking_date' => sanitize_text_field($_POST['booking_date']),
                    'status' => sanitize_text_field($_POST['status'])
                ],
                ['id' => $booking_id]
            );

            echo '<div class="updated"><p>Booking updated successfully.</p></div>';
        }

        /* =========================
           STATUS FILTER
        ========================= */
        $selected_status = isset($_GET['status_filter'])
            ? sanitize_text_field($_GET['status_filter'])
            : '';

        /* =========================
           FETCH BOOKINGS (WITH FILTER)
        ========================= */
        $query = "SELECT * FROM $table";

        if (!empty($selected_status)) {
            $query .= $wpdb->prepare(" WHERE status = %s", $selected_status);
        }

        $query .= " ORDER BY id DESC";

        $bookings = $wpdb->get_results($query);
        ?>

        <div class="wrap">
            <h1>Bookings</h1>

            <!-- FILTER FORM -->
            <form method="GET" style="margin:15px 0;">
                <input type="hidden" name="page" value="ibp-bookings">

                <select name="status_filter">
                    <option value="">All Status</option>
                    <option value="Pending" <?php selected($selected_status, 'Pending'); ?>>Pending</option>
                    <option value="Approved" <?php selected($selected_status, 'Approved'); ?>>Approved</option>
                    <option value="Rejected" <?php selected($selected_status, 'Rejected'); ?>>Rejected</option>
                </select>

                <?php submit_button('Filter', 'secondary', '', false); ?>
            </form>

        <?php
        /* =========================
           EDIT FORM
        ========================= */
        if (isset($_GET['edit_id'])):

            $edit_id = intval($_GET['edit_id']);
            $booking = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $edit_id)
            );
        ?>

            <h2>Edit Booking</h2>

            <form method="POST" style="background:#fff;padding:20px;margin-bottom:20px;">
                <input type="hidden" name="booking_id" value="<?= esc_attr($booking->id) ?>">

                <table class="form-table">
                    <tr>
                        <th>Name</th>
                        <td><input type="text" name="name" value="<?= esc_attr($booking->name) ?>" required></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><input type="email" name="email" value="<?= esc_attr($booking->email) ?>" required></td>
                    </tr>

                    <tr>
                        <th>Date</th>
                        <td><input type="date" name="booking_date" value="<?= esc_attr($booking->booking_date) ?>" required></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            <select name="status">
                                <option value="Pending" <?= selected($booking->status, 'Pending'); ?>>Pending</option>
                                <option value="Approved" <?= selected($booking->status, 'Approved'); ?>>Approved</option>
                                <option value="Rejected" <?= selected($booking->status, 'Rejected'); ?>>Rejected</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <?php submit_button('Update Booking', 'primary', 'update_booking'); ?>
            </form>

        <?php endif; ?>

            <table class="widefat">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($bookings): ?>
                    <?php foreach ($bookings as $booking): 
                        $status = strtolower($booking->status);
                        $class = 'ibp-pending';

                        if ($status === 'approved') $class = 'ibp-approved';
                        if ($status === 'rejected') $class = 'ibp-cancelled';
                    ?>
                    <tr>
                        <td><?= intval($booking->id) ?></td>
                        <td><?= esc_html($booking->name) ?></td>
                        <td><?= esc_html($booking->email) ?></td>
                        <td><?= esc_html($booking->booking_date) ?></td>

                        <td>
                            <span class="ibp-badge <?= esc_attr($class) ?>">
                                <?= esc_html(ucfirst($status)) ?>
                            </span>
                        </td>

                        <td>
                            <a href="?page=ibp-bookings&edit_id=<?= $booking->id ?>" class="button">
                                Edit
                            </a>

                            <a href="?page=ibp-bookings&delete_id=<?= $booking->id ?>" 
                               class="button button-danger"
                               onclick="return confirm('Are you sure?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No bookings found.</td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

        <style>
        .ibp-badge { padding:4px 8px; border-radius:4px; color:#fff; font-weight:bold; }
        .ibp-pending { background:orange; }
        .ibp-approved { background:green; }
        .ibp-cancelled { background:red; }

        .button-danger {
            background: #dc3232;
            color: #fff;
            border-color: #dc3232;
        }
        .button-danger:hover {
            background: #a00;
        }
        </style>

        <?php
    }
}

new IBP_Admin_Bookings_Page();