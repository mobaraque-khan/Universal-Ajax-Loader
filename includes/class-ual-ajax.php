<?php
if (!defined('ABSPATH')) exit;

class UAL_Ajax {
    public function __construct() {
        add_action('wp_ajax_ual_load_posts', [$this, 'load_posts']);
        add_action('wp_ajax_nopriv_ual_load_posts', [$this, 'load_posts']);
    }

    public function load_posts() {
        check_ajax_referer('ual_nonce', 'nonce');

        $post_type = sanitize_text_field($_POST['post_type']);
        $posts_per_page = intval($_POST['posts_per_page']);
        $paged = intval($_POST['paged']);
        $layout = sanitize_text_field($_POST['layout']);

        $args = [
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish'
        ];

        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_post_item($layout);
            }
            wp_reset_postdata();
        }

        wp_die();
    }

    private function render_post_item($layout) {
        ?>
        <div class="ual-post-item ual-layout-<?php echo esc_attr($layout); ?>">
            <?php if (has_post_thumbnail()): ?>
                <div class="ual-post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="ual-post-content">
                <h3 class="ual-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <div class="ual-post-excerpt">
                    <?php the_excerpt(); ?>
                </div>
                <div class="ual-post-meta">
                    <span class="ual-post-date"><?php echo get_the_date(); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}