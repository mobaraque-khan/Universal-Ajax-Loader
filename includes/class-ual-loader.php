<?php
if (!defined('ABSPATH')) exit;

class UAL_Loader {
    public function init() {
        // Admin area
        if (is_admin()) {
            require_once plugin_dir_path(__FILE__) . 'admin/class-ual-admin.php';
            new UAL_Admin();
        }

        //css file
        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }


        // Public
        require_once plugin_dir_path(__FILE__) . 'public/class-ual-ajax.php';
        require_once plugin_dir_path(__FILE__) . 'public/class-ual-shortcode.php';

        // Enqueue
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {
        wp_enqueue_style('ual-frontend-style', plugin_dir_url(__FILE__) . '../assets/css/frontend-style.css');
        wp_enqueue_script('ual-script', plugin_dir_url(__FILE__) . '../assets/js/ajax-loader.js', ['jquery'], null, true);
        wp_localize_script('ual-script', 'ual_ajax', [
            'ajax_url' => admin_url('admin-ajax.php')
        ]);
    }

    // Enqueue admin CSS
    public function enqueue_admin_assets() {
        wp_enqueue_style('ual-admin-style', plugin_dir_url(__FILE__) . '../assets/css/admin-style.css');
    }

}
