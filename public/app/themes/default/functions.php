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
        wp_enqueue_style('default/public.css', asset_path('public.css'));
        wp_enqueue_script('default/public.js', asset_path('public.js'));
    }
);

add_editor_style(asset_path('public.css'));

/**
 * Enable features from Soil when plugin is activated
 * https://roots.io/plugins/soil/
 */
add_theme_support('soil-clean-up');
add_theme_support('soil-disable-rest-api');
add_theme_support('soil-disable-asset-versioning');
add_theme_support('soil-disable-trackbacks');
add_theme_support('soil-js-to-footer');
add_theme_support('soil-nav-walker');
add_theme_support('soil-nice-search');
add_theme_support('soil-relative-urls');
if (env('GA_ANALYTICS_ID')) {
    add_theme_support('soil-google-analytics', env('GA_ANALYTICS_ID'));
}

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}

function asset_path(string $asset): string
{
    $manifestFile = get_template_directory_uri().'/build/manifest.json';
    $manifestData = \json_decode(\file_get_contents($manifestFile), true);
    if (0 < \json_last_error()) {
        throw new \RuntimeException(
            sprintf(
                'Error parsing JSON from asset manifest file "%s" - %s',
                $manifestFile,
                json_last_error_msg()
            )
        );
    }

    return $manifestData[$asset];
}
