<?php
/**
 * Default Post Template
 */
if (!defined('ABSPATH')) exit;
?>

<div class="ual-item">
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <div class="ual-item-image">
                <?php the_post_thumbnail('medium'); ?>
            </div>
        <?php endif; ?>
        <div class="ual-item-content">
            <h3><?php the_title(); ?></h3>
            <p class="ual-excerpt"><?php echo get_the_excerpt(); ?></p>
            <div class="ual-meta">
                <span class="ual-date"><?php echo get_the_date(); ?></span>
                <span class="ual-author">by <?php the_author(); ?></span>
            </div>
        </div>
    </a>
</div>