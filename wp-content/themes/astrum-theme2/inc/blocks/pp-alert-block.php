<?php
/** Notifications block **/

if(!class_exists('PP_Alert_Block')) {
	class PP_Alert_Block extends AQ_Block {

		//set and create block
		function __construct() {
			$block_options = array(
				'name' => 'Alerts',
				'size' => 'span8',
				'last' => ''
				);

			//create the block
			parent::__construct('pp_alert_block', $block_options);
		}

		function form($instance) {

			$defaults = array(
				'content' => '',
				'type' => 'note',
				'style' => '',
				'wp_autop' => 1
				);
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);

			$type_options = array(
				'notice' => 'Notice',
				'error' => 'Error',
				'warning' => 'Warning',
				'success' => 'Success'
				);

				?>

				<p class="description">
					<label for="<?php echo $this->get_field_id('content') ?>">
						Alert Text (required)<br/>
						<?php echo aq_field_textarea('content', $block_id, $content) ?>
					</label>
					<label for="<?php echo $this->get_field_id('wp_autop') ?>">
						<?php echo aq_field_checkbox('wp_autop', $block_id, $wp_autop) ?>
						Do not create the paragraphs automatically. <code>"wpautop"</code> disable.
					</label>
				</p>
				<p class="description half">
					<label for="<?php echo $this->get_field_id('type') ?>">
						Alert Type<br/>
						<?php echo aq_field_select('type', $block_id, $type_options, $type) ?>
					</label>
				</p>
				<p class="description half last">
					<label for="<?php echo $this->get_field_id('style') ?>">
						Additional inline css styling (optional)<br/>
						<?php echo aq_field_input('style', $block_id, $style) ?>
					</label>
				</p>
				<?php

			}

			function block($instance) {
				extract($instance);

				echo '<div class="notification closeable '.$type.'" style="'. $style .'">';
				if(isset($wp_autop) && $wp_autop == 1){
					echo do_shortcode(htmlspecialchars_decode($content));
				}
				else
				{
					echo wpautop(do_shortcode(htmlspecialchars_decode($content)));
				}
				echo '<a class="close" href="#"></a></div>';

			}

		}
	}