<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Technical_Competence_Block')) {
    class PP_Technical_Competence_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Technical Competence',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Technical_Competence_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_technologies-competence_add_new', array($this, 'add_clientbox'));
            add_action('wp_ajax_aq_block_tech-competence-link_add_new', array($this, 'add_column'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Technical competence',
                'rows' => array(
                    1 => array(
                        'title' => 'Technical competence Unit',
                        'link' => '',
						'type' => 'white',
                        'links' => array(
                            1 => array(
                                'resize' => 'technology__icon-inner--resize-normal',
                                'icon' =>'',
                                'name' => 'Tech',
                                'image' => '',
                                'link' => ''
                            ),
                        ),
                        
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
                    $rows = is_array($rows) ? $rows : $defaults['rows'];
                    $count = 1;
                    foreach($rows as $row) {
                        $this->client($row, $count,$block_id);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="technologies-competence" class="aq-sortable-add-new button">Add New Technical competence Unit</a>
                <p></p>
            </div>

            <?php
        }

        function client($row = array(), $count = 0, $block_id = 'aq_block_12000') {
			      $select_options = array(
                array('value'=> 'white','label' => 'White'),
			    array('value'=> 'blue','label' => 'Blue') 
			      );

            ?>
            <li id="<?php echo $this->get_field_id('rows') ?>-sortable-item-<?php echo $count ?>" class="sortable-item sortable-root" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $row['title'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title">
                            Column title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][title]" value="<?php echo $row['title'] ?>" />
                        </label>
                    </p>
					
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-type">
                            Color type<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][type]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title">
                                    <?php
                                    foreach ($select_options as $key) {
                                        if ( $row['type'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
                                        echo '<option '.$sel.' value="'.$key['value'].'">'.$key['label'].'</option>';
                                    } ?>
                                </select>
                            </label>
                        </p>

                    <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-link">
                          Link URL<br/>
                          <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-link" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][link]" value="<?php echo $row['link'] ?>" />
                      </label>
                  </p>
                        <ul id="aq-sortable-list-link-<?php echo $block_id; ?>" class="aq-sortable-list" rel="<?php echo $block_id; ?>">
                        <?php
                            $link_count = 1;
                                foreach ($row['links'] as $link) {
                                $this->link($link, $count, $link_count);
                                $link_count++;
                            }
                        ?>
                        </ul>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                    <a href="#" rel="tech-competence-link" class="aq-sortable-add-new button" data-args="<?php echo $count; ?>">Add New Tech</a>
                    
                </div>

            </li>
            <?php
        }

        function link($link = array(), $count = 0, $link_count = 0, $block_id = 'aq_block_120000') {			
            $resize_options = array(
                array('value'=> 'technology__icon-inner--resize-small','label' => 'Small'),
                array('value'=> 'technology__icon-inner--resize-normal','label' => 'Normal'),
                array('value'=> 'technology__icon-inner--resize-big','label' => 'Big'),
                array('value'=> 'technology__icon-inner--resize-ultra-big','label' => 'Huge') 
            );
            ?>
            <li class="sortable-item" rel="<?php echo $link_count; ?>">

            <div class="sortable-head cf">
                <div class="sortable-title">
                    <strong><?php echo $link['name'] ?></strong>
                </div>
                <div class="sortable-handle" rel="aq-sortable-list-link-<?php echo $block_id; ?>">
                    <a href="#">Open / Close</a>
                </div>
            </div>
        
            <div class="sortable-body">

                 <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-name">
                        Link Name<br/>
                        <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-name" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][name]" value="<?php echo $link['name'] ?>" />
                    </label>
                </p>

                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-link">
                        Link URL<br/>
                        <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-link" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][link]" value="<?php echo $link['link'] ?>" />
                    </label>
                </p>
 
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-icon">
                        Icon URL<br/>
                        <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-icon" class="input-full"  name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][icon]" value="<?php echo $link['icon'] ?>" />
                        <a rel="image" class="aq_upload_button button" href="#">Upload</a>
                    </label>
                </p>
                <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-resize">
                            Icon size type<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][resize]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-resize">
                                    <?php
				
                                    foreach ($resize_options as $key) {
                                        if ( $link['resize'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
                                        echo '<option '.$sel.' value="'.$key['value'].'">'.$key['label'].'</option>';
                                    } ?>
                            </select>
                        </label>
                </p>
                <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
            </div>
            
            </li>
            <?php
        }

        // ------------

        function render_tech($tech){
            $link_prefix = $tech['link'] === '' ? 'span' : 'a href="'.$tech['link'].'"';
            $link_postfix = $tech['link'] === '' ? 'span' : 'a';

            ?>
            <<?=$link_prefix?> class="technology">
              <span class="technology__icon">
                <img 
                  src="<?=$tech['icon']?>"
                  alt="<?=$tech['name']?>"
                  width="35"
                  height="35"
                  class="technology__icon-inner <?=$tech['resize']?>"
                />
              </span>
              <span class="text-second"><?=$tech['name']?></span>
            </<?=$link_postfix?>><?php
        }

        function render_competence_unit($tech_unit){
            $tech_colors = array('white' => '', 'blue'=>'technical-competence-unit--blue');

            ?>
            <div class="technical-competence-unit <?=$tech_colors[$tech_unit['type']]?>">
                <a href="<?=$tech_unit['link']?>" class="technical-competence-unit__row-title-container">
                  <span class="headline-third headline-third--color-inherit"><?=$tech_unit['title']?></span>
                </a>
                <div class="technical-competence-unit__technologies">
                <?php foreach($tech_unit['links'] as $tech){
                    $this->render_tech($tech);
                } ?>
                </div>
              </div><?php
        }

        function block($instance) {
            extract($instance);
            $headline = $title ? '<h2 class="headline-second">'.$title.'</h2>' : '';
          ?>

        <section class="section technical-competence">
            <?=$headline?>
            <div class="technical-competence__table">
            <?php foreach($rows as $tech_unit){
                $this->render_competence_unit($tech_unit);
            } ?>
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
                'title' => 'Technical competence Unit',
                'link' => '',
                'type' => 'white',
                'links' => array(
                    1 => array(
                        'resize' => 'technology__icon-inner--resize-normal',
                        'icon' =>'',
                        'name' => 'Tech',
                        'image' => '',
                        'link' => ''
                    ),
                ),
                
            );

            if($count) {
                $this->client($client, $count, $_POST['block_id']);
            } else {
                die(-1);
            }

            die();
        }

        
        function add_column() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $args = isset($_POST['args']) ? absint($_POST['args']) : 1;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $client = array(
                'resize' => 'technology__icon-inner--resize-normal',
                'icon' =>'',
                'name' => 'Tech',
                'image' => '',
                'link' => ''
            );

            if($count) {
                $this->link($client, $args, $count,$_POST['block_id']);
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