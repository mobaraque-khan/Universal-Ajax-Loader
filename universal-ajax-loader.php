<?php
/**
 * Plugin Name: Universal Ajax Loader
 * Plugin URI: https://wordpress.org/plugins/universal-ajax-loader/
 * Description: Load posts/products via Ajax with customizable settings.
 * Version: 1.0.0
 * Author: Mobaraque Khan
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: universal-ajax-loader
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

if (!defined('ABSPATH')) exit;

define('UAL_VERSION', '1.0.0');
define('UAL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('UAL_PLUGIN_URL', plugin_dir_url(__FILE__));

class Universal_Ajax_Loader {
    public function __construct() {
        add_action('init', [$this, 'init']);
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }

    public function init() {
        load_plugin_textdomain('universal-ajax-loader', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        if (is_admin()) {
            require_once UAL_PLUGIN_DIR . 'includes/admin/class-ual-admin.php';
            new UAL_Admin();
        }
        
        require_once UAL_PLUGIN_DIR . 'includes/class-ual-shortcode.php';
        new UAL_Shortcode();
        
        require_once UAL_PLUGIN_DIR . 'includes/class-ual-ajax.php';
        new UAL_Ajax();
    }

    public function activate() {
        add_option('ual_post_type', 'post');
        add_option('ual_posts_per_page', 6);
        add_option('ual_infinite_scroll', 0);
        add_option('ual_post_layout', 'default');
        add_option('ual_product_layout', 'default');
    }

    public function deactivate() {
        // Clean up if needed
    }
}

new Universal_Ajax_Loader();