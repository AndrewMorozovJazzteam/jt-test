<?php
/* Aqua Recent Project - PureThemes */
if(!class_exists('PP_New_Design_Recent_Project')) {
    class PP_New_Design_Recent_Project extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'ND Recent Project',
                'size' => 'span16',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_New_Design_Recent_Project', $block_options);
			
			add_action('wp_ajax_aq_block_project_add_new', array($this, 'add_new_project'));
            add_action('wp_ajax_aq_block_custom-case_add_new', array($this, 'add_new_custom_case'));
        }

        function form($instance) {

            $defaults = array(
                'title' => 'Recent Work',
				'description' => '',
                'limit' => '',
                'orderby' => '',
                'queryorder' => '',
                'filters' => '',
				'boxes' => array(),
				'meta_value_num' => 'weights',
                'custom_cases' => array(),
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
         <p class="description">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Title (required)<br/>
                <?php echo aq_field_input('title', $block_id, $title) ?>
            </label>
        </p>



        <p class="description">
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
              Custom Select portfolio<br/>
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
				</script>
            </div>

            <div class="description cf">
              Create cases as portfolio<br/>
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $custom_cases = is_array($custom_cases) ? $custom_cases : $defaults['custom_cases'];
                    $count = 1;
                    foreach($custom_cases as $box) {
                        $this->render_admin_custom_cases($box, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="custom-case" class="aq-sortable-add-new button">Add New</a>
                <p></p>
            </div>
        <p><span style="color: #dba617; font-weight: 700;">Note:</span> <span>If custom select portfolio is selected then the filters will not work. Only when both custom select portfolio and create cases as portfolio are NOT selected will the filters work properly.</span></p>
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


    function render_admin_custom_cases($custom_case = array(), $count = 0) {
        ?>
        <li id="<?php echo $this->get_field_id('custom_cases') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

            <div class="sortable-head cf">
                <div class="sortable-title">
                    <strong><?php echo $custom_case['title'] ?></strong>
                </div>
                <div class="sortable-handle">
                    <a href="#">Open / Close</a>
                </div>
            </div>

            <div class="sortable-body">
                <p class="tab-desc half description">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title">
                      Title(required)<br/>
                      <input type="text" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][title]" value="<?php echo $custom_case['title'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc half last description">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-link">
                      link(required)<br/>
                      <input type="text" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][link]" value="<?php echo $custom_case['link'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-img_url">
                            Client logo<br/>
                            <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-img_url" class="input-full"  name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][img_url]" value="<?php echo $custom_case['img_url'] ?>" />
                            <a rel="image" class="aq_upload_button button" href="#">Upload</a>
                        </label>
                </p>
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('custom_cases') ?>_<?php echo $count ?>_with_info">
                        <input type="hidden" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][with_info]" value="0" />
		                <input type="checkbox" id="<?php echo $this->get_field_id('custom_cases') ?>_<?php echo $count ?>_with_info" class="input-checkbox" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][with_info]" <?=checked( 1, $custom_case['with_info'], false )?> value="1"/>
                        With info box 
                    </label>
                </p>
                 <div style=" padding: 10px; border: 1px solid rgb(216 216 216); border-radius: 5px; ">
                 <h4 style="margin: 0 0 10px;">Info box content</h4>
                 <p class="tab-desc description half">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-team_size">
                      Team size<br/>
                      <input type="number" min="0" max="999" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][team_size]" value="<?php echo $custom_case['team_size'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc description half last">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-duration">
                      Duration<br/>
                      <input type="text" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][duration]" value="<?php echo $custom_case['duration'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc description half">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-weights">
                      Weights<br/>
                      <input type="number" min="0" max="999" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][weights]" value="<?php echo $custom_case['weights'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc description half last">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-competition_year">
                      Competition year<br/>
                      <input type="text" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][competition_year]" value="<?php echo $custom_case['competition_year'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc description half">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-customer">
                      Customer<br/>
                      <input type="text" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][customer]" value="<?php echo $custom_case['customer'] ?>" />
                  </label>
                 </p>
                 <p class="tab-desc description half last">
                  <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-tags">
                      Tags<br/>
                      <input type="text" id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][tags]" value="<?php echo $custom_case['tags'] ?>" />
                  </label>
                 </p>
                <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-killing_features">
                          Description(only for CEO)<br/>
                          <textarea id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-killing_features" class="textarea-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][killing_features]" rows="5"><?php echo $custom_case['killing_features'] ?></textarea>
                      </label>
                </p>
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-additional_description">
                        Additional Features(only for CEO)<br/>
                        <textarea id="<?php echo $this->get_field_id('custom_cases') ?>-<?php echo $count ?>-additional_description" class="textarea-full" name="<?php echo $this->get_field_name('custom_cases') ?>[<?php echo $count ?>][additional_description]" rows="5"><?php echo $custom_case['additional_description'] ?></textarea>
                    </label>
                </p>
                 </div>
                <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
            </div>
            
        </li>
        <?php
    }

    // $forms - массив, содержащий три формы слова для склонения. единственное число, множественное число, а третий - множественное число для остальных чисел
    // $n -  число, для которого нужно склонить слово
      function pluralize_text($n, $forms) {
      return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
    }

    function render_project($title, $img_url, $link, $team_size, $duration, $competition_year, $customer, $killing_features, $additional_description, $weights, $tags, $with_info){
        $team_size_prettified = $team_size . ' ' . $this->pluralize_text($team_size, ['member', 'members', 'members']);
        $duration_prettified = str_replace(array('years', 'months', 'days', 'hours', 'minutes', 'seconds'), array('yrs', 'mo', 'd', 'h', 'm', 's'), $duration);
        ?>
        	 <div class="recent-project__slider-slide swiper-slide">
						<a href="<?=$link?>" class="recent-project__slider-slide-content">
						  <div class="recent-project__slider-slide-img-wrapper"><div class="recent-project__slider-slide-img-bg"></div>
						  <img 
							class="recent-project__slider-slide-img"
							src="<?=$img_url?>"
							alt="<?=$title?>"
							height="204"
							width="285"
						  /></div>
			        <div class="recent-project__slider-slide-text-wrapper">
                <?php if($with_info == '1') { ?>
                    <div class="recent-project__slider-info">
								<div class="recent-project__slider-info-splitted">
									<div class="recent-project__slider-info-column">
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Team size"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=$team_size_prettified;?>"></span>
										</div>
										<?php if ( is_user_logged_in() ) { ?>
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Completion year"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=$competition_year?>"></span>
										</div>
										<?php } ?>
									</div>
									<div class="recent-project__slider-divider"></div>
									<div class="recent-project__slider-info-column">
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Duration"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=$duration_prettified;?>"></span>
										</div>
										<?php if ( is_user_logged_in() ) { ?>
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Default (weight)"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=$weights?>"></span>
										</div>
										<?php } ?>
									</div>

								</div>

                                <?php if ( get_userlogin() == 'CEO' && ($customer || $killing_features || $additional_description) ) { ?>
										<div class="recent-project__slider-admin-info">
										<?php if ( $customer != ''){ ?>
											<div class="recent-project__slider-info-unit">
												<span class="recent-project__slider-info-unit-title" data-title="Customer"></span>
												<span class="recent-project__slider-info-unit-value" data-value="<?=$customer;?>"></span>
											</div>
										<?php } ?>
										<?php if ( $killing_features != ''){ ?>
											<div class="recent-project__slider-info-unit">
												<span class="recent-project__slider-info-unit-title" data-title="Killing Featured"></span>
												<span class="recent-project__slider-info-unit-value" data-value="<?=$killing_features;?>"></span>
											</div>
										<?php } ?>
											<?php if ( $additional_description != ''){ ?>
												<div class="recent-project__slider-info-unit">
													<span class="recent-project__slider-info-unit-title" data-title="Additional Desc"></span>
													<span class="recent-project__slider-info-unit-value" data-value="<?=$additional_description;?>"></span>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
							</div> 
                <?php } ?>
							<span class="headline-four headline-four--color-inherit">
							  <?=$title?>
							</span>
							<?php if($tags) { ?>
                                <div class="recent-project__slider-slide-text__tags"><?=$tags?></div>
                            <?php } ?>
						  </div>
						</a>
					  </div>
        <?php
    }

    function render_projects_distributor($is_custom, $filters, $limit, $orderby, $queryorder, $boxes){

        if($is_custom){
            foreach($boxes as $box){
                $this->render_project($box['title'], $box['img_url'], $box['link'], $box['team_size'], $box['duration'], $box['competition_year'], $box['customer'], $box['killing_features'], $box['additional_description'], $box['weights'], $box['tags'], $box['tags']);
            }
    
            return;
        }

		$args = array('post_type' => 'portfolio',
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
			unset($args['tax_query']);
        }
        
        
        $wp_query = new WP_Query( $args );

        if ( $wp_query->have_posts() ):
            while( $wp_query->have_posts() ) : $wp_query->the_post();
        $post_id = get_the_ID();

        $additional_description = get_post_meta($post_id, 'additional_description', 1);
        $killing_features = get_post_meta($post_id, 'killing_features', 1);
        $customer = get_post_meta($post_id, 'customer', 1);

        $team_size = array_sum(get_post_meta( $post_id, 'quantity', 'true' ));
        $duration = get_duration($post_id);

    
        $thumb = get_post_thumbnail_id();
        $img_url = wp_get_attachment_url($thumb);

           
        $terms = get_the_terms( $wp_query->post->ID, 'filters' );
          $output = '';
            if ( $terms && ! is_wp_error( $terms ) ) {
                $filters = array();
                $i = 0;
                foreach ( $terms as $term ) {
                  $filters[] = $term->name;
                    if ($i++ > 0) break;
                    }
                   $outputfilters = join( ", ", $filters );
                 $output .= $outputfilters;    
            }
        $terms_prettified = $output;                                  
        $this->render_project(get_the_title($post_id), $img_url, get_permalink(),$team_size,$duration, get_completion_year($post_id), $customer, $killing_features, $additional_description, get_post_meta( $post_id, 'weights', 'true' ), $terms_prettified, true );
       endwhile;  // close the Loop
      endif; 
        wp_reset_postdata();
    }

    function block($instance) {
        extract($instance);
        ?>
		<section class="section recent-project">
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

            <div class="recent-project__slider">
              <div
                class="recent-project__slider-container"
                data-slider-container
                data-slider-setup='{"slidesPerView":1,"spaceBetween":20,"navigation":{"nextEl":".recent-project .sdc---next","prevEl":".recent-project .sdc---prev"},"breakpoints":{"565":{"slidesPerView":2},"768":{"slidesPerView":3},"1024":{"slidesPerView":4}}}'
              >
                <div class="recent-project__slider-wrapper swiper-wrapper swiper-wrapper--auto-height">
                    <?php if(is_array($custom_cases) && count($custom_cases) != 0) {
                        $this->render_projects_distributor(true, $filters, $limit, $orderby, $queryorder, $custom_cases);
                     }else {
                        $this->render_projects_distributor(false, $filters, $limit, $orderby, $queryorder, $boxes);
                     } ?>
		       </div>
            </div>
		</div>  
	</section>		
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

    function add_new_custom_case() {
        $nonce = $_POST['security'];
        if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

        $count = isset($_POST['count']) ? absint($_POST['count']) : false;
        $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

        //default key/value for the tab
        $box = array(
          'title' => 'New service',
          'link' => '',
          'img_url' => '',
          'team_size' => 0,
          'duration' => 0,
          'competition_year' => '',
          'customer' => '',
          'killing_features' => '',
          'additional_description' => '',
          'weights' => 0,
          'tags' => '',
          'with_info' => 0,
        );

        if($count) {
            $this->render_admin_custom_cases($box, $count);
        } else {
            die(-1);
        }

        die();
    }

        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}
