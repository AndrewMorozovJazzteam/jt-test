<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?>
 <div class="four columns sb">
	 <div class="sidebar-toc-wrapper">
		<div class="sidebar-toc-inner">
			<?php do_action( 'before_sidebar' ); ?>
			<?php if ( !is_archive() && !is_search() ) {	$sidebar = get_post_meta($post->ID, "pp_sidebar_set", $single = true); }
			else {
				$sidebar = '';
			}
			if ($sidebar) {
				if ( ! dynamic_sidebar( $sidebar ) ) : ?>
					<div class="clearfix"></div>
					<div id="archives" class="widget  widget_archive">
						<h4 class="widget-title"><?php _e( 'Archives', 'nevia' ); ?></h4>
						<ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
						</ul>
					</div>

					<div id="meta" class="widget  widget_meta">
						<h4 class="widget-title"><?php _e( 'Meta', 'nevia' ); ?></h4>
						<ul>
							<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
							<?php wp_meta(); ?>
						</ul>
					</div>

				<?php endif;
			} // end sidebar widget area ?>
		<?php
		if (!$sidebar) {
			if (!dynamic_sidebar('sidebar')) : ?>
				
				<div id="archives" class="widget">
					<h4 class="widget-title"><?php _e( 'Archives', 'nevia' ); ?></h4>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</div>

				<div id="meta" class="widget">
					<h4 class="widget-title"><?php _e( 'Meta', 'nevia' ); ?></h4>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</div>
			<?php endif;
		    } // end primary widget area
		    ?>
		</div>
	</div>
</div>