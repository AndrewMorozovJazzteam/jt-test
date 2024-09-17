<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Testimonials_Block')) {
    class PP_Testimonials_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Testimonials',
                'size' => 'span8',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Testimonials_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
             'limit'=>'4',
			 'dynamic_height' => '0',
			 'dynamic_height_margin' => 25,
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


        		<p class="description last">
            		<label for="<?php echo $this->get_field_id('dynamic_height_margin') ?>">
                		Dynamic height margin bottom<br/>
                		<input id="<?php echo $this->get_field_id('dynamic_height_margin') ?>" class="input-full" type="number" min="0" max="999"  value="<?php echo $dynamic_height_margin ?>"  name="<?php echo $this->get_field_name('dynamic_height_margin') ?>">
            		</label>
        		</p>

				<p class="description last">
					<label for="<?php echo $this->get_field_id('dynamic_height') ?>">
					<?php echo aq_field_checkbox('dynamic_height', $block_id, $dynamic_height) ?>
						is dynamic height active
					</label>
				</p>

                    <?php
                }

                function block($instance) {
                    extract($instance);
                    $width = AQ_Block::transform_span_to_gs($size);
                    $randID = rand(1, 99);
					$dynamic_height_class = $dynamic_height === '0' ? '' : 'strict--auto-height';
					
					$dynamic_height_margin_attr = $dynamic_height_margin ? 'data-margin-bottom=' . $dynamic_height_margin : '';
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
                        ?>
<?php if($type == 'standard') { ?>
<div class="testimonials_wrap showbiz-container">
    <h2 class="headline" data-toc-numbers="true"><?php echo $title; ?></h2>
    <span class="line" style="margin-bottom:0;"></span>

    <!-- Navigation -->
    <div class="showbiz-navigation">
        <div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
        <div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
    </div>
    <div class="clearfix"></div>

    <!-- Entries -->
    <div class="showbiz" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
        <div class="overflowholder <?=$dynamic_height_class;?>" <?=$dynamic_height_margin_attr;?> >
            <ul>
               <?php if ( $wp_query->have_posts() ):
               while( $wp_query->have_posts() ) : $wp_query->the_post();

               $id = $wp_query->post->ID;
               $author = get_post_meta($id, 'pp_author', true);
               $link = get_post_meta($id, 'pp_link', true);
               $position = get_post_meta($id, 'pp_position', true);

               echo '<li class="testimonial">';
               echo '<div class="testimonials">'.get_the_content().'</div><div class="testimonials-bg"></div>';
               if($link) {
                echo ' <div class="testimonials-author"><a href="'.$link.'">'.$author.'</a>';
            } else {
                echo ' <div class="testimonials-author">'.$author;
            }
            if($position) { echo ', <span>'.$position.'</span>'; }
            echo '</div></li>';
                endwhile; wp_reset_query();  // close the Loop
                endif;  ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<?php } else { ?>
<div class="happywrapper ">
    <!-- Navigation / Left -->
    <div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left-2 alt"><i class="icon-angle-left"></i></div>

    <!-- ShowBiz Carousel -->
    <div id="happy-clients" class="happy-clients showbiz-container  carousel " >

        <!-- Portfolio Entries -->
        <div class="showbiz our-clients" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
            <div class="overflowholder">
                <ul>
<?php if ( $wp_query->have_posts() ):
while( $wp_query->have_posts() ) : $wp_query->the_post();

$id = $wp_query->post->ID;
$author = get_post_meta($id, 'pp_author', true);
$link = get_post_meta($id, 'pp_link', true);
$position = get_post_meta($id, 'pp_position', true);
echo '<li>';
echo '<div class="happy-clients-photo">'. get_the_post_thumbnail($wp_query->post->ID,'portfolio-thumb').'</div>';
echo '<div class="happy-clients-cite">'.get_the_content().'</div>';
if($link) {
   echo ' <div class="happy-clients-author"><a href="'.$link.'">'.$author.'</a>';
} else {
    echo ' <div class="happy-clients-author">'.$author;
}
echo '</li>';

endwhile;  wp_reset_query(); // close the Loop
endif;  ?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right-2 alt"><i class="icon-angle-right"></i></div>
</div>
<?php }
}


function update($new_instance, $old_instance) {
    $new_instance = aq_recursive_sanitize($new_instance);
    return $new_instance;
}
}
}
