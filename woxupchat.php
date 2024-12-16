<?php
/**
 * Plugin Name: WoxupChat
 * Plugin URI: https://www.kloudboy.com/woxupchat
 * Description: A modern messaging contact form with instant chat capabilities, analytics tracking, and dark mode support
 * Version: 2.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: KLOUDBOY
 * Author URI: https://www.kloudboy.com
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: woxupchat
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WOXUPCHAT_VERSION', '2.0.0');
define('WOXUPCHAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WOXUPCHAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOXUPCHAT_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Load the main plugin class
require_once WOXUPCHAT_PLUGIN_DIR . 'includes/class-woxupchat.php';

// Initialize the plugin
function run_woxupchat() {
    $plugin = new WoxupChat();
    $plugin->run();
}
run_woxupchat();
