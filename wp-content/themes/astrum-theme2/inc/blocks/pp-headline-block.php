<?php
/** A simple text block **/
class PP_Headline_Block extends AQ_Block {

	//set and create block
	function __construct() {
		$block_options = array(
			'name' => 'Headline',
			'size' => 'span6',
			'last' => ''
		);

		//create the block
		parent::__construct('pp_headline_block', $block_options);
	}

	function form($instance) {

		$defaults = array(
			'text' => '',
			'margintop' => '0',
			'marginbottom' => '32',
			'wp_autop' => 0
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		?>
		<p class="description">
			<label for="<?php echo $this->get_field_id('title') ?>">
				Headline
				<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
			</label>
		</p>
		<div class="description half">
			<label for="<?php echo $this->get_field_id('margintop') ?>">
				Margin top (optional)<br/>
				<?php echo aq_field_input('margintop', $block_id, $margintop, 'min', 'number') ?> px
			</label>
		</div>
		<div class="description half last">
			<label for="<?php echo $this->get_field_id('marginbottom') ?>">
				Margin bottom (optional)<br/>
				<?php echo aq_field_input('marginbottom', $block_id, $marginbottom, 'min', 'number') ?> px
			</label>
		</div>
		<?php
	}

	function block($instance) {
		extract($instance);
		if($title) echo '<h3 class="headline" style="margin-top:'.$margintop.'px">'.strip_tags($title).'</h3><span class="line" style="margin-bottom:'.$marginbottom.'px"></span><div class="clearfix"></div>';


	}

}
