<?php
/** A simple text block **/
if(!class_exists('PP_Cases_Block')) {
	class PP_Cases_Block extends AQ_Block {

		//set and create block
		function __construct() {
			$block_options = array(
                'name' => 'Cases',
                'size' => 'span14',
                'last' => ''
            );
			

			//create the block
			parent::__construct('pp_cases_block', $block_options);
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
				<div class="case-pointer center-headline">
					<h3 class="headline"><?php echo $caption; ?></h3>
					<span class="line" style="margin-bottom:35px;"></span>
					<div class="clearfix"></div>
					<p><a href="<?php echo $url; ?>"></a></p>
					<a href="<?php echo $url; ?>">
					<div class="view view-first"><img src="<?php echo $upload; ?>" alt="<?php echo $caption; ?>"><p></p>
						<div class="mask">
							<h2 class="info"><?php echo $caption; ?></h2>
						</div>
					</div>
					</a>
					<p>
						<a href="<?php echo $url; ?>">
						</a>
					</p>
				</div>
				
				<?php }
			
			
			
		}

	}

}
