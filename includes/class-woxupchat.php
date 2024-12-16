<?php
/**
 * Main WoxupChat Class
 */
class WoxupChat {
    private $version;
    private $plugin_name;

    public function __construct() {
        $this->version = WOXUPCHAT_VERSION;
        $this->plugin_name = 'woxupchat';
        
        // Add menu item to WordPress admin
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Register shortcode and assets
        add_shortcode('woxupchat_form', array($this, 'render_chat_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
        
        // Register AJAX handlers
        add_action('wp_ajax_woxupchat_submit', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_woxupchat_submit', array($this, 'handle_form_submission'));
    }

    public function run() {
        // Initialize plugin functionality
    }

    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_menu_page(
            'WoxupChat', // Page title
            'WoxupChat', // Menu title
            'manage_options', // Capability
            'woxupchat', // Menu slug
            array($this, 'display_admin_dashboard'), // Function to display the page
            'dashicons-whatsapp', // Icon
            30 // Position
        );

        add_submenu_page(
            'woxupchat', // Parent slug
            'Settings', // Page title
            'Settings', // Menu title
            'manage_options', // Capability
            'woxupchat-settings', // Menu slug
            array($this, 'display_settings_page') // Function to display the page
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('woxupchat_settings', 'woxupchat_number');
        register_setting('woxupchat_settings', 'woxupchat_default_message');
    }

    /**
     * Display admin dashboard
     */
    public function display_admin_dashboard() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p><?php _e('Welcome to WoxupChat! Use the shortcode [woxupchat_form] to display the contact form.', 'woxupchat'); ?></p>
        </div>
        <?php
    }

    /**
     * Display settings page
     */
    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('woxupchat_settings');
                do_settings_sections('woxupchat_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="woxupchat_number"><?php _e('WhatsApp Number', 'woxupchat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="woxupchat_number" name="woxupchat_number" 
                                value="<?php echo esc_attr(get_option('woxupchat_number')); ?>" 
                                class="regular-text"
                                placeholder="e.g., 919876543210"
                            />
                            <p class="description">
                                <?php _e('Enter your WhatsApp number with country code (e.g., 919876543210 for India)', 'woxupchat'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="woxupchat_default_message"><?php _e('Default Message', 'woxupchat'); ?></label>
                        </th>
                        <td>
                            <textarea id="woxupchat_default_message" name="woxupchat_default_message" 
                                class="large-text" rows="3"
                            ><?php echo esc_textarea(get_option('woxupchat_default_message')); ?></textarea>
                            <p class="description">
                                <?php _e('Default message template for WhatsApp (optional)', 'woxupchat'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Enqueue public facing assets
     */
    public function enqueue_public_assets() {
        wp_enqueue_style(
            'woxupchat-style',
            WOXUPCHAT_PLUGIN_URL . 'assets/css/woxupchat.css',
            array(),
            $this->version
        );

        wp_enqueue_script(
            'woxupchat-script',
            WOXUPCHAT_PLUGIN_URL . 'assets/js/woxupchat.js',
            array('jquery'),
            $this->version,
            true
        );

        wp_localize_script('woxupchat-script', 'woxupChat', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('woxupchat_nonce')
        ));
    }

    /**
     * Render the chat form shortcode
     */
    public function render_chat_form() {
        $whatsapp_number = get_option('woxupchat_number', '');
        $default_message = get_option('woxupchat_default_message', '');
        
        ob_start();
        ?>
        <div class="woxupchat-container">
            <form id="woxupchat-form" class="woxupchat-form">
                <?php wp_nonce_field('woxupchat_nonce', 'woxupchat_nonce'); ?>
                
                <div class="woxupchat-form-group">
                    <label for="woxupchat-name"><?php _e('Name', 'woxupchat'); ?> *</label>
                    <input type="text" id="woxupchat-name" name="name" required>
                </div>

                <div class="woxupchat-form-group">
                    <label for="woxupchat-email"><?php _e('Email', 'woxupchat'); ?> *</label>
                    <input type="email" id="woxupchat-email" name="email" required>
                </div>

                <div class="woxupchat-form-group">
                    <label for="woxupchat-subject"><?php _e('Subject', 'woxupchat'); ?> *</label>
                    <input type="text" id="woxupchat-subject" name="subject" required>
                </div>

                <div class="woxupchat-form-group">
                    <label for="woxupchat-message"><?php _e('Message', 'woxupchat'); ?> *</label>
                    <textarea id="woxupchat-message" name="message" rows="4" required></textarea>
                </div>

                <div class="woxupchat-form-group">
                    <button type="submit" class="woxupchat-submit-btn">
                        <?php _e('Send Message', 'woxupchat'); ?>
                    </button>
                </div>

                <div id="woxupchat-response" class="woxupchat-response"></div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Handle form submission via AJAX
     */
    public function handle_form_submission() {
        // Verify nonce
        if (!check_ajax_referer('woxupchat_nonce', 'nonce', false)) {
            wp_send_json_error(array(
                'message' => __('Security check failed.', 'woxupchat')
            ));
        }

        // Get and sanitize form data
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        
        // Get WhatsApp number from settings
        $whatsapp_number = get_option('woxupchat_number', '');

        // Validate required fields
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            wp_send_json_error(array(
                'message' => __('Please fill in all required fields.', 'woxupchat')
            ));
        }

        // Validate email
        if (!is_email($email)) {
            wp_send_json_error(array(
                'message' => __('Please enter a valid email address.', 'woxupchat')
            ));
        }

        // Format message for WhatsApp
        $whatsapp_message = sprintf(
            "Name: %s\nEmail: %s\nSubject: %s\nMessage: %s",
            $name,
            $email,
            $subject,
            $message
        );

        // Send success response
        wp_send_json_success(array(
            'message' => __('Message sent successfully!', 'woxupchat'),
            'whatsapp_number' => $whatsapp_number,
            'whatsapp_message' => urlencode($whatsapp_message)
        ));
    }
}