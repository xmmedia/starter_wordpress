<?php

add_action('init', function ()
{
    wp_register_script(
        'block-example',
        ThemeHelpers::assetPath('blocks.js'),
        // plugins_url('block.js', __FILE__),
        ['wp-blocks', 'wp-element']
    );

    register_block_type(
        'default-theme/example',
        [
            'editor_script' => 'block-example',
        ]
    );
});
