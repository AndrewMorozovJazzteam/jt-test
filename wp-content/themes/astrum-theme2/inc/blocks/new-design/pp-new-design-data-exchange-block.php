<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_Data_Exchange_Block')) {
    class PP_Data_Exchange_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Data Exchange',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Data_Exchange_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_data-exchange_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'What we need',
                'title_variant' => 'h3',
                'list_variant' => 'arrow',
                'exchanges' => array(
                    1 => array(
                        'content' => 'Exchange...',
                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance);
            $title_variant_options = [
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
                'span'=> 'span',
            ];

            $list_variant_options = [
                'arrow' => 'arrow',
                'dash' => 'dash'
            ];
            
            ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (optional)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
            <div class="description half">
                <label for="<?php echo $this->get_field_id('title_variant') ?>">
                    Title element<br/>
                    <?php echo aq_field_select('title_variant', $block_id,  $title_variant_options, $title_variant) ?>
                </label>
            </div>
            <div class="description half last">
                <label for="<?php echo $this->get_field_id('list_variant') ?>">
                    List type<br/>
                    <?php echo aq_field_select('list_variant', $block_id,  $list_variant_options, $list_variant) ?>
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $exchanges = is_array($exchanges) ? $exchanges : $defaults['exchanges'];
                    $count = 1;
                    foreach($exchanges as $exchange) {
                        $this->data_exchange($exchange, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="data-exchange" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function data_exchange($exchange = array(), $count = 0) {
            $content_title = strlen($exchange['content']) > 15 ? substr($exchange['content'], 0, 15) . '...' : $exchange['content'];
            ?>
            <li id="<?php echo $this->get_field_id('exchanges') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

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
                      <label for="<?php echo $this->get_field_id('exchanges') ?>-<?php echo $count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('exchanges') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('exchanges') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $exchange['content'] ?></textarea>
                      </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>
                
            </li>
            <?php
        }

	function block($instance) {
       extract($instance);
       $title_variant_element = $title_variant ? $title_variant : 'h3';
       $headline = $title ? '<div class="data-exchange__headline-container"><'.$title_variant_element.' class="headline-third">'.do_shortcode(htmlspecialchars_decode($title)).'</'.$title_variant_element.'></div>' : '';
       $current_list_type = $list_variant ? $list_variant : 'arrow';
        ?>
		<section class="section data-exchange">  
            <?=$headline?>
            <div class="data-exchange__exchange-content">
                <ul class="list-compound list-compound--column list-compound--default-gap">
                    <?php
                    foreach($exchanges as $exchange){
                        ?><li class="list-compound__item list-compound__item--type-<?=$current_list_type?> list-compound__item--default-gap"><?=do_shortcode(htmlspecialchars_decode($exchange['content']))?></li><?php
                    }
                    ?>
                </ul>
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
                'content' => 'Exchange...',
            );

            if($count) {
                $this->data_exchange($new_need, $count);
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