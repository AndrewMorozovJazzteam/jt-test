<?php
/* Aqua Video Testimonials - PureThemes */
if(!class_exists('PP_Video_Testimonials_Block')) {
    class PP_Video_Testimonials_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Video Testimonials',
                'size' => 'span12',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Video_Testimonials_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_videos_add_new', array($this, 'add_newvideotestimonials'));

        }

        function form($instance) {

            $defaults = array(
                'boxes' => array(
                    1 => array(
                        'iframe' => '',
						'image' => ''
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
                        $this->videos($box, $count);
                        $count++;
                    }
                    ?>
                </ul>
               
                <a href="#" rel="videos" class="aq-sortable-add-new button">Add New</a>
                
            </div>

            <?php
        }

        function videos($box = array(), $count = 0) {

            ?>
            <li id="<?php echo $this->get_field_id('boxes') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong>New Iframe</strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">					
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-iframe">
                            Iframe<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-iframe" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][iframe]" value="<?php echo $box['iframe'] ?>" />
                        </label>
                    </p> 

                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-poster">
                            Video poster<br/>
                            <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-poster" class="input-full"  name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][image]" value="<?php echo $box['image'] ?>" />
                            <a rel="image" class="aq_upload_button button" href="#">Upload</a>
                        </label>
                    </p>
					
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>

            </li>
            <?php
        }
		
		function getYouTubeVideoID($url) {
    		$pattern = '/^https:\/\/www\.youtube\.com\/embed\/([A-Za-z0-9_-]+)$/i';
    		preg_match($pattern, $url, $matches);
    		if (isset($matches[1])) {
        		return $matches[1];
    		}

   		 	return ''; // Если идентификатор не найден
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
            $count = 0; 
			$posclass = '';
			

			$output = '<div class="testimonials-container">';
                foreach( $boxes as $box ){
					$videoId = $this->getYouTubeVideoID($box['iframe']);				
					$backgrond = $box['image'] ? 'url('.$box['image'].') #000 center center/cover no-repeat' : '#000';
                    $count++;
					$output .= '<div class="testimonials-col-2">';
					$output .='<div class="yt-video">
        <div class="yt-player" id="yt-player-'.$count.'"></div>
        <div class="yt-to-play" data-id="yt-player-'.$count.'" data-video="'.$videoId.'" style="background: '.$backgrond.';">		
			<button class="yt-player-start"><svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path></svg></button>
        </div>
</div>';
					$output .= '</div>';
					
                }
			$output .= '</div>';
			
            echo $output;

        }

        /* AJAX add tab */
        function add_newvideotestimonials() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $box = array(
                'iframe' => '',
            );

            if($count) {
                $this->videos($box, $count);
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
