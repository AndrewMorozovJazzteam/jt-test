<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Data_Solution_Block')) {
    class PP_Data_Solution_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Data Solution',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Data_Solution_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'Data Solution',
                'description'=> 'Some data solution',
                'image'=> '',
                'button_content' => 'Schedule a meeting',
                'button_url' => '#',
                'skeleton_type' => 'first'
            );


            $instance = wp_parse_args($instance, $defaults);
            extract($instance); 

            $skeleton_options = array(
                'first' => 'first',
                'second' => 'second',
				'third' => 'third',
            );
            ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (optional)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>

            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Description (optional)<br/>
                    <?php echo aq_field_textarea('description', $block_id, $description) ?>
                </label>
            </div>

            <div class="description">
                <label for="<?php echo $this->get_field_id('button_content') ?>">
                    Button content (required)<br/>
                    <?php echo aq_field_input('button_content', $block_id, $button_content) ?>
                </label>
            </div>

            <div class="description">
                <label for="<?php echo $this->get_field_id('button_url') ?>">
                    Button url (required)<br/>
                    <?php echo aq_field_input('button_url', $block_id, $button_url) ?>
                </label>
            </div>

            <div class="description">
                <label for="<?php echo $this->get_field_id('skeleton_type') ?>">
                    Skeleton type (required)<br/>
                    <?php echo aq_field_select('skeleton_type', $block_id, $skeleton_options, $skeleton_type) ?>
                </label>
            </div>

			<div class="description">
				<label for="<?php echo $this->get_field_id('image') ?>">
					Upload an image<br/>
					<?php echo aq_field_upload('image', $block_id, $image) ?>
				</label>

				<?php if($image) { ?>

				<div class="screenshot">
					<img src="<?php echo $image ?>" alt="empty image" />
				</div>

				<?php } ?>

			</div>
            
            <?php
        }
    

        function block($instance) {
            extract($instance);
            $headline = $title ? '<h1 class="headline-first">'.$title.'</h1>' : '';
            $uploads_dir = wp_upload_dir()['baseurl'];

            $skeleton_content_by_type = array('first' => '<img src="'.$uploads_dir.'/2024/03/mule-first-skeleton.svg" class="data-solution__skeleton-first" alt="skeleton">',
											  'second' =>'<img src="'.$uploads_dir.'/2024/03/skeleton-second.svg" class="data-solution__skeleton-second" alt="skeleton">',
											  'third' =>'<img src="'.$uploads_dir.'/2024/03/skeleton-third.svg" class="data-solution__skeleton-third" alt="skeleton">',
											 );
  
            ?>
      
            <div class="data-solution">
              <div class="data-solution__main-solutions">
                <?=$headline?>
                <div class="data-solution__solutions">
                    <div class="data-solution__solution-provision"><?=do_shortcode(htmlspecialchars_decode($description))?></div>
                    <span class="data-solution__controls lwptoc_itemWrap">
						<a class="data-solution__schedule btn btn--orange  btn--default-space" href="#Contact_Us"><span class="headline-four">Send a request</span></a>
						<a class="data-solution__schedule btn btn--skeleton btn--glass btn--default-space" href="<?=$button_url?>"><span class="headline-four"><?=$button_content?></span></a>
					</span>
                </div>
              </div>
              <div class="data-solution__picture">
                <?=$skeleton_content_by_type[$skeleton_type]?>
                <img fetchpriority="high" class="data-solution__picture-icon" src="<?=$image?>" height="420" width="40" alt="solutions"/>
              </div>
            </div>
            <?php
        }

        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}