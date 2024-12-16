<?php
/*
 * Plugin Name: Wha Form
 * Plugin URI: 
 * Description: A plugin that allows users to submit a form with their name, email address, and requirements, and sends the form data to a WhatsApp number using the WhatsApp API.
 * Author: KLOUDBOY
 * Author URI: https://www.kloudboy.com
 * Version: 1.0
 * License: GPL2
 */
 
function whatsapp_contact_form_styles() {
  wp_enqueue_style( 'whatsapp-contact-form', plugin_dir_url( __FILE__ ) . 'whatsapp-contact-form.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'whatsapp_contact_form_styles' );


function whatsapp_contact_form_scripts() {
  // Enqueue the script that handles the form submission
  wp_enqueue_script( 'whatsapp-contact-form', plugin_dir_url( __FILE__ ) . 'whatsapp-contact-form.js', array( 'jquery' ), '1.0', true );

  // Pass the WhatsApp number to the script
  wp_localize_script( 'whatsapp-contact-form', 'whatsapp_contact_form_vars', array(
    'whatsapp_number' => get_option( 'whatsapp_contact_form_number' )
  ));
}
add_action( 'wp_enqueue_scripts', 'whatsapp_contact_form_scripts' );

function whatsapp_contact_form_shortcode() {
  // Output the form HTML
  ob_start();
  ?>
<form id="whatsapp-contact-form">
  <p>
    
    <input type="text" id="name" name="name" placeholder="Your name" required>
  </p>
  <p>
  
    <input type="email" id="email" name="email" placeholder="Your email address" required>
  </p>
  <p>
    
    <textarea id="requirements" name="requirements" placeholder="Your requirements" required></textarea>
  </p>
  <p>
    <input type="submit" value="Send">
  </p>
</form>

  <?php
  return ob_get_clean();
}
add_shortcode( 'whatsapp_contact_form', 'whatsapp_contact_form_shortcode' );

// Add an admin menu item for the plugin settings
add_action( 'admin_menu', 'whatsapp_contact_form_menu' );
function whatsapp_contact_form_menu() {
  add_options_page( 'WhatsApp Contact Form Settings', 'WhatsApp Contact Form', 'manage_options', 'whatsapp-contact-form', 'whatsapp_contact_form_settings_page' );
}

// Register the plugin settings
add_action( 'admin_init', 'whatsapp_contact_form_settings' );
function whatsapp_contact_form_settings() {
  register_setting( 'whatsapp-contact-form-settings-group', 'whatsapp_contact_form_number' );
}

// Output the plugin settings page
function whatsapp_contact_form_settings_page() {
  ?>
  <div class="wrap">
    <h1>WhatsApp Contact Form Settings</h1>
    <form method="post" action="options.php">
      <?php settings_fields( 'whatsapp-contact-form-settings-group' ); ?>
      <?php do_settings_sections( 'whatsapp-contact-form-settings-group' ); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">WhatsApp Number</th>
          <td><input type="text" name="whatsapp_contact_form_number" value="<?php echo esc_attr( get_option( 'whatsapp_contact_form_number' ) ); ?>" /></td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}
