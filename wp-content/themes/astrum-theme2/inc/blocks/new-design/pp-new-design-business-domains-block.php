<?php
/* Business Domains Block - PureThemes */
if(!class_exists('PP_New_Design_Business_Domains_Block')) {
    class PP_New_Design_Business_Domains_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Business Domains',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_New_Design_Business_Domains_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_client_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Our clients',
                'clients' => array(
                    1 => array(
                        'title' => 'New Title',
                        'image' => 'Icon',
                        'url' => 'URL',
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
	<section class="section industry-experience">  
        <?=$headline?>
		<div class="industry-experience__list">		
			<?php
				$output = '';
				foreach( $clients as $client ){					
					if($client['url']) {								
						$output .= '<a class="industry-experience__link" href="'.$client['url'].'"><span class="industry-experience__icon-container">
						  <img loading="lazy"
							src="'.$client['image'].'"
							alt="'.$client['title'].'"							
						  />
						</span><span class="headline-four headline-four--color-inherit">'.$client['title'].'</span></a>';
						} else {
						  $output .= '<span class="industry-experience__icon-container"><img loading="lazy"
							src="'.$client['image'].'"
							alt="'.$client['title'].'"
						  /></span><span class="headline-four headline-four--color-inherit">'.$client['title'].'</span>';
					}					
				}						
				echo $output;
			?>					
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