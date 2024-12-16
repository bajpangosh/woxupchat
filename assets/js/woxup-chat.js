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
    
    const name = document.getElementById('woxup-name').value;
    const email = document.getElementById('woxup-email').value;
    const message = document.getElementById('woxup-message').value;
    const phone = document.getElementById('woxup-phone').value;
    
    if (validateForm(name, email, message)) {
        // Format the WhatsApp message
        const whatsappMessage = `Hello! My name is ${name}. ${message}`;
        const encodedMessage = encodeURIComponent(whatsappMessage);
        
        // Open WhatsApp with the pre-filled message
        window.open(`https://wa.me/${phone}?text=${encodedMessage}`, '_blank');
    }
}

function validateForm(name, email, message) {
    if (!name || !email || !message) {
        showError('Please fill in all required fields');
        return false;
    }
    
    if (!isValidEmail(email)) {
        showError('Please enter a valid email address');
        return false;
    }
    
    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showError(message) {
    const errorDiv = document.getElementById('woxup-error-message');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 3000);
    }
}

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    // Save user preference
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('woxupDarkMode', isDarkMode);
}
