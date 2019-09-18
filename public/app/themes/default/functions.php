<?php

declare(strict_types=1);

/**
 * Load theme required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) {
    $file = "app/{$file}.php";
    if (!locate_template($file, true, true)) {
        throw new \InvalidArgumentException(sprintf('Unable to load file %s', $file));
    }
}, ['helpers', 'blocks', 'svg']);

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support(
        'html5',
        ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']
    );
    add_theme_support('editor-styles');

    add_action(
        'wp_enqueue_scripts',
        function () {
            wp_enqueue_style(
                'default/public.css',
                ThemeHelpers::assetPath('public.css')
            );
            // wp_enqueue_style(
            //     'google_fonts',
            //     'https://...'
            // );
            wp_enqueue_script(
                'default/public.js',
                ThemeHelpers::assetPath('public.js')
            );
        }
    );

    add_editor_style(ThemeHelpers::assetPath('public.css'));

    /**
     * Enable features from Soil when plugin is activated
     * https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-disable-asset-versioning');
    add_theme_support('soil-disable-trackbacks');
    add_theme_support('soil-js-to-footer');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');
    if (env('GA_ANALYTICS_ID')) {
        add_theme_support('soil-google-analytics', env('GA_ANALYTICS_ID'));
    }

    add_action('wp_print_styles', 'wps_deregister_styles', 100);
    function wps_deregister_styles()
    {
        wp_dequeue_style('wp-block-library');
    }
});

