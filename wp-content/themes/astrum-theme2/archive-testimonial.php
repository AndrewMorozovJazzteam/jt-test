<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nevia
 * @since Nevia 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header($htype);
?>

<!-- Titlebar
    ================================================== -->
    <section id="titlebar">
        <!-- Container -->
        <div class="container">

            <div class="eight columns">
                <h2><?php _e('Testimonials', 'purepress'); ?></h2>
            </div>

            <div class="eight columns">
                <nav id="breadcrumbs">
                  <?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>
              </nav>
          </div>
      </div>
      <!-- Container / End -->
    </section>

    <div class="container">
        <div class="twelve alt columns">
        <?php if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                $author = get_post_meta($post->ID, 'pp_author', true);
                $link = get_post_meta($post->ID, 'pp_link', true);
                $position = get_post_meta($post->ID, 'pp_position', true);
            ?>
            <article  class="post">
                <div class="testimonials" ><?php the_content(); ?></div>
                <div class="testimonials-bg"></div>
                <div class="testimonials-author"><?php echo $author; ?>, <span><?php echo $position; ?></span></div>
            </article>
            <?php endwhile;
        else :
            get_template_part( 'no-results', 'index' );
        endif;

if(function_exists('pagination')) {
    pagination();
} else { ?>
<?php if ( get_next_posts_link() ||  get_previous_posts_link() ) : ?>
    <nav class="pagination">
        <ul>
            <?php if ( get_next_posts_link() ) : ?>
            <li class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'purepress' ) ); ?></li>
        <?php endif; ?>

        <?php if ( get_previous_posts_link() ) : ?>
        <li class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'purepress' ) ); ?></li>
    <?php endif; ?>
        </ul>
        <div class="clearfix"></div>
    </nav>
<?php endif; ?>
<?php } ?>

</div>
<!-- Sidebar
    ================================================== -->
<?php get_sidebar(); ?>
</div>
<!-- Container / End -->

<?php get_footer(); ?>
