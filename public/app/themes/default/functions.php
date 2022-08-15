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
}, ['helpers', 'blocks']);

add_action('after_setup_theme', function () {
    // @todo check if both are still required...maybe just so we don't have "worry"
    // Set the from name on emails (non-Postmark)
    add_filter('wp_mail_from_name', function () {
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
        ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script']
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
                ThemeHelpers::assetPath('build/public.css')
            );
            // wp_enqueue_style(
            //     'google_fonts',
            //     'https://...'
            // );
            wp_enqueue_script(
                'default/public.js',
                ThemeHelpers::assetPath('build/public.js')
            );
        }
    );

    add_editor_style(ThemeHelpers::assetPath('build/public.css'));

    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'disable-asset-versioning',
        'disable-trackbacks',
        'js-to-footer',
        'nav-walker',
        'nice-search',
        'relative-urls'
    ]);
    if (env('GA_ANALYTICS_ID')) {
        add_theme_support('soil', ['google-analytics' => 'UA-XXXXX-Y']);
    }

    // Remove the ability to change the site icon in the theme customizer
    // & remove from head
    add_filter('site_icon_meta_tags', function () {
        global $wp_customize;

        $wp_customize->remove_control('site_icon');

        return [];
    }, 20, 1);
    remove_action('wp_head', 'wp_site_icon', 99);

    register_nav_menus([
        'main' => 'Main',
    ]);

    // disables both the Post Via Email functionality and the associated UI options
    add_filter(
        'enable_post_by_email_configuration',
        '__return_false',
        PHP_INT_MAX,
    );

    /**
     * Remove All Yoast HTML Comments
     * From: https://gist.github.com/paulcollett/4c81c4f6eb85334ba076
     */
    add_filter('wpseo_debug_markers', '__return_false');

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

            // remove the media settings page
            // we depend on the these configurations to optimize the size
            remove_submenu_page('options-general.php', 'options-media.php');
            // remove the permalink settings
            remove_submenu_page('options-general.php', 'options-permalink.php');
        }
    );

    // Allow Support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    /**
     * Add additional image sizes
     * To view the existing ones: dump(wp_get_registered_image_subsizes());
     * Reserved names: thumb, thumbnail, medium, large, post-thumbnail
     * If you want the image sizes to be available for existing images, run: bin/wp media regenerate
     * @see https://developer.wordpress.org/reference/functions/add_image_size/
     */
    // add_image_size('hero', 2500, 1000);
    // add as a selection in the UI
    // add_filter(
    //     'image_size_names_choose',
    //     function ($sizes) {
    //         return array_merge($sizes, [
    //             'hero' => __('Hero'),
    //         ]);
    //     }
    // );

    /**
     * From: https://wordpress.stackexchange.com/questions/25793/how-to-force-one-column-layout-on-custom-post-type-edit-page/25814#25814
     *
     * @param array|mixed $order
     *
     * @return array|mixed
     */
    $forceOneColEditLayout = function ($order) {
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
    add_filter('get_user_option_meta-box-order_page', $forceOneColEditLayout);
    add_filter('get_user_option_meta-box-order_post', $forceOneColEditLayout);

    add_action('init', function () {
        /** @var $wp_rewrite WP_Rewrite */
        global $wp_rewrite;

        // force the permalink structure
        $wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%postname%');

        /**
         * Ensure all URLs are absolute in sitemaps
         *
         * @link https://github.com/roots/soil/issues/156
         */
        if (!isset($_SERVER['REQUEST_URI'])) {
            return;
        }

        $request_uri = $_SERVER['REQUEST_URI'];
        $extension = substr($request_uri, -4);

        if (false !== stripos($request_uri, 'sitemap') && in_array($extension, ['.xml', '.xsl'])) {
            $filter = '\Roots\Soil\Utils\root_relative_url';

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

