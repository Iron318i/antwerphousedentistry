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
        $css_version = 1.18;
        wp_enqueue_style('antwerphousedentistry-styles', get_stylesheet_directory_uri() . '/css/theme.min.css', array(), $css_version);

        wp_enqueue_script('masonry-scripts', get_stylesheet_directory_uri() . '/js/masonry.pkgd.min.js', array(), $css_version, true);
        wp_enqueue_script('antwerphousedentistry-scripts', get_stylesheet_directory_uri() . '/js/theme.js', array(), $css_version, true);
    }

} // End of if function_exists( 'antwerphousedentistry_scripts' ).

add_action('wp_enqueue_scripts', 'antwerphousedentistry_scripts');

function page_header()
{
    $exclude = array("14363", "14368", "14372", "14358", "4297", "4303");
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

add_action('init', 'register_ahd_post_type');

function register_ahd_post_type()
{
    register_taxonomy('casestudiecat', ['casestudie'], [
        'label' => 'Treatment types',
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'hierarchical' => true,
        'rewrite' => false,
        'show_admin_column' => true,
    ]);
    register_post_type('casestudie', array(
        'label' => 'casestudies',
        'labels' => array(
            'name' => 'Cases tudies',
            'singular_name' => 'Case studie',
            'project_name' => 'Case studies',
            'all_items' => 'All case studies',
            'add_new' => 'Add case studie',
            'add_new_item' => 'Add new case studie',
            'edit' => 'Edit',
            'edit_item' => 'Edit case studie',
            'new_item' => 'New case studie',
        ),
        'description' => '',
        'public' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_rest' => false,
        'rest_base' => '',
        'show_in_menu' => true,
        'exclude_from_search' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor'),
        'taxonomies' => array('casestudiecat'),
        'hierarchical' => false,
        'query_var' => true,
    ));
}

add_image_size('casestudie', 275, 132, array('center', 'center'));

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
/**
 * Adds new shortcode "ahd_casestudies" and registers it to
 * the Visual Composer plugin
 *
 */
if (!class_exists('AHD_Casestudies_Shortcode')) {

    class AHD_Casestudies_Shortcode
    {

        /**
         * Main constructor
         */
        public function __construct()
        {

            // Registers the shortcode in WordPress
            add_shortcode('ahd_casestudies', __CLASS__ . '::output');

            // Map shortcode to WPBakery so you can access it in the builder
            if (function_exists('vc_lean_map')) {
                vc_lean_map('ahd_casestudies', __CLASS__ . '::map');
            }

        }

        /**
         * Shortcode output
         */
        public static function output($atts, $content = null)
        {

            // Extract shortcode attributes (based on the vc_lean_map function - see next function)
            $atts = vc_map_get_attributes('ahd_casestudies', $atts);

            // Define output and open element div.
            $output = '<div class="ahd-case-studies">';
            $output .= '<div class="studies-header"><h2 class="text-pink">Choose treatment type</h2>';
            $output .= '<select id="treatmentType">';
            $output .= '<option value="all">All</option>';
            $terms = get_terms([
                'taxonomy' => 'casestudiecat'
            ]);
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $output .= '<option value="tax-' . $term->term_id . '">' . $term->name . '</option>';
                }
            }
            $output .= '</select>';
            $output .= '</div>';
            $output .= '<hr>';
            $output .= '<div class="grid">';
            $output .= '<div class="grid-sizer"></div>';
            $output .= '<div class="gutter-sizer"></div>';

            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'casestudie',
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $casestudieterms = get_the_terms(get_the_ID(), 'casestudiecat');
                    $output .= '<div class="grid-item">';
                    $output .= '<div class="case';

                    if ($casestudieterms && !is_wp_error($casestudieterms)) {
                        foreach ($casestudieterms as $term) {
                            $output .= ' tax-' . $term->term_id;
                        }
                    }
                    $output .= '">';
                    $output .= '<div class="img">';
                    $images = get_field('images', get_the_ID());
                    if ($images):
                        foreach ($images as $image_id):
                            $output .= wp_get_attachment_image($image_id, 'casestudie');
                        endforeach;
                    endif;
                    $output .= '</div>';
                    $output .= '<div class="content">';
                    $output .= '<h4>' . get_the_title() . '</h4>';
                    $output .= get_the_content();
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
            }
            wp_reset_postdata();
            $output .= '</div>';
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
                'name' => esc_html__('AHD Case Studies', 'locale'),
                'description' => esc_html__('Shortcode outputs Testimonial.', 'locale'),
                'base' => 'ahd_casestudies',
            );
        }

    }

}
new AHD_Casestudies_Shortcode;

/**
 * Adds new shortcode "ahd_testtiomonial" and registers it to
 * the Visual Composer plugin
 *
 */
if (!class_exists('AHD_Consent_Form_Shortcode')) {

    class AHD_Consent_Form_Shortcode
    {

        /**
         * Main constructor
         */
        public function __construct()
        {

            // Registers the shortcode in WordPress
            add_shortcode('ahd_consent_form', __CLASS__ . '::output');

            // Map shortcode to WPBakery so you can access it in the builder
            if (function_exists('vc_lean_map')) {
                vc_lean_map('ahd_consent_form', __CLASS__ . '::map');
            }

        }

        /**
         * Shortcode output
         */
        public static function output($atts, $content = null)
        {

            // Extract shortcode attributes (based on the vc_lean_map function - see next function)
            $atts = vc_map_get_attributes('ahd_consent_form', $atts);
            // Define output and open element div.
            $url_link = vc_build_link($atts['page_link']);

            $output = '<div class="ahd-consent-form">';
            $output .= '<div class="icon">' . wp_get_attachment_image($atts['icon'], array(87, 87), false, array("class" => '')) . '</div>';
            $output .= '<div class="heading"><h4>' . $atts['heading'] . '</h4></div>';
            $output .= '<div class="link"><a href="' . $url_link['url'] . '"><img src="https://www.antwerphousedentistry.co.uk/wp-content/uploads/menu-consent-gray.png" alt="' . esc_html($atts['heading']) . '"></a></div>';
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
                'name' => esc_html__('AHD Consent Form', 'locale'),
                'description' => esc_html__('Shortcode outputs Consent Form.', 'locale'),
                'base' => 'ahd_consent_form',
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => esc_html__('Icon', 'locale'),
                        'param_name' => 'icon',
                        "description" => __("87x87px", 'locale')
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => esc_html__('Heading', 'locale'),
                        'param_name' => 'heading',
                    ),
                    array(
                        'type' => 'vc_link',
                        'heading' => esc_html__('Link', 'locale'),
                        'param_name' => 'page_link',
                    ),
                ),
            );
        }

    }

}

new AHD_Consent_Form_Shortcode;

add_action('after_setup_theme', function () {
    register_nav_menus(
        array(
            'topbar' => __('Top Bar Menu', 'somnowell'),
        )
    );
});