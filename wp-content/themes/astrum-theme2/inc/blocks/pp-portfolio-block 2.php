<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Portfolio_Block')) {
    class PP_Portfolio_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Portfolio block',
                'size' => 'span12',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Portfolio_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'Recent Work',
                'limit' => '',
                'orderby' => '',
                'queryorder' => '',
                'filters' => '',
                );

            $portfolio_filters = get_terms('filters');
            $filters_options = array();
            foreach($portfolio_filters as $filter) {
                $filters_options[$filter->term_id] = $filter->name;
            }

            $limit_options = array();
            for ($i=0; $i < 25 ; $i++) {
             $limit_options[$i] = $i;
         }

         $instance = wp_parse_args($instance, $defaults);
         extract($instance); ?>
         <p>Note: You should only use this block on a full-width template</p>
         <p class="description half">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Title (required)<br/>
                <?php echo aq_field_input('title', $block_id, $title) ?>
            </label>
        </p>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('limit') ?>">
                Limit (required)<br/>
                <?php echo aq_field_select('limit', $block_id, $limit_options, $limit) ?>
            </label>
        </p>
        <?php $orderby_options = array(
                    'none' => 'none' ,
                    'ID' => 'ID' ,
                    'author' => 'author' ,
                    'title' => 'title' ,
                    'name' => 'name' ,
                    'date' => 'date' ,
                    'modified' => 'modified' ,
                    'parent' => 'parent' ,
                    'rand' => 'rand' ,
                    'comment_count' => 'comment_count' ,
        ); ?>
        <p class="description half ">
            <label for="<?php echo $this->get_field_id('orderby') ?>">
                Orderby<br/>
                <?php echo aq_field_select('orderby', $block_id, $orderby_options, $orderby) ?>
            </label>
        </p>
              <?php $order_options = array(
                    'ASC' => 'from lowest to highest values (1, 2, 3; a, b, c)' ,
                    'DESC' => 'from highest to lowest values (3, 2, 1; c, b, a)' ,

        ); ?>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('queryorder') ?>">
                Order (ASC/DSC)<br/>
                <?php echo aq_field_select('queryorder', $block_id, $order_options, $queryorder) ?>
            </label>
        </p>
        <p class="description">
            <label for="<?php echo $this->get_field_id('filters') ?>">
                Portfolio Filters (for all leave blank)<br/>
                <?php echo aq_field_multiselect('filters', $block_id, $filters_options, $filters); ?>
            </label>
        </p>
        <?php
    }

    function block($instance) {
        extract($instance);
        $width = AQ_Block::transform_span_to_gs($size);
        $randID = uniqid();
	$isFrontPage = is_front_page() ? 1 : 0;
        ?>
	<script><?php echo '__isFrontPage = '.$isFrontPage.';'; ?></script>
        <div class="">
            <h2 class="headline"><?php echo $title; ?></h2>
            <span class="line" style="margin-bottom:0;"></span>
        </div>

        <!-- ShowBiz Carousel -->
        <div class="showbiz-container recent-work">

            <!-- Navigation -->
            <div class="showbiz-navigation">
                <div class="sb-navigation-left" data-slick-left data-control-id="<?php echo $randID; ?>" ><i class="icon-angle-left"></i></div>
                <div class="sb-navigation-right" data-slick-right data-control-id="<?php echo $randID; ?>"><i class="icon-angle-right"></i></div>
            </div>
            <div class="clearfix"></div>

            <!-- Portfolio Entries -->
            <div class="slider-slick slider--default-gap slider--auto-height slick-slider--home" data-slick-slider data-controls-id="<?php echo $randID; ?>">
                        <?php
                        $args = array(
                            'post_type' => 'portfolio',
                            'posts_per_page' => $limit,
                            'orderby' => $orderby,
                            'order' => $queryorder,
                            );

                        if(!empty($filters)) {

                            $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'filters',
                                    'field' => 'id',
                                    'terms' => $filters
                                    )
                                );
                        }
                        $wp_query = new WP_Query( $args );
                        if($wp_query->found_posts > 1) { $mfpclass= "mfp-gallery"; } else { $mfpclass= "mfp-image"; }
                        if ( $wp_query->have_posts() ):
                            while( $wp_query->have_posts() ) : $wp_query->the_post();
					?>
						
                         <!-- Item -->
                        <div data-slick-slide>
                            <div class="media portfolio-item__hover-control">
                                <figure>
                                    <div class="mediaholder portfolio-block__additional-info-container">
										<button class="portfolio-block__mobile-icon-wrapper portfolio-block__mobile-icon-wrapper--info">
											<div class="portfolio-block__mobile-icon"></div>
										</button>
										
                                        <?php
                                        $thumb = get_post_thumbnail_id();
                                        $img_url = wp_get_attachment_url($thumb);
                                        $lightbox = get_post_meta($wp_query->post->ID, 'pp_pf_lightbox', true);
										$post_id = get_the_ID();
		
										$additional_description = get_post_meta($post_id, 'additional_description', 1);
										$killing_features = get_post_meta($post_id, 'killing_features', 1);
										$customer = get_post_meta($post_id, 'customer', 1);

                                        if($lightbox == 'lightbox') {
                                            $fullsize = wp_get_attachment_image_src($thumb, 'full');
                                            ?>
                                            <a href="<?php echo $fullsize[0]; ?>" class="<?php echo $mfpclass; ?>" title="<?php the_title(); ?>">
                                                <?php the_post_thumbnail('portfolio-4col'); ?>
                                                <div class="hovercover">
                                                    <div class="hovericon"><i class="hoverzoom"></i></div>
                                                </div>
                                            </a>
                                        <?php } else { ?>
                                          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                <?php the_post_thumbnail('portfolio-4col'); ?>
                                                <div class="hovercover">
                                                    <div class="hovericon"><i class="hoverlink"></i></div>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>">
                                        <figcaption class="item-description portfolio-block__description">
											<div class="portfolio-block__additional-info">
												<?php if ( get_userlogin() == 'CEO' ){ ?>
													<?php if ( $customer != ''){ ?>
														<h6>Customer: <?php echo $customer; ?></h6>
													<?php } ?>				
													<?php if ( $additional_description != ''){ ?>
														<h6>Additional Description: <?php echo $additional_description; ?></h6>
													<?php } ?>
													<?php if ( $killing_features != ''){ ?>
														<h6>Killing Features: <?php echo $killing_features; ?></h6>
													<?php } ?>
												<?php } ?>
												<h6>Team size: <?php echo array_sum(get_post_meta( $post_id, 'quantity', 'true' )); ?></h6>
												<h6>Duration: <?php echo get_duration($post_id);?></h6>
											</div>
											
                                            <p class="item-h5"><?php the_title(); ?></p>
                                            <?php
                                                $terms = get_the_terms( $wp_query->post->ID, 'filters' );
                                                $output = '';
                                                if ( $terms && ! is_wp_error( $terms ) ) : $output .= '<span>';
                                                    $filters = array();
                                                    $i = 0;
                                                    foreach ( $terms as $term ) {
                                                        $filters[] = $term->name;
                                                        if ($i++ > 0) break;
                                                    }
                                                    $outputfilters = join( ", ", $filters );
                                                    $output .= $outputfilters;
                                                $output .= '</span>';
                                                endif;
                                                echo $output;
                                            ?>
                                        </figcaption>
                                    </a>
                                </figure>
                            </div>
                        </div>
                    <?php   endwhile;  // close the Loop
                        endif; ?>
  
<!--                     <div class="clearfix"></div>

  
                <div class="clearfix"></div> -->

            </div>
        </div>
            <?php
        }


        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}
