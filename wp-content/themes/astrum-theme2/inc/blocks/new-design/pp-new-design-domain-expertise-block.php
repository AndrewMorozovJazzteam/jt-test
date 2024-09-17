<?php
/* Aqua New Design Clients Block - PureThemes */
if(!class_exists('PP_Domain_Expertise_Block')) {
    class PP_Domain_Expertise_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Domain Expertise',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Domain_Expertise_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_domain-expertise_add_new', array($this, 'add_clientbox'));

        }

        function form($instance) {

            $defaults = array(
                'title' => 'Expertise',
                'image' => '',
                'expertises' => array(
                    1 => array(
                        'title' => 'Expertise title',
                        'content' => 'Expertise...',
                        'title_variant' => 'h4',
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

            <div class="description">
				<label for="<?php echo $this->get_field_id('image') ?>">
					Upload an image<br/>
					<?php echo aq_field_upload('image', $block_id, $image) ?>
				</label>

				<?php if($image) { ?>

				<div class="screenshot">
					<img src="<?php echo $image ?>" alt="empty image" />
				</div>

				<?php } ?>

			</div>

            <div class="clearfix"></div>
            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $expertises = is_array($expertises) ? $expertises : $defaults['expertises'];
                    $count = 1;
                    foreach($expertises as $expertise) {
                        $this->domain_expertise($expertise, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <p></p>
                <a href="#" rel="domain-expertise" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function domain_expertise($expertise = array(), $count = 0) {
            $title_variant_options = array(
                array('value'=> 'h1','label' => 'h1'),
                array('value'=> 'h2','label' => 'h2'),
                array('value'=> 'h3','label' => 'h3'),
                array('value'=> 'h4','label' => 'h4'),
                array('value'=> 'h5','label' => 'h5'),
                array('value'=> 'h6','label' => 'h6'),
                array('value'=> 'span','label' => 'span'),
            );
            $title_variant = $expertise['title_variant'] ? $expertise['title_variant'] : 'h4';
            ?>
            <li id="<?php echo $this->get_field_id('expertises') ?>-sortable-item-<?php echo $count ?>" class="sortable-item" rel="<?php echo $count ?>">

                <div class="sortable-head cf">
                    <div class="sortable-title">
                        <strong><?php echo $expertise['title']?></strong>
                    </div>
                    <div class="sortable-handle">
                        <a href="#">Open / Close</a>
                    </div>
                </div>

                <div class="sortable-body">
                     <p class="tab-desc description">
                     <label for="<?php echo $this->get_field_id('expertises') ?>-<?php echo $count ?>-title">
                         Title<br/>
                        <input type="text" id="<?php echo $this->get_field_id('expertises') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('expertises') ?>[<?php echo $count ?>][title]" value="<?php echo $expertise['title'] ?>" />
                     </label>
                    </p>
                    <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('expertises') ?>-<?php echo $count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('expertises') ?>-<?php echo $count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('expertises') ?>[<?php echo $count ?>][content]" rows="5"><?php echo $expertise['content'] ?></textarea>
                      </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('expertises') ?>-<?php echo $count ?>-title_variant">
                            Title element<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('expertises') ?>[<?php echo $count ?>][title_variant]" id="<?php echo $this->get_field_id('expertises') ?>-<?php echo $count ?>-title_variant">
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

	function block($instance) {
       extract($instance);
       $headline = $title ? '<h2 class="headline-second">'.do_shortcode(htmlspecialchars_decode($title)).'</h2>' : '';
       $count = count($expertises);
       $i = 0;
       $uploads_dir = wp_upload_dir()['baseurl'];
       $image_url = $image ? $image : $uploads_dir.'/2024/02/domain-expertise.svg';
        ?>
		<section class="section domain-expertise">  
            <?=$headline?>
            <div class="domain-expertise__inner">
                <img class="domain-expertise__picture" src="<?=$image_url?>" alt="Retail domain expertise" height="600" width="300"/>
                <div class="domain-expertise__grid">
                        <?php
                        foreach($expertises as $expertise){
                            $custom_tablet_class = $i == $count - 1 && $count % 2 != 0 ? 'domain-expertise__tile--tablet-odd' : '';
                            $title_variant_element = $expertise['title_variant'] ? $expertise['title_variant'] : 'h4';
                            ?>
                            <div class="domain-expertise__tile <?=$custom_tablet_class?>">
                                <img class="domain-expertise__star" src="<?=$uploads_dir?>/2024/02/star.svg" alt="star" height="32" width="32"/>
                                    <div class="domain-expertise__tile-preview">
                                        <div class="domain-expertise__tile-preview-inner">
                                            <<?=$title_variant_element ?> class="headline-four"><?=$expertise['title']?></<?=$title_variant_element ?>>
                                        </div>
                                    </div>
                                <div class="domain-expertise__content">
                                    <<?=$title_variant_element ?> class="headline-four domain-expertise__mobile-headline"><?=$expertise['title']?></<?=$title_variant_element ?>>
                                    <span class="text-third text-third--dark"><?=$expertise['content']?></span>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                </div>
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
                'title' => 'Expertise',
                'content' => 'Expertise...',
                'title_variant' => 'h4',
                'image' => '',
            );

            if($count) {
                $this->domain_expertise($new_need, $count);
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