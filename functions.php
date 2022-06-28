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

/**
 * Adds new shortcode "ahd_testtiomonial" and registers it to
 * the Visual Composer plugin
 *
 */
if (!class_exists('AHD_Testtiomonial_Shortcode')) {

    class AHD_Testtiomonial_Shortcode
    {

        /**
         * Main constructor
         */
        public function __construct()
        {

            // Registers the shortcode in WordPress
            add_shortcode('ahd_testtiomonial', __CLASS__ . '::output');

            // Map shortcode to WPBakery so you can access it in the builder
            if (function_exists('vc_lean_map')) {
                vc_lean_map('ahd_testtiomonial', __CLASS__ . '::map');
            }

        }

        /**
         * Shortcode output
         */
        public static function output($atts, $content = null)
        {

            // Extract shortcode attributes (based on the vc_lean_map function - see next function)
            $atts = vc_map_get_attributes('ahd_testtiomonial', $atts);

            // Define output and open element div.
            $output = '<div class="ahd-testimonial"><div class="top">';

            // Display custom heading if enabled and set.
            if (!empty($atts['video_link'])) {
                $output .= '<div class="video-link"><a href="' . esc_html($atts['video_link']) . '" target="_blank"><img src="' . get_stylesheet_directory_uri() . '/img/video-yes.png" alt="video"></a></div>';
            } else {
                $output .= '<div class="video-link"><img src="' . get_stylesheet_directory_uri() . '/img/video-no.png" alt="no video"></div>';
            }
            $output .= '<div class="icon"><img src="' . get_stylesheet_directory_uri() . '/img/testimonial-icon.png" alt="testimonial"></div>';
            // Display content.
            $output .= '</div><div class="content">';
            if ($content) {
                $output .= wp_kses_post($content);
            }
            $output .= '</div>';

            // Close element.
            $output .= '</div>';

            // Return output
            return $output;

        }

        /**
         * Map shortcode to WPBakery
         *
         * This is an array of all your settings which become the shortcode attributes ($atts)
         * for the output. See the link below for a description of all available parameters.
         *
         * @since 1.0.0
         * @link  https://kb.wpbakery.com/docs/inner-api/vc_map/
         */
        public static function map()
        {
            return array(
                'name' => esc_html__('AHD Testimonial', 'locale'),
                'description' => esc_html__('Shortcode outputs Testimonial.', 'locale'),
                'base' => 'ahd_testtiomonial',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Video link', 'locale'),
                        'param_name' => 'video_link',
                    ),
                    array(
                        'type' => 'textarea_html',
                        'heading' => esc_html__('Custom Text', 'locale'),
                        'param_name' => 'content',
                    ),
                ),
            );
        }

    }

}
new AHD_Testtiomonial_Shortcode;