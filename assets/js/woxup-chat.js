/**
 * Woxup Chat JavaScript
 * Version: 2.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the chat widget
    initWoxupChat();
});

function initWoxupChat() {
    const form = document.getElementById('woxup-chat-form');
    const darkModeToggle = document.getElementById('woxup-dark-mode-toggle');
    
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
    
    // Initialize dark mode based on user preference
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('dark-mode');
    }
}

function handleFormSubmit(event) {
    event.preventDefault();
    
    const $form = document.getElementById('woxup-chat-form');
    const $response = document.getElementById('woxup-response');
    const $submitButton = $form.querySelector('button[type="submit"]');
    
    // Disable submit button
    $submitButton.disabled = true;
    
    // Clear previous messages
    $response.classList.remove('woxup-error', 'woxup-success');
    $response.innerHTML = '';
    
    // Collect form data
    const formData = new FormData();
    formData.append('action', 'woxup_chat_submit');
    formData.append('nonce', woxupChat.nonce);
    formData.append('name', document.getElementById('woxup-name').value);
    formData.append('email', document.getElementById('woxup-email').value);
    formData.append('message', document.getElementById('woxup-message').value);
    formData.append('phone', document.getElementById('woxup-phone').value);
    
    // Send AJAX request
    fetch(woxupChat.ajaxurl, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            // Show success message
            $response.classList.add('woxup-success');
            $response.innerHTML = response.data.message;
            
            // Reset form
            $form.reset();
            
            // Redirect to WhatsApp
            const whatsappUrl = `https://wa.me/${response.data.whatsapp_number}?text=${response.data.whatsapp_message}`;
            window.open(whatsappUrl, '_blank');
        } else {
            // Show error message
            $response.classList.add('woxup-error');
            $response.innerHTML = response.data.message;
        }
    })
    .catch(() => {
        $response.classList.add('woxup-error');
        $response.innerHTML = 'An error occurred. Please try again.';
    })
    .finally(() => {
        // Re-enable submit button
        $submitButton.disabled = false;
    });
}

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    // Save user preference
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('woxupDarkMode', isDarkMode);
}
