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
        $css_version = filemtime(get_stylesheet_directory_uri() . '/css/theme.min.css');
        wp_enqueue_style('antwerphousedentistry-styles' . $css_version, get_stylesheet_directory_uri() . '/css/theme.min.css', array(), $css_version);
    }

} // End of if function_exists( 'antwerphousedentistry_scripts' ).

add_action('wp_enqueue_scripts', 'antwerphousedentistry_scripts');

function page_header()
{
    if (is_page_template('page-new.php')) {
        if (has_post_thumbnail()) {
            $bg_img = get_the_post_thumbnail_url();
        } else {
            $bg_img = get_stylesheet_directory_uri() . "/img/page-header.jpg";
        }
        echo '<div class="new-page-header" style="background-image: url(' . $bg_img . ');"><h1>' . get_the_title() . '</h1></div>';
    }
}

add_action('generate_after_header', 'page_header');

