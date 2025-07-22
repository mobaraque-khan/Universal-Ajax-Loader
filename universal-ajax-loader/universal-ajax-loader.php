<?php
/*
Plugin Name: Universal Ajax Loader
Plugin URI: https://yourwebsite.com/universal-ajax-loader
Description: Load posts/products via Ajax with customizable settings.
Version: 1.0.0
Author: Mobaraque Khan
Author URI: https://yourwebsite.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: universal-ajax-loader
Domain Path: /languages
*/


if (!defined('ABSPATH')) exit;

// Autoload classes
require_once plugin_dir_path(__FILE__) . 'includes/class-ual-loader.php';

// Initialize
function ual_run_loader() {
    $loader = new UAL_Loader();
    $loader->init();
}

add_action('plugins_loaded', 'ual_run_loader');
