<?php
if (!defined('ABSPATH')) {
    exit;
}

class MVP_Vendor {
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_register_vendor', array($this, 'register_vendor'));
    }

    public function init() {
        // تسجيل نوع المستخدم للبائع
        add_role('vendor', 'بائع', array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'upload_files' => true,
            'manage_products' => true
        ));
    }

    // تسجيل بائع جديد
    public function register_vendor() {
        if (!wp_verify_nonce($_POST['nonce'], 'vendor_registration')) {
            wp_send_json_error('خطأ في التحقق من الأمان');
        }

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('يجب تسجيل الدخول أولاً');
        }

        global $wpdb;
        $shop_name = sanitize_text_field($_POST['shop_name']);
        $shop_description = sanitize_textarea_field($_POST['shop_description']);

        $result = $wpdb->insert(
            $wpdb->prefix . 'mvp_vendors',
            array(
                'user_id' => $user_id,
                'shop_name' => $shop_name,
                'shop_description' => $shop_description,
                'status' => 'pending'
            ),
            array('%d', '%s', '%s', '%s')
        );

        if ($result) {
            // إضافة دور البائع للمستخدم
            $user = new WP_User($user_id);
            $user->add_role('vendor');
            wp_send_json_success('تم تسجيل المتجر بنجاح وبانتظار الموافقة');
        }

        wp_send_json_error('حدث خطأ أثناء التسجيل');
    }

    // الحصول على معلومات البائع
    public function get_vendor_info($vendor_id) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}mvp_vendors WHERE id = %d",
                $vendor_id
            )
        );
    }

    // تحديث معلومات البائع
    public function update_vendor_info($vendor_id, $data) {
        global $wpdb;
        return $wpdb->update(
            $wpdb->prefix . 'mvp_vendors',
            $data,
            array('id' => $vendor_id)
        );
    }
}