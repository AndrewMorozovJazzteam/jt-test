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
         $position = get_post_meta($post->ID, 'pp_position', true);
         $social = get_post_meta($post->ID, 'pp_socialicons', true);
         ?>
         <?php the_title(); ?>
         <?php if($position) { echo " <span>".$position."</span>"; } ?>
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
  <div class="five alt columns alpha">
    <?php the_post_thumbnail('blog-medium'); ?>
  </div>
  <div class="seven columns">
    <?php the_content(); if(!empty($social)){
      $output .= '<ol class="social-icons">';
      foreach ($social as $icon) {
        $output .= '<li><a class="'.$icon['icons_service'].'" href="'.$icon['icons_url'].'"><i class="icon-'.$icon['icons_service'].'"></i></a></li>';
      }
      $output .= '</ol>';
      echo $output;
    }
    ?>
  </div>

</div>
<div class="container">
  <div class="sixteen columns">
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