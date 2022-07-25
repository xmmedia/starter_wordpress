<?php

/**
 * Remove the custom Akismet filter that adds the hidden fields with JS to each form.
 * The code adds a script tag which ends up inside the Vue #app element (scripts are not allowed).
 * This is virtually the same, but instead adds the script as an inline script after the
 * contact-form-7 script.
 * We also change the ID of the field to ensure it's unique in case there are other plugins adding
 * forms to the page.
 */
add_action('wp_loaded', function () {
    // seems that sometimes this method doesn't exist
    if (!function_exists('is_plugin_active')) {
        return;
    }

    // we only know that this works with Contact Form 7 with Akismet
    if (!is_plugin_active('akismet/akismet.php') || !is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
        return;
    }

    // remove existing filter added by Akismet
    remove_filter('wpcf7_form_elements', ['Akismet', 'append_custom_form_fields']);

    add_filter('wpcf7_form_elements', function ($html) {
        $fields = '';

        $prefix = '_wpcf7_ak_';

        $fields .= '<p style="display: none !important;">';
        $fields .= '<label>&#916;<textarea name="'.$prefix.'hp_textarea" cols="45" rows="8" maxlength="100"></textarea></label>';

        // Keep track of how many ak_js fields are in this page so that we don't re-use
        // the same ID.
        static $field_count = 0;

        $fields .= '<input type="hidden" id="ak_js_wpcf7_'.$field_count.'" name="'.$prefix.'js" value="'.mt_rand(0, 250).'"/>';

        wp_add_inline_script(
            'contact-form-7',
            'document.getElementById( "ak_js_wpcf7_'.$field_count.'" ).setAttribute( "value", ( new Date() ).getTime() );'
        );

        $fields .= '</p>';

        return $html.$fields;
    });
});
