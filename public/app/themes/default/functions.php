<?php

declare(strict_types=1);

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'default/public.css',
        asset_path('styles/main.css'),
        false,
        null
    );
    wp_enqueue_script(
        'default/public.js',
        asset_path('scripts/main.js'),
        false,
        null,
        true
    );

    // wp_enqueue_style( 'main', get_template_directory_uri() . '/public/build/main.c0d9d23c.css' );
    // wp_enqueue_script( 'app', get_template_directory_uri() . '/public/build/app.036b7c01.js' );
});

function asset_path(string $path): string {
    return get_template_directory_uri();
}
