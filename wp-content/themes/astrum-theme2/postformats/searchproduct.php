  <!-- Post -->
  <?php $format = get_post_format();
  if( false === $format )  $format = 'standard'; ?>
  <article <?php post_class('post medium '.$format); ?> id="post-<?php the_ID(); ?>" >
    <?php

    $thumb_status = get_post_meta($post->ID, 'pp_thumb_status', TRUE);
    if(empty($thumb_status)) { $thumb_status = array(); }
    if(has_post_thumbnail() && !in_array("hide_blog", $thumb_status)) { ?>
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


  <?php if(!in_array("hide_blog", $thumb_status)){ ?><div class="seven columns"> <?php } else { ?>
  <div class="post-format">
      <div class="circle"><i class="icon-camera"></i><span></span></div>
    </div>
  <?php } ?>
    <section class="post-content">

      <header class="meta">
        <h2 class="entry-title" ><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'purepress'), the_title_attribute('echo=0')); ?>" rel="bookmark">
          <?php the_title(); ?>
        </a></h2>
        <?php astrum_posted_on(); ?>
      </header>

      <p>
        <?php $excerpt = get_the_content();
              $short_excerpt = strip_shortcodes( $excerpt ); echo $short_excerpt; ?>
      </p>

      <a href="<?php the_permalink(); ?>" class="button color"><?php  _e('Read More', 'purepress'); ?> </a>

    </section>
  <?php if(!in_array("hide_blog", $thumb_status)){ ?></div><?php } ?>
  <div class="clearfix"></div>
</article>