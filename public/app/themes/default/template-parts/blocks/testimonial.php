<?php

/**
 * Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Load values and assign defaults.
$text = get_field('testimonial') ?? null;
$author = get_field('author') ?? null;
$image = get_field('image') ?? null;

?>
<blockquote>
    <div><?php echo $text; ?></div>
    <?php if ($author) : ?>
        <div>– <?php echo esc_html($author); ?></div>
    <?php endif ?>
    <?php
    if ($image) :
        echo wp_get_attachment_image($image, 'large');
    endif;
    ?>
</blockquote>
