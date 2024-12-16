/**
 * Woxup Chat JavaScript
 * Version: 2.0.0
 */

jQuery(document).ready(function($) {
    const $form = $('#woxup-chat-form');
    const $response = $('#woxup-response');
    const darkModeToggle = document.getElementById('woxup-dark-mode-toggle');
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
    
    // Initialize dark mode based on user preference
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('dark-mode');
    }
    
    $form.on('submit', function(e) {
        e.preventDefault();
        
        const $submitButton = $form.find('button[type="submit"]');
        
        // Disable submit button
        $submitButton.prop('disabled', true);
        
        // Clear previous messages
        $response.removeClass('woxup-error woxup-success').html('').show();
        
        // Collect form data
        const formData = new FormData();
        formData.append('action', 'woxup_chat_submit');
        formData.append('nonce', $('#woxup_chat_nonce').val());
        formData.append('name', $('#woxup-name').val());
        formData.append('email', $('#woxup-email').val());
        formData.append('subject', $('#woxup-subject').val());
        formData.append('message', $('#woxup-message').val());
        formData.append('phone', $('#woxup-phone').val());
        
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
                    $response.addClass('woxup-success').html(response.data.message);
                    
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
                    $response.addClass('woxup-error').html(response.data.message || 'An error occurred. Please try again.');
                }
            },
            error: function() {
                $response.addClass('woxup-error').html('An error occurred. Please try again.');
            },
            complete: function() {
                // Re-enable submit button
                $submitButton.prop('disabled', false);
            }
        });
    });
});

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    // Save user preference
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('woxupDarkMode', isDarkMode);
}
