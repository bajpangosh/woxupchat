/**
 * WoxupChat JavaScript
 * Version: 2.0.0
 */

jQuery(document).ready(function($) {
    const $form = $('#woxupchat-form');
    const $response = $('#woxupchat-response');
    
    $form.on('submit', function(e) {
        e.preventDefault();
        
        const $submitButton = $form.find('button[type="submit"]');
        
        // Disable submit button
        $submitButton.prop('disabled', true);
        
        // Clear previous messages
        $response.removeClass('woxupchat-error woxupchat-success').html('').show();
        
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
                    // Show success message
                    $response.addClass('woxupchat-success').html(response.data.message);
                    
                    // Reset form
                    $form[0].reset();
                    
                    // Redirect to WhatsApp
                    if (response.data.whatsapp_number && response.data.whatsapp_message) {
                        setTimeout(function() {
                            window.open('https://wa.me/' + response.data.whatsapp_number + '?text=' + response.data.whatsapp_message, '_blank');
                        }, 1000);
                    }
                } else {
                    // Show error message
                    $response.addClass('woxupchat-error').html(response.data.message || 'An error occurred. Please try again.');
                }
            },
            error: function() {
                $response.addClass('woxupchat-error').html('An error occurred. Please try again.');
            },
            complete: function() {
                // Re-enable submit button
                $submitButton.prop('disabled', false);
            }
        });
    });
});
