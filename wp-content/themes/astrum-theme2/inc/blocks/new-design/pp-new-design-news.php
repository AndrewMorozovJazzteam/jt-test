<?php
/* Aqua ND NEWS - PureThemes */
if(!class_exists('PP_New_Design_News')) {
    class PP_New_Design_News extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'ND News',
                'size' => 'span16',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_New_Design_News', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'Recent News',
                'limit' => '',
                'orderby' => '',
                'queryorder' => '',
                'categories' => array(),
                'tags' => array(),
                );

            $post_categories = ($temp = get_terms('category')) ? $temp : array();
            $categories_options = array();
            foreach($post_categories as $cat) {
                $categories_options[$cat->term_id] = $cat->name;
            }

            $post_tags = ($temp = get_terms('post_tag')) ? $temp : array();
            $tags_options = array();
            foreach($post_tags as $tag) {
                $tags_options[$tag->term_id] = $tag->name;
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
        <p class="description half">
                <label for="<?php echo $this->get_field_id('categories') ?>">
                Posts Categories (leave empty to display all)<br/>
                <?php echo aq_field_multiselect('categories', $block_id, $categories_options, $categories); ?>
                </label>
            </p>
            <p class="description half last">
                <label for="<?php echo $this->get_field_id('types') ?>">
                Posts Tags (leave empty to display all)<br/>
                <?php echo aq_field_multiselect('tags', $block_id, $tags_options, $tags); ?>
                </label>
            </p>

        <?php
    }

    function block($instance) {
        extract($instance);
        $width = AQ_Block::transform_span_to_gs($size);
        $randID = rand(1, 99);
        ?>
		<section class="section section-news">			
            <div class="heading-wrapper">
              <h2 class="headline-second"><?php echo $title; ?></h2>
              <div class="slider-double-controls">
                <button class="btn slider-double-controls__control sdc---prev">
                  <svg height="16" viewBox="0 0 11 16" fill="none">
                    <path
                      d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
                      fill="currentColor"
                    />
                  </svg>
                </button>
                <button class="btn slider-double-controls__control slider-double-controls__control--next sdc---next">
                  <svg height="16" viewBox="0 0 11 16" fill="none">
                    <path
                      d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
                      fill="currentColor"
                    />
                  </svg>
                </button>
              </div>
            </div>			


            <!-- Portfolio Entries -->
            <div class="news__slider">
              <div class="news__slider-container-outer">
                <div
                  class="news__slider-container"
                  data-slider-container
                  data-slider-setup='{"slidesPerView":1,"spaceBetween":20,"navigation":{"nextEl":".section-news .sdc---next","prevEl":".section-news .sdc---prev"},"breakpoints":{"565":{"slidesPerView":2},"768":{"slidesPerView":3},"1024":{"slidesPerView":4}}}'
                >
                  	<div class="news__slider-wrapper swiper-wrapper swiper-wrapper--auto-height">
                        <?php
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $limit,
                            'orderby' => $orderby,
                            'queryorder' => $queryorder,
                            );
                        if($categories) $args['category__in'] = $categories;
                        if($tags) $args['tag__in'] = $tags;
						


                        $wp_query = new WP_Query( $args );
                        if ( $wp_query->have_posts() ):
                            while( $wp_query->have_posts() ) : $wp_query->the_post();
                        ?>
						<div class="news__slider-slide swiper-slide">
						  
						  <a href="<?php the_permalink(); ?>" class="news__slider-slide-content">
							<?php
								$thumb = get_post_thumbnail_id();
                $custom_post_image_url = get_post_meta( get_the_ID(), 'custom_post_image_url', true );
								$img_url =  !empty( $custom_post_image_url ) ? esc_url( $custom_post_image_url ) : wp_get_attachment_url($thumb);

							?>

							<div class="news__slider-slide-image-wrapper">
 							    <img class="news__slider-slide-img" height="200" width="285" src="<?php echo  $img_url;?>" alt="<?php the_title(); ?>" />
							  </div>
							  
							<div class="news__slider-slide-text-wrapper">
							  <span class="headline-four headline-four--color-inherit news__slider-slide-title">
								<?php echo get_the_title();?>
							  </span>
								<?php
								$time_string = '%2$s';
								$time_string = sprintf( $time_string,esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );                            
								?>
							  <div class="news__slider-slide-text__tags"><?php echo $time_string; ?></div>
							</div>
						  </a>
						
						 </div>	
                    <?php   endwhile;  // close the Loop
                        endif; ?>

						
				  </div>
                </div>
              </div>
            </div>
	 	</section>		
            <?php
        }


        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}
