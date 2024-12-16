/**
 * WoxupChat JavaScript
 * Version: 2.0.0
 */

jQuery(document).ready(function($) {
    const $form = $('#woxupchat-form');
    const $response = $('#woxupchat-response');
    const $loading = $('.woxupchat-loading');
    
    // Handle form submission
    $form.on('submit', function(e) {
        e.preventDefault();
        
        const $submitButton = $form.find('button[type="submit"]');
        const $submitText = $submitButton.text();
        
        // Show loading animation
        $loading.show();
        $submitButton.prop('disabled', true);
        
        // Clear previous messages with fade effect
        $response.fadeOut(200, function() {
            $(this).removeClass('woxupchat-error woxupchat-success').html('');
        });
        
        // Collect form data
        const formData = new FormData();
        formData.append('action', 'woxupchat_submit');
        formData.append('nonce', $('#woxupchat_nonce').val());
        formData.append('name', $('#woxupchat-name').val());
        formData.append('email', $('#woxupchat-email').val());
        formData.append('subject', $('#woxupchat-subject').val());
        formData.append('message', $('#woxupchat-message').val());
        
        // Send AJAX request
        $.ajax({
            url: woxupChat.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message with fade effect
                    $response
                        .addClass('woxupchat-success')
                        .html(response.data.message)
                        .fadeIn(300);
                    
                    // Reset form with smooth animation
                    $form.find('input, textarea').each(function() {
                        $(this).fadeOut(200).fadeIn(200);
                    });
                    $form[0].reset();
                    
                    // Redirect to WhatsApp after a short delay
                    if (response.data.whatsapp_number && response.data.whatsapp_message) {
                        setTimeout(function() {
                            window.open('https://wa.me/' + response.data.whatsapp_number + '?text=' + response.data.whatsapp_message, '_blank');
                        }, 1000);
                    }
                } else {
                    // Show error message with fade effect
                    $response
                        .addClass('woxupchat-error')
                        .html(response.data.message || 'An error occurred. Please try again.')
                        .fadeIn(300);
                }
            },
            error: function() {
                // Show error message with fade effect
                $response
                    .addClass('woxupchat-error')
                    .html('An error occurred. Please try again.')
                    .fadeIn(300);
            },
            complete: function() {
                // Hide loading animation and re-enable submit button
                $loading.hide();
                $submitButton.prop('disabled', false);
            }
        });
    });
    
    // Add input focus effects
    $form.find('input, textarea').on('focus', function() {
        $(this).closest('.woxupchat-form-group').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.woxupchat-form-group').removeClass('focused');
    });
});
