<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Modern_Technologies_Block')) {
    class PP_Modern_Technologies_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(M) Technologies',
                'size' => 'span12',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Modern_Technologies_Block', $block_options);
			

            add_action('wp_ajax_aq_block_modern_technologies_add_new', array($this, 'add_tech'));
        }

        function form($instance) {

            $defaults = array(
                'technologies' => array(
                    1 => array(
                        'title' => 'New Technology',                        
                        'upload' => ''
                    ),
                ),
				'columns' => 4
            );

        $instance = wp_parse_args($instance, $defaults);
        extract($instance); ?>
        <p class="description">
            <label for="<?php echo $this->get_field_id('columns') ?>">
                Columns (required)<br/>
                <input id="<?php echo $this->get_field_id('columns') ?>" class="input-full" type="number" min="2" max="4"  value="<?php echo $columns ?>"  name="<?php echo $this->get_field_name('columns') ?>">
            </label>
        </p>

        <div class="description cf">
              Technologies<br/>
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $technologies = is_array($technologies) ? $technologies : $defaults['technologies'];
                    $count = 1;
                    foreach($technologies as $tech) {
                        $this->admin_render_techies($tech, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <a href="#" rel="modern_technologies" class="aq-sortable-add-new button">Add New</a>
            </div>
        <?php
    }
		
    function admin_render_techies($tech = array(), $count = 0) {
        ?>
        <li id="<?php echo $this->get_field_id('technologies') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

            <div class="sortable-head cf">
                <div class="sortable-title">
                    <strong><?php echo $tech['title'] ?></strong>
                </div>
                <div class="sortable-handle">
                    <a href="#">Open / Close</a>
                </div>
            </div>

            <div class="sortable-body">
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('technologies') ?>-<?php echo $count ?>-title">
                        Tech Title<br/>
                        <input type="text" id="<?php echo $this->get_field_id('technologies') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('technologies') ?>[<?php echo $count ?>][title]" value="<?php echo $tech['title'] ?>" />
                    </label>
                </p>
                
                <p class="description">
                    <label for="<?php echo $this->get_field_id('technologies') ?>-<?php echo $count ?>-upload">
                        Upload Image <br/>
                        <input type="text" id="<?php echo $this->get_field_id('technologies') ?>-<?php echo $count ?>-upload" class="input-full input-upload" value="<?php if(isset($tech['upload'])) { echo $tech['upload']; } ?>" name="<?php echo $this->get_field_name('technologies') ?>[<?php echo $count ?>][upload]">
                        <a href="#" class="aq_upload_button button" rel="image">Upload</a>                            
                        <?php if(isset($upload) && !empty($upload)) { ?>
                            <div class="screenshot">
                                <img src="<?php echo $upload ?>"  alt="screenshot"/>
                            </div>
                        <?php } ?>
                    </label>
                </p>
                
                <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
            </div>

        </li>
        <?php
    }

    function block($instance) {
        extract($instance);

        ?>

        <div class="modern-technologies modern-technologies--columns-<?php echo $columns; ?>">
            <?php 
            foreach($technologies as $tech){
                ?> 
                <div class="modern-technologies__tech">
                    <div class="modern-technologies__tech-picture-container">
                        <img class="modern-technologies__tech-picture" src="<?php echo $tech['upload']; ?>" alt="tech picture" width="60" height="60"/>
                    </div>
                    <span class="modern-technologies__title"><?php echo $tech['title']; ?></span>
                </div>
                <?php
            }
            ?>
        </div>
        <?php 
    }
	

    function add_tech() {
        $nonce = $_POST['security'];
        if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

        $count = isset($_POST['count']) ? absint($_POST['count']) : false;
        $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';


        $box = array(
            'title' => 'New Technology',                        
            'upload' => ''
        );

        if($count) {
            $this->admin_render_techies($box, $count);
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
