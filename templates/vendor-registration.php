<?php
if (!defined('ABSPATH')) {
    exit;
}

$templates = (new MVP_Vendor())->get_store_templates();
?>

<div class="mvp-registration-form">
    <h2><?php _e('تسجيل متجر جديد', 'multi-vendor-plugin'); ?></h2>
    
    <form id="vendor-registration-form" method="post">
        <?php wp_nonce_field('vendor_registration', 'vendor_nonce'); ?>
        
        <div class="form-group">
            <label for="shop_name"><?php _e('اسم المتجر', 'multi-vendor-plugin'); ?></label>
            <input type="text" id="shop_name" name="shop_name" required>
        </div>
        
        <div class="form-group">
            <label for="shop_description"><?php _e('وصف المتجر', 'multi-vendor-plugin'); ?></label>
            <textarea id="shop_description" name="shop_description" rows="4" required></textarea>
        </div>
        
        <div class="form-group">
            <label><?php _e('اختر قالب المتجر', 'multi-vendor-plugin'); ?></label>
            <div class="template-selector">
                <?php foreach ($templates as $id => $template): ?>
                    <div class="template-option">
                        <input type="radio" 
                               name="template_id" 
                               id="template_<?php echo esc_attr($id); ?>" 
                               value="<?php echo esc_attr($id); ?>"
                               <?php echo ($id === 1) ? 'checked' : ''; ?>>
                        
                        <label for="template_<?php echo esc_attr($id); ?>">
                            <img src="<?php echo esc_url($template['preview']); ?>" 
                                 alt="<?php echo esc_attr($template['name']); ?>">
                            <h3><?php echo esc_html($template['name']); ?></h3>
                            <p><?php echo esc_html($template['description']); ?></p>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="button button-primary">
                <?php _e('تسجيل المتجر', 'multi-vendor-plugin'); ?>
            </button>
        </div>
    </form>
</div>

<style>
.template-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.template-option {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
}

.template-option:hover {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.template-option input[type="radio"] {
    display: none;
}

.template-option label {
    cursor: pointer;
    display: block;
}

.template-option img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-bottom: 10px;
}

.template-option input[type="radio"]:checked + label {
    background: #f7f7f7;
    border-radius: 8px;
}

.template-option h3 {
    margin: 10px 0;
    font-size: 18px;
}

.template-option p {
    color: #666;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#vendor-registration-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'register_vendor');
        formData.append('nonce', $('#vendor_nonce').val());
        
        $.ajax({
            url: mvp_vars.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.button-primary').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    window.location.reload();
                } else {
                    alert(response.data);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء معالجة الطلب');
            },
            complete: function() {
                $('.button-primary').prop('disabled', false);
            }
        });
    });
    
    // معاينة القوالب
    $('.template-option').on('click', function() {
        $(this).find('input[type="radio"]').prop('checked', true);
    });
});
</script>
