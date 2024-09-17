<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Social_Block')) {
    class PP_Social_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Social icons',
                'size' => 'span12',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Social_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_socialicon_add_new', array($this, 'add_socialicon'));

        }

        function form($instance) {

            $defaults = array(
                'boxes' => array(
                    1 => array(
                        'type' => 'Social service',
                        'url' => 'URL'
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
                <a href="#" rel="socialicon" class="aq-sortable-add-new button">Add New</a>
                <p></p>
            </div>

            <?php
        }

        function box($box = array(), $count = 0) {

            ?>
            <li id="<?php echo $this->get_field_id('boxes') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $box['type'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-type">
                            Type<br/>
                            <?php $icons =array(
                                array('value'=> 'twitter','label' => 'Twitter','src'=> ''),
								array('value'=> 'vk','label' => 'vk','src'=> ''),
                                array('value'=> 'wordpress','label' => 'WordPress','src'=> ''),
                                array('value'=> 'facebook','label' => 'Facebook','src'=> ''),
                                array('value'=> 'linkedin','label' => 'LinkedIN','src'=> ''),
                                array('value'=> 'steam','label' => 'Steam','src'=> ''),
                                array('value'=> 'tumblr','label' => 'Tumblr','src'=> ''),
                                array('value'=> 'github','label' => 'GitHub','src'=> ''),
                                array('value'=> 'delicious','label' => 'Delicious','src'=> ''),
                                array('value'=> 'instagram','label' => 'Instagram','src'=> ''),
                                array('value'=> 'xing','label' => 'Xing','src'=> ''),
                                array('value'=> 'amazon','label'=> 'Amazon','src'=> ''),
                                array('value'=> 'dropbox','label'=> 'Dropbox','src'=> ''),
                                array('value'=> 'paypal','label'=> 'PayPal','src'=> ''),
                                array('value'=> 'lastfm','label' => 'LastFM','src'=> ''),
                                array('value'=> 'gplus','label' => 'Google Plus','src'=> ''),
                                array('value'=> 'yahoo','label' => 'Yahoo','src'=> ''),
                                array('value'=> 'pinterest','label' => 'Pinterest','src'=> ''),
                                array('value'=> 'dribbble','label' => 'Dribbble','src'=> ''),
                                array('value'=> 'flickr','label' => 'Flickr','src'=> ''),
                                array('value'=> 'reddit','label' => 'Reddit','src'=> ''),
                                array('value'=> 'vimeo','label' => 'Vimeo','src'=> ''),
                                array('value'=> 'spotify','label' => 'Spotify','src'=> ''),
                                array('value'=> 'rss','label' => 'RSS','src'=> ''),
                                array('value'=> 'youtube','label' => 'YouTube','src'=> ''),
                                array('value'=> 'blogger','label' => 'Blogger','src'=> ''),
                                array('value'=> 'appstore','label' => 'AppStore','src'=> ''),
                                array('value'=> 'evernote','label' => 'Evernote','src'=> ''),
                                array('value'=> 'digg','label' => 'Digg','src'=> ''),
                                array('value'=> 'forrst','label' => 'Forrst','src'=> ''),
                                array('value'=> 'fivehundredpx','label' => '500px','src'=> ''),
                                array('value'=> 'stumbleupon','label' => 'StumbleUpon','src'=> ''),
                                array('value'=> 'dribbble','label' => 'Dribbble','src'=> '')
                                );
                                ?>
                                <select name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][type]" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-type">
                                    <?php
                                    foreach ($icons as $key) {
                                        if ( $box['type'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
                                        echo '<option '.$sel.' value="'.$key['value'].'">'.$key['label'].'</option>';
                                    } ?>
                                </select>
                            </label>
                        </p>
                        <p class="tab-desc description">
                            <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url">
                                URL <br/>
                                <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][url]" value="<?php echo $box['url'] ?>" />
                            </label>
                        </p>
                        <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                    </div>

                </li>
                <?php
            }

            function block($instance) {
                extract($instance);
                $output = '<ul class="social-icons clearfix">';
                foreach( $boxes as $box ){
                   $output .= ' <li><a class="'.$box['type'].'" href="'.$box['url'].'"><i class="icon-'.$box['type'].'"></i></a></li>';
               }
               $output .= '</ul>';
               echo $output;

           }

           /* AJAX add tab */
           function add_socialicon() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $box = array(
                'type' => 'New Icon',
                'url' => ''
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
