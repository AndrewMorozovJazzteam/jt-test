<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Featured_Block')) {
    class PP_Featured_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Featured boxes',
                'size' => 'span12',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Featured_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_box_add_new', array($this, 'add_featuredbox'));

        }


        function form($instance) {

            $defaults = array(
				'description' => '',
                'boxes' => array(
                    1 => array(
                        'title' => 'New box',
                        'icon' => 'Icon',
                        'content' => 'New box contents',
                        'style' => 'circle',
                        'url' => '',
                        'upload' => '',
                    )
                ),
				'is_span_h4' => 0,
            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
			
            <div class="description">
                <label for="<?php echo $this->get_field_id('description') ?>">
                    Description (optional)<br/>
					<?php echo aq_field_textarea('description', $block_id,  $description); ?>
                </label>
            </div>

            <div class="description cf">
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
				<label for="<?php echo $this->get_field_id('is_span_h4') ?>">
				    <?php echo aq_field_checkbox('is_span_h4', $block_id, $is_span_h4) ?>
				    Is  spanned<code>h4</code>
			    </label>
                <a href="#" rel="box" class="aq-sortable-add-new button">Add New</a>
                <p></p>
            </div>

            <?php
        }

        function box($box = array(), $count = 0) {

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
                            Box Title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][title]" value="<?php echo $box['title'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-icon">
                            Box Icon <br/>
                            <?php global $fontawesome; ?>
                            <select name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][icon]" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-icon">
                                <option value="none">None</option>
                                <?php
                                foreach ($fontawesome as $key => $value) {
                                    if ($box['icon'] == $key) { $sel = "selected"; } else { $sel = ''; }
                                    echo '<option '.$sel.' value="'.$key.'">'.$value.'</option>';
                                } ?>
                            </select>

                        </label>
                    </p>
                    <p class="description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-upload">
                            Upload Image insted of icon (it has to be max 60x60px)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-upload" class="input-full input-upload" value="<?php if(isset($box['upload'])) { echo $box['upload']; } ?>" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][upload]">
                            <a href="#" class="aq_upload_button button" rel="image">Upload</a>
                            <p></p>
                            <?php if(isset($upload) && !empty($upload)) { ?>
                                <div class="screenshot">
                                    <img src="<?php echo $upload ?>" />
                                </div>
                            <?php } ?>
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-content">
                            Box Content<br/>
                            <textarea id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $box['content'] ?></textarea>
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-style">
                            Icon animation style <br/>
                            <?php
                            $style_options = array(
                            'circle' => 'Style 1' ,
                            'circle-2' => 'Style 2' ,
                            'circle-3' => 'Style 3'
                            );
                        ?>
                        <select name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][style]" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-style">
                                <?php
                                foreach ($style_options as $key => $value) {
                                    if ($box['style'] == $key) { $sel = "selected"; } else { $sel = ''; }
                                    echo '<option '.$sel.' value="'.$key.'">'.$value.'</option>';
                                } ?>
                            </select>
                        </label>
                    </p>
                     <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url">
                            Optional url<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][url]" value="<?php if(isset($box['url'])) { echo $box['url']; } ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>
            </li>
            <?php
        }

        function render_full_block($instance){
			extract($instance);
			
			if($description){
				?>
					<div class="description featured-block-description">
                	<?php echo htmlspecialchars_decode($description); ?>
            		</div>
				<?php
			}
			
			
            $size = substr($instance['size'], 4);
            $boxes_number = count($instance['boxes']);
            $columns = $size / $boxes_number;
            $new_col = ($boxes_number == 3 && $size == 16) ? "one-third" : AQ_Block::transform_span_to_gs("span" . floor($columns));
            $output = '';
            $count = 0;

            foreach ($instance['boxes'] as $box) {
                $count++;
                $posclass = ($count == 1) ? "alpha" : (($count == $boxes_number) ? "omega" : "");
                $url = isset($box['url']) && !empty($box['url']) ? $box['url'] : '#';
                
                $output .= '<div class="' . $new_col . ' ' . $posclass . ' columns">';
                $output .= '<a href="'. $url .'" class="featured-box' . (($box['icon'] == 'none' && empty($box['upload'])) ? ' no-margin' : '') . '">';
                if (!empty($box['upload'])) {
                    $output .= '<div class="circle-image"><img src="' . $box['upload'] . '"/></div>';
                } elseif ($box['icon'] != 'none') {
                    $output .= '<div class="' . $box['style'] . '"><i class="' . $box['icon'] . '"></i><span></span></div>';
                }

                $output .= '<div class="featured-desc">';
                $output .= '<span class="featured-title h4-custom">' . $box['title'] . '</span>';
                $output .= wpautop(do_shortcode(htmlspecialchars_decode($box['content'])));
                $output .= '</div>';

                $output .= '</a>';
                $output .= '</div>';
            }
        
            echo $output;
        }

        function render_partical_block($instance){
			extract($instance);
			
			if($description){
				?>
					<div class="description featured-block-description">
                	<?php echo htmlspecialchars_decode($description); ?>
            		</div>
				<?php
			}
			
			
            $size = substr($size, 4);
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
                foreach( $boxes as $box ){
                    $count++;
                    if( $count == 1 ) { $posclass = "alpha"; } elseif ($count == $boxes_number) {
                        $posclass = "omega";
                    } else {
                        $posclass = "";
                    }

                    $output .= '<div class="'. $new_col .' '.$posclass.' columns">';

                        if($box['icon'] == 'none' && empty($box['upload'])  ) {
                            $output  .= '<div class="featured-box no-margin">';
                        } else {
                            $output  .= '<div class="featured-box">';
                        }

                        if(!empty($box['upload'])) {
                             $output .= '<div class="circle-image"><img src="'. $box['upload'] .'"/></div>';
                        } else {
                            if($box['icon'] != 'none') {
                                $output .= '<div class="'. $box['style'] .'"><i class="'. $box['icon'] .'"></i><span></span></div>';
                            }
                        }
                        $output .= '<div class="featured-desc">';
                        if(isset($box['url']) && !empty($box['url'])) {
                            $output .= '<h4><a href="'. $box['url'] .'">'. $box['title'] .'</a></h4>';
                        } else {
                            $output .= '<span class="h4">' . $box['title'] . '</span>';
							
							
                        }
                            $output .= wpautop(do_shortcode(htmlspecialchars_decode($box['content'])));
                        $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }

            echo $output;
        }
        

        function block($instance) {
			$is_front = is_front_page();
						
            if ($is_front) {
                $this -> render_full_block($instance);
			} else{
                $this -> render_partical_block($instance);
            }
        }


        /* AJAX add tab */
        function add_featuredbox() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $box = array(
                'title' => 'New Box',
                'content' => ''
            );

            if($count) {
                $this->box($box, $count);
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
