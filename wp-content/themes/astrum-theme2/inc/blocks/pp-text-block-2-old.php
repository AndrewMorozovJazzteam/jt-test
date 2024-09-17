<?php
/** A simple text block **/
if(!class_exists('PP_Text_Block2')) {
	class PP_Text_Block2 extends AQ_Block {
		
		//set and create block
		function __construct() {
			$block_options = array(
				'name' => __('Text 2', 'aqpb-l10n'),
				'size' => 'span6',
			);
			
			//create the block
			parent::__construct('PP_Text_Block2', $block_options);
		}
		
		function form($instance) {
			
			$defaults = array(
				'text'   => '',
				'filter' => 0,
				'type' => 'h1',
				'classname' => 'headline',
			);
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			
			
			$type_options = array(
					'h1' => 'h1',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6'
				);

				$class_options = array(
					'headline'  => 'headline',
					'head-line' => 'head-line'
				);
			
			?>
			<p class="description">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title (optional)
					<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
				</label>
			</p>
			
			<p class="description">
				<label for="<?php echo $this->get_field_id('text') ?>">
					Content
					<?php echo aq_field_textarea('text', $block_id, $text, $size = 'full') ?>
				</label>
				<label for="<?php echo $this->get_field_id('filter') ?>">
					<?php echo aq_field_checkbox('filter', $block_id, $filter) ?>
					<?php _e('Automatically add paragraphs', 'aqpb-l10n') ?>
				</label>
			</p>
			<p class="description half">
					<label for="<?php echo $this->get_field_id('type') ?>">
						Heading type<br/>
						<?php echo aq_field_select('type', $block_id, $type_options, $type) ?>
					</label>
			</p>
			<p class="description half">
					<label for="<?php echo $this->get_field_id('classname') ?>">
						Class type<br/>
						<?php echo aq_field_select('classname', $block_id, $class_options, $classname) ?>
					</label>
			</p>
			<?php
		}
		
		function block($instance) {
			extract($instance);

			$wp_autop = ( isset($wp_autop) ) ? $wp_autop : 0;
			
			if($title) echo '<div style="margin-top: 35px;"><'.$type.' data-toc-numbers="true" class="'.$classname.'" style="margin-top:0px;">'.strip_tags($title).'</'.$type.'><span class="line" style="margin-bottom:35px;"></span><div class="clearfix"></div>
</div>';
			if($wp_autop == 1) {
				echo do_shortcode(htmlspecialchars_decode($text));
			} else {
				echo wpautop(do_shortcode(htmlspecialchars_decode($text)));
			}
			
		}
		
	}
}
