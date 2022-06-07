<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

    <div <?php generate_do_attr('content'); ?>>
        <main <?php generate_do_attr('main'); ?>>
            <?php
            /**
             * generate_before_main_content hook.
             *
             * @since 0.1
             */
            do_action('generate_before_main_content');

            $main_site_page = get_field('main_site_page');
            if ($main_site_page):
                global $search;
                $search = site_url('/wp-content/uploads');
                global $replace;
                $replace = get_site_url(1) . '/wp-content/uploads';

                switch_to_blog($main_site_page['site_id']);
                foreach ($main_site_page['selected_posts'] as $post):
                    setup_postdata($post);
                    get_template_part('content', 'page-clone');
                endforeach;
                wp_reset_postdata();
                restore_current_blog();
            else:
                if (generate_has_default_loop()) {
                    while (have_posts()) :
                        the_post();
                        generate_do_template_part('page');
                    endwhile;
                }
            endif;
            /**
             * generate_after_main_content hook.
             *
             * @since 0.1
             */
            do_action('generate_after_main_content');
            ?>
        </main>
    </div>
<?php
/**
 * generate_after_primary_content_area hook.
 *
 * @since 2.0
 */
do_action('generate_after_primary_content_area');

generate_construct_sidebars();

get_footer();
