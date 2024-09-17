<?php
/** A simple text block **/
if(!class_exists('PP_Ftac_Block')) {
	class PP_Ftac_Block extends AQ_Block {

		//set and create block
		function __construct() {
			$block_options = array(
                'name' => 'Ftac',
                'size' => 'span15',
                'last' => ''
            );
			

			//create the block
			parent::__construct('pp_ftac_block', $block_options);
		}

		function form($instance) {

			$defaults = array(
                        'caption' => 'New title',
                        'upload' => 'Image',
                        'url' => 'Link',                   
            );
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);

			?>
			
			<div class="description half">
				<label for="<?php echo $this->get_field_id('caption') ?>">
					Caption (optional)<br/>
					<?php echo aq_field_input('caption', $block_id, $caption) ?>
				</label>
			</div>
			<div class="description half last">
				<label for="<?php echo $this->get_field_id('url') ?>">
					URL (optional)<br/>
					<?php echo aq_field_input('url', $block_id, $url) ?>
				</label>
			</div>
			<div class="description half ">
				<label for="<?php echo $this->get_field_id('upload') ?>">
					Upload an image<br/>
					<?php echo aq_field_upload('upload', $block_id, $upload) ?>
				</label>
				<?php if($upload) { ?>
				<div class="screenshot">
					<img src="<?php echo $upload ?>" />
				</div>
				<?php } ?>
			</div>
			<?php
		}

		function block($instance) {
			extract($instance);			
			
			
			
			 if($upload) { ?>
							
				
				<div class="ftac">
					<div class="notice-box">
						<a href="<?php echo $url; ?>" target="_blank" title="<?php echo $caption; ?>">
							<center>
								<img src="<?php echo $upload; ?>" >
							</center>
							<br>
							<span class="h5" style="margin-top: -15px!important;">
								<span id=""><?php echo $caption; ?></span>
							</span>
							<p></p>
						</a>
					</div>
				</div>	
				
				
				
				
				<?php }
			
			
			
		}

	}

}