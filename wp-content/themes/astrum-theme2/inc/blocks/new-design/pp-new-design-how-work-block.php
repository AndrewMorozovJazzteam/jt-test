<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_How_Work_Block')) {
    class PP_How_Work_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) How Work',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_How_Work_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_how-work_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'How Work',
				'variant' => 'first',
                'rows' => array(
                    1 => array(
                        'title' => 'How Work Unit',
                        'content' => '',
                        'title_variant' => 'h3',
                    )
                )
            );
						
            $instance = wp_parse_args($instance, $defaults);
            extract($instance);
			
            $variant_options = array(
                'first' => 'first',
                'second' => 'with light blue background-color',
            );
			
            ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('variant') ?>">
                    Variant (required)<br/>
                    <?php echo aq_field_select('variant', $block_id, $variant_options, $variant) ?>
                </label>
            </div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $rows = is_array($rows) ? $rows : $defaults['rows'];
                    $count = 1;
                    foreach($rows as $row) {
                        $this->how_work($row, $count,$block_id);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="how-work" class="aq-sortable-add-new button">Add New work Unit</a>
                <p></p>
            </div>

            <?php
        }

        function how_work($row = array(), $count = 0, $block_id = 'aq_block_12000') {

            $title_variant_options = array(
                array('value'=> 'h1','label' => 'h1'),
                array('value'=> 'h2','label' => 'h2'),
                array('value'=> 'h3','label' => 'h3'),
                array('value'=> 'h4','label' => 'h4'),
                array('value'=> 'h5','label' => 'h5'),
                array('value'=> 'h6','label' => 'h6'),
                array('value'=> 'span','label' => 'span'),
            );
            $title_variant = $row['title_variant'] ? $row['title_variant'] : 'h3';
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
                            Title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][title]" value="<?php echo $row['title'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $row['content'] ?></textarea>
                      </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title_variant">
                            Title element<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][title_variant]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title_variant">
                                    <?php
                                    foreach ($title_variant_options as $key) {
                                        if ( $title_variant == $key['value']) { 
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
        // ------------
        function render_how_work_unit($how_work_unit, $number, $title_variant_element, $is_last, $modif){
			$number_prettified = $number < 10 ? '0'.$number : $number;
			$headline = $how_work_unit['title'] ? '<'.$title_variant_element.' class="headline-third">'.$how_work_unit['title'].'</'.$title_variant_element.'>' : '';
			$last_class = $is_last ? 'how-work-unit--last' : '';
            ?>
            <div class="how-work-unit <?=$last_class?> <?=$modif['class']?>">
              <div class="how-work-unit__meta">
                <span class="how-work-unit__counter">
                  <?=$number_prettified?>
                </span>
              </div>
              <div class="how-work-unit__content">
				<?=$headline?>
                <?=do_shortcode(htmlspecialchars_decode($how_work_unit['content']))?>
              </div>
            </div><?php
        }

        function block($instance) {
            extract($instance);
            $headline = $title ? '<h2 class="headline-second">'.$title.'</h2>' : '';
            $i = 0;
			$current_variant = $variant ? $variant : 'first';
          ?>
          <section>
            <?=$headline?>
            <div class="how-work__list">
			<div class="how-work__timeline"></div>
            <?php 
			  $modif = $current_variant === 'second' ? array('class' => 'how-work-unit--color-invert') : array();
			  foreach($rows as $tech_unit){
                $i++;
			    $is_last = count($rows) === $i;
                $title_variant_element = $tech_unit['title_variant'] ? $tech_unit['title_variant'] : 'h3';
                $this->render_how_work_unit($tech_unit, $i, $title_variant_element, $is_last, $modif);
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
            $new_work = array(
              'title' => 'How Work Unit',
              'content' => '',
              'title_variant' => 'h3',
            );

            if($count) {
                $this->how_work($new_work, $count, $_POST['block_id']);
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