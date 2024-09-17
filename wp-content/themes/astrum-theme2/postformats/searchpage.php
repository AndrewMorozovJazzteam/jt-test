  <!-- Post -->
  <?php $format = get_post_format();
  if( false === $format )  $format = 'standard'; ?>
  <article itemscope="" itemtype="http://schema.org/BlogPosting" class="post search-blog-post-container" id="post-<?php the_ID(); ?>" >
<!-- 
    <div class="post-format">
      <div class="circle"><i class="icon-pencil"></i><span></span></div>
    </div>
 -->
    <section class="post-content">

      <header class="meta">
        <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'purepress'), the_title_attribute('echo=0')); ?>" rel="bookmark"><h2 class="headline-third"  itemprop="name headline">
          <?php the_title(); ?>
        </h2></a>
         <?php astrum_posted_on(); ?>
      </header>
      <p class="text-second" itemprop="articleBody">
          <?php $excerpt = get_the_excerpt();//get_the_content();
          $short_excerpt = strip_shortcodes( $excerpt ); echo $short_excerpt.'..'; ?>
     </p>
		<div class="left-modern-button"><a href="<?php the_permalink(); ?>" class="btn btn--orange left-modern-button__content"><span class="headline-four"><?php  _e('Read More', 'purepress'); ?> </span></a></div>
    </section>
    <div class="clearfix"></div>
  </article>




