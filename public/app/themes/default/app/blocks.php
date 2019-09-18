<?php

// @todo-wordpress either comment out, delete, or modify for the site's uses

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

add_action('init', function ()
{
    wp_register_script(
        'block-example',
        ThemeHelpers::assetPath('blocks.js'),
        ['wp-blocks', 'wp-element']
    );

    register_block_type(
        'default-theme/example',
        [
            'editor_script' => 'block-example',
        ]
    );
});
