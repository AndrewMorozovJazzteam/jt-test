<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Modern_About_Block')) {
    class PP_Modern_About_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(M) About us',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Modern_About_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'About JazzTeam',
                'description'=> 'Description about us',
                'image'=> '',
            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); 
            
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

            do_action( 'register_block_styles');

            $block_prefix = 'modern-about-block';
            ?>

	        <section class="modern-about-block" <?php echo 'data-' . $block_prefix; ?>>


                <div class="modern-about-block__content">
					
                    <div class="modern-about-block__description">
						<div class="modern-title-unit">
                    	    <h2 class="headline modern-title-unit__content"><?php echo $title; ?></h2>
                    	    <span class="line" style="margin-bottom:0;"></span>
                    	    <div class="clearfix"></div>
                    	</div>
                        <div class="modern-about-block__desc-content">
							<?php echo do_shortcode(htmlspecialchars_decode($description)); ?>
						</div>
						<a class="modern-about-block__presentation" href="https://www.slideserve.com/JazzTeam/jazzteam-company-preentation" target="_blank">
							<span class="modern-about-block__presentation-content">PDF Presentation</span>
							<svg class="modern-about-block__presentation-arrow" width="24" height="24" viewBox="0 0 24 24" color="currentColor" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.72773 17.8798C9.80859 17.9599 9.90157 18 10.0067 18C10.1118 18 10.2048 17.9599 10.2856 17.8798L15.9376 12.2766C16.0184 12.1964 16.0588 12.1042 16.0588 12C16.0588 11.8958 16.0184 11.8036 15.9376 11.7234L10.2856 6.12024C10.2048 6.04008 10.1118 6 10.0067 6C9.90157 6 9.80859 6.04008 9.72773 6.12024L9.1213 6.72144C9.04044 6.8016 9.00001 6.89379 9.00001 6.998C9.00001 7.1022 9.04044 7.19439 9.1213 7.27455L13.8878 12L9.1213 16.7255C9.04044 16.8056 9.00001 16.8978 9.00001 17.002C9.00001 17.1062 9.04044 17.1984 9.1213 17.2786L9.72773 17.8798Z" fill="#FFBA34"/></svg>
						</a>
                    </div>
                    <div class="modern-about-block__our-place">
                        <img class="modern-about-block__our-place-picture" src="<?php echo $image; ?>" alt="our place" />
                    </div>

                </div>

            </section>

            <?php
        }

        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}