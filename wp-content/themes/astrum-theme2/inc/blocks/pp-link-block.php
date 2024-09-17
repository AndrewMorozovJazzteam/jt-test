<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Link_Block')) {
    class PP_Link_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Link',
                'size' => 'span14',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Link_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_client_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Link',
                'clients' => array(
                    1 => array(
                        'title' => 'New title',
                        'image' => 'Image',
                        'url' => 'Link',
                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (required)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
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
                <a href="#" rel="client" class="aq-sortable-add-new button">Add New</a>
                <p></p>
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
            $randID = rand(1, 99);
            $size=substr($size, 4);
            $insidewidth = $size-2;
            $temp_width = "span".floor($insidewidth);
            $new_witdh =  AQ_Block::transform_span_to_gs($temp_width);
          ?>
            

           
		    <!-- ShowBiz Carousel -->
        <div class="showbiz-container recent-work">

            <!-- Navigation -->
            <div class="showbiz-navigation">
                <div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
                <div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
            </div>
            <div class="clearfix"></div>

            <!-- Portfolio Entries -->
            <div class="showbiz" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
              <div class="overflowholder">
                    <ul>
                        

                         <!-- Item -->
						<?php foreach( $clients as $client ){ ?> 
						 
                        <li>
                            <div class="blog-item media">
                                <figure>
									<?php if($client['url']) { ?>	
                                    <div class="mediaholder">
                                        
										
											 								
												<a href="<?php echo $client['url']; ?>" title="<?php echo $client['title']; ?>">
													
													<img width="280" height="200" src="<?php echo $client['image']; ?>" class="attachment-portfolio-4col size-portfolio-4col wp-post-image" alt="<?php echo $client['image']; ?>" loading="lazy">
													<div class="hovercover">
													<div class="hovericon"><i class="hoverlink"></i></div>
													</div>
												</a>
												
												
												
												
											
											
										
                                        
                                    </div>
									<a href="<?php echo $client['url']; ?>">
										<figcaption class="item-description">

										<h5><?php echo $client['title']; ?></h5>
										</figcaption>
									</a>
									<?php } ?>

                                </figure>
                            </div>
                        </li>                    
						<?php } ?> 
                    </ul>
                    <div class="clearfix"></div>

                </div>
                <div class="clearfix"></div>

            </div>
        </div>
          
		   
		   

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
                'title' => 'New title',
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