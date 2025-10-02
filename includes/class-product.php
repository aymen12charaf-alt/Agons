<?php
if (!defined('ABSPATH')) {
    exit;
}

class MVP_Product {
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('woocommerce_product_data_tabs', array($this, 'add_vendor_product_data_tab'));
        add_action('woocommerce_product_data_panels', array($this, 'add_vendor_product_data_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_vendor_product_data'));
    }

    public function init() {
        // إضافة خصائص مخصصة للمنتجات
        add_action('add_meta_boxes', array($this, 'add_vendor_meta_box'));
    }

    // إضافة تبويب البائع في صفحة المنتج
    public function add_vendor_product_data_tab($tabs) {
        $tabs['vendor'] = array(
            'label' => __('معلومات البائع', 'multi-vendor-plugin'),
            'target' => 'vendor_product_data',
            'class' => array('show_if_simple', 'show_if_variable'),
        );
        return $tabs;
    }

    // إضافة حقول بيانات البائع
    public function add_vendor_product_data_fields() {
        global $post;
        ?>
        <div id="vendor_product_data" class="panel woocommerce_options_panel">
            <?php
            woocommerce_wp_text_input(array(
                'id' => '_vendor_commission',
                'label' => __('نسبة العمولة (%)', 'multi-vendor-plugin'),
                'desc_tip' => true,
                'description' => __('أدخل نسبة العمولة للبائع', 'multi-vendor-plugin'),
                'type' => 'number',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '0',
                    'max' => '100'
                )
            ));
            ?>
        </div>
        <?php
    }

    // حفظ بيانات المنتج
    public function save_vendor_product_data($post_id) {
        if (isset($_POST['_vendor_commission'])) {
            update_post_meta($post_id, '_vendor_commission', sanitize_text_field($_POST['_vendor_commission']));
        }
    }

    // إضافة صندوق معلومات البائع
    public function add_vendor_meta_box() {
        add_meta_box(
            'vendor_product_meta_box',
            __('معلومات البائع', 'multi-vendor-plugin'),
            array($this, 'render_vendor_meta_box'),
            'product',
            'side',
            'default'
        );
    }

    // عرض صندوق معلومات البائع
    public function render_vendor_meta_box($post) {
        $vendor_id = get_post_meta($post->ID, '_vendor_id', true);
        $vendors = $this->get_all_vendors();
        ?>
        <select name="vendor_id" id="vendor_id">
            <option value=""><?php _e('اختر البائع', 'multi-vendor-plugin'); ?></option>
            <?php foreach ($vendors as $vendor) : ?>
                <option value="<?php echo esc_attr($vendor->id); ?>" <?php selected($vendor_id, $vendor->id); ?>>
                    <?php echo esc_html($vendor->shop_name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    // الحصول على قائمة البائعين
    private function get_all_vendors() {
        global $wpdb;
        return $wpdb->get_results("SELECT id, shop_name FROM {$wpdb->prefix}mvp_vendors WHERE status = 'approved'");
    }

    // حساب العمولة للمنتج
    public function calculate_commission($product_id, $price) {
        $commission_rate = get_post_meta($product_id, '_vendor_commission', true);
        if (!$commission_rate) {
            $commission_rate = 10; // نسبة العمولة الافتراضية
        }
        return ($price * $commission_rate) / 100;
    }
}