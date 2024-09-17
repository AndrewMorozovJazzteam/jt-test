<?php
if(!class_exists('PP_Portfolio_Slider_Block')) {
    class PP_Portfolio_Slider_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Portfolio Slider',
                'size' => 'span12',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Portfolio_Slider_Block', $block_options);

            add_action('wp_ajax_aq_block_project_add_new', array($this, 'add_new_project'));
        }

        function form($instance) {
            $defaults = array(
                'title' => 'Our Projects',
                'limit' => '16',
                'orderby' => '',
                'queryorder' => '',
                'filters' => '',
                'boxes' => array()
            );


            
            $projects_filters = get_posts( array('post_type' => 'portfolio', 'posts_per_page' => -1) );
            $projects_options = array();
            
            foreach($projects_filters as $project){
                $projects_options[$project -> ID] = $project->post_title;
            }
            
            $portfolio_filters = get_terms('filters');
            $filters_options = array();

            foreach($portfolio_filters as $filter) {
                $filters_options[$filter->term_id] = $filter->name;
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
                <input id="<?php echo $this->get_field_id('limit') ?>" class="input-full" type="number" min="1" max="999"  value="<?php echo $limit ?>"  name="<?php echo $this->get_field_name('limit') ?>">
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
                    'comment_count' => 'comment count' ,
			 		'meta_value_num' => 'weights',
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
        <p class="description ">
            <label for="<?php echo $this->get_field_id('filters') ?>">
                Filters by business domain (for all leave blank)<br/>
                <?php echo aq_field_multiselect('filters', $block_id, $filters_options, $filters); ?>
            </label>
        </p>
        <div class="description cf">
              Select portfolio<br/>
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $boxes = is_array($boxes) ? $boxes : $defaults['boxes'];
                    $count = 1;
                    foreach($boxes as $box) {
                        $this->box($box, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="project" class="aq-sortable-add-new button">Add New</a>
                <p></p>
				<script>
					
					<?php
					$raw_options = array();
        			foreach($projects_filters as $project){
        			    array_push($raw_options, array ('project_id' => $project -> ID, 'label' => $project -> post_title));
        			}
					?>
					var __allProjects= <?php echo json_encode($raw_options); ?>;
					
					function initUniqueSelects(){
// 					const selects = document.querySelectorAll("#aq-sortable-list-<?php echo $block_id ?> select");
// 					const selectsBuf = [...selects].forEach(select => select.addEventListener('change', handleChangeSelect));
// 						function handleChangeSelect(evt){
// 							[...selects].forEach((sel) =>{
// 								const ignoredValues = [...sel.querySelectorAll('option[selected]')].map(el => el.value);
// 								ignoredValues.forEach(value => {
// 									const el = sel.querySelectorAll(`option[value="${value}"]`);
// 									el.forEach(opt => {
// 										console.log(!!opt.getAttribute('selected'));
// 										if(!opt.getAttribute('selected')){
// 											console.log(opt)
// 										}
// 									})
// 								});
// 							 }
// 							);
// 						}
					}
					
					initUniqueSelects();
				</script>
            </div>

        <p style="color: #dba617; font-weight: 700;">Filters won't apply if portfolio is selected</p>
        <?php
    }

    function box($box = array(), $count = 0) {
        $projects_filters = get_posts( array('post_type' => 'portfolio', 'posts_per_page' => -1) );
        $projects_options = array();
        $projects_labels_tree = array();

        foreach($projects_filters as $project){
            array_push($projects_options, array ('project_id' => $project -> ID, 'label' => $project -> post_title));
            $projects_labels_tree[$project -> ID] = $project->post_title;
        }

        ?>
        <li id="<?php echo $this->get_field_id('boxes') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

            <div class="sortable-head cf">
                <div class="sortable-title">
                    <strong><?php echo $projects_labels_tree[$box['id']] ?></strong>
                </div>
                <div class="sortable-handle">
                    <a href="#">Open / Close</a>
                </div>
            </div>

            <div class="sortable-body">
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-id">
                        Project<br/>
                            <select style="min-width:100%;" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][id]" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-id">
                                <?php
                                foreach ($projects_options as $project) {
                                    if ( $box['id'] == $project['project_id']) { 
                                        $sel = "selected"; 
                                    } else { 
                                        $sel = '';
                                    }
                                    echo '<option '.$sel.' value="'.$project['project_id'].'">'.$project['label'].'</option>';
                                } ?>
                            </select>
                        </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>

            </li>
            <?php
        }

    function block($instance) {
        extract($instance);
        $width = AQ_Block::transform_span_to_gs($size);
        $randID = rand(1, 99);			
	$isFrontPage = is_front_page() ? 1 : 0;
        ?>
	<script><?php echo '__isFrontPage = '.$isFrontPage.';'; ?></script>
        <div class="headline-through">
            <h3 class="headline"><?php echo $title; ?></h3>
            <span class="line" style="margin-bottom:0;"></span>
        </div>
        <!-- ShowBiz Carousel -->
        <div class="showbiz-container recent-work">

            <!-- Navigation -->
            <div class="showbiz-navigation">
                <div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
                <div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
            </div>
            <div class="clearfix"></div>

            <!-- Portfolio Entries -->
            <div class="showbiz" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
              <div class="overflowholder">
                    <ul>
                        <?php
		               $args = array(
    						'post_type' => 'portfolio',
						    'posts_per_page' => $limit,
    						'meta_key' => 'weights',
   							'orderby' => $orderby,
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
                      }

                        $wp_query = new WP_Query( $args );

                        if($wp_query->found_posts > 1) { $mfpclass= "mfp-gallery"; } else { $mfpclass= "mfp-image"; }
                        if ( $wp_query->have_posts() ):
                            while( $wp_query->have_posts() ) : $wp_query->the_post();
                        ?>
                        <li>
                            <div class="portfolio-item media">
                                <figure>
                                    <div class="mediaholder">
                                        <?php
                                        $thumb = get_post_thumbnail_id();
                                        $img_url = wp_get_attachment_url($thumb);
                                        $lightbox = get_post_meta($wp_query->post->ID, 'pp_pf_lightbox', true);

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
                                        <figcaption class="item-description">
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
                        </li>
                    <?php   endwhile;  // close the Loop
                        endif; ?>
                    </ul>
                    <div class="clearfix"></div>

                </div>
                <div class="clearfix"></div>

            </div>
        </div>
            <?php
        }

        function add_new_project() {
          $nonce = $_POST['security'];
          if (!wp_verify_nonce($nonce, 'aqpb-settings-page-nonce')) {
              die('-1');
          }
      
          $count = isset($_POST['count']) ? absint($_POST['count']) : false;
          $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
      
          // Get existing project IDs from $boxes array
          $existing_project_ids = array();
          $boxes = isset($this->instance['boxes']) ? $this->instance['boxes'] : array();
          if (is_array($boxes)) {
              foreach ($boxes as $box) {
                  if (isset($box['id'])) {
                      $existing_project_ids[] = $box['id'];
                  }
              }
          }
      
          $projects = get_posts(array('post_type' => 'portfolio', 'posts_per_page' => -1));
          $project_ids = wp_list_pluck($projects, 'ID');
 
          // Find available project IDs that don't exist in $boxes array
          $available_project_ids = array_diff($project_ids, $existing_project_ids);
      
          if (empty($available_project_ids)) {
              die('-1'); // No available project IDs
          }
      
          // Shuffle the available project IDs
          shuffle($available_project_ids);
      
          // Choose a random project ID
          $new_project_id = array_shift($available_project_ids);
      
          // Create a new box with the chosen project ID
          $box = array(
              'id' => $new_project_id
          );
      
          if ($count) {
              $this->box($box, $count);
          } else {
              die('-1');
          }
      
          die();
      }
      
      

        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}
