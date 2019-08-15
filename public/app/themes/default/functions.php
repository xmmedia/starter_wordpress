<?php

declare(strict_types=1);

add_theme_support('title-tag');
add_theme_support(
    'html5',
    ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']
);

add_action(
    'wp_enqueue_scripts',
    function () {
        // wp_enqueue_style(
        //     'default/public.css',
        //     asset_path('styles/main.css'),
        //     false,
        //     null
        //);
        // wp_enqueue_script(
        //     'default/public.js',
        //     asset_path('scripts/main.js'),
        //     false,
        //     null,
        //     true
        //);

        // wp_enqueue_style('main', get_template_directory_uri() . '/public/build/main.c0d9d23c.css');
        // wp_enqueue_script('app', get_template_directory_uri() . '/public/build/app.036b7c01.js');
    }
);

function asset_path(string $path): string
{
    return get_template_directory_uri();
}

/**
 * Enable features from Soil when plugin is activated
 * https://roots.io/plugins/soil/
 */
add_theme_support('soil-clean-up');
add_theme_support('soil-jquery-cdn');
add_theme_support('soil-nav-walker');
add_theme_support('soil-nice-search');
add_theme_support('soil-relative-urls');

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}

