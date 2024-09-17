<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_New_Design_Clients_Block')) {
    class PP_New_Design_Clients_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Clients',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_New_Design_Clients_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_new-client_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Our clients',
				'description' => '',
                'desktop_amount' => '5',
                'tablet_amount' => '3',
                'mobile_amount' => '1',
                'clients' => array(
                    1 => array(
                        'title' => 'New Client name',
                        'image' => 'New Client logo',
                        'url' => '',
                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
            <div class="description half">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (required)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
            <div class="description half last">
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
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $clients = is_array($clients) ? $clients : $defaults['clients'];
                    $count = 1;
                    foreach($clients as $client) {
                        $this->client($client, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="new-client" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function client($client = array(), $count = 0) {

            ?>
            <li id="<?php echo $this->get_field_id('clients') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $client['title'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-title">
                            Clients name<br/>
                            <input type="text" id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][title]" value="<?php echo $client['title'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-icon">
                            Client logo<br/>
                            <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-icon" class="input-full"  name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][image]" value="<?php echo $client['image'] ?>" />
                            <a rel="image" class="aq_upload_button button" href="#">Upload</a>
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-content">
                            Client's url (optional)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('clients') ?>-<?php echo $count ?>-url" class="input-full" name="<?php echo $this->get_field_name('clients') ?>[<?php echo $count ?>][url]" value="<?php echo $client['url'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>

            </li>
            <?php
        }

	function block($instance) {
       extract($instance);
       $headline = $title ? '<h2 class="headline-second">'.$title.'</h2>' : '';
?>
		<section class="section customers">  
    <?=$headline?>
		<div class="customers__slider">
            <button class="btn customers__slider-control customers__slider-prev">
              <svg width="11" height="16" viewBox="0 0 11 16" fill="none">
                <path
                  d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
                  fill="currentColor"
                />
              </svg>
            </button>
            <div class="customers__slider-container-outer">
              <div
                 class="customers__slider-container"
                 data-slider-container
                 data-slider-setup='{"slidesPerView":<?=$mobile_amount?>,"navigation":{"nextEl":".customers__slider-next","prevEl":".customers__slider-prev"},"breakpoints":{"768":{"slidesPerView":<?=$tablet_amount?>},"1124":{"slidesPerView":<?=$desktop_amount?>}}}'
                  >
                <div class="customers__slider-wrapper swiper-wrapper">
						<?php
						$output = '';
						foreach( $clients as $client ){
							$output .= '<div class="customers__slider-slide swiper-slide"><div class="customers__slider-slide-content">';
                            
							if($client['url']) {								
								$output .= '<a href="'.$client['url'].'">
											  <img 										    
												src="'.$client['image'].'"
												alt="'.$client['title'].'"
												width="174"
												height="78"
											  />
											</a>';
							} else {
								$output .= '<img  
												src="'.$client['image'].'"
												alt="'.$client['title'].'"
												width="174"
												height="78"
											  />';
							}
							$output .= '</div></div>';
						}
						
						echo $output;
						?>						
						
			    </div>					
			  </div>
			</div>
        <button class="btn customers__slider-control customers__slider-next">
          <svg width="11" height="16" viewBox="0 0 11 16" fill="none">
            <path
              d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
              fill="currentColor"
            />
          </svg>
        </button>
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

            //default key/value for the tab
            $client = array(
                'title' => 'New Client name',
                'image' => '',
                'url' => ''
            );

            if($count) {
                $this->client($client, $count);
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