<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage purepress
 * @since purepress 1.0
 */

$ajax_status = ot_get_option('pp_ajax_pf');
?>

<!-- Container -->
<div class="container">
	<div class="sixteen columns">

		<?php
		$filterswitcher = get_post_meta($post->ID, 'pp_filters_switch', true);
		if($filterswitcher != 'no') {
			$filters = get_post_meta($post->ID, 'portfolio_filters', true);
			if(!empty($filters)) { ?>
			<div class="showing"><?php _e('Showing:','purepress') ?></div>
			<span class="line showing"></span>
			<div id="filters" class="filters-dropdown headline"><span></span>
				<ul class="option-set" data-option-key="filter">
					<li><a href="#filter" class="selected" data-option-value="*"><?php  _e('All', 'purepress'); ?></a></li>
					<?php
					foreach ( $filters as $id ) {
						$term = get_term( $id, 'filters' );
						echo '<li><a href="#filter" data-option-value=".'.$term->slug.'">'. $term->name .'</a></li>';

					} ?>
				</ul>
			</div>
			<span class="line filters"></span><div class="clearfix"></div>
			<?php } }
			if(!is_tax()) {
				$terms = get_terms("filters");
				$count = count($terms);
				if ( $count > 0 ){ ?>
				<div class="showing">Showing:</div>
				<span class="line showing"></span>
				<div id="filters" class="filters-dropdown headline">
					<ul class="option-set" data-option-key="filter">
						<li><a href="#filter" class="selected" data-option-value="*"><?php  _e('All', 'purepress'); ?></a></li>
						<?php
						foreach ( $terms as $term ) {
							echo '<li><a href="#filter" data-option-value=".'.$term->slug.'">'. $term->name .'</a></li>';
						} ?>
					</ul>
				</div>
				<span class="line filters"></span><div class="clearfix"></div>
				<?php }
			} ?>

		</div>
	</div>
	<!-- Container / End -->
	<?php if($ajax_status == 'yes') { ?>
	<div id="portfolio_ajax_wrapper">

		<div class="container">
			<div id="portfolio_ajax" class="columns sixteen  omega">
					<a href="#" class="leftarrow ajaxarrows"><i class="icon-chevron-left"></i></a>
					<a href="#" class="rightarrow ajaxarrows"><i class="icon-chevron-right"></i></a>
					<a href="#" class="close"><i class="icon-remove"></i></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<!-- 960 Container -->
	<div class="container">

		<!-- Portfolio Content -->
		<div id="portfolio-wrapper" class="pf-col3">


			<!-- Post -->
			<?php
			while (have_posts()) : the_post(); ?>

			<!-- Portfolio Item -->
			<?php if($ajax_status == 'yes') { ?>
				<div <?php post_class('one-third column portfolio-item portfolio-item-ajax media'); ?> id="post-<?php the_ID(); ?>" >
			<?php } else { ?>
				<div <?php post_class('one-third column portfolio-item media'); ?> id="post-<?php the_ID(); ?>" >
			<?php } ?>
					<figure>
						<div class="mediaholder">
							<a href="<?php the_permalink(); ?>">
								<?php
								$type  = get_post_meta($post->ID, 'pp_pf_type', true);
								$videothumbtype = ot_get_option('pp_portfolio_videothumb');
								if($type == 'video' && $videothumbtype == 'video') {
									$videoembed = get_post_meta($post->ID, 'pp_pfvideo_embed', true);
									if($videoembed) {
										echo '<div class="video">'.$videoembed.'</div>';
									} else {
										global $wp_embed;
										$videolink = get_post_meta($post->ID, 'pp_pfvideo_link', true);
										$post_embed = $wp_embed->run_shortcode('[embed  width="300" height="200"]'.$videolink.'[/embed]') ;
										echo '<div class="video">'.$post_embed.'</div>';
									}
								} else {
									$thumbbig = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
									if(ot_get_option('portfolio_thumb') == 'lightbox'){
										the_post_thumbnail("portfolio-3col");
									} else {
										the_post_thumbnail("portfolio-3col");
									}
								} ?>
								<div class="hovercover">
									<div class="hovericon"><i class="hoverlink"></i></div>
								</div>
							</a>
						</div>
						<a href="<?php the_permalink(); ?>">
							<figcaption class="item-description">
								<h5><?php the_title(); ?></h5>
								<?php $terms = get_the_terms( $post->ID, 'filters' );
								if ( $terms && ! is_wp_error( $terms ) ) : echo '<span>';
								$filters = array();
								$i = 0;
								foreach ( $terms as $term ) {
									$filters[] = $term->name;
									if ($i++ > 2) break;
								}
								$outputfilters = join( ", ", $filters ); echo $outputfilters;
								echo '</span>';
								endif; ?>
							</figcaption>
						</a>
					</figure>

			</div>
			<!-- eof Portfolio Item -->

		<?php endwhile; // End the loop. Whew.  ?>
	</div>
	</div>
	<!-- 960 Container -->
	<div class="container">
		<div class="columns sixteen">
			<?php if(function_exists('pagination')) {
				pagination();
			} else { ?>
			<?php if ( get_next_posts_link() ||  get_previous_posts_link() ) : ?>
			<nav class="pagination">
				<ul>
					<?php if ( get_next_posts_link() ) : ?>
					<li class="nav-previous"><?php next_posts_link( __( '&larr; Previous page', 'purepress' ) ); ?></li>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<li class="nav-next"><?php previous_posts_link( __( 'Next page &rarr;', 'purepress' ) ); ?></li>
			<?php endif; ?>
		</ul>
		<div class="clearfix"></div>
	</nav>
<?php endif; ?>
<?php } ?>
</div>
</div>
<div id="astrum-ajax-loader"><i class="icon-spinner icon-spin icon-large"></i></div>