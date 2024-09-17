<?php
/**
 * The main template file.
 * @package WordPress
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
        <h1><?php the_title(); ?> <?php $subtitle  = get_post_meta($post->ID, 'pp_subtitle', true);
        if ( $subtitle) {
          echo ' <span>  '.$subtitle.'</span>';
        } ?></h1>
      </div>
      <div class="eight columns">
       <?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>
     </div>
   </div>
   <!-- Container / End -->
 </section>



 <?php while (have_posts()) : the_post(); ?>
 <!-- Container -->
 <div class="container">
  <!-- Slider -->
  <?php $layout =  get_post_meta($post->ID, 'pp_pf_layout', true);
       if($layout == 'half') { ?><div class="eleven alt columns"> <?php } else { ?> <div class="sixteen columns"> <?php }

  $type  = get_post_meta($post->ID, 'pp_pf_type', true);

  if($type == 'video') {
    $videoembed = get_post_meta($post->ID, 'pp_pfvideo_embed', true);
    if($videoembed) {
      echo '<div class="container"><div class="sixteen columns video video-cont">'.$videoembed.'</div></div>';
    } else {
      global $wp_embed;
      $videolink = get_post_meta($post->ID, 'pp_pfvideo_link', true);
      $post_embed = $wp_embed->run_shortcode('[embed width="940" height="530"]'.$videolink.'[/embed]') ;
      echo '<div class="container"><div class="sixteen columns video video-cont">'.$post_embed.'</div></div>';
    }
  } else {
            // Check what to display above post title (image,vide, slideshow)
    $ids = get_post_meta($post->ID, 'pp_gallery_slider', TRUE);
    $args = array(
      'post_type' => 'attachment',
      'post_status' => 'inherit',
      'post_mime_type' => 'image',
      'post__in' => explode( ",", $ids),
      'posts_per_page' => '-1',
      'orderby' => 'post__in'
      );

    $images_array = get_posts( $args );
    if ( $images_array ) {
      $slides = count($images_array);
      $captions = ot_get_option('pp_portfolio_caption');
      ?>
      <section class="flexslider post-img">
        <div class="media">
          <ul class="slides mediaholder">
           <?php foreach( $images_array as $images ) : setup_postdata($images); ?>
           <!-- 960 Container -->
           <?php
           $attachment = wp_get_attachment_image_src($images->ID, 'full');

           if($layout == 'half') {
              $thumb = wp_get_attachment_image_src($images->ID, 'portfolio-half');
            } else {
               $thumb = wp_get_attachment_image_src($images->ID, 'portfolio-wide');
            }
           ?>
           <li>
            <a href="<?php echo $attachment[0] ?>" class="<?php if($slides > 1){ echo 'mfp-gallery';  } else { echo 'mfp-image'; } ?>" title="<?php echo $images->post_title; ?>" >
              <img src="<?php echo $thumb[0] ?>" alt="<?php echo $images->post_title; ?>" />
              <?php if($captions == 'yes') { ?><div class="slide-caption"><h3><?php echo $images->post_title; ?></h3></div><?php } ?>
            </a>
          </li>
        <?php endforeach;  ?>
      </ul>
    </div>
  </section>
  <?php
    } //eof if type
    wp_reset_query();
  } //eof if type ?>
</div>
    <!-- Slider / End -->

  <?php if($layout == 'half') { ?>
    <div class="five columns">
      <?php the_content() ?>
    </div>
    <?php } ?>
  </div>
  <!-- Container / End -->

  <?php if($layout != 'half') { ?>
  <!-- Container -->
    <div class="container">
      <div class="sixteen columns">
      <?php the_content() ?>
    </div>
  </div>
  <!-- Container / End -->
  <?php }
  endwhile; // End the loop. Whew.  ?>

<?php  if(ot_get_option('pp_related_pf') != 'hide') { ?>
  <div class="container" id="pf_recents">
    <?php
    if(ot_get_option('pp_samefilter_pf') == 'same') {
      $filters =  get_the_terms( $post->ID, 'filters');
      $exclusivefilters = '';
      foreach ($filters as $filter) {
        $exclusivefilters = $filter->slug.',';
      }
      echo do_shortcode( '[recent_work limit="6" filters="'.$exclusivefilters.'" title="'.ot_get_option('pp_recenttext_pf','Recent Work').'" exclude_posts="'.$id.'" place="none" ][/recent_work]' );
    } else {
      echo do_shortcode( '[recent_work limit="6" title="'.ot_get_option('pp_recenttext_pf','Recent Work').'" exclude_posts="'.$id.'" place="none" ][/recent_work]' );
    }
    ?>

  </div>
  <div class="container" id="pf-contact-form">
 		<?php echo do_shortcode('[template id="11118"]'); ?> 
  </div>
<?php
}
get_footer();
?>