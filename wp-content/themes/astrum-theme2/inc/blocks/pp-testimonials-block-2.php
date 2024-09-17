<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Testimonials_Block2')) {
    class PP_Testimonials_Block2 extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Testimonials2',
                'size' => 'span8',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Testimonials_Block2', $block_options);
        }

        function form($instance) {

            $defaults = array(
             'limit'=>'4',
             'title' => 'Testimonials',
             'orderby' => 'date',
             'queryorder' => 'ASC',
             'type' => 'standard',
             'testimonials' => ''
             );

        $wp_query = new WP_Query(
            array(
                'post_type' => array('testimonial'),
                'showposts' => 99,
                )
            );
        $testgroup = array();
        while( $wp_query->have_posts() ) : $wp_query->the_post();
        $testgroup[$wp_query->post->ID] = get_the_title();
        endwhile;
            $limit_options = array();
            for ($i=0; $i < 25 ; $i++) {
             $limit_options[$i] = $i;
         }

         $instance = wp_parse_args($instance, $defaults);
         extract($instance); ?>
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
                <?php $type_options = array(
                    'standard' => 'Standard' ,
                    'happy' => 'Wide testimonials with logo' ,

                    ); ?>
                <p class="description half ">
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

		
		
function block($instance) {
                    extract($instance);
                    $width = AQ_Block::transform_span_to_gs($size);
                    $randID = rand(1, 99);
                     if(!empty($testimonials)) {
                    $args = array(
                         'post_type' => array('testimonial'),
                            'showposts' => -1,//$limit,
                            'orderby' => $orderby,
                            'order' => $queryorder,
                            'post__in' => $testimonials,
                        );
                    } else {
                       $args = array(
                         'post_type' => array('testimonial'),
                            'showposts' => -1,//$limit,
                            'order' => $queryorder,
                            'orderby' => $orderby
                        );
                   }
$wp_query = new WP_Query($args); ?>

<div>
   <?php /*<h3 class="headline" data-toc-numbers="true"><?php echo $title; ?></h3>
    <span class="line" style="margin-bottom:0;"></span>
	<div class="clearfix"></div>*/ ?>

    <!-- Entries -->

		<?php if ( $wp_query->have_posts() ):
	
            while( $wp_query->have_posts() ) : $wp_query->the_post();

               $id = $wp_query->post->ID;
               $author = get_post_meta($id, 'pp_author', true);
               $link = get_post_meta($id, 'pp_link', true);
               $position = get_post_meta($id, 'pp_position', true);
			   $project_info = get_post_meta($id, 'pp_project_info', true);
			   $second_content = get_post_meta($id, 'pp_second_content', true);
				if ( empty( get_the_post_thumbnail_url() ) ) {
					$img_url = 'https://jazzteam.org/wp-content/uploads/2015/10/nameless.png';
				} else {
					$img_url = get_the_post_thumbnail_url();
				}
			echo '<div class="astrum-testimonials-block">';
			echo '<div class="astrum-testimonials-block-img"><img src="'.$img_url.'" alt="'.$author.'"></div>';
            echo '<div class="astrum-testimonials-block-content">';   
			if ( $link ) {
                echo ' <div class="astrum-testimonials-block-name"><span class="astrum-testimonials-block-orange"><a href="'.$link.'">'.$author.'</a></span>';
            } else {
                echo ' <div class="astrum-testimonials-block-name"><span class="astrum-testimonials-block-orange">'.$author.'</span>';
            }
	
            if ( !empty($position) ) { 
				echo '<div class="astrum-testimonials-position">'.$position.'</div>'; 
			}
			if ( !empty($project_info) ) {
				echo '<div class="astrum-testimonials-project-info">'.$project_info.'</div>'; 
			}
            echo '</div>';
			echo '<div class="astrum-testimonials-block-text">'.$second_content.'</div>';
            echo '</div>'; 
			echo '</div>'; 

			endwhile; 
			wp_reset_query();  // close the Loop
            endif;  ?>
			
</div>

<?php }


		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}
	}
}
