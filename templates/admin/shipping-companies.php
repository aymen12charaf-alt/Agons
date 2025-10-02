<?php
if (!defined('ABSPATH')) {
    exit;
}

$shipping = new MVP_Shipping();
$companies = $shipping->get_available_companies();
?>

<div class="wrap">
    <h1><?php _e('إدارة شركات الشحن', 'multi-vendor-plugin'); ?></h1>

    <!-- قائمة شركات الشحن -->
    <div class="mvp-shipping-companies">
        <h2><?php _e('شركات الشحن المتوفرة', 'multi-vendor-plugin'); ?></h2>
        <div class="companies-grid">
            <?php foreach ($companies as $id => $company): ?>
                <div class="company-card">
                    <img src="<?php echo MVP_PLUGIN_URL . 'assets/images/shipping/' . $company['logo']; ?>" 
                         alt="<?php echo esc_attr($company['name']); ?>"
                         class="company-logo">
                    
                    <h3><?php echo esc_html($company['name']); ?></h3>
                    <p class="company-description"><?php echo esc_html($company['description']); ?></p>
                    
                    <div class="company-details">
                        <p>
                            <strong><?php _e('التكلفة الأساسية:', 'multi-vendor-plugin'); ?></strong>
                            <?php echo number_format($company['base_cost'], 2) . ' ' . __('دج', 'multi-vendor-plugin'); ?>
                        </p>
                        <p>
                            <strong><?php _e('مدة التوصيل المقدرة:', 'multi-vendor-plugin'); ?></strong>
                            <?php echo esc_html($company['estimated_days']) . ' ' . __('أيام', 'multi-vendor-plugin'); ?>
                        </p>
                    </div>

                    <div class="company-actions">
                        <button class="button edit-company" data-company-id="<?php echo esc_attr($id); ?>">
                            <?php _e('تعديل', 'multi-vendor-plugin'); ?>
                        </button>
                        <a href="<?php echo esc_url($company['tracking_url']); ?>" 
                           target="_blank" 
                           class="button button-secondary">
                            <?php _e('تتبع الشحنات', 'multi-vendor-plugin'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- إضافة شركة شحن جديدة -->
    <div class="mvp-add-shipping-company">
        <h2><?php _e('إضافة شركة شحن جديدة', 'multi-vendor-plugin'); ?></h2>
        <form id="add-shipping-company-form" method="post">
            <?php wp_nonce_field('add_shipping_company', 'shipping_nonce'); ?>
            
            <div class="form-group">
                <label for="company_name"><?php _e('اسم الشركة', 'multi-vendor-plugin'); ?></label>
                <input type="text" id="company_name" name="company_name" required>
            </div>

            <div class="form-group">
                <label for="company_description"><?php _e('وصف الشركة', 'multi-vendor-plugin'); ?></label>
                <textarea id="company_description" name="company_description" rows="3" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="base_cost"><?php _e('التكلفة الأساسية (دج)', 'multi-vendor-plugin'); ?></label>
                    <input type="number" id="base_cost" name="base_cost" min="0" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="estimated_days"><?php _e('مدة التوصيل المقدرة (أيام)', 'multi-vendor-plugin'); ?></label>
                    <input type="text" id="estimated_days" name="estimated_days" placeholder="مثال: 2-3" required>
                </div>
            </div>

            <div class="form-group">
                <label for="tracking_url"><?php _e('رابط تتبع الشحنات', 'multi-vendor-plugin'); ?></label>
                <input type="url" id="tracking_url" name="tracking_url" required>
            </div>

            <div class="form-group">
                <label for="company_logo"><?php _e('شعار الشركة', 'multi-vendor-plugin'); ?></label>
                <input type="file" id="company_logo" name="company_logo" accept="image/*" required>
            </div>

            <button type="submit" class="button button-primary">
                <?php _e('إضافة شركة الشحن', 'multi-vendor-plugin'); ?>
            </button>
        </form>
    </div>
</div>

<style>
.companies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.company-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
}

.company-logo {
    max-width: 150px;
    height: auto;
    margin-bottom: 15px;
}

.company-description {
    color: #666;
    margin: 10px 0;
}

.company-details {
    margin: 15px 0;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
}

.company-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.mvp-add-shipping-company {
    max-width: 600px;
    margin-top: 30px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

input[type="text"],
input[type="number"],
input[type="url"],
textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // تحديث شركة الشحن
    $('.edit-company').on('click', function() {
        var companyId = $(this).data('company-id');
        // فتح نموذج التحرير
        // يمكن إضافة المزيد من الكود هنا
    });

    // إضافة شركة شحن جديدة
    $('#add-shipping-company-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'add_shipping_company');
        formData.append('nonce', $('#shipping_nonce').val());
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('تم إضافة شركة الشحن بنجاح');
                    window.location.reload();
                } else {
                    alert(response.data);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء إضافة شركة الشحن');
            }
        });
    });
});
</script>
