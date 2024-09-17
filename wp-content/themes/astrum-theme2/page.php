<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Nevia
 * @since Nevia 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header('new-design');

while ( have_posts() ) : the_post();
$layout  = get_post_meta($post->ID, 'pp_sidebar_layout', true);

switch ($layout) {
    case 'full-width':
        get_template_part( 'content', 'page' );
        break;
    case 'left-sidebar':
        get_template_part( 'content', 'pageleft' );
        break;
    case 'right-sidebar':
        get_template_part( 'content', 'pageright' );
        break;
    default:
        get_template_part( 'content', 'page' );
        break;
}

endwhile; // end of the loop. ?>



<?php get_footer('new-design'); ?>