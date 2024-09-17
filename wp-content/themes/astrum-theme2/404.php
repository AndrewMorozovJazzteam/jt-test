<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Nevia
 * @since Nevia 1.0
 */

//$htype = ot_get_option('pp_header_menu');
get_header('new-design');
?>

<!-- Titlebar
	================================================== -->
	<section id="titlebar" class="titlebar">
		<!-- Container -->
		<div class="container">

			<div class="eight columns">

				<h1 class="headline-first headline-first--indent-strict"><?php _e("404 Page Not Found", 'purepress' ); ?></h1>
			</div>
			<div class="eight columns">
				<?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>

			</div>

		</div>
		<!-- Container / End -->
	</section>


	<div class="container">

		<div class="sixteen columns">

			<section id="not-found">
				<h2>404 <i class="icon-question-sign"></i></h2>
				<p><?php _e("We're sorry, but the page you were looking for doesn't exist.", 'purepress' ); ?></p>
			</section>

		</div>

	</div>


	<?php // get_sidebar(); ?>
	<?php get_footer('new-design'); ?>


