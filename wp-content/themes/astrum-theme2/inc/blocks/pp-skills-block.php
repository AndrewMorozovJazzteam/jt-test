<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Skills_Block')) {
    class PP_Skills_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Skills bars',
                'size' => 'span8',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Skills_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_skill_add_new', array($this, 'add_skillbox'));

        }

        function form($instance) {

            $defaults = array(
                'boxes' => array(
                    1 => array(
                        'title' => 'New box',
                        'icon' => 'Icon',
                        'value' => 'Value %'

                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $boxes = is_array($boxes) ? $boxes : $defaults['boxes'];
                    $count = 1;
                    foreach($boxes as $box) {
                        $this->box($box, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="skill" class="aq-sortable-add-new button">Add New</a>
                <p></p>
            </div>

            <?php
        }

        function box($box = array(), $count = 0) {

            ?>
            <li id="<?php echo $this->get_field_id('boxes') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $box['title'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title">
                            Box Title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][title]" value="<?php echo $box['title'] ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-icon">
                            Box Icon <br/>
                            <?php global $fontawesome; ?>
                            <select name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][icon]" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-icon">
                                <?php
                                foreach ($fontawesome as $key => $value) {
                                    if ($box['icon'] == $key) { $sel = "selected"; } else { $sel = ''; }
                                    echo '<option '.$sel.' value="'.$key.'">'.substr($key,5).'</option>';
                                } ?>
                            </select>

                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-value">
                            Box Content<br/>
                              <select name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][value]" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-value">
                                <?php
                                $perc = array();
                                for ($i=0; $i < 101 ; $i=$i+5) {
                                     $perc[] = $i;
                                }
                                foreach ($perc as $key ) {
                                    if ($box['value'] == $key) { $sel = "selected"; } else { $sel = ''; }
                                    echo '<option '.$sel.' value="'.$key.'">'.$key.'</option>';
                                } ?>
                            </select>
                        </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>

            </li>
            <?php
        }

        function block($instance) {
            extract($instance);
            $count = 0;
            $output = '<div id="skillzz">';
            foreach( $boxes as $box ){
                $output  .= '<div class="skill-bar"><span class="skill-title"><i class="'.$box['icon'].'"></i> '.$box['title'].'</span><div class="skill-bar-value" style="width: '.$box['value'].'%;"></div></div>';
            }
            $output .= '</div>';
            echo $output;

        }

        /* AJAX add tab */
        function add_skillbox() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $box = array(
                'title' => 'New Skill',
                'icon' => 'Icon',
                'value' => 'Value %'
            );

            if($count) {
                $this->box($box, $count);
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
