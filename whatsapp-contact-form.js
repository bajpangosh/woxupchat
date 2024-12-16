jQuery(document).ready(function($) {
  // Handle the form submission
  $('#whatsapp-contact-form').submit(function(e) {
    e.preventDefault();

    // Get the form data
    var name = $('#name').val();
    var email = $('#email').val();
    var requirements = $('#requirements').val();

    // Get the WhatsApp number
    var whatsapp_number = whatsapp_contact_form_vars.whatsapp_number;

    // Build the WhatsApp API URL
    var whatsapp_api_url = 'https://api.whatsapp.com/send';
    var url = whatsapp_api_url + '?phone=' + whatsapp_number + '&text=';

    // Build the message text
    var text = 'Name: ' + name + '%0AEmail: ' + email + '%0ARequirements: ' + requirements;

    // Send the message
    window.open(url + text);
  });
});
