<?php
/**
 * New Site Header
 *
 * @package GeneratePress
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<div class="site-header">
    <div class="top">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'topbar',
                'menu_class' => 'nav',
                'menu_id' => 'top',
            )
        );
        ?>
    </div>
    <div class="main">
        <div class="logo">
            <a href="<?php echo site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ahd-logo.png" alt="<?php bloginfo('name'); ?>"></a>
        </div>
        <div class="content">
            <div class="info">
                <strong>Cambridge</strong><br>
                <a href="tel:01223247690" class="phone">01223 247690</a><br>
                <a href="mailto:reception@antwerphousedentistry.co.uk" class="email">reception@antwerphousedentistry.co.uk</a>
            </div>
            <div id="wpac-google-review"><a href="https://www.google.com/search?q=Antwerp+House+Dentistry+Cambridge+Brookfields,+Cambridge,+England,+United+Kingdom&ludocid=6311085454783223603&#lrd=0x47d870798b50c819:0x57957ac8977d1733,1" rel="nofollow" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/rating4-6.png"/></a></div>
        </div>
        <div class="content right">
            <div class="search">
                <form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" role="search">
                    <div class="input-group">
                        <input class="field" id="s" name="s" type="text" value="<?php the_search_query(); ?>" placeholder="Search">
                        <button class="btn" id="searchsubmit" name="submit" type="submit"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/search.png" alt="Search"></button>
                    </div>
                </form>
            </div>
            <div class="contact">
                <a href="<?php the_permalink(); ?>">BOOK AN APPOINTMENT</a>
            </div>
        </div>
    </div>
</div>