<?php
if (!defined('ABSPATH')) exit;

class UAL_Ajax {
    public function __construct() {
        add_action('wp_ajax_ual_load_posts', [$this, 'handle']);
        add_action('wp_ajax_nopriv_ual_load_posts', [$this, 'handle']);
    }

    public function handle() {
        $post_type = get_option('ual_post_type', 'post');
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
        $posts_per_page = get_option('ual_posts_per_page', 6);

        $query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
        ]);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                if ($post_type === 'product' && function_exists('wc_get_product')) {
                    $product = wc_get_product(get_the_ID());
                    ?>
                    <div class="ual-item" style="border:1px solid #ddd; padding:10px; margin:10px 0;">
                        <a href="<?php the_permalink(); ?>">
                            <?php echo $product->get_image(); ?>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo $product->get_price_html(); ?></p>
                        </a>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="ual-item" style="border:1px solid #ddd; padding:10px; margin:10px 0;">
                        <a href="<?php the_permalink(); ?>">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo get_the_excerpt(); ?></p>
                        </a>
                    </div>
                    <?php
                }
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Did not find any posts</p>';
        endif;

        wp_die();
    }
}

new UAL_Ajax();
