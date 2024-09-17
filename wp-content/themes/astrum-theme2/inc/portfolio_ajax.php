 <?php
 $portfolio = get_post( $postid ); ?>
<div class="added_item" id="post-<?php echo $postid; ?>">
 <!-- Container -->

  <!-- Slider -->
  <div class="eleven alpha alt columns">
  <?php
  $type  = get_post_meta($portfolio->ID, 'pp_pf_type', true);

  if($type == 'video') {
    $videoembed = get_post_meta($portfolio->ID, 'pp_pfvideo_embed', true);
    if($videoembed) {
      echo '<div class="video video-cont">'.$videoembed.'</div';
    } else {
      global $wp_embed;
      $videolink = get_post_meta($portfolio->ID, 'pp_pfvideo_link', true);
      $post_embed = $wp_embed->run_shortcode('[embed width="775" height="430"]'.$videolink.'[/embed]') ;
      echo '<div class="video video-cont">'.$post_embed.'</div>';
    }
  } else {
            // Check what to display above post title (image,vide, slideshow)
    $ids = get_post_meta($portfolio->ID, 'pp_gallery_slider', TRUE);
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
      $captions = ot_get_option('pp_portfolio_caption');
      ?>
      <section class="flexslider post-img">
        <div class="media">
          <ul class="slides mediaholder">
           <?php foreach( $images_array as $images ) : setup_postdata($images); ?>
           <!-- 960 Container -->
           <?php
           $attachment = wp_get_attachment_image_src($images->ID, 'full');
           $thumb = wp_get_attachment_image_src($images->ID, 'portfolio-half');
           ?>
           <li>
              <img src="<?php echo $thumb[0] ?>" alt="<?php echo $images->post_title; ?>" />
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


    <div class="five omega columns">
      <a href="<?php echo get_permalink($portfolio->ID); ?>">
        <h3 class="ajax_pf_title"><?php echo $portfolio->post_title; ?></h3>
      </a>
      <?php
      $content = $portfolio->post_excerpt;
      if(empty($content)) { $content = $portfolio->post_content; }
      echo apply_filters( 'the_content', $content );  ?>
    </div>

</div>