<?php
if (!defined('ABSPATH')) exit;

class UAL_Shortcode {
    public function __construct() {
        add_shortcode('ajax_post_loader', [$this, 'render']);
    }

    public function render() {
        $infinite_scroll = get_option('ual_infinite_scroll', 0);
        ob_start(); ?>
        <div class="ual-container" data-infinite="<?php echo $infinite_scroll; ?>">
            <div id="ual-output" class="ual-grid"></div>
            <div class="ual-load-section" <?php echo $infinite_scroll ? 'style="display:none;"' : ''; ?>>
                <button id="ual-load-btn" class="ual-load-btn">
                    <span class="ual-btn-text">Load More</span>
                    <span class="ual-btn-loader"></span>
                </button>
            </div>
        </div>
        <?php return ob_get_clean();
    }
}

new UAL_Shortcode();
