<?php
if (!defined('ABSPATH')) exit;

class UAL_Template_Loader {
    
    public static function load_template($post_type, $template = null) {
        if (!$template) {
            $template = self::get_selected_layout($post_type);
        }
        
        $template_path = self::get_template_path($post_type, $template);
        
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            self::load_fallback_template($post_type);
        }
    }
    
    private static function get_selected_layout($post_type) {
        if ($post_type === 'product') {
            return get_option('ual_product_layout', 'default');
        } else {
            return get_option('ual_post_layout', 'default');
        }
    }
    
    private static function get_template_path($post_type, $template) {
        $plugin_dir = plugin_dir_path(__FILE__);
        
        // Map post types to template folders
        $folder_map = [
            'product' => 'products',
            'post' => 'posts'
        ];
        
        $folder = isset($folder_map[$post_type]) ? $folder_map[$post_type] : 'posts';
        
        return $plugin_dir . "../templates/{$folder}/{$template}.php";
    }
    
    private static function load_fallback_template($post_type) {
        if ($post_type === 'product' && function_exists('wc_get_product')) {
            $product = wc_get_product(get_the_ID());
            ?>
            <div class="ual-item">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $product->get_image(); ?>
                    <h3><?php the_title(); ?></h3>
                    <p class="price"><?php echo $product->get_price_html(); ?></p>
                </a>
            </div>
            <?php
        } else {
            ?>
            <div class="ual-item">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium'); ?>
                    <?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo get_the_excerpt(); ?></p>
                </a>
            </div>
            <?php
        }
    }
}