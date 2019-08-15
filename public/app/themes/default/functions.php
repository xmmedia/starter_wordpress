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

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}

// remove emoji code
add_filter('emoji_svg_url', '__return_false');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Disable REST API link tag
remove_action('wp_head', 'rest_output_link_wp_head', 10);
// Disable REST API link in HTTP headers
remove_action('template_redirect', 'rest_output_link_header', 11);
// Disable weblog
remove_action('wp_head', 'rsd_link');
// Windows live writer manifest
remove_action('wp_head', 'wlwmanifest_link');
// Disable generator meta
remove_action('wp_head', 'wp_generator');

function disable_embeds_code_init()
{
    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');

    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
    add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');

    // Remove all embeds rewrite rules.
    add_filter('rewrite_rules_array', 'disable_embeds_rewrites');

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
}

add_action('init', 'disable_embeds_code_init', 9999);

function disable_embeds_tiny_mce_plugin($plugins)
{
    return array_diff($plugins, ['wpembed']);
}

function disable_embeds_rewrites($rules)
{
    foreach ($rules as $rule => $rewrite) {
        if (false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }

    return $rules;
}
