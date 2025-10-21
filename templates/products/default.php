<?php
/**
 * Default Product Template
 */
if (!defined('ABSPATH')) exit;

$product = wc_get_product(get_the_ID());
if (!$product) return;
?>

<div class="ual-item ual-product">
    <a href="<?php the_permalink(); ?>">
        <div class="ual-item-image">
            <?php echo $product->get_image(); ?>
            <?php if ($product->is_on_sale()) : ?>
                <span class="ual-sale-badge">Sale!</span>
            <?php endif; ?>
        </div>
        <div class="ual-item-content">
            <h3><?php the_title(); ?></h3>
            <div class="ual-price"><?php echo $product->get_price_html(); ?></div>
            <?php if ($product->get_rating_count()) : ?>
                <div class="ual-rating">
                    <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                    <span class="ual-rating-count">(<?php echo $product->get_rating_count(); ?>)</span>
                </div>
            <?php endif; ?>
        </div>
    </a>
</div>