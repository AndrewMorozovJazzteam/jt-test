<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Clients_Block')) {
    class PP_Clients_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Clients',
                'size' => 'span12',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Clients_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_client_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Our clients',
				'description' => '',
                'clients' => array(
                    1 => array(
                        'title' => 'New client',
                        'image' => 'Clients logo',
                        'url' => 'Clients website',
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
            <div class="description">
                <label for="<?php echo $this->get_field_id('description') ?>">
                    Description (optional)<br/>
					<?php echo aq_field_textarea('description', $block_id,  $description); ?>
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
	   <?php if (!empty($title)) { ?>
            <div class="headline-through">
                <h2 class="headline" data-toc-numbers="true"><?php echo $title; ?></h2>
                <span class="line" style="margin-bottom:0;"></span>
            </div>
			<div class="clearfix"></div>
	    <?php } ?>
	   <?php if (!empty($description)) { ?>
            <div class="description clients-description">
                <?php echo htmlspecialchars_decode($description); ?>
            </div>
	    <?php } ?>

            <!-- Navigation / Left -->
            <div class="one alpha carousel column"><div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left-2"><i class="icon-angle-left"></i></div></div>

            <!-- ShowBiz Carousel -->
            <div class="our-clients-cont showbiz-container <?php echo $new_witdh; ?> carousel columns" >

            <!-- Portfolio Entries -->
            <div class="showbiz our-clients" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
                <div class="overflowholder">

                    <ul>
                    <?php
                    $output = '';
                    foreach( $clients as $client ){
                        if($client['url']) {
                            $output .= '<li><a href="'.$client['url'].'"><img src="'.$client['image'].'" alt="'.$client['title'].'" /></a></li>';
                        } else {
                            $output .= '<li><img src="'.$client['image'].'" alt="'.$client['title'].'" /></li>';
                        }
                    }
                    echo $output;
                     ?>

                    </ul>
                    <div class="clearfix"></div>

                </div>
                <div class="clearfix"></div>

            </div>
            </div>

            <!-- Navigation / Right -->
            <div class="one carousel omega column"><div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right-2"><i class="icon-angle-right"></i></div></div>

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
                'title' => 'New Client',
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