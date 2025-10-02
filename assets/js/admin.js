jQuery(document).ready(function($) {
    // التعامل مع الموافقة على البائع
    $('.approve-vendor').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var vendorId = button.data('vendor-id');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'approve_vendor',
                vendor_id: vendorId,
                nonce: mvp_admin.nonce
            },
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // تحديث حالة البائع في الواجهة
                    button.closest('tr').find('.mvp-status')
                        .removeClass('status-pending')
                        .addClass('status-approved')
                        .text('معتمد');
                    
                    button.remove();
                    
                    // عرض رسالة نجاح
                    $('.wrap h1').after(
                        '<div class="mvp-notice success">' +
                        '<p>تم اعتماد البائع بنجاح</p>' +
                        '</div>'
                    );
                } else {
                    // عرض رسالة الخطأ
                    $('.wrap h1').after(
                        '<div class="mvp-notice error">' +
                        '<p>' + response.data + '</p>' +
                        '</div>'
                    );
                }
            },
            error: function() {
                // عرض رسالة خطأ عامة
                $('.wrap h1').after(
                    '<div class="mvp-notice error">' +
                    '<p>حدث خطأ أثناء معالجة الطلب</p>' +
                    '</div>'
                );
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
    
    // إخفاء رسائل النظام بعد 3 ثواني
    setTimeout(function() {
        $('.mvp-notice').fadeOut();
    }, 3000);
    
    // تحديث نسبة العمولة
    $('#commission_rate').on('change', function() {
        var rate = $(this).val();
        if (rate < 0) {
            $(this).val(0);
        } else if (rate > 100) {
            $(this).val(100);
        }
    });
});