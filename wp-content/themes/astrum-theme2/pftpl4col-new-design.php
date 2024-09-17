<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage purepress
 * @since purepress 1.0
 */

$ajax_status = ot_get_option('pp_ajax_pf');
?>

<?php

function this_pluralize_text($n, $forms) {
	return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
}


function display_portfolio_items_by_filter() {
	$args = array(
    	'post_type' => 'portfolio',
	    'posts_per_page' => $limit,
   		'orderby' => $orderby,
		'meta_key' => 'weights',
    	'order' => $queryorder);
               if(!empty($filters)) {
                       $args['tax_query'] = array(
                           array(
                               'taxonomy' => 'filters',
                               'field' => 'id',
                               'terms' => $filters
                               )
                           );
                   }
	if (isset($boxes) && is_array($boxes)) {
        $post_ids = wp_list_pluck($boxes, 'id');
        $args['orderby'] = 'post__in';
        $args['post__in'] = $post_ids;
		$args['posts_per_page'] = '-1';
    }
  echo '<div class="recent-project__page-wrapper">';	
  while (have_posts()) : the_post();
      
			$post_id = get_the_ID();
		
			$additional_description = get_post_meta($post_id, 'additional_description', 1);
			$killing_features = get_post_meta($post_id, 'killing_features', 1);
			$customer = get_post_meta($post_id, 'customer', 1);
            
            $team_size = array_sum(get_post_meta( $post_id, 'quantity', 'true' ));
            $team_size_prettified = $team_size . ' ' . this_pluralize_text($team_size, ['member', 'members', 'members']);
            
            $duration = get_duration($post_id);
            $duration_prettified = str_replace(array('years', 'months', 'days', 'hours', 'minutes', 'seconds'), array('yrs', 'mo', 'd', 'h', 'm', 's'), $duration);

?>

      <!-- Portfolio Item -->

	<div class="recent-project__slider-slide swiper-slide">
    	<a href="<?php echo get_permalink();?>" class="recent-project__slider-slide-content">
                      <?php
	
					$thumb = get_post_thumbnail_id();
                    $img_url = wp_get_attachment_url($thumb);
                      /*$type  = get_post_meta($post->ID, 'pp_pf_type', true);
                      $videothumbtype = ot_get_option('pp_portfolio_videothumb');
                      if ($type == 'video' && $videothumbtype == 'video') {
                          $videoembed = get_post_meta($post->ID, 'pp_pfvideo_embed', true);
                          if ($videoembed) {
                              echo '<div class="video">' . $videoembed . '</div>';
                          } else {
                              global $wp_embed;
                              $videolink = get_post_meta($post->ID, 'pp_pfvideo_link', true);
                              $post_embed = $wp_embed->run_shortcode('[embed  width="300" height="200"]' . $videolink . '[/embed]');
                              echo '<div class="video">' . $post_embed . '</div>';
                          }
                      } else {
                          $thumbbig = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                          if (ot_get_option('portfolio_thumb') == 'lightbox') {
                              the_post_thumbnail("portfolio-4col");
                          } else {
                              the_post_thumbnail("portfolio-4col");
                          }
                      }*/
                      ?>
			<div class="recent-project__slider-slide-img-wrapper">
				<div class="recent-project__slider-slide-img-bg"></div>
				<img
					class="recent-project__slider-slide-img"
					src="<?php echo $img_url; ?>"
					alt="<?php the_title(); ?>"
					height="204"
					width="285"
					loading="lazy"
				/>
			</div>
			<div class="recent-project__slider-slide-text-wrapper">
				<div class="recent-project__slider-info">
								<div class="recent-project__slider-info-splitted">
									<div class="recent-project__slider-info-column">
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title">Team size</span>
											<span class="recent-project__slider-info-unit-value"><?=$team_size_prettified;?></span>
										</div>
										<?php if ( is_user_logged_in() ) { ?>
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title">Completion year</span>
											<span class="recent-project__slider-info-unit-value"><?=get_completion_year($post_id);?></span>
										</div>
										<?php } ?>
									</div>
									<div class="recent-project__slider-divider"></div>
									<div class="recent-project__slider-info-column">
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title">Duration</span>
											<span class="recent-project__slider-info-unit-value"><?=$duration_prettified;?></span>
										</div>
										<?php if ( is_user_logged_in() ) { ?>
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title">Default (weight):</span>
											<span class="recent-project__slider-info-unit-value"><?=get_post_meta( $post_id, 'weights', 'true' );?></span>
										</div>
										<?php } ?>
									</div>

								</div>

							<?php if ( get_userlogin() == 'CEO' ) { ?>
										<div class="recent-project__slider-admin-info">
										<?php if ( $customer != ''){ ?>
											<div class="recent-project__slider-info-unit">
												<span class="recent-project__slider-info-unit-title">Customer:</span>
												<span class="recent-project__slider-info-unit-value"><?=$customer;?></span>
											</div>
										<?php } ?>
										<?php if ( $killing_features != ''){ ?>
											<div class="recent-project__slider-info-unit">
												<span class="recent-project__slider-info-unit-title">Killing Features:</span>
												<span class="recent-project__slider-info-unit-value"><?=$killing_features;?></span>
											</div>
										<?php } ?>
											<?php if ( $additional_description != ''){ ?>
												<div class="recent-project__slider-info-unit">
													<span class="recent-project__slider-info-unit-title">Additional Desc:</span>
													<span class="recent-project__slider-info-unit-value"><?=$additional_description;?></span>
												</div>
											<?php } ?>
										</div>
									<?php } ?>

							</div> 
              
				<span class="headline-four headline-four--color-inherit">
				  <?php the_title(); ?>
				</span>
				<div class="recent-project__slider-slide-text__tags">
					<?php
               			$terms = get_the_terms( $wp_query->post->ID, 'filters' );
                		$output = '';
                		if ( $terms && ! is_wp_error( $terms ) ) : 
                			$filters = array();
                            $i = 0;
                            foreach ( $terms as $term ) {
                            	$filters[] = $term->name;
                                if ($i++ > 0) break;
                            }
                            $outputfilters = join( ", ", $filters );
                            $output .= $outputfilters;                                       
                            endif;
                        echo $output;
          			?>
				</div>
			</div>
		</a>
	</div>

      <!-- eof Portfolio Item -->
  <?php endwhile; // End the loop.
}

echo '</div>';
   
function display_portfolio_items() {
  $query_posts = new WP_Query( array(
      'post_type'         => 'Portfolio',         
      'post_status'       => 'publish',
      'posts_per_page'    => -1,                   
  ));

  if (isset($_GET["filter"])) {
      $filter = $_GET["filter"];
  } else {
      $filter = null;
  }

  $current_index = 0;
  $max_items_per_page = ot_get_option('pp_portfolio_showpost', 10000);
  $current_page = get_query_var('paged') ? get_query_var('paged') : 1;

  while ($query_posts->have_posts()) : $query_posts->the_post();
      $current_index += 1;
      ?>
      <!-- Portfolio Item -->
      <?php if($ajax_status == 'yes') { ?>
          <div <?php post_class('four columns portfolio-item portfolio-item-ajax media'); ?> id="post-<?php the_ID(); ?>" data-idx="<?php echo $current_index; ?>" data-page="<?php echo ceil($current_index / $max_items_per_page); ?>">
      <?php } else { ?>
          <div <?php post_class('four columns portfolio-item media'); ?> id="post-<?php the_ID(); ?>" data-idx="<?php echo $current_index; ?>" data-page="<?php echo ceil($current_index / $max_items_per_page); ?>" data-max="<?php echo $max_items_per_page; ?>"<?php echo $current_page == ceil($current_index / $max_items_per_page) ? 'data-hidden="none"' : 'data-hidden="full"'; ?>>
      <?php } ?>
          <figure>
              <div class="mediaholder">
                  <a href="<?php the_permalink(); ?>">
                      <?php
                      $type  = get_post_meta($post->ID, 'pp_pf_type', true);
                      $videothumbtype = ot_get_option('pp_portfolio_videothumb');
                      if ($type == 'video' && $videothumbtype == 'video') {
                          $videoembed = get_post_meta($post->ID, 'pp_pfvideo_embed', true);
                          if ($videoembed) {
                              echo '<div class="video">' . $videoembed . '</div>';
                          } else {
                              global $wp_embed;
                              $videolink = get_post_meta($post->ID, 'pp_pfvideo_link', true);
                              $post_embed = $wp_embed->run_shortcode('[embed  width="300" height="200"]' . $videolink . '[/embed]');
                              echo '<div class="video">' . $post_embed . '</div>';
                          }
                      } else {
                          $thumbbig = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                          if (ot_get_option('portfolio_thumb') == 'lightbox') {
                              the_post_thumbnail("portfolio-4col");
                          } else {
                              the_post_thumbnail("portfolio-4col");
                          }
                      }
                      ?>
                      <div class="hovercover">
                          <div class="hovericon"><i class="hoverlink"></i></div>
                      </div>
                  </a>
              </div>
              <a href="<?php the_permalink(); ?>">
                  <figcaption class="item-description">
                      <h5><?php the_title(); ?></h5>
                      <?php
                      $terms = get_the_terms($post->ID, 'filters');
                      if ($terms && ! is_wp_error($terms)) :
                          echo '<span>';
                          $filters = array();
                          $i = 0;
                          foreach ($terms as $term) {
                              $filters[] = $term->name;
                              if ($i++ > 2) break;
                          }
                          $outputfilters = join(", ", $filters);
                          echo $outputfilters;
                          echo '</span>';
                      endif;
                      ?>
                  </figcaption>
              </a>
          </figure>
      </div>
  <?php endwhile; // End the loop.

}

?>


<!-- Container -->
<div class="container">
	<div class="sixteen columns">
		<script>
			var __transferProjectsPerPage = <?php echo ot_get_option('pp_portfolio_showpost'); ?>
		</script>
		<!-- Filters -->
		<?php
		$filterswitcher = get_post_meta($post->ID, 'pp_filters_switch', true);
		if($filterswitcher != 'no') {
			$filters = get_post_meta($post->ID, 'portfolio_filters', true);
			if(!empty($filters)) { ?>
			<div class="showing"><?php _e('Showing:','purepress') ?></div>
			<span class="line showing"></span>
			<div id="filters" class="filters-dropdown headline"><span></span>
				<ul class="option-set" data-option-key="filter">
					<li><a href="#filter" class="selected" data-option-value="*"><?php  _e('All', 'purepress'); ?></a></li>
					<?php
					foreach ( $filters as $id ) {
						$term = get_term( $id, 'filters' );
						echo '<li><a href="#filter" data-option-value=".'.$term->slug.'">'. $term->name .'</a></li>';

					} ?>
				</ul>
			</div>
			<span class="line filters"></span><div class="clearfix"></div>
			<?php } }
			if(!is_tax()) {
				$terms = get_terms("filters");
				$count = count($terms);
				if ( $count > 0 ){ ?>
				<div class="showing">Showing:</div>
				<span class="line showing"></span>
				<div id="filters" class="filters-dropdown headline">
					<ul class="option-set" data-option-key="filter">
						<li><a href="#filter" class="selected" data-option-value="*"><?php  _e('All', 'purepress'); ?></a></li>
						<?php
						foreach ( $terms as $term ) {
							echo '<li><a href="#filter" data-option-value=".'.$term->slug.'">'. $term->name .'</a></li>';
						} ?>
					</ul>
				</div>
				<span class="line filters"></span><div class="clearfix"></div>
				<?php }
			} ?>

		</div>
	</div>
	<!-- Container / End -->
	<?php if($ajax_status == 'yes') { ?>
	<div id="portfolio_ajax_wrapper">

		<div class="container">
			<div id="portfolio_ajax" class="columns sixteen  omega">
					<a href="#" class="leftarrow ajaxarrows"><i class="icon-chevron-left"></i></a>
					<a href="#" class="rightarrow ajaxarrows"><i class="icon-chevron-right"></i></a>
					<a href="#" class="close"><i class="icon-remove"></i></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<!-- 960 Container -->
	<div class="container">

		<!-- Portfolio Content -->
		<div id="portfolio-wrapper" class="portfolio-nd-4col">
			<!-- Post -->
      <?php
      
      $currentUrl = $_SERVER['REQUEST_URI'];
			$pattern = '/\/filters\/(.*?)\//';
			preg_match($pattern, $currentUrl, $matches);
			$filter_domain = $matches[1];

      if(!empty($filter_domain)){
        display_portfolio_items_by_filter();
      }else{
        display_portfolio_items();
      }
      
      ?>
	</div>
	</div></div>
	<div class="clearfix"></div>

<div id="astrum-ajax-loader"><i class="icon-spinner icon-spin icon-large"></i></div>