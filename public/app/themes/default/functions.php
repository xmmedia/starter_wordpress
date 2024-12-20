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
        // @todo-wordpress adjust if needed
        return get_bloginfo('name');
    });
    // Set the from name on emails (Postmark)
    add_filter('wp_mail', function ($args) {
        if (is_string($args['headers'])) {
            $headers = explode("\n", str_replace("\r\n", "\n", $args['headers']));
        }
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
                ThemeHelpers::assetPath('public', 'css'),
            );
            // wp_enqueue_style(
            //     'google_fonts',
            //     'https://...',
            // );
            wp_enqueue_script_module(
                'default/public.js',
                ThemeHelpers::assetPath('public', 'js'),
            );
        }
    );

    add_editor_style(ThemeHelpers::assetPath('public', 'css'));

    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'disable-asset-versioning',
        'disable-trackbacks',
        'js-to-footer',
        // disabled because the NavWalker class has deprecated callable method on line 114
        // 'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    // Remove the ability to change the site icon in the theme customizer
    // & remove from head
    add_filter('customize_register', function (WP_Customize_Manager $wp_customize) {
        $wp_customize->remove_control('site_icon');
    }, 20, 1);
    remove_action('wp_head', 'wp_site_icon', 99);

    // use these by passing 'theme_location' => 'main' to wp_nav_menu
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
     * Override the default Yoast robots meta tag.
     * By default, it's "index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"
     * We change it to only "index, follow" in production and "noindex, nofollow" in all other environments.
     * "max-image-preview:large" will still be added.
     */
    add_filter('wpseo_robots', function () {
        if (defined('WP_ENV') && WP_ENV !== 'production') {
            return 'noindex, nofollow';
        }

        return 'index, follow';
    });

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

            // disable the warning about persistent object cache in Site Health
            add_filter('site_status_should_suggest_persistent_object_cache', '__return_false');
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

    $seoPlugMetaBoxPriority = function () {
        return 'default';
    };
    /**
     * Make Yoast SEO metabox show with default priority
     */
    add_filter('wpseo_metabox_prio', $seoPlugMetaBoxPriority);
    /**
     * Make All In One SEO (AIOSEO) metabox show with default priority.
     */
    add_filter('aioseo_post_metabox_priority', $seoPlugMetaBoxPriority);

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

        /**
         * Change the link on the logo on the WP login page.
         */
        add_filter('login_headerurl', function () {
            return home_url();
        });
        /**
         * Change the text that's shown until the logo is loaded on the WP login page.
         */
        add_filter('login_headertext', function () {
            return get_bloginfo('name');
        });
// @todo check size of logo/test
        /**
         * Change the logo on the WP login form.
         * From: https://codex.wordpress.org/Customizing_the_Login_Form#Change_the_Login_Logo
         */
        // 150x90
        add_action('login_enqueue_scripts', function () { ?>
            <style>
                #login h1 a, .login h1 a {
                    background-image: url("<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg");
                    height: 90px;
                    width: 160px;
                    background-size: 160px 90px;
                    background-repeat: no-repeat;
                }
            </style>
        <?php });
    });
});

