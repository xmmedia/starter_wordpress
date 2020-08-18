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

    add_action(
        'wp_print_styles',
        function () {
            wp_dequeue_style('wp-block-library');
        },
        100
    );

    // @todo consider: https://developer.wordpress.org/block-editor/developers/themes/theme-support/#responsive-embedded-content

    // Allow Support for WooCommerce
    add_theme_support('woocommerce');

    // remove sections from the dashboard
    add_action(
        'admin_init',
        function () {
            // WordPress News and Events
            remove_meta_box('dashboard_primary', 'dashboard', 'side');
        }
    );

    /**
     * Disable unwanted blocks.
     * From https://rudrastyh.com/gutenberg/remove-default-blocks.html + comments
     */
    add_filter(
        'allowed_block_types',
        function ($allowedBlocks, $post = null) {
            // get widget blocks and registered by plugins blocks
            $registeredBlocks = WP_Block_Type_Registry::get_instance()
                ->get_all_registered();

            // remove unwanted ones
            unset($registeredBlocks['core/latest-comments']);
            unset($registeredBlocks['core/archives']);
            unset($registeredBlocks['core/code']);
            unset($registeredBlocks['core/preformatted']);
            unset($registeredBlocks['core/calendar']);
            unset($registeredBlocks['core/rss']);
            unset($registeredBlocks['core/search']);
            unset($registeredBlocks['core/tag-cloud']);
            unset($registeredBlocks['core/social-icons']);

            // now $registered_blocks contains only blocks registered by plugins, but we need keys only
            $registeredBlocks = array_keys($registeredBlocks);

            // merge the whitelist with plugins blocks
            return array_merge(
                [
                    'core/image',
                    'core/paragraph',
                    'core/heading',
                    'core/list',
                ],
                $registeredBlocks
            );
        }
    );

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
});

