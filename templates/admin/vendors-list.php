<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$vendors = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mvp_vendors ORDER BY date_created DESC");
?>

<div class="mvp-vendors-list">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('اسم المتجر', 'multi-vendor-plugin'); ?></th>
                <th><?php _e('البائع', 'multi-vendor-plugin'); ?></th>
                <th><?php _e('الحالة', 'multi-vendor-plugin'); ?></th>
                <th><?php _e('تاريخ التسجيل', 'multi-vendor-plugin'); ?></th>
                <th><?php _e('الإجراءات', 'multi-vendor-plugin'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($vendors): ?>
                <?php foreach ($vendors as $vendor): ?>
                    <?php
                    $user_info = get_userdata($vendor->user_id);
                    $status_class = $vendor->status === 'approved' ? 'status-approved' : 'status-pending';
                    ?>
                    <tr>
                        <td><?php echo esc_html($vendor->shop_name); ?></td>
                        <td><?php echo esc_html($user_info->display_name); ?></td>
                        <td>
                            <span class="mvp-status <?php echo esc_attr($status_class); ?>">
                                <?php echo esc_html($vendor->status === 'approved' ? 'معتمد' : 'قيد المراجعة'); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($vendor->date_created))); ?></td>
                        <td>
                            <?php if ($vendor->status !== 'approved'): ?>
                                <a href="#" class="button approve-vendor" data-vendor-id="<?php echo esc_attr($vendor->id); ?>">
                                    <?php _e('موافقة', 'multi-vendor-plugin'); ?>
                                </a>
                            <?php endif; ?>
                            <a href="#" class="button edit-vendor" data-vendor-id="<?php echo esc_attr($vendor->id); ?>">
                                <?php _e('تعديل', 'multi-vendor-plugin'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5"><?php _e('لا يوجد بائعين حالياً', 'multi-vendor-plugin'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
