  <!-- Post -->
  <?php $format = get_post_format();
  if( false === $format )  $format = 'standard'; ?>
  <article  itemscope="" itemtype="http://schema.org/BlogPosting" <?php post_class('post '.$format); ?> id="post-<?php the_ID(); ?>" >
    <?php
    $ids = get_post_meta($post->ID, 'pp_gallery_slider', TRUE);
    $args = array(
      'post_type' => 'attachment',
      'post_status' => 'inherit',
      'post_mime_type' => 'image',
      'post__in' => explode( ",", $ids),
      'posts_per_page' => '-1',
      'orderby' => 'post__in'
      );

    $thumb_status = get_post_meta($post->ID, 'pp_thumb_status', TRUE);
    if(empty($thumb_status)) { $thumb_status = array(); }

    $images_array = get_posts( $args );
    if ( $images_array && !in_array("hide_blog", $thumb_status)) { ?>
    <section class="flexslider post-img">
      <div class="media">
        <ul class="slides mediaholder">
          <?php foreach( $images_array as $images ) : setup_postdata($images); ?>
          <!-- 960 Container -->
          <?php
          $attachment = wp_get_attachment_image_src($images->ID, 'full');
          $thumb = wp_get_attachment_image_src($images->ID, 'portfolio-wide');
          ?>
          <li>
            <a href="<?php echo $attachment[0] ?>" class="mfp-gallery" title="<?php echo $images->post_title; ?>" >
              <img src="<?php echo $thumb[0] ?>" alt="<?php echo $images->post_title; ?>" />
              <div class="hovercover">
                <div class="hovericon"><i class="hoverzoom"></i></div>
              </div>
            </a>
          </li>
        <?php endforeach;  ?>
      </ul>
    </div>
  </section>
    <!-- End 960 Container -->
    <?php   } //eof if type ?>
 <div class="post-format">
      <div class="circle"><i class="icon-picture"></i><span></span></div>
    </div>

    <section class="post-content">

      <header class="meta">
        <h2  class="entry-title" itemprop="name headline"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'purepress'), the_title_attribute('echo=0')); ?>" rel="bookmark">
          <?php the_title(); ?>
        </a></h2>
         <?php astrum_posted_on(); ?>
      </header>
      <p itemprop="articleBody">
      <?php the_excerpt(); ?>
      </p>
      <a href="<?php the_permalink(); ?>" class="button color"><?php  _e('Read More', 'purepress'); ?> </a>

    </section>
    <div class="clearfix"></div>
  </article>
