<?php
/*
Plugin Name: WPSOOG - نظام متعدد البائعين
Plugin URI: https://github.com/aymen12charaf-alt/Agons
Description: نظام متكامل متعدد البائعين مع دعم شركات الشحن في الجزائر
Version: 1.0.0
Author: aymen12charaf-alt
Author URI: https://github.com/aymen12charaf-alt
Text Domain: wpsoog
Domain Path: /languages
*/

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WPSOOG_VERSION', '1.0.0');
define('WPSOOG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPSOOG_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once WPSOOG_PLUGIN_DIR . 'includes/class-vendor.php';
require_once WPSOOG_PLUGIN_DIR . 'includes/class-product.php';
require_once WPSOOG_PLUGIN_DIR . 'includes/class-admin.php';
require_once WPSOOG_PLUGIN_DIR . 'includes/class-shipping.php';

// Initialize the plugin
function wpsoog_init() {
    // Load text domain for translations
    load_plugin_textdomain('wpsoog', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize classes
    new WPSOOG_Vendor();
    new WPSOOG_Product();
    new WPSOOG_Admin();
    new WPSOOG_Shipping();
}
add_action('plugins_loaded', 'wpsoog_init');

// Activation hook
register_activation_hook(__FILE__, 'wpsoog_activate');
function wpsoog_activate() {
    // Create necessary database tables
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpsoog_vendors (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        shop_name varchar(200) NOT NULL,
        shop_description text,
        template_id int(11) DEFAULT 1,
        date_created datetime DEFAULT CURRENT_TIMESTAMP,
        status varchar(20) DEFAULT 'pending',
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wpsoog_deactivate');
function wpsoog_deactivate() {
    // Cleanup tasks if needed
}