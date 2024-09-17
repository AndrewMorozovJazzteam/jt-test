<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_Services_Need_Block')) {
    class PP_Services_Need_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Services Need',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Services_Need_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_services-need_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'What we need',
                'variant' => 'first',
				'description' => '',
                'desktop_amount' => '5',
                'tablet_amount' => '3',
                'mobile_amount' => '1',
                'force_controls_right' => 0,
                'clients' => array(
                    1 => array(
                        'content' => 'We need...',
                        'image' => 'Logo url',
                        'url' => '',
                        'url_text' => 'Learn More'
                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance);
            $variant_options = array(
                'first' => 'first',
                'second' => 'second(light blue with image)',
                'third' => 'third(light blue with big image at top)'
            );
            ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (optional)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
            <div class="description">
                <label for="<?php echo $this->get_field_id('variant') ?>">
                    Variant (required)<br/>
                    <?php echo aq_field_select('variant', $block_id, $variant_options, $variant) ?>
                </label>
            </div>
            <div class="description half">
                <label for="<?php echo $this->get_field_id('desktop_amount') ?>">
                    Desktop amount (required)<br/>
                    <input type="number" class="input-full input-upload" id="<?php echo $this->get_field_id('desktop_amount') ?>" name="<?php echo $this->get_field_name('desktop_amount') ?>" value="<?php echo $desktop_amount ?>" min="1" max="5" />
                </label>
            </div>
            <div class="description half">
                <label for="<?php echo $this->get_field_id('tablet_amount') ?>">
                    Tablet amount (required)<br/>
                    <input type="number" class="input-full input-upload" id="<?php echo $this->get_field_id('tablet_amount') ?>" name="<?php echo $this->get_field_name('tablet_amount') ?>" value="<?php echo $tablet_amount ?>" min="1" max="5" />
                </label>
            </div>
            <div class="description half last">
                <label for="<?php echo $this->get_field_id('mobile_amount') ?>">
                    Mobile amount (required)<br/>
                    <input type="number" class="input-full input-upload" id="<?php echo $this->get_field_id('mobile_amount') ?>" name="<?php echo $this->get_field_name('mobile_amount') ?>" value="<?php echo $mobile_amount ?>" min="1" max="5" />
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="description">
                <label for="<?php echo $this->get_field_id('force_controls_right') ?>">
                    <?php echo aq_field_checkbox('force_controls_right', $block_id, $force_controls_right) ?>
                    Place controls on the right
                </label>
            </div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $clients = is_array($clients) ? $clients : $defaults['clients'];
                    $count = 1;
                    foreach($clients as $client) {
                        $this->what_we_need($client, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="services-need" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function what_we_need($client = array(), $count = 0) {
            $content_title = strlen($client['content']) > 15 ? substr($client['content'], 0, 15) . '...' : $client['content'];
            ?>
            <li id="<?php echo $this->get_field_id('clients') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $content_title ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                     <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $client['content'] ?></textarea>
                      </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-image">
                            Image(only for second variant)<br/>
                            <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-image" class="input-full"  name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][image]" value="<?php echo $client['image'] ?>" />
                            <a rel="image" class="aq_upload_button button" href="#">Upload</a>
                        </label>
                    </p>
                    <p class="tab-desc description half">
                        <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-url">
                            Url (optional)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-url" class="input-full" name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][url]" value="<?php echo $client['url'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description half last">
                        <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-url_text">
                            Url text (optional)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-url_text" class="input-full" name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][url_text]" value="<?php echo $client['url_text'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>

            </li>
            <?php
        }

    function render_first_variant($services){
        foreach( $services as $service ){
            $url = trim($service['url']) ? $service['url'] : '#';
            $url_text = trim($service['url_text']) ? $service['url_text'] : 'Learn More';
            ?>
              <a href="<?=$url?>" target="_blank" class="services-need__slider-slide swiper-slide">
                <div class="services-need__slider-slide-content-wrapper">
                    <div class="services-need__slider-slide-content">
                        <?=do_shortcode(htmlspecialchars_decode($service['content']))?>
                    </div>
                    <span class="btn btn--orange services-need__learn-more"><span class="headline-four"><?=$url_text?></span></span>
                </div>
              </a>
            <?php
        }
    }

    function render_second_variant($services){
        foreach( $services as $service ){
            $url = trim($service['url']) ? $service['url'] : '#';
            $url_text = trim($service['url_text']) ? $service['url_text'] : 'Learn More';
            ?>
              <div class="services-need-variant-second__slider-slide swiper-slide">
                <div class="services-need-variant-second__slider-slide-content-wrapper">
                    <div class="services-need-variant-second__slider-slide-content">
                        <?=do_shortcode(htmlspecialchars_decode($service['content']))?>
                    </div>
                    <div class="services-need-variant-second__preview-container">
                        <img class="services-need-variant-second__preview" src="<?=$service['image']?>" alt="service provide" height="200" width="285">
                    </div>
                </div>
            </div>
            <?php
        }
    }

    function render_third_variant($services){
        foreach( $services as $service ){
            $url = trim($service['url']) ? $service['url'] : '#';
            $url_text = trim($service['url_text']) ? $service['url_text'] : 'Learn More';
            ?>
              <div class="services-need-variant-third__slider-slide swiper-slide">
                <div class="services-need-variant-third__slider-slide-content-wrapper">
                    <div class="services-need-variant-third__slider-slide-main-content">
                        <div class="services-need-variant-third__preview-container">
                            <img class="services-need-variant-third__preview" src="<?=$service['image']?>" alt="service provide" height="200" width="285">
                        </div>
                        <div class="services-need-variant-third__slider-slide-content">
                            <?=do_shortcode(htmlspecialchars_decode($service['content']))?>
                        </div>
                    </div>
                    <?=trim($service['url']) ? '<a class="btn btn--orange btn--default-space btn--content-centered btn--strict-fit services-need-variant-third__learn-more" href="'.$url.'"><span class="headline-four">'.$url_text.'</span></a>' : ''?>
                </div>
            </div>
            <?php
        }
    }

	function block($instance) {
       extract($instance);
       $headline = $title ? '<h2 class="headline-second">'.$title.'</h2>' : '';
       $force_controls_class = $force_controls_right ? 'services-need--force-controls' : '';
       $current_variant = $variant ? $variant : 'first';

        ?>
		<section class="section services-need">  
		    <div class="heading-wrapper <?=$force_controls_class?>">
            <?=$headline?>
				<div class="slider-double-controls">
					<button class="btn slider-double-controls__control sdc---prev swiper-button-disabled" >
					  <svg height="16" viewBox="0 0 11 16" fill="none">
						<path d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z" fill="currentColor"></path>
					  </svg>
					</button>
					<button class="btn slider-double-controls__control slider-double-controls__control--next sdc---next">
					  <svg height="16" viewBox="0 0 11 16" fill="none">
						<path d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z" fill="currentColor"></path>
					  </svg>
					</button>
				</div>
			</div>

            <div class="services-need__slider">
                <div class="services-need__slider-container-outer">
                    <div
                        class="services-need__slider-container"
                        data-slider-container
                        data-slider-setup='{"slidesPerView":1,"spaceBetween":20,"grid":{"rows":1},"navigation":{"nextEl":".services-need .sdc---next","prevEl":".services-need .sdc---prev"},"breakpoints":{"565":{"slidesPerView":<?php echo $mobile_amount;?>},"768":{"slidesPerView":<?php echo $tablet_amount;?>},"1024":{"slidesPerView":<?php echo $desktop_amount;?>}}}'
                      >
                        <div class="services-need__slider-wrapper swiper-wrapper swiper-wrapper--auto-height">
                              <?php
                                if($current_variant == 'first'){
                                    $this->render_first_variant($clients);
                                }else if ($current_variant == 'second'){
                                    $this->render_second_variant($clients);
                                }else if ($current_variant == 'third'){
                                    $this->render_third_variant($clients);
                                }
                              ?>
			        	  </div>
                      </div>

                </div>
            </div>
	    </section>
        <?php
        }

        /* AJAX add tab */
        function add_clientbox() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';


            $new_need = array(
                'content' => 'We need...',
                'url' => ''
            );

            if($count) {
                $this->what_we_need($new_need, $count);
            } else {
                die(-1);
            }

            die();
        }

        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}