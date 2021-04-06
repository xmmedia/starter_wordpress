<?php

declare(strict_types=1);

use function Env\env;

/**
 * Load theme required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) {
    $file = "app/{$file}.php";
    if (!locate_template($file, true)) {
        throw new \InvalidArgumentException(sprintf('Unable to load file %s', $file));
    }
}, ['helpers', 'blocks', 'svg']);

add_action('after_setup_theme', function () {
    // Set the from name on emails (non-Postmark)
    add_filter('wp_mail_from_name', function ($original_email_from) {
        return '@todo-wordpress';
    });
    // Set the from name on emails (Postmark)
    add_filter('wp_mail', function ($args) {
        $headers = explode("\n", str_replace("\r\n", "\n", $args['headers']));
        $headers['From'] = 'todo-wordpress <site@example.com>';

        return ['headers' => $headers] + $args;
    });

    add_theme_support('title-tag');
    add_theme_support(
        'html5',
        ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']
    );
    add_theme_support('editor-styles');
    // Adds featured image to posts
    add_theme_support('post-thumbnails');
    // Additional support/css for blocks when wider
    add_theme_support('align-wide');
    // Allow units other than px in editor
    add_theme_support('custom-units');
    // Allow videos/etc to scale full width
    add_theme_support('responsive-embeds');

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

    // @todo consider: https://developer.wordpress.org/block-editor/developers/themes/theme-support/#responsive-embedded-content

    // remove sections from the dashboard
    add_action(
        'admin_init',
        function () {
            // WordPress News and Events
            remove_meta_box('dashboard_primary', 'dashboard', 'side');

            // disable the auto save in the admin
            // because it saves to the actual page, not just a draft or locally
            wp_deregister_script('autosave');
        }
    );

    // Allow Support for WooCommerce
    add_theme_support('woocommerce');

    /**
     * From: https://wordpress.stackexchange.com/questions/25793/how-to-force-one-column-layout-on-custom-post-type-edit-page/25814#25814
     *
     * @param array|mixed $order
     *
     * @return array|mixed
     */
    $moveWpSeoToBottom = function ($order) {
        if (!is_array($order)) {
            return $order;
        }

        $boxes = explode(',', $order['normal']);

        $wpSeoKey = array_search('wpseo_meta', $boxes);
        if (false !== $wpSeoKey) {
            // remove & add a end
            unset($boxes[$wpSeoKey]);
            $boxes[] = 'wpseo_meta';
        }

        $order['normal'] = implode(',', $boxes);

        return $order;
    };
    add_filter('get_user_option_meta-box-order_page', $moveWpSeoToBottom);
    add_filter('get_user_option_meta-box-order_post', $moveWpSeoToBottom);

    /**
     * Ensure all URLs are absolute in sitemaps
     *
     * @link https://github.com/roots/soil/issues/156
     */
    add_action('init', function () {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return;
        }

        $request_uri = $_SERVER['REQUEST_URI'];
        $extension = substr($request_uri, -4);

        if (false !== stripos($request_uri, 'sitemap') && in_array($extension, ['.xml', '.xsl'])) {
            $filter = \Roots\Soil\Utils\root_relative_url::class;

            remove_filter('bloginfo_url', $filter, 10);
            remove_filter('the_permalink', $filter, 10);
            remove_filter('wp_list_pages', $filter, 10);
            remove_filter('wp_list_categories', $filter, 10);
            remove_filter('the_tags', $filter, 10);
            remove_filter('get_pagenum_link', $filter, 10);
            remove_filter('get_comment_link', $filter, 10);
            remove_filter('month_link', $filter, 10);
            remove_filter('day_link', $filter, 10);
            remove_filter('year_link', $filter, 10);
            remove_filter('term_link', $filter, 10);
            remove_filter('the_author_posts_link', $filter, 10);
            remove_filter('wp_get_attachment_url', $filter, 10);
        }
    });
});

