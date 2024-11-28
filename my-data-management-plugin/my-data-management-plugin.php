<?php

/*
Plugin Name: My Data Management Plugin
Plugin URI: #
Description: A plugin to manage data via a custom table and AJAX in the WordPress admin panel.
Version: 1.0
Author: Andriy Vovk
Author URI: #
License: GPL2
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ )  );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ )  );
define( 'MY_TABLE_NAME', 'custom_tb' );

// Include necessary files
require_once MY_PLUGIN_PATH . 'inc/setup-db.php';
require_once MY_PLUGIN_PATH . 'inc/my-data-table.php';
require_once MY_PLUGIN_PATH . 'inc/my-data-ajax.php';

// Add plugin to the Tools submenu
add_action('admin_menu', 'my_data_management_add_to_tools_menu');

function my_data_management_add_to_tools_menu () {
    add_submenu_page(
        'tools.php',                     // Parent menu (Tools)
        'My Data Management',            // Page title (tab name in menu)
        'My Data Management',            // Menu title
        'manage_options',                // Required capability for access
        'my-data-management',            // Menu slug (URL part)
        'my_data_management_page_html'   // Function to display the page content
    );
}

function my_data_management_page_html () {
    require_once MY_PLUGIN_PATH  . 'templates/table-admin.php';
}

// Enqueue styles and scripts for the admin panel of the plugin
add_action( 'admin_enqueue_scripts', 'my_plugin_admin_assets' );

function my_plugin_admin_assets() {
    $page = isset( $_GET['page'] ) ? $_GET['page'] : '';

    if ( $page == 'my-data-management' ) {
        wp_enqueue_style( 'my-plugin-admin-style', MY_PLUGIN_URL . 'assets/css/admin.css' ); // Enqueue CSS style for the admin panel
        wp_enqueue_script( 'my-plugin-admin-script', MY_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), null, true );// Enqueue JavaScript for the admin panel
    }
    
}

// Create the custom table on plugin activation
register_activation_hook( __FILE__, 'my_data_management_create_table' );






