<?php
/* Aqua Modern_Cases - PureThemes */
if(!class_exists('PP_Modern_Cases_Block')) {
    class PP_Modern_Cases_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Modern Cases boxes',
                'size' => 'span12',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Modern_Cases_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_cases_add_new', array($this, 'add_newcases'));

        }

        function form($instance) {

            $defaults = array(
                'variant' => 'first',
                'boxes' => array(
                    1 => array(
                        'title' => 'New Case',                        
                        'url' => '',
                        'upload' => ''
                    )
                )
            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance);

            $variant_options = array(
                'first' => 'first',
                'second' => 'second(tile with two columns)'
            );
            
            ?>
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

                <div class="description">
                    <label for="<?php echo $this->get_field_id('variant') ?>">
                        Variant (required)<br/>
                        <?php echo aq_field_select('variant', $block_id, $variant_options, $variant) ?>
                    </label>
                </div>
               
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

        function render_cases_second($cases){
          ?>
            <section class="cases-second">
              <?php foreach($cases as $case) { ?>
                <a href="<?=$case['url']?>" class="cases-second__card" target="_blank">
					<div class="cases-second__picture-container">
					  <div class="cases-second__backdrop"></div>
					  <img class="cases-second__preview" src="<?=$case['upload']?>" alt="<?=$case['title']?>" height="204" width="285" loading="lazy">
					</div>
					<div class="cases-second__text-container">
					  <div class="cases-second__title"><?=$case['title']?></div>
					</div>
				</a>
                <?php } ?>
            </section>
          <?php
        }


        function block($instance) {
            extract($instance);
            $output ='';
            $count = 0; 
            $current_variant = $variant ? $variant : 'first';
		

            if($current_variant == 'first'){
                $output = '<div class="modern-ftac-container">';
                foreach( $boxes as $box ){
                    $count++;				
					$output .= '<div class="modern-ftac"><a class="modern-ftac__link" href="'. $box['url'] .'" target="_blank" title="'.$box['title'].'"><div class="modern-ftac__notice-box">';
							$output .= '<div class="modern-ftac__picture"><img src="'. $box['upload'] .'" alt="'.$box['title'].'"><div class="modern-ftac__backdrop"></div></div>';					
							$output .= '<span class="modern-ftac__title headline-four headline-four--color-inherit">';
							$output .= $box['title'];
							$output .= '</span>';			
					$output .= '</div></a>';
				$output .= '</div>';
					
                }
			$output .= '</div>';


            echo $output;
            }
            else if ($current_variant == 'second'){
                $this->render_cases_second($boxes);
            }

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
