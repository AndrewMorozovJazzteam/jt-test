<?php
/** A simple text block **/
if(!class_exists('PP_Otas_Block')) {
	class PP_Otas_Block extends AQ_Block {

		//set and create block
		function __construct() {
			$block_options = array(
                'name' => 'Otas',
                'size' => 'span16',
                'last' => ''
            );
			

			//create the block
			parent::__construct('pp_otas_block', $block_options);
		}

		function form($instance) {

			$defaults = array(
                        'title' => 'New title',
                        'upload' => 'Image',
                        'text' => 'Text',                   
            );
			$instance = wp_parse_args($instance, $defaults);
			extract($instance);

			?>
			
			<div class="description half">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title (optional)<br/>
					<?php echo aq_field_input('title', $block_id, $title) ?>
				</label>
			</div>
			<div class="description half">
				<label for="<?php echo $this->get_field_id('text') ?>">
					Content
					<?php echo aq_field_textarea('text', $block_id, $text) ?>
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
				
				<div class="otas-block">
					<h4 class="otas-title aq-block-title"><span><?php echo $title; ?></span></h4>
						<div style="margin-bottom: 22px;">
							<p>
								<img class="otas-image wp-image-7472 alignleft" src="<?php echo $upload ?>">
							</p>
							<p class="otas-content has-text-align-left has-text-color" style="color: #5b5b5b;">								 
								<?php echo htmlspecialchars_decode($text); ?>
							</p>
						</div>			
                    </div>
				
				<?php }
			
		}

	}

}