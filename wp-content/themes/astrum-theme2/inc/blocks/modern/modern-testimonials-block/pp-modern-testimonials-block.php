<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Modern_Testimonials_Block')) {
    class PP_Modern_Testimonials_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(M) Testimonials',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Modern_Testimonials_Block', $block_options);
        }
        

        function form($instance) {

            $defaults = array(
                'title' => 'Testimonials',
                'description' => '',
                'limit'=>'4',
                'orderby' => 'date',
                'queryorder' => 'ASC',
                'type' => 'standard',
                'testimonials' => ''
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

            <p class="description half last">
                <label for="<?php echo $this->get_field_id('filters') ?>">
                    Select testimonials (for all leave blank)<br/>
                    <?php echo aq_field_multiselect('testimonials', $block_id, $testgroup, $testimonials); ?>
                </label>
            </p>
            
            <?php
        }


        function render_standart_slider($slider_id, $wp_query, $desc){
            ?>
                <!-- Navigation -->
                <div class="modern-testimonials-block__nav">
                    <div class="sb-navigation-left" data-slick-left data-control-id="<?php echo $slider_id; ?>"><i class="icon-angle-left"></i></div>
                    <div class="sb-navigation-right" data-slick-right data-control-id="<?php echo $slider_id; ?>"><i class="icon-angle-right"></i></div>
                </div>
                <div class="clearfix"></div>

				<?php if($desc) {?>
						<div class="description modern-testimonials-block__description" >
    			           <?php echo htmlspecialchars_decode($desc); ?>
    			        </div>
				<?php } ?>

                <!-- Entries -->
                <div class="slider-slick slider--default-gap slick-slider--home" data-syntatic-margin data-slick-slider data-swipe="true" data-desktop-count="2" data-tablet-count="1" data-mobile-count="1" data-controls-id="<?php echo $slider_id; ?>">
                        <?php if ( $wp_query->have_posts() ):
                           while( $wp_query->have_posts() ) : $wp_query->the_post();
                        
                                $id = $wp_query->post->ID;
                                $author = get_post_meta($id, 'pp_author', true);
                                $link = get_post_meta($id, 'pp_link', true);
                                $position = get_post_meta($id, 'pp_position', true);

                                echo '<div data-slick-slide><div class="modern-testimonials-block__testimonial">';
                                echo '<div class="modern-testimonials-block__testimonial-content">'.get_the_content().'</div>';

                                if($link) {
                                     echo '<div class="modern-testimonials-block__testimonial-author"><div class="modern-testimonials-block__testimonial-author-content"><a href="'.$link.'">'.$author.'</a>';
                                 } else {
                                     echo '<div class="modern-testimonials-block__testimonial-author"><div class="modern-testimonials-block__testimonial-author-content">'.$author;
                                 }
                             
                                 if($position) { 
                                     echo ', <span>'.$position.'</span>';
                                 }
                                 echo '</div></div></div></div>';

                            endwhile; wp_reset_query(); 
                        endif;  ?>
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


            $block_prefix = 'modern-testimonials-block';
            ?>


	        <section class="modern-testimonials-block"  <?php echo 'data-' . $block_prefix; ?>>
				
			   <h2 class="headline"><?php echo $title; ?></h2>
               <span class="line" style="margin-bottom:0;"></span>
				<div class="clearfix"></div>

            	<?php if($type === 'standard'){
                	$this -> render_standart_slider($slider_id, $wp_query, $description);
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