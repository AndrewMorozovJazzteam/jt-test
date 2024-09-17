<?php
/**
 * The main template file.
 * @package WordPress
 */
$htype = ot_get_option('pp_header_menu');
get_header($htype);
?>


<?php while (have_posts()) : the_post(); ?>
<!-- Titlebar
  ================================================== -->
  <section id="titlebar">
    <!-- Container -->
    <div class="container">
      <div class="eight columns">
        <h2>
          <?php
          $author = get_post_meta($id, 'pp_author', true);
          $link = get_post_meta($id, 'pp_link', true);
          $position = get_post_meta($id, 'pp_position', true);
          if($link) {
            echo '<a href="'.$link.'">'.$author.'</a>';
          } else {
            echo $author;
          }
          if($position) {
            echo " <span>/ ".$position."</span>";
          }
          ?>
        </h2>
      </div>

      <div class="eight columns">
       <?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>
     </div>
   </div>
   <!-- Container / End -->
 </section>

 <!-- Container -->
 <div class="container">
  <!-- Slider -->
  <!-- Slider / End -->
  <div class="three alt columns alpha">
    <?php the_post_thumbnail('blog-medium'); ?>
  </div>
  <div class="twelve columns">
    <div class="testimonials">
      <?php the_content(); ?>
    </div>
    <nav class="pagination">
      <ul>
        <li><?php previous_post_link( '<div class="nav-previous">%link</div>', '' . _x( '&larr;', 'Previous post link', 'purepress' ) . ' %title' ); ?></li>
        <li><?php next_post_link( '<div class="nav-next">%link</div>', '%title ' . _x( '&rarr;', 'Next post link', 'purepress' ) . '' ); ?></li>
      </ul>
      <div class="clearfix"></div>
    </nav>

  </div>
</div>
<!-- Container / End -->

<?php endwhile; // End the loop. Whew.  ?>

<?php
get_footer();
?>