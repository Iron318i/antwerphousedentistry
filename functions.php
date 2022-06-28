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
        //$css_version = date('H-i', time());
        $css_version = 1.14;
        wp_enqueue_style('antwerphousedentistry-styles', get_stylesheet_directory_uri() . '/css/theme.min.css', array(), $css_version);

        wp_enqueue_script('antwerphousedentistry-scripts', get_stylesheet_directory_uri() . '/js/theme.js', array(), $css_version, true);
    }

} // End of if function_exists( 'antwerphousedentistry_scripts' ).

add_action('wp_enqueue_scripts', 'antwerphousedentistry_scripts');

function page_header()
{
    $exclude = array("5832", "5834", "5836", "5841");
    if (!in_array(get_the_ID(), $exclude)) {
        if (is_page_template('page-new.php')) {
            if (has_post_thumbnail()) {
                $bg_img = get_the_post_thumbnail_url();
            } else {
                $bg_img = get_stylesheet_directory_uri() . "/img/page-header.jpg";
            }
            echo '<div class="new-page-header" style="background-image: url(' . $bg_img . ');"><h1>' . get_the_title() . '</h1></div>';
        } elseif (115 == get_the_ID()) {
            if (has_post_thumbnail()) {
                $bg_img = get_the_post_thumbnail_url();
            } else {
                $bg_img = get_stylesheet_directory_uri() . "/img/page-header.jpg";
            }
            echo '<div class="page-template-page-new"><div class="new-page-header" style="background-image: url(' . $bg_img . ');"><h1>' . get_the_title() . '</h1></div></div>';
        }
    }
}

add_action('generate_after_header', 'page_header');


// Fully Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action('wp_enqueue_scripts', 'remove_block_css', 100);

function remove_block_css()
{
    wp_dequeue_style('wp-block-library'); // WordPress core
    wp_dequeue_style('wp-block-library-theme'); // WordPress core
    wp_dequeue_style('wc-block-style'); // WooCommerce
    wp_dequeue_style('storefront-gutenberg-blocks'); // Storefront theme
}

/**
 * Disable the emoji's
 */
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove from TinyMCE
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}

add_action('init', 'disable_emojis');

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
