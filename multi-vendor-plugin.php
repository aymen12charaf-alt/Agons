<?php
/*
Plugin Name: نظام متعدد البائعين
Plugin URI: https://github.com/aymen12charaf-alt/Agons
Description: إضافة نظام متعدد البائعين لووردبريس
Version: 1.0.0
Author: aymen12charaf-alt
Author URI: https://github.com/aymen12charaf-alt
Text Domain: multi-vendor-plugin
Domain Path: /languages
*/

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MVP_VERSION', '1.0.0');
define('MVP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MVP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once MVP_PLUGIN_DIR . 'includes/class-vendor.php';
require_once MVP_PLUGIN_DIR . 'includes/class-product.php';
require_once MVP_PLUGIN_DIR . 'includes/class-admin.php';

// Initialize the plugin
function mvp_init() {
    // Load text domain for translations
    load_plugin_textdomain('multi-vendor-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize classes
    new MVP_Vendor();
    new MVP_Product();
    new MVP_Admin();
}
add_action('plugins_loaded', 'mvp_init');

// Activation hook
register_activation_hook(__FILE__, 'mvp_activate');
function mvp_activate() {
    // Create necessary database tables
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mvp_vendors (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        shop_name varchar(200) NOT NULL,
        shop_description text,
        date_created datetime DEFAULT CURRENT_TIMESTAMP,
        status varchar(20) DEFAULT 'pending',
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'mvp_deactivate');
function mvp_deactivate() {
    // Cleanup tasks if needed
}