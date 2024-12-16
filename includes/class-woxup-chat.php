<?php
/**
 * Main Woxup Chat Class
 */
class Woxup_Chat {
    private $version;
    private $plugin_name;

    public function __construct() {
        $this->version = WOXUP_CHAT_VERSION;
        $this->plugin_name = 'woxup-chat';
        
        // Add menu item to WordPress admin
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function run() {
        // Initialize plugin functionality
    }

    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_menu_page(
            'Woxup Chat', // Page title
            'Woxup Chat', // Menu title
            'manage_options', // Capability
            'woxup-chat', // Menu slug
            array($this, 'display_admin_dashboard'), // Function to display the page
            'dashicons-whatsapp', // Icon (you can change this to any dashicon)
            30 // Position
        );

        add_submenu_page(
            'woxup-chat', // Parent slug
            'Settings', // Page title
            'Settings', // Menu title
            'manage_options', // Capability
            'woxup-chat-settings', // Menu slug
            array($this, 'display_settings_page') // Function to display the page
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('woxup_chat_settings', 'woxup_chat_number');
        register_setting('woxup_chat_settings', 'woxup_chat_default_message');
        register_setting('woxup_chat_settings', 'woxup_chat_button_text');
        register_setting('woxup_chat_settings', 'woxup_chat_theme_color');
        register_setting('woxup_chat_settings', 'woxup_chat_position');
    }

    /**
     * Display the main dashboard
     */
    public function display_admin_dashboard() {
        // Get statistics
        $total_messages = get_option('woxup_chat_total_messages', 0);
        $recent_messages = get_option('woxup_chat_submissions', array());
        ?>
        <div class="wrap">
            <h1>Woxup Chat Dashboard</h1>
            
            <div class="woxup-dashboard-stats">
                <div class="woxup-stat-box">
                    <h3>Total Messages</h3>
                    <p class="big-number"><?php echo esc_html($total_messages); ?></p>
                </div>
                
                <div class="woxup-stat-box">
                    <h3>Active Number</h3>
                    <p class="big-number"><?php echo esc_html(get_option('woxup_chat_number', 'Not Set')); ?></p>
                </div>
            </div>

            <div class="woxup-quick-settings">
                <h2>Quick Settings</h2>
                <form method="post" action="options.php">
                    <?php settings_fields('woxup_chat_settings'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">WhatsApp Number</th>
                            <td>
                                <input type="text" 
                                       name="woxup_chat_number" 
                                       value="<?php echo esc_attr(get_option('woxup_chat_number')); ?>"
                                       placeholder="911234567890"
                                       class="regular-text">
                                <p class="description">Enter your WhatsApp number with country code (no spaces or special characters)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Default Message</th>
                            <td>
                                <textarea name="woxup_chat_default_message" 
                                          rows="3" 
                                          class="large-text"><?php echo esc_textarea(get_option('woxup_chat_default_message', 'Hello! I have a question about...')); ?></textarea>
                                <p class="description">Default message that will appear in WhatsApp</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Button Text</th>
                            <td>
                                <input type="text" 
                                       name="woxup_chat_button_text" 
                                       value="<?php echo esc_attr(get_option('woxup_chat_button_text', 'Chat with us')); ?>"
                                       class="regular-text">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Theme Color</th>
                            <td>
                                <input type="color" 
                                       name="woxup_chat_theme_color" 
                                       value="<?php echo esc_attr(get_option('woxup_chat_theme_color', '#25D366')); ?>">
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>

            <div class="woxup-recent-messages">
                <h2>Recent Messages</h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($recent_messages)) {
                            foreach (array_slice($recent_messages, 0, 5) as $message) {
                                echo '<tr>';
                                echo '<td>' . esc_html($message['timestamp']) . '</td>';
                                echo '<td>' . esc_html($message['name']) . '</td>';
                                echo '<td>' . esc_html($message['email']) . '</td>';
                                echo '<td>' . esc_html($message['requirements']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">No messages yet</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <style>
            .woxup-dashboard-stats {
                display: flex;
                gap: 20px;
                margin: 20px 0;
            }
            
            .woxup-stat-box {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                flex: 1;
            }
            
            .woxup-stat-box h3 {
                margin: 0 0 10px 0;
                color: #23282d;
            }
            
            .big-number {
                font-size: 24px;
                font-weight: bold;
                margin: 0;
                color: #2271b1;
            }
            
            .woxup-quick-settings,
            .woxup-recent-messages {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                margin: 20px 0;
            }
        </style>
        <?php
    }

    /**
     * Display the settings page
     */
    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h1>Woxup Chat Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('woxup_chat_settings');
                do_settings_sections('woxup_chat_settings');
                ?>
                
                <h2>Advanced Settings</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">Button Position</th>
                        <td>
                            <select name="woxup_chat_position">
                                <option value="bottom-right" <?php selected(get_option('woxup_chat_position'), 'bottom-right'); ?>>Bottom Right</option>
                                <option value="bottom-left" <?php selected(get_option('woxup_chat_position'), 'bottom-left'); ?>>Bottom Left</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
