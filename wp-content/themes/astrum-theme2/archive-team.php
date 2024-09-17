<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astrum
 * @since Astrum 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header($htype); ?>
<!-- Titlebar
  ================================================== -->
  <section id="titlebar">
    <!-- Container -->
    <div class="container">
      <div class="eight columns">
        <h2><?php _e('Team','purepress'); ?></h2>
      </div>

      <div class="eight columns">
        <nav id="breadcrumbs">
          <?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>
        </nav>
      </div>
    </div>
    <!-- Container / End -->
  </section>

<!-- Content
  ================================================== -->
  <?php $layout = ot_get_option('pp_blog_layout'); ?>
  <!-- Container -->
  <div class="container <?php if($layout == 'left-sidebar') { echo "page-left"; }?>">
    <div class="twelve <?php if($layout == 'left-sidebar') { echo "alt2"; } else { echo "alt"; } ?> columns">
      <?php if ( have_posts() ) :
        while ( have_posts() ) : the_post(); ?>
            <!-- Post -->
            <article <?php post_class('post medium'); ?> id="post-<?php the_ID(); ?>" >
              <?php if(has_post_thumbnail()) { ?>
              <div class="five alt columns alpha">
                <figure class="post-img media">
                  <div class="mediaholder">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-medium'); ?>
                    <div class="hovercover">
                      <div class="hovericon"><i class="hoverlink"></i></div>
                    </div>
                  </a>
                </div>
              </figure>
            </div>
            <?php } ?>
            <div class="seven columns">
              <section class="post-content">
                <?php
                  $position = get_post_meta($post->ID, 'pp_position', true);
                  $social = get_post_meta($post->ID, 'pp_socialicons', true);
                ?>
                <header class="meta">
                  <h2><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'purepress'), the_title_attribute('echo=0')); ?>" rel="bookmark">
                    <?php the_title();  ?>
                  </a></h2>
                  <?php if($position) { echo " <span>".$position."</span>"; } ?>
                </header>
                <?php the_content();
                $output = '';
                if(!empty($social)){
                  $output .= '<ol class="social-icons">';
                  foreach ($social as $icon) {
                    $output .= '<li><a class="'.$icon['icons_service'].'" href="'.$icon['icons_url'].'"><i class="icon-'.$icon['icons_service'].'"></i></a></li>';
                  }
                  $output .= '</ol>';
                  echo $output;
                }
                ?>

              </section>
            </div>
            <div class="clearfix"></div>
          </article>
      <?php endwhile; ?>
        <?php else :
        get_template_part( 'no-results', 'index' );
        endif; ?>

      <?php if(function_exists('pagination')) {
        pagination();
      } else { ?>
      <?php if ( get_next_posts_link() ||  get_previous_posts_link() ) : ?>
        <nav class="pagination">
          <ul>
            <?php if ( get_next_posts_link() ) : ?>
            <li class="nav-previous"><?php next_posts_link( __( '&larr; Older posts', 'purepress' ) ); ?></li>
              <?php endif; if ( get_previous_posts_link() ) : ?>
              <li class="nav-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'purepress' ) ); ?></li>
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
