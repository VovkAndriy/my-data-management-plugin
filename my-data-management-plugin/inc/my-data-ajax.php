<?php

// Register the AJAX action for logged-in users
add_action('wp_ajax_load_data', 'load_data_callback'); 

// Function to handle AJAX request
function load_data_callback() {
    global $wpdb;

    $error = [
        'results' => [],
        'total_pages' => 0,
        'message' => 'No data',
    ];

    if ( ! my_plugin_check_table_exists() ) {
        wp_send_json($error);
    }

    // Get the current page number from AJAX request, default to 1 if not set
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    // Get the search query, if provided
    $search_query = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    // Calculate the offset for SQL query
    $offset = ($page - 1) * 5; // 5 records per page

    // Base SQL query with optional search filter
    $sql = "SELECT id, name, email, date_added FROM {$wpdb->prefix}" . MY_TABLE_NAME;
    if (!empty($search_query)) {
        $sql .= $wpdb->prepare(" WHERE name LIKE %s", '%' . $search_query . '%');
    }
    
    $sql .= " ORDER BY date_added DESC";

    $sql .= $wpdb->prepare(" LIMIT %d, 5", $offset);

    // Fetch the filtered data
    $results = $wpdb->get_results($sql);

    // Get the total records count for pagination
    $count_sql = "SELECT COUNT(*) FROM {$wpdb->prefix}" . MY_TABLE_NAME;
    if (!empty($search_query)) {
        $count_sql .= $wpdb->prepare(" WHERE name LIKE %s", '%' . $search_query . '%');
    }
    $total_records = $wpdb->get_var($count_sql);

    // Calculate the total pages
    $total_pages = ceil($total_records / 5);

    // Check if any data was returned
    if ($results) {
        // Return the data in JSON format to JavaScript
        wp_send_json([
            'results' => $results, // Data for the table
            'total_pages' => $total_pages // Total pages for pagination
        ]);
    } else {
        // Return an empty array if no data was found
        wp_send_json($error);
    }

}



