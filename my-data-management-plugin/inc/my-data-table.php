<?php 

// Register the AJAX action for logged-in users
add_action('wp_ajax_add_new_record', 'add_new_record_callback');

// Function to handle the AJAX request to add a new record
function add_new_record_callback() {
    global $wpdb;

    // Get the name and email from the AJAX request
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : ''; // Sanitize the name input
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : ''; // Sanitize the email input

    if ( empty( $name ) || empty( $email ) ) {
        wp_send_json_error(['message' => 'Empty fields.']);
    }

    // Validate the email format
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email format.']); // If email is invalid, return an error
    }

    // Insert the new record into the custom table
    $table_name = $wpdb->prefix . MY_TABLE_NAME; // The custom table where the data will be stored
    $wpdb->insert($table_name, [
        'name' => $name,
        'email' => $email,
    ]);

    // Check if the insert was successful
    if ($wpdb->insert_id) {
        wp_send_json_success([
            'id' => $wpdb->insert_id, // Return the new record's ID
            'name' => $name,
            'email' => $email,
            'date_added' => current_time('mysql') // Return the date added
        ]);
    } else {
        wp_send_json_error(['message' => 'Failed to add record.']); // Return an error if insertion fails
    }

}
