<?php

// @todo-wordpress either comment out, delete, or modify for the site's uses

add_action('acf/init', function () {
    if (function_exists('acf_register_block_type')) {
        // see https://www.advancedcustomfields.com/resources/blocks/
        acf_register_block_type([
            'name'            => 'testimonial',
            'title'           => __('Testimonial'),
            'description'     => __('Custom testimonial block.'),
            'render_template' => 'template-parts/blocks/testimonial.php',
            'category'        => 'formatting',
            'icon'            => 'format-quote',
        ]);
        acf_register_block_type([
            'name'            => 'video_block',
            'title'           => __('Video'),
            'description'     => __('Video block.'),
            'render_template' => 'template-partials/blocks/video.php',
            'category'        => 'media',
            'icon'            => 'playlist-video',
        ]);
    }
});

function theme_block_categories($categories, $post)
{
    if (!in_array($post->post_type, ['post', 'page'])) {
        return $categories;
    }

    return array_merge(
        $categories,
        [
            [
                'slug'  => 'custom',
                'title' => __('Custom', 'custom'),
                'icon'  => 'star-filled',
            ],
        ]
    );
}
add_filter('block_categories', 'theme_block_categories', 10, 2);

// add_action('init', function ()
// {
//     wp_register_script(
//         'block-example',
//         ThemeHelpers::assetPath('blocks.js'),
//         ['wp-blocks', 'wp-element']
//     );
//
//     register_block_type(
//         'default-theme/example',
//         [
//             'editor_script' => 'block-example',
//         ]
//     );
// });

add_action(
    'enqueue_block_editor_assets',
    function () {
        wp_enqueue_script(
            'deny-list-blocks',
            ThemeHelpers::assetPath('blocks.js'),
            ['wp-blocks', 'wp-dom-ready', 'wp-edit-post']
        );
    }
);
