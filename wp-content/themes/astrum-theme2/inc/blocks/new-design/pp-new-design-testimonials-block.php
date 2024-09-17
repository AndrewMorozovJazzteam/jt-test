<?php
/* Aqua Testimonials Block - PureThemes */
if(!class_exists('PP_New_Design_Testimonials_Block')) {
    class PP_New_Design_Testimonials_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Testimonials',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_New_Design_Testimonials_Block', $block_options);
        }
        

        function form($instance) {

            $defaults = array(
                'title' => 'Testimonials',
                'description' => '',
                'limit'=>'4',
                'orderby' => 'date',
                'queryorder' => 'ASC',
                'type' => 'standard',
                'testimonials' => '',
				'mobileslide' => '',
				'tabletslide' => '',
				'desktopslide' => '',
				'mainheading' => 0,
            );


            $wp_query = new WP_Query(array('post_type' => array('testimonial'), 'showposts' => 99));
            $testgroup = array();
            while( $wp_query->have_posts() ) : $wp_query->the_post();
            $testgroup[$wp_query->post->ID] = get_the_title();
            endwhile;
                $limit_options = array();
                for ($i=0; $i < 25 ; $i++) {
                 $limit_options[$i] = $i;
             }
             

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); 
            

            $orderby_options = array(
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
            );

            $order_options = array(
                'ASC' => 'from lowest to highest values (1, 2, 3; a, b, c)' ,
                'DESC' => 'from highest to lowest values (3, 2, 1; c, b, a)' ,
            );

            $type_options = array(
                'standard' => 'Standard' ,
                'happy' => 'Wide testimonials with logo' ,

            );

            ?>
            <p class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (optional)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </p>

            <p class="description">
                <label for="<?php echo $this->get_field_id('description') ?>">
                    Description (optional)<br/>
					<?php echo aq_field_textarea('description', $block_id,  $description); ?>
                </label>
            </p>

            <p class="description half">
                <label for="<?php echo $this->get_field_id('limit') ?>">
                    Limit (required)<br/>
                    <?php echo aq_field_select('limit', $block_id, $limit_options, $limit) ?>
                </label>
            </p>

            <p class="description half last">
                <label for="<?php echo $this->get_field_id('orderby') ?>">
                    Orderby<br/>
                    <?php echo aq_field_select('orderby', $block_id, $orderby_options, $orderby) ?>
                </label>
            </p>

            <p class="description half">
                <label for="<?php echo $this->get_field_id('queryorder') ?>">
                    Order (ASC/DSC)<br/>
                    <?php echo aq_field_select('queryorder', $block_id, $order_options, $queryorder) ?>
                </label>
            </p>

            <p class="description half last">
                <label for="<?php echo $this->get_field_id('type') ?>">
                    Testimonials style (ASC/DSC)<br/>
                    <?php echo aq_field_select('type', $block_id, $type_options, $type) ?>
                </label>
            </p>



            <p class="mobile slide">
                <label for="<?php echo $this->get_field_id('mobileslide') ?>">
                    565:<br/>
                    <?php echo aq_field_input('mobileslide', $block_id, $mobileslide) ?>
                </label>
            </p>
            <p class="tablet slide">
                <label for="<?php echo $this->get_field_id('tabletslide') ?>">
                    768:<br/>
                    <?php echo aq_field_input('tabletslide', $block_id, $tabletslide) ?>
                </label>
            </p>
            <p class="desktop slide">
                <label for="<?php echo $this->get_field_id('desktopslide') ?>">
                    1024:<br/>
                    <?php echo aq_field_input('desktopslide', $block_id, $desktopslide) ?>
                </label>
            </p>

            <p class="description half last">
                <label for="<?php echo $this->get_field_id('filters') ?>">
                    Select testimonials (for all leave blank)<br/>
                    <?php echo aq_field_multiselect('testimonials', $block_id, $testgroup, $testimonials); ?>
                </label>
				<label for="<?php echo $this->get_field_id('mainheading') ?>">
                    <?php echo aq_field_checkbox('mainheading', $block_id, $mainheading) ?>
                    On heading for main page.
                </label>
            </p>
            
            <?php
        }


        function render_standart_slider($slider_id, $wp_query, $desc, $mobileslide, $tabletslide, $desktopslide){ ?>
           
			<div class="testimonials__slider">
              <div class="testimonials__slider-container-outer">
                <div
                  class="testimonials__slider-container"
                  data-slider-container
                  data-slider-setup='{"slidesPerView":1,"spaceBetween":20,"grid":{"rows":1},"navigation":{"nextEl":".section-testimonials .sdc---next","prevEl":".section-testimonials .sdc---prev"},"breakpoints":{"565":{"slidesPerView":<?php echo $mobileslide;?>},"768":{"slidesPerView":<?php echo $tabletslide;?>},"1024":{"slidesPerView":<?php echo $desktopslide;?>}}}'
                >
                  <div class="testimonials__slider-wrapper swiper-wrapper swiper-wrapper--auto-height">
                        <?php if ( $wp_query->have_posts() ):
                           while( $wp_query->have_posts() ) : $wp_query->the_post();
                        
                                $id = $wp_query->post->ID;
                                $author = get_post_meta($id, 'pp_author', true);
                                $link = get_post_meta($id, 'pp_link', true);
                                $position = get_post_meta($id, 'pp_position', true);
								$thumb = get_post_thumbnail_id();
								$img_url = wp_get_attachment_url($thumb);	
								$second_content = get_post_meta($id, 'pp_second_content', true);
								$position_prettified = str_replace('<a', '<a class="link"', $position);
								?>
					  				<div class="testimonials__slider-slide swiper-slide">
									  <div class="testimonials__slider-slide-content">
										<div class="testimonials__slider-slide-content__testimonial-content">
										  <div class="testimonials__slider-slide-img">
											<img src="<?php echo $img_url; ?>" alt="<?php echo $author; ?>" width="80" height="80" />
										  </div>
											<span class="text-second text-second--gray"><?php echo get_the_content();?></span>
										</div>
										<span class="text-second testimonials__slider-slide__testimonial-author">
											<a class="link" target="_blank" href="<?php echo $link; ?>"><?php echo $author; ?>, </a><span><?php echo $position_prettified; ?></span>
										</span>
									  </div>
									</div>
					  			<?php

                            endwhile; wp_reset_query(); 
                        endif;  ?>
				  </div>
                </div>
              </div>
            </div>


            <?php
        }
    

        function block($instance) {
            extract($instance);

            do_action( 'register_block_styles');

            $slider_id = uniqid();

            if(!empty($testimonials)) {
                $args = array(
                     'post_type' => array('testimonial'),
                        'showposts' => $limit,
                        'orderby' => $orderby,
                        'order' => $queryorder,
                        'post__in' => $testimonials,
                    );
                } else {
                   $args = array(
                     'post_type' => array('testimonial'),
                        'showposts' => $limit,
                        'order' => $queryorder,
                        'orderby' => $orderby
                    );
               }

            $wp_query = new WP_Query($args);


            //$block_prefix = 'modern-testimonials-block';
            ?>


	 		<section class="section section-testimonials">
				<div class="heading-wrapper">
				<?php if($mainheading == 1){ ?>
				  <h2 class="headline-second"><?php echo $title; ?></h2>
				<?php } else {?>
					<div class="project-headline"><h2 class="headline" data-toc-numbers="true"><?php echo $title; ?></h2></div>
				<?php } ?>
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

            	<?php if($type === 'standard'){ 
                	$this -> render_standart_slider($slider_id, $wp_query, $description, $mobileslide, $tabletslide, $desktopslide);
           		} ?>

            </section>

            <?php
        }

        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}