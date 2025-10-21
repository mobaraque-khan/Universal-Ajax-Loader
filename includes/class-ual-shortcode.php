<?php
if (!defined('ABSPATH')) exit;

class UAL_Shortcode {
    public function __construct() {
        add_shortcode('ajax_post_loader', [$this, 'render_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('ual-frontend', UAL_PLUGIN_URL . 'assets/js/frontend.js', ['jquery'], UAL_VERSION, true);
        wp_localize_script('ual-frontend', 'ual_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ual_nonce')
        ]);
        wp_enqueue_style('ual-frontend', UAL_PLUGIN_URL . 'assets/css/frontend.css', [], UAL_VERSION);
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts([
            'post_type' => get_option('ual_post_type', 'post'),
            'posts_per_page' => get_option('ual_posts_per_page', 6),
            'layout' => get_option('ual_post_layout', 'default')
        ], $atts);

        ob_start();
        ?>
        <div id="ual-container" data-post-type="<?php echo esc_attr($atts['post_type']); ?>" 
             data-posts-per-page="<?php echo esc_attr($atts['posts_per_page']); ?>"
             data-layout="<?php echo esc_attr($atts['layout']); ?>">
            <div id="ual-posts"></div>
            <button id="ual-load-more" class="ual-btn">Load More</button>
            <div id="ual-loading" style="display:none;">Loading...</div>
        </div>
        <?php
        return ob_get_clean();
    }
}