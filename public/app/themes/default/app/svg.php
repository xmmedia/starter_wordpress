<?php

// adds/enables full SVG support within WP
add_action('after_setup_theme', function () {
    // add SVG to allowed file uploads
    add_action('upload_mimes', function ($file_types) {
        $file_types['svg'] = 'image/svg+xml';

        return $file_types;
    });
});

// called via AJAX. returns the full URL of a media attachment (SVG)
add_action('wp_ajax_svg_get_attachment_url', function () {
    $url = '';
    $attachmentId = isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '';
    if($attachmentId){
        $url = wp_get_attachment_url($attachmentId);
    }

    echo $url;

    die();
});

add_action(
    'admin_enqueue_scripts',
    function () {
        wp_enqueue_script(
            'default/admin.js',
            ThemeHelpers::assetPath('admin.js'),
            ['jquery'],
            false,
            true
        );
    }
);
