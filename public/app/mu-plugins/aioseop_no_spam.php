<?php

add_action('init', function () {
    /**
     * No All in one SEO Notifications - STOP nagging me.
     * From: https://wordpress.org/plugins/no-aioseop-nags/
     * Author: Eilert Behrends
     * hello@kontur.us
     */
    add_action('wp_enqueue_scripts', function () {
        if (is_user_logged_in()) {
            wp_register_style('aioseop_no_spam_1', get_theme_file_uri('/css/aioseop_no_spam.css'));
            wp_enqueue_style('aioseop_no_spam_1'); // code
        }
    });
    add_action('admin_enqueue_scripts', function () {
        wp_register_style('aioseop_no_spam_2', get_theme_file_uri('/css/aioseop_no_spam.css'));
        wp_enqueue_style('aioseop_no_spam_2');
    });
});
