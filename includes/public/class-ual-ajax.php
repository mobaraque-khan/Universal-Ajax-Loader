<?php
if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . '../class-ual-template-loader.php';

class UAL_Ajax {
    public function __construct() {
        add_action('wp_ajax_ual_load_posts', [$this, 'handle']);
        add_action('wp_ajax_nopriv_ual_load_posts', [$this, 'handle']);
    }

    public function handle() {
        $post_type = get_option('ual_post_type', 'post');
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
        $posts_per_page = get_option('ual_posts_per_page', 6);

        $args = [
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish'
        ];
        
        // Add product-specific args if needed
        if ($post_type === 'product' && function_exists('wc_get_product_visibility_term_ids')) {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['exclude-from-catalog'],
                    'operator' => 'NOT IN',
                ],
            ];
        }
        
        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                UAL_Template_Loader::load_template($post_type);
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Did not find any posts</p>';
        endif;

        wp_die();
    }
}

new UAL_Ajax();
