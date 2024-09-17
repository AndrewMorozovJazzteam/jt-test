<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_New_Design_About_Block')) {
    class PP_New_Design_About_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) About us',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_New_Design_About_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'About JazzTeam',
                'description'=> 'Description about us',
                'image'=> '',
                'button_content' => 'PDF Presentation',
                'button_url' => '#',
                'variant' => 'first',
            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); 
            $variant_options = array(
              'first' => 'first',
              'second' => 'with content size 692px & orange button',
			  'third' => 'with content size 692px & skeleton small button'
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
                <label for="<?php echo $this->get_field_id('variant') ?>">
                    Variant (required)<br/>
                    <?php echo aq_field_select('variant', $block_id, $variant_options, $variant) ?>
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
            $headline = $title ? '<h2 class="headline-second">'.$title.'</h2>' : '';
            $current_variant = $variant ? $variant : 'first';
            $btn_scroll_class = str_contains($button_url, '#') ? 'lwptoc_itemWrap' : '';
            ?>
              <section class="about-us about-us--variant-<?=$current_variant?>">
                <div class="about-us__info <?=$btn_scroll_class?>">
                  <?=$headline?>
                  <div class="about-us__info-content">
                    <div class="about-us__info-text">
                      <?=do_shortcode(htmlspecialchars_decode($description))?>
                    </div>
                    <?php if($current_variant === 'first') {?>
                      <span class="headline-four">Only useful, targeted and effective solutions.</span>
                    <?php } ?>
                  </div>
                  <?php if($current_variant === 'first') { ?>
                    <a class="btn btn--skeleton about-us__presentation" href="<?=$button_url?>" target="_blank">
                    <span class="headline-four headline-four--strict-color"><?=$button_content?></span>
                    <div class="about-us__presentation-icon">
                      <svg class="about-us__presentation-arrow" width="12" height="12" viewBox="0 0 11 16" fill="none">
                        <path
                          d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
                          fill="currentColor"
                        />
                      </svg>
                    </div>
                  </a>
                  <?php } ?>
                  <?php if($current_variant === 'second') { ?>
                    <a class="btn btn--orange btn--default-space about-us__presentation-second" href="<?=$button_url?>">
                      <span class="headline-four headline-four--strict-color"><?=$button_content?></span>
                    </a>
                  <?php } ?>
                  <?php if($current_variant === 'third') { ?>
		  <a class="btn btn--skeleton about-us__presentation-third" href="<?=$button_url?>" target="_blank">
                    <span class="headline-four headline-four--strict-color"><?=$button_content?></span>
                    <div class="about-us__presentation-icon">
                      <svg class="about-us__presentation-arrow" width="12" height="12" viewBox="0 0 11 16" fill="none">
                        <path d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z" fill="currentColor"></path>
                      </svg>
                    </div>
                  </a>
                  <?php } ?>
                </div>
                <div class="about-us__office-container">
                  <div class="about-us__office">
                    <img loading="lazy" class="about-us__office-picture" src="<?=$image?>" alt="About us"/>
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