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
$text = get_field('testimonial') ?: null;
$author = get_field('author') ?: null;
$image = get_field('image') ?: null;
$imageSize = [200, 200];
// if ($image) {
//     $imageDetails = wp_get_attachment_image_src($image);
//     dump($imageDetails);
// }

?>
<div>
    <blockquote class="">
        <div><?php echo $text; ?></div>
        <?php if ($author) : ?>
            <div>– <?php echo $author; ?></div>
        <?php endif ?>
        <?php if ($image) : ?>
            <img src="<?php echo esc_attr(wp_get_attachment_image_src($image)[0]); ?>"
                 width="200"
                 height="200"
                 alt="<?php echo esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)); ?>">
        <?php endif ?>
    </blockquote>
</div>
