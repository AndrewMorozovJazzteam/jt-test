<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Technologies_Block')) {
    class PP_Technologies_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Technologies block',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Technologies_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_technologies_add_new', array($this, 'add_clientbox'));
            add_action('wp_ajax_aq_block_technologies-link_add_new', array($this, 'add_column'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Left column',
				'description' => '',
                'rows' => array(
                    1 => array(
                        'title' => 'Row',
                        'link' => '',
						            'type' => 'white',
                        'links' => array(
                            1 => array(
                                'icon' =>'',
								'resize' => 'resize--normal',
                                'name' => 'Column',
                                'image' => '',
                                'link' => ''
                            ),
                        ),
                        
                    )
                )
            );
						
            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (required)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
            <div class="description">
                <label for="<?php echo $this->get_field_id('description') ?>">
                    Description (optional)<br/>
					<?php echo aq_field_textarea('description', $block_id,  $description); ?>
                </label>
            </div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $rows = is_array($rows) ? $rows : $defaults['rows'];
                    $count = 1;
                    foreach($rows as $row) {
                        $this->client($row, $count,$block_id);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="technologies" class="aq-sortable-add-new button">Add New Row</a>
                <p></p>
            </div>

            <?php
        }

        function client($row = array(), $count = 0, $block_id = 'aq_block_12000') {
			      $select_options = array(
                array('value'=> 'white','label' => 'White'),
			      		array('value'=> 'gray','label' => 'Gray') 
			      );

            ?>
            <li id="<?php echo $this->get_field_id('rows') ?>-sortable-item-<?php echo $count ?>" class="sortable-item sortable-root" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $row['title'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title">
                            Column title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][title]" value="<?php echo $row['title'] ?>" />
                        </label>
                    </p>
					
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-type">
                            Color type<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][type]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title">
                                    <?php
                                    foreach ($select_options as $key) {
                                        if ( $row['type'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
                                        echo '<option '.$sel.' value="'.$key['value'].'">'.$key['label'].'</option>';
                                    } ?>
                                </select>
                            </label>
                        </p>

                    <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-link">
                          Link URL<br/>
                          <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-link" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][link]" value="<?php echo $row['link'] ?>" />
                      </label>
                  </p>
                        <ul id="aq-sortable-list-link-<?php echo $block_id; ?>" class="aq-sortable-list" rel="<?php echo $block_id; ?>">
                        <?php
                            $link_count = 1;
                                foreach ($row['links'] as $link) {
                                $this->link($link, $count, $link_count);
                                $link_count++;
                            }
                        ?>
                        </ul>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                    <a href="#" rel="technologies-link" class="aq-sortable-add-new button" data-args="<?php echo $count; ?>">Add New column</a>
                    
                </div>

            </li>
            <?php
        }

        function link($link = array(), $count = 0, $link_count = 0, $block_id = 'aq_block_12000') {
						$resize_options = array(
							  array('value'=> 'resize--small','label' => 'Small'),
                array('value'=> 'resize--normal','label' => 'Normal'),
								array('value'=> 'resize--big','label' => 'Big'),
							  array('value'=> 'resize--ultra-big','label' => 'Huge') 
			      );
			
            ?>
            <li class="sortable-item" rel="<?php echo $link_count; ?>">

            <div class="sortable-head cf">
                <div class="sortable-title">
                    <strong><?php echo $link['name'] ?></strong>
                </div>
                <div class="sortable-handle" rel="aq-sortable-list-link-<?php echo $block_id; ?>">
                    <a href="#">Open / Close</a>
                </div>
            </div>
        
            <div class="sortable-body">

                 <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-name">
                        Link Name<br/>
                        <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-name" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][name]" value="<?php echo $link['name'] ?>" />
                    </label>
                </p>

                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-link">
                        Link URL<br/>
                        <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-link" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][link]" value="<?php echo $link['link'] ?>" />
                    </label>
                </p>
 
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-icon">
                        Icon URL<br/>
                        <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-icon" class="input-full"  name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][icon]" value="<?php echo $link['icon'] ?>" />
                        <a rel="image" class="aq_upload_button button" href="#">Upload</a>
                    </label>
                </p>
				
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-resize">
                            Icon size type<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][resize]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-resize">
                                    <?php
				
                                    foreach ($resize_options as $key) {
                                        if ( $link['resize'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
                                        echo '<option '.$sel.' value="'.$key['value'].'">'.$key['label'].'</option>';
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
            $size = substr($size, 4);
            $insidewidth = $size-2;
            $temp_width = "span".floor($insidewidth);
            $new_witdh =  AQ_Block::transform_span_to_gs($temp_width);
          ?>
	  	<div>

      <?php if (!empty($title)) { ?>
            <div class="headline-through" id="our_technical_competence">
                <h3 class="headline"><?php echo $title; ?></h3>
                <span class="line" style="margin-bottom:35px;"></span>
                <div class="clearfix"></div>
            </div>
	    <?php } ?>
			
      	<?php if (!empty($description)) { ?>
            <div class="description technologies-description">
                <?php echo htmlspecialchars_decode($description); ?>
            </div>
	    <?php } ?>

        <?php
		
		$counter = 0;
			
        foreach ($rows as $row) {
		  $container_class = $row['type'] === 'white' ? 'sd-white-2' : 'sd-gray-2-2';
		  $link_class = $row['type'] === 'white' ? 'sd-white-1' : 'sd-gray-2-1';
		  $first_class = $counter === 0 ? 'tech-first' : '';
		  $last_class = $counter === (count($rows) - 1) ? 'tech-last' : '';
		  
		  $is_container_without_link = trim($row['link']) === '';
			
          echo '<div class="tech-flex '.$first_class.' '.$last_class.'">';
          echo  $is_container_without_link ? '<div class="'.$link_class.'"><span class="gray-headline-2">' . $row['title'] . '</span></div>' : '<a href="'.$row['link'].'" class="'.$link_class.'"><span class="gray-headline-2">' . $row['title'] . '</span></a>';
			
          echo '<div class="'.$container_class.'">';

          foreach ($row['links'] as $link) {
              $link_icon = trim($link['icon']) !== '' ? $link['icon'] : '/PreProdOct/wp-content/images/technologies/Java.svg';
			  $is_without_link = trim($link['link']) === '';
			  
              echo '<div class="two columns">';
			  

		      echo $is_without_link ? '<div class="tech-box">' : '<a href="' . $link['link'] . '" class="tech-box">';              
              echo '<div class="img-container">';
              echo '<img class="'.$link['resize'].'" src="' . $link_icon . '" alt="' . $link['name'] . '" />';
              echo '</div>';
              echo '<div class="featured-desc">';
			  
              echo $is_without_link ? '<p class="h4">' . $link['name'] . '</p>' : '<span class="h4">' . $link['name'] . '</span>';
              echo '</div>';
			  echo $is_without_link ? '</div>' : '</a>';
	
			  
              echo '</div>';
          }
      
          echo '</div>';
          echo '</div>';
		  $counter += 1;
      }
        
        ?>

        <?php
        }

        /* AJAX add tab */
        function add_clientbox() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $client = array(
                'title' => 'Row',
                'link' => '',
				'type' => 'white',
                'links' => array(
                1 => array(
                            'icon' =>'',
							'resize' => 'normal',
                            'name' => 'Column',
                            'image' => '',
                            'link' => ''
                    ),
                ),
            );

            if($count) {
                $this->client($client, $count, $_POST['block_id']);
            } else {
                die(-1);
            }

            die();
        }

        
        function add_column() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $args = isset($_POST['args']) ? absint($_POST['args']) : 1;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $client = array(
                'icon' => '',
				'resize' => 'resize--normal',
                'name' => 'Column',
                'image' => '',
                'link' => ''
            );

            if($count) {
                $this->link($client, $args, $count,$_POST['block_id']);
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