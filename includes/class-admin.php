<?php
if (!defined('ABSPATH')) {
    exit;
}

class MVP_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    // إضافة قائمة الإدارة
    public function add_admin_menu() {
        add_menu_page(
            __('نظام متعدد البائعين', 'multi-vendor-plugin'),
            __('متعدد البائعين', 'multi-vendor-plugin'),
            'manage_options',
            'mvp-settings',
            array($this, 'render_settings_page'),
            'dashicons-store',
            55
        );

        add_submenu_page(
            'mvp-settings',
            __('البائعون', 'multi-vendor-plugin'),
            __('البائعون', 'multi-vendor-plugin'),
            'manage_options',
            'mvp-vendors',
            array($this, 'render_vendors_page')
        );
    }

    // تهيئة الإعدادات
    public function init_settings() {
        register_setting('mvp_options', 'mvp_commission_rate');
        register_setting('mvp_options', 'mvp_auto_approve_vendors');
        
        add_settings_section(
            'mvp_general_settings',
            __('الإعدادات العامة', 'multi-vendor-plugin'),
            array($this, 'render_general_settings_section'),
            'mvp-settings'
        );

        add_settings_field(
            'commission_rate',
            __('نسبة العمولة الافتراضية', 'multi-vendor-plugin'),
            array($this, 'render_commission_rate_field'),
            'mvp-settings',
            'mvp_general_settings'
        );

        add_settings_field(
            'auto_approve_vendors',
            __('موافقة تلقائية على البائعين', 'multi-vendor-plugin'),
            array($this, 'render_auto_approve_field'),
            'mvp-settings',
            'mvp_general_settings'
        );
    }

    // إضافة ملفات CSS و JavaScript
    public function enqueue_admin_scripts() {
        wp_enqueue_style(
            'mvp-admin-style',
            MVP_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            MVP_VERSION
        );

        wp_enqueue_script(
            'mvp-admin-script',
            MVP_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            MVP_VERSION,
            true
        );
    }

    // عرض صفحة الإعدادات
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('mvp_options');
                do_settings_sections('mvp-settings');
                submit_button(__('حفظ الإعدادات', 'multi-vendor-plugin'));
                ?>
            </form>
        </div>
        <?php
    }

    // عرض صفحة البائعين
    public function render_vendors_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php _e('إدارة البائعين', 'multi-vendor-plugin'); ?></h1>
            <?php
            // عرض قائمة البائعين
            require_once MVP_PLUGIN_DIR . 'templates/admin/vendors-list.php';
            ?>
        </div>
        <?php
    }

    // عرض قسم الإعدادات العامة
    public function render_general_settings_section() {
        echo '<p>' . __('الإعدادات الأساسية لنظام متعدد البائعين', 'multi-vendor-plugin') . '</p>';
    }

    // عرض حقل نسبة العمولة
    public function render_commission_rate_field() {
        $value = get_option('mvp_commission_rate', 10);
        ?>
        <input type="number" 
               name="mvp_commission_rate" 
               value="<?php echo esc_attr($value); ?>"
               class="small-text"
               min="0"
               max="100"
               step="0.1"
        /> %
        <?php
    }

    // عرض حقل الموافقة التلقائية
    public function render_auto_approve_field() {
        $value = get_option('mvp_auto_approve_vendors', 0);
        ?>
        <input type="checkbox" 
               name="mvp_auto_approve_vendors" 
               value="1"
               <?php checked(1, $value); ?>
        />
        <span class="description">
            <?php _e('الموافقة التلقائية على طلبات البائعين الجدد', 'multi-vendor-plugin'); ?>
        </span>
        <?php
    }
}