<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_Solutions_Goals_Block')) {
    class PP_Solutions_Goals_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Solutions goals',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Solutions_Goals_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_solution-goal_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Solutions Goals',
                'solutions' => array(
                    1 => array(
                        'content' => 'Goal...',
                    )
                )

            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
            <div class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title (optional)<br/>
                    <?php echo aq_field_input('title', $block_id, $title) ?>
                </label>
            </div>
            <div class="clearfix"></div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $solutions = is_array($solutions) ? $solutions : $defaults['solutions'];
                    $count = 1;
                    foreach($solutions as $solution) {
                        $this->solution_goals($solution, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="solution-goal" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function solution_goals($solution = array(), $count = 0) {
            $content_title = strlen($solution['content']) > 15 ? substr($solution['content'], 0, 15) . '...' : $solution['content'];
            ?>
            <li id="<?php echo $this->get_field_id('solutions') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

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
                      <label for="<?php echo $this->get_field_id('solutions') ?>-<?php echo $count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('solutions') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('solutions') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $solution['content'] ?></textarea>
                      </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>
                
            </li>
            <?php
        }

	function block($instance) {
       extract($instance);
       $headline = $title ? '<div class="solution-goals__headline-container"><h2 class="headline-second">'.do_shortcode(htmlspecialchars_decode($title)).'</h2></div>' : '';
       $uploads_dir = wp_upload_dir()['baseurl'];
       $icon_url = $uploads_dir.'/2024/02/arrow-circle.svg';
        ?>
		<section class="solution-goals">  
            <?=$headline?>
            <div class="solution-goals__goals">
                <?php
                foreach($solutions as $solution){
                    ?><div class="solution-goals__goal"><img  class="solution-goals__icon" src="<?=$icon_url?>" alt="solution check" height="24" width="24"/>
                    <div class="solution-goals__content"><?=do_shortcode(htmlspecialchars_decode($solution['content']))?></div>
                    </div><?php
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
                'content' => 'Goal...',
            );

            if($count) {
                $this->solution_goals($new_need, $count);
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