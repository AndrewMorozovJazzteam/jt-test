<?php
/* Aqua New_Cases - PureThemes */
if(!class_exists('PP_New_Cases_Block')) {
    class PP_New_Cases_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'New Cases boxes',
                'size' => 'span12',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_New_Cases_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_cases_add_new', array($this, 'add_newcases'));

        }

        function form($instance) {

            $defaults = array(
                'boxes' => array(
                    1 => array(
                        'title' => 'New Case',                        
                        'url' => '',
                        'upload' => ''
                    )
                )
            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $boxes = is_array($boxes) ? $boxes : $defaults['boxes'];
                    $count = 1;
                    foreach($boxes as $box) {
                        $this->cases($box, $count);
                        $count++;
                    }
                    ?>
                </ul>
               
                <a href="#" rel="cases" class="aq-sortable-add-new button">Add New</a>
                
            </div>

            <?php
        }

        function cases($box = array(), $count = 0) {

            ?>
            <li id="<?php echo $this->get_field_id('boxes') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $box['title'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title">
                            Case Title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][title]" value="<?php echo $box['title'] ?>" />
                        </label>
                    </p>


 

                     <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url">
                            Case url<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][url]" value="<?php if(isset($box['url'])) { echo $box['url']; } ?>" />
                        </label>
                    </p>
					
                    <p class="description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-upload">
                            Upload Image <br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-upload" class="input-full input-upload" value="<?php if(isset($box['upload'])) { echo $box['upload']; } ?>" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][upload]">
                            <a href="#" class="aq_upload_button button" rel="image">Upload</a>                            
                            <?php if(isset($upload) && !empty($upload)) { ?>
                                <div class="screenshot">
                                    <img src="<?php echo $upload ?>" />
                                </div>
                            <?php } ?>
                        </label>
                    </p>
					
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>

            </li>
            <?php
        }

        function block($instance) {

            extract($instance);
            $size=substr($size, 4);
            $boxes_number = count($boxes);
            $columns = $size/$boxes_number;
            if($boxes_number == 3 && $size == 16) {
                $new_col = "one-third";
            } else {
                $new_width = "span".floor($columns);
                $new_col =  AQ_Block::transform_span_to_gs($new_width);
            }
            $output ='';
            $count = 0; $posclass = '';
			
			$output = '<div class="ftac-container">';
                foreach( $boxes as $box ){
                    $count++;
					
					$output .= '<div class="ftac"><div class="notice-box">';
						$output .= '<a href="'. $box['url'] .'" target="_blank" title="'.$box['title'].'">';				
						
							$output .= '<center>';
								$output .= '<img src="'. $box['upload'] .'" >';
							$output .= '</center><br>';
							
							$output .= '<span class="h5" style="margin-top: -15px!important;">';
								$output .= '<span id="">'.$box['title'].'</span>';
							$output .= '</span><p></p>';							
						$output .= '</a>';
					$output .= '</div>';
				$output .= '</div>';
					
                }
			$output .= '</div>';


            echo $output;

        }

        /* AJAX add tab */
        function add_newcases() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $box = array(
                'title' => 'New Case',
                'content' => ''
            );

            if($count) {
                $this->cases($box, $count);
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
