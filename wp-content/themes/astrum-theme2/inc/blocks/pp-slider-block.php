<?php
/** Slider Block **/
if(!class_exists('PP_Slider_Block')) {
	class PP_Slider_Block extends AQ_Block {

		function __construct() {
			$block_options = array(
				'name'			=> 'Slider',
				'size'			=> 'span16',
				'last' => ''
			);

			parent::__construct('pp_slider_block', $block_options);

			add_action('wp_ajax_aq_block_slider_add_new', array($this, 'add_slide'));
		}

		function form($instance) {
			$defaults = array(
				'slides' => array(
					1 => array(
						'title' => 'My New Slide',
						'upload' => '',
						'caption' => '',
					)
				),
				'slider'		=> ''
			);
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);

			?>
			<p class="description">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title (optional)<br/>
					<?php echo aq_field_input('title', $block_id, $title) ?>
				</label>
			</p>

			<div class="description cf">
				<ul id="sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
					<?php
					$slides = is_array($slides) ? $slides : $defaults['slides'];
					$count = 1;
					foreach($slides as $slide) {
						$this->slide($slide, $count);
						$count++;
					}
					?>
				</ul>
				<p></p>
				<a href="#" rel="slider" class="aq-sortable-add-new button">Add New</a>
				<p></p>
			</div>



			<?php
		}

		function slide($slide = array(), $count = 0) {

			$defaults = array (
				'title' => '',
				'upload' => '',
				'caption' => ''
			);
			$slide = wp_parse_args($slide, $defaults);

			?>
			<li id="<?php echo $this->get_field_id('testimonials') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

				<div class="sortable-head cf">
					<div class="sortable-title">
						<strong><?php echo $slide['title'] ?></strong>
					</div>
					<div class="sortable-handle">
						<a href="#">Open / Close</a>
					</div>
				</div>

				<div class="sortable-body">
					<p class="description">
						<label for="<?php echo $this->get_field_id('slides') ?>-<?php echo $count ?>-title">
							Slide Title<br/>
							<input type="text" id="<?php echo $this->get_field_id('slides') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('slides') ?>[<?php echo $count ?>][title]" value="<?php echo $slide['title'] ?>" />
						</label>
					</p>
					<p class="description">
						<label for="<?php echo $this->get_field_id('slides') ?>-<?php echo $count ?>-upload">
							Upload Image<br/>
							<input type="text" id="<?php echo $this->get_field_id('slides') ?>-<?php echo $count ?>-upload" class="input-full input-upload" value="<?php echo $slide['upload'] ?>" name="<?php echo $this->get_field_name('slides') ?>[<?php echo $count ?>][upload]">
							<a href="#" class="aq_upload_button button" rel="image">Upload</a>
							<p></p>
						</label>
					</p>
					<p class="description">
						<label for="<?php echo $this->get_field_id('slides') ?>-<?php echo $count ?>-caption">
							Slide Caption<br/>
							<textarea id="<?php echo $this->get_field_id('slides') ?>-<?php echo $count ?>-caption" class="textarea-full" name="<?php echo $this->get_field_name('slides') ?>[<?php echo $count ?>][caption]" rows="5"><?php echo $slide['caption'] ?></textarea>
						</label>
					</p>

					<p class="description"><a href="#" class="sortable-delete">Delete</a></p>
				</div>

			</li>
			<?php

		}

		function add_slide() {
			$nonce = $_POST['security'];
			if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

			$count = isset($_POST['count']) ? absint($_POST['count']) : false;
			$this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

			//default key/value for the tab
			$slide = array(
				'title' => 'New Slide',
				'upload' => '',
				'caption' => '',
				'embed' => ''
			);

			if($count) {
				$this->slide($slide, $count);
			} else {
				die(-1);
			}

			die();
		}

		function block($instance) {

			extract($instance);

			wp_enqueue_script('flexslider');
			$width = aq_get_column_width($size);
			$crop = $crop ? true : false;

			if($overflow && $size = 'span16') {
				$width = 1200;
			}
			if($slides) {
				$rand = rand(1,100); 
			?>
				 <section class="flexslider flex-<?php echo $rand; ?> post-img">
				        <div class="media">
				          <ul class="slides mediaholder">
				<?php
						if ($slides) {
							foreach ($slides as $i=>$slide) { ?>
								<li>
										<img src="<?php echo $slide['upload']; ?>" alt="<?php echo $slide['title']; ?>" />
										<?php if($slide['caption']) { ?><div class="slide-caption"><h5><?php echo $slide['caption']; ?></h5></div><?php } ?>
								</li>

								<?php
							}
						}
				?>
				</ul>
    		</div>
  		</section>
		<?php

	}

			echo $output;

		}

		function update($new_instance, $old_instance) {
			$new_instance = aq_recursive_sanitize($new_instance);
			return $new_instance;
		}



	}
}
