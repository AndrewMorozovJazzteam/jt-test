<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_Test_Automation_Grid_Block')) {
    class PP_Test_Automation_Grid_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) TA Grid',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Test_Automation_Grid_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_ta-grid_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Test automation',
		'anchor' => '',
		'variant' => 'first',
                'list' => array(
                    1 => array(
                        'content' => 'Test automation content...',
                        'image' => '',
                        'direction' => 'forward',
                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); 
            $variant_options = array(
                'first' => 'first',
                'second' => 'second(with medium gap between text & image, and gap between elements)',
            );?>
            <div class="description half">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (optional)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
	    <div class="description half last">
                <label for="<?php echo $this->get_field_id('anchor') ?>">
                    Anchor (optional)<br/>
                    <?php echo aq_field_input('anchor', $block_id, $anchor) ?>
                </label>
            </div>
            <div class="description">
                <label for="<?php echo $this->get_field_id('variant') ?>">
                    Variant (required)<br/>
                    <?php echo aq_field_select('variant', $block_id, $variant_options, $variant) ?>
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $list = is_array($list) ? $list : $defaults['list'];
                    $count = 1;
                    foreach($list as $list_el) {
                        $this->render_admin_ta_grid($list_el, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="ta-grid" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function render_admin_ta_grid($list_el = array(), $count = 0) {
            $content_title = strlen($list_el['content']) > 15 ? substr($list_el['content'], 0, 15) . '...' : $list_el['content'];
            
            $direction_variant_options = array(
              array('value'=> 'forward','label' => 'forward'),
              array('value'=> 'reverse','label' => 'reverse'),
          );

          $direction_variant = $list_el['direction'] ? $list_el['direction'] : 'forward';
            ?>
            <li id="<?php echo $this->get_field_id('list') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

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
                        <label for="<?php echo $this->get_field_id('list') ?>-<?php echo $count ?>-icon">
                            Image<br/>
                            <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('list') ?>-<?php echo $count ?>-icon" class="input-full"  name="<?php echo $this->get_field_name('list') ?>[<?php echo $count ?>][image]" value="<?php echo $list_el['image'] ?>" />
                            <a rel="image" class="aq_upload_button button" href="#">Upload Image</a>
                        </label>
                    </p>

                     <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('list') ?>-<?php echo $count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('list') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('list') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $list_el['content'] ?></textarea>
                      </label>
                     </p>

                     <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('list') ?>-<?php echo $count ?>-direction">
                            Direction<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('list') ?>[<?php echo $count ?>][direction]" id="<?php echo $this->get_field_id('list') ?>-<?php echo $count ?>-direction">
                                    <?php
                                    foreach ($direction_variant_options as $key) {
                                        if ( $direction_variant == $key['value']) { 
                                            $sel = "selected";
                                         } else { 
                                            $sel = '';
                                         }
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
  function render_board($img_src, $content, $direction, $modif){
    ?>
      <div class="ta-board-item ta-board-item--direction-<?=$direction?> <?=$modif['class']?>">
        <div class="ta-board-item__left">
          <img class="ta-board-item__picture" src="<?=$img_src?>" alt="Icon" height="200" width="200"/>
        </div>
        <div class="ta-board-item__right">
          <?=do_shortcode(htmlspecialchars_decode($content))?>
        </div>
      </div>
    <?php
  }

    function block($instance) {
       extract($instance);
       		$title_with_anchor = $anchor ? '<span id="'.$anchor.'">'.do_shortcode(htmlspecialchars_decode($title)).'</span>'  : do_shortcode(htmlspecialchars_decode($title));
       		$headline = $title ? '<div class="list_el-goals__headline-container"><h2 class="headline-second">'.$title_with_anchor.'</h2></div>' : '';	   
		$current_variant = $variant ? $variant : 'first';
        ?>
		  <section class="section ta-board">  
            <?=$headline?>
            <div class="ta-board__container <?=$current_variant === 'second' ? 'ta-board__container--medium-gap' : ''?>">
                <?php
				$modif = $current_variant === 'second' ? array('class' => 'ta-board-item--medium-gap') : array();
                foreach($list as $list_el){
                  $direction = $list_el['direction'] ? $list_el['direction'] : 'forward';
                  $this->render_board($list_el['image'], $list_el['content'], $direction,$modif);
                }
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


            $new_need = array(
              'content' => 'Test automation content...',
              'image' => '',
              'direction' => 'forward',
            );

            if($count) {
                $this->render_admin_ta_grid($new_need, $count);
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