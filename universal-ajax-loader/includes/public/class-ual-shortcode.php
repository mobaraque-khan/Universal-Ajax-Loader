<?php
if (!defined('ABSPATH')) exit;

class UAL_Shortcode {
    public function __construct() {
        add_shortcode('ajax_post_loader', [$this, 'render']);
    }

    public function render() {
        ob_start(); ?>
        <div id="ual-output"></div>
        <button id="ual-load-btn">আরও লোড করুন</button>
        <?php return ob_get_clean();
    }
}

new UAL_Shortcode();
