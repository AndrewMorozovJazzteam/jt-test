<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Nevia
 * @since Nevia 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header('new-design');
 ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php get_template_part( 'content', 'single' ); ?>
<?php endwhile; // end of the loop. ?>
<?php get_footer('new-design'); ?>