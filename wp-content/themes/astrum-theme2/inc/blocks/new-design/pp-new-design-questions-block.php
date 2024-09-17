<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_Questions_Block')) {
    class PP_Questions_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Questions',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Questions_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_questions_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Questions',
                'questions' => array(
                    1 => array(
                        'content_preview' => 'Question...',
                        'content' => 'Question answer...',
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
                    $questions = is_array($questions) ? $questions : $defaults['questions'];
                    $count = 1;
                    foreach($questions as $question) {
                        $this->render_admin_questions($question, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="questions" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function render_admin_questions($question = array(), $count = 0) {
            ?>
            <li id="<?php echo $this->get_field_id('questions') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $question['content_preview'] ?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                    <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('questions') ?>-<?php echo $count ?>-content_preview">
                          Question<br/>
                          <input type="text" id="<?php echo $this->get_field_id('questions') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('questions') ?>[<?php echo $count ?>][content_preview]" value="<?php echo $question['content_preview'] ?>" />
                      </label>
                     </p>

                     <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('questions') ?>-<?php echo $count ?>-content">
                          Answer<br/>
                          <textarea id="<?php echo $this->get_field_id('questions') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('questions') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $question['content'] ?></textarea>
                      </label>
                     </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>
                
            </li>
            <?php
        }
  function render_question($question, $answer){
    ?>
      <div class="question questions__preview">
        <div class="question__header-container">
          <a href="#" class="question__header">
            <div class="question__header-main-content">
              <span class="question__prefix">Q:</span>
              <span class="question__title"><?=do_shortcode(htmlspecialchars_decode($question))?></span>
            </div>
            <span class="question__toggle">
              <svg width="12" height="8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.88 1.521a.385.385 0 0 1 0 .558L6.277 7.731A.379.379 0 0 1 6 7.853a.379.379 0 0 1-.277-.122L.12 2.08a.385.385 0 0 1 0-.558L.721.915A.379.379 0 0 1 .998.794c.104 0 .196.04.277.121L6 5.682 10.726.915a.378.378 0 0 1 .276-.121c.104 0 .196.04.277.121l.6.606Z" fill="#566171"/>
              </svg>
            </span>
          </a>
        </div>
        <div class="question__content">
          <span class="question__answer-prefix">A:</span>
          <div class="question__answer">
            <div class="question__answer-inner">
              <?=do_shortcode(htmlspecialchars_decode($answer))?>
            </div>
          </div>
        </div>
      </div>
    <?php
  }

	function block($instance) {
       extract($instance);
       $headline = $title ? '<div class="question-goals__headline-container"><h2 class="headline-second">'.do_shortcode(htmlspecialchars_decode($title)).'</h2></div>' : '';
        ?>
		  <section class="section questions">  
            <?=$headline?>
            <div class="questions__container">
                <?php
                foreach($questions as $question){
                  $this->render_question($question['content_preview'], $question['content']);
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
              'content_preview' => 'Question...',
              'content' => 'Question answer...',
            );

            if($count) {
                $this->render_admin_questions($new_need, $count);
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