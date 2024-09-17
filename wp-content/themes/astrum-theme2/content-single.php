<?php
/**
 * @package Nevia
 * @since Nevia 1.0
 */
?>
<!-- Titlebar
  ================================================== -->
  <section id="titlebar" class="titlebar">
    <!-- Container -->
    <div class="container container-heading">
        <h1 class="headline-first headline-first--indent-strict"><?php
        $pp_blog_page = the_title();
        if (function_exists('icl_register_string')) {
          icl_register_string('Blog page title','pp_blog_page', $pp_blog_page);
          echo icl_t('Blog page title','pp_blog_page', $pp_blog_page); }
        else {
            echo $pp_blog_page;
        } ?><?php edit_post_link( __( 'Edit', 'purepress' ), '', '', 0, 'link edit-link' ); ?></h1>

    	<div class="heading-breadcrumbs"><?php echo nd_breadcrumbs();?></div>
    </div>
    <!-- Container / End -->
  </section>


<!-- Content
  ================================================== -->
<?php $sbside = get_post_meta($post->ID, 'pp_sidebar_layout', TRUE); ?>
  <!-- Container -->
  <div    class="container container--split <?php  if($sbside == 'left-sidebar') { echo 'page-left'; }  ?>">
	<main class="article-content" >
    
      <?php

			$categories = get_the_category( $post->ID );
			$is_ba;
			foreach ($categories as $category) {
				if ( $category->name == 'Business Articles For IT Founders and C-Level Managers')
					$is_ba = true;				
			}
            /* Include the Post-Format-specific template for the content.
             * If you want to overload this in a child theme then include a file
             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
             */
             $thumb_status = get_post_meta($post->ID, 'pp_thumb_status', TRUE);
            if(empty($thumb_status)) { $thumb_status = array(); }
            $format = get_post_format();
            if( false === $format )  $format = 'standard'; ?>
            <article <?php post_class('post '.$format); ?> id="post-<?php the_ID(); ?>" >
            <?php if(!in_array("hide_single", $thumb_status)) { 
	
				if($format == 'image' && !$is_ba ) {
						//if(  ){  
						if(has_post_thumbnail() && !wp_is_mobile() ) {
						  //$thumbbig = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );				

						 ?>
						  <figure class="post-img media blog-no-mobile">
							<div class="mediaholder">
								<?php 
									$img_url = get_the_post_thumbnail_url();
									echo '<img src="'.$img_url.'" alt="Featured iamge">';
									//the_post_thumbnail();  ?>
							</div>
						  </figure>
						  <?php 
						//}  
						}
					}
			
				if($format == 'gallery') {
					$ids = get_post_meta($post->ID, 'pp_gallery_slider', TRUE);
					$args = array(
					  'post_type' => 'attachment',
					  'post_status' => 'inherit',
					  'post_mime_type' => 'image',
					  'post__in' => explode( ",", $ids),
					  'posts_per_page' => '-1',
					  'orderby' => 'post__in'
					  );


					$sliderquery = new WP_Query( $args  );
					if ( $sliderquery->have_posts() ) { ?>
						<section class="flexslider post-img">
						  <div class="media">
							<ul class="slides mediaholder">
							  <?php while ( $sliderquery->have_posts() ) {
								$sliderquery->the_post();
								$attachment = wp_get_attachment_image_src($sliderquery->post->ID, 'full');
								$thumb = wp_get_attachment_image_src($sliderquery->post->ID, 'portfolio-main');
								?>
								<li>
								  <a href="<?php echo $attachment[0] ?>" class="mfp-gallery" title="<?php echo $image->post_title; ?>" >
									<img src="<?php echo $thumb[0] ?>" alt="<?php echo $image->post_title; ?>" />
									<div class="hovercover">
									  <div class="hovericon"><i class="hoverzoom"></i></div>
									</div>
								  </a>
								</li>
								<?php } ?>
							  </ul>
							</div>
						</section>
						<!-- End 960 Container -->
						<?php
                    }   wp_reset_query();
                }


                  if($format == 'video') {
						if (( function_exists( 'get_post_format' ) && 'video' == get_post_format( $post->ID ) )  ) {
						  global $wp_embed;
						  $videoembed = get_post_meta($post->ID, 'pp_video_embed', true);
						  if($videoembed) {
							echo '<section class="video" style="margin-bottom:20px">'.$videoembed.'</section>';
						  } else {
							$videolink = get_post_meta($post->ID, 'pp_video_link', true);
							if($layout =='full-width') {
							  $post_embed = $wp_embed->run_shortcode('[embed  width="940" ]'.$videolink.'[/embed]') ; }
							  else {
								$post_embed = $wp_embed->run_shortcode('[embed  width="860" ]'.$videolink.'[/embed]') ;
							  }
							  echo '<section class="video" style="margin-bottom:20px">'.$post_embed.'</section>';
							}
						}
                    } 
				//}
			}	
                  $format = get_post_format();
                  $formatslist = array('status','aside','quote','audio','chat','link');
                  if( false === $format  )  $format = 'standard';

                  if (in_array($format, $formatslist))  $format = 'standard';
                  switch ($format) {
                    case 'gallery':
                      $icon = "picture";
                      break;
                    case 'image':
                      $icon = "camera";
                      break;
                    case 'video':
                      $icon = "film";
                      break;
                    case 'standard':
                      $icon = "pencil";
                      break;
                    default:
                      $icon = "pencil";
                      break;
                  }?>

                    <section class="post-content">
                      <div class="project-content-x">
                        <?php the_content(); ?>
                      </div>
						<?php wp_link_pages(); ?> 
                    </section>
                    <div class="clearfix"></div>
                  </article>



                  <!-- Divider -->
                  <nav class="pagination">
                    <ul>
                      <li><?php previous_post_link( '<div class="nav-previous">%link</div>', '' . _x( '&larr;', 'Previous post link', 'purepress' ) . ' %title' ); ?></li>
                      <li><?php next_post_link( '<div class="nav-next">%link</div>', '%title ' . _x( '&rarr;', 'Next post link', 'purepress' ) . '' ); ?></li>
                    </ul>
                    <div class="clearfix"></div>
                  </nav>          
               <!-- Content / End -->
	  
	  </main>

<!-- Sidebar
  ================================================== -->
<aside class="sidebar-static-right">
    <?php  get_sidebar(); ?>
	  </aside>
    </div>

  <!-- Container / End -->


<!-- Content Wrapper / End -->
