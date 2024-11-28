<?php

function my_data_management_create_table () {
    global $wpdb;

    // Set the table name (with the WordPress database prefix)
    $table_name = $wpdb->prefix . MY_TABLE_NAME;

    // SQL query to create the table
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    // Include the necessary WordPress file for dbDelta function
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Execute the SQL query
    dbDelta( $sql );

    // Check if the table was created successfully
    if ( my_plugin_check_table_exists() ) {
        // Add test data
        my_plugin_insert_test_data( $table_name );
    }
}

function my_plugin_check_table_exists() {
    global $wpdb;
    return ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . MY_TABLE_NAME . "'" ) ) ? 1 : 0;
}

// Function to insert test data into the custom table
function my_plugin_insert_test_data( $table_name ) {
    global $wpdb;

    // Check if the table already contains records
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

    if ( $count > 0 ) {
        return; // If records exist, do nothing
    }


    // Prepare test data
    $test_data = [
        [ 'name' => 'John Doe', 'email' => 'john.doe@example.com' ],
        [ 'name' => 'Jane Smith', 'email' => 'jane.smith@example.com' ],
        [ 'name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com' ],
        [ 'name' => 'Bob Brown', 'email' => 'bob.brown@example.com' ],
        [ 'name' => 'Charlie Davis', 'email' => 'charlie.davis@example.com' ],
        [ 'name' => 'Eve White', 'email' => 'eve.white@example.com' ],
        [ 'name' => 'Frank Green', 'email' => 'frank.green@example.com' ],
        [ 'name' => 'Grace Black', 'email' => 'grace.black@example.com' ],
        [ 'name' => 'Hank Wilson', 'email' => 'hank.wilson@example.com' ],
        [ 'name' => 'Ivy Taylor', 'email' => 'ivy.taylor@example.com' ],
    ];

    // Insert each record into the table
    foreach ( $test_data as $data ) {
        $wpdb->insert( $table_name, $data );
    }
}
