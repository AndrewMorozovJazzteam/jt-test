<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?>

<article id="post-0" class="post no-results not-found">
	 <section class="post-content">
	 	 <header class="meta">
	 	 	<h2><?php _e( 'Nothing Found', 'purepress' ); ?></h2>
	 	 </header>
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'purepress' ), admin_url( 'post-new.php' ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'purepress' ); ?></p>

		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'purepress' ); ?></p>


		<?php endif; ?>
	 </section>
    <div class="clearfix"></div>
  </article>

