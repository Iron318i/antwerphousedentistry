<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

function generatepress_child_enqueue_scripts()
{
    if (is_rtl()) {
        wp_enqueue_style('generatepress-rtl', trailingslashit(get_template_directory_uri()) . 'rtl.css');
    }
}

add_action('wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 100);


function acf_force_type_array($var)
{

    // is array?
    if (is_array($var)) {
        return $var;
    }

    // bail early if empty
    if (empty($var) && !is_numeric($var)) {
        return array();
    }


    // string
    if (is_string($var)) {
        return explode(', ', $var);
    }


    // place in array
    return array($var);
}

if (!function_exists('antwerphousedentistry_scripts')) {

    /**
     * Load theme's JavaScript and CSS sources.
     */
    function antwerphousedentistry_scripts()
    {
        // Get the theme data.
        $the_theme = wp_get_theme();
        $theme_version = $the_theme->get('Version');
        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/css/theme.min.css');

        wp_enqueue_style('antwerphousedentistry-styles', get_template_directory_uri() . '/css/theme.min.css', array(), $css_version);
    }

} // End of if function_exists( 'antwerphousedentistry_scripts' ).

add_action('wp_enqueue_scripts', 'antwerphousedentistry_scripts');