<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Scheme_Block')) {
    class PP_Scheme_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Scheme',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_Scheme_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_scheme-block_add_new', array($this, 'add_clientbox'));
            add_action('wp_ajax_aq_block_scheme-block-item_add_new', array($this, 'add_column'));

        }

        function form($instance) {
            $uploads_dir = wp_upload_dir()['baseurl'];
            $defaults = array(
                'caption' => '',
                'rows' => array(
                    1 => array(
                        'title' => 'TASKS AND CHALLENGES',
                        'icon' => $uploads_dir.'/2024/05/clipboard.svg',
						'gap' => 'default',
                        'caption' => '',
                        'links' => array(
                            1 => array(
                                'type' => 'highlight',
                                'icon' => '',
                                'name' => 'Block item',
                                'content' => '',
                            ),
                        ),
                        
                    )
                )
            );
						
            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>

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
                <a href="#" rel="scheme-block" class="aq-sortable-add-new button">Add New Block</a>
                <p></p>
            </div>

            <div class="description">
                <label for="<?php echo $this->get_field_id('caption') ?>">
                    Caption (optional)<br/>
                    <?php echo aq_field_input('caption', $block_id, $caption) ?>
                </label>
            </div>

            <?php
        }

        function client($row = array(), $count = 0, $block_id = 'aq_block_12000') {
			$select_options = array(
                array('value'=> 'default','label' => 'default'),
			    array('value'=> 'small','label' => 'small') 
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
                            Block title(optional)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][title]" value="<?php echo $row['title'] ?>" />
                        </label>
                    </p>

                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-icon">
                            Block icon(optional)<br/>
                            <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-icon" class="input-full"  name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][icon]" value="<?php echo $row['icon'] ?>" />
                            <a rel="image" class="aq_upload_button button" href="#">Upload icon</a>
                        </label>
                    </p>
					
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-gap">
                            Items gap<br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][gap]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-title">
                                    <?php
                                    foreach ($select_options as $key) {
                                        if ( $row['gap'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
                                        echo '<option '.$sel.' value="'.$key['value'].'">'.$key['label'].'</option>';
                                    } ?>
                                </select>
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

                    <a href="#" rel="scheme-block-item" class="aq-sortable-add-new button" data-args="<?php echo $count; ?>">Add New Block item</a>

                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-caption">
                            Delimiter Caption(optional)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-caption" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][caption]" value="<?php echo $row['caption'] ?>" />
                        </label>
                    </p>

                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                    
                </div>

            </li>
            <?php
        }

        function link($link = array(), $count = 0, $link_count = 0, $block_id = 'aq_block_120001') {			
            $type_options = array(
                array('value'=> 'highlight','label' => 'highlight'),
                array('value'=> 'highlight-centered','label' => 'highlight(centered)'),
                array('value'=> 'highlight-dashed','label' => 'highlight(dashed)'),
                array('value'=> 'numered-list','label' => 'Numered list item'),
                array('value'=> 'icon-list','label' => 'List item [icon]'),
                array('value'=> 'grid-item','label' => 'Grid item [icon]'),
                array('value'=> 'grid-tile-item-blue','label' => 'Grid tile item [icon]'),
				array('value'=> 'customizeble','label' => 'Customizeble'),
            );

            $selected_label = '';

            foreach ($type_options as $option) {
                if ($option['value'] === $link['type']) {
                    $selected_label = $option['label'];
                    break;
                }
            }
  
            ?>
            <li class="sortable-item" rel="<?php echo $link_count; ?>">

            <div class="sortable-head cf">
                <div class="sortable-title">
                    <strong>(<?php echo $selected_label; ?>) <?php echo $link['name'] ?></strong>
                </div>
                <div class="sortable-handle" rel="aq-sortable-list-link-<?php echo $block_id; ?>">
                    <a href="#">Open / Close</a>
                </div>
            </div>
        
            <div class="sortable-body">

                 <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-name">
                        Name<br/>
                        <input type="text" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-name" class="input-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][name]" value="<?php echo $link['name'] ?>" />
                    </label>
                </p>

                <p class="tab-desc description">
                      <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-content">
                          Content<br/>
                          <textarea id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-content" class="textarea-full" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][content]" rows="5"><?php echo $link['content'] ?></textarea>
                      </label>
                </p>
 
                <p class="tab-desc description">
                    <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-icon">
                        Icon<br/>
                        <input type="text" class="input-full input-upload" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-links-<?php echo $link_count ?>-icon" class="input-full"  name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][icon]" value="<?php echo $link['icon'] ?>" />
                        <a rel="image" class="aq_upload_button button" href="#">Upload icon</a>
                    </label>
                </p>
                <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-type">
                            Type <br/>
                                <select style="min-width: 100%;" name="<?php echo $this->get_field_name('rows') ?>[<?php echo $count ?>][links][<?php echo $link_count ?>][type]" id="<?php echo $this->get_field_id('rows') ?>-<?php echo $count ?>-type">
                                    <?php
				
                                    foreach ($type_options as $key) {
                                        if ( $link['type'] == $key['value']) { $sel = "selected"; } else { $sel = ''; }
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

        function group_items($items){          
            $result = [];
            $currentType = null;
            $currentItems = [];
            
            foreach ($items as $item) {
                if ($item['type'] !== $currentType) {
                    if (!empty($currentItems)) {
                        $result[] = [
                            'type' => $currentType,
                            'items' => $currentItems
                        ];
                    }
                    $currentType = $item['type'];
                    $currentItems = [];
                }
                $currentItems[] = $item;
            }
            
            if (!empty($currentItems)) {
                $result[] = [
                    'type' => $currentType,
                    'items' => $currentItems
                ];
            }
            
            return $result;
        }


// Renderer

        function render_highlight($item,$classes_mods){
            ?>
            <div class="tech-block-highlight <?=$classes_mods?>">
		        <span class="text-third text-third--dark tech-block-highlight__text"><?=do_shortcode(htmlspecialchars_decode($item['name']))?></span>
	        </div>
            <?php
        }
		
		function render_customizeble($item){
			?>
			<div class="tech-block-customizeble">
				<?=do_shortcode(htmlspecialchars_decode($item['name']))?>
				<?=do_shortcode(htmlspecialchars_decode($item['content']))?>
			</div>
			<?php
		}

        function render_numered_list_item($item, $idx){
            ?>
            <li class="tech-block-list-item ">
                <div class="tech-block-list-item--type-numered"><?=$idx?></div>
                <div class="tech-block-list-item__text-container">
                    <span class="text-third tech-block-list-item__text tech-block-list-item__text--blue tech-block-list-item__text--weight-bold"><?=do_shortcode(htmlspecialchars_decode($item['name']))?></span>
                    <?=do_shortcode(htmlspecialchars_decode($item['content']))?>
                </div>
            </li>
            <?php
        }

        function render_icon_list_item($item){
            ?>
            <li class="tech-block-list-item ">
                <img class="tech-block-list-item__icon" src="<?=$item['icon']?>" height="32" width="32">
                <div class="tech-block-list-item__text-container">
                    <span class="text-third tech-block-list-item__text text-third--dark "><?=do_shortcode(htmlspecialchars_decode($item['name']))?></span>
                </div>
            </li>
            <?php
        }

        function render_grid_item($item, $classes_mods){
            ?>
            <div class="tech-block-item <?=$classes_mods?>">
                <img class="tech-block-item__icon" src="<?=$item['icon']?>" alt="Item icon" width="24" height="24">
                <span class="text-third text-third--dark"><?=do_shortcode(htmlspecialchars_decode($item['name']))?></span>
            </div>
            <?php
        }

        function render_block($block, $has_delimiter){
            $delimiter = '';

            if($has_delimiter){
                $delimiter_caption = $block['caption'] ? '<div class="tech-delimiter__caption"><span class="text-third text-third--dark tech-delimiter__caption-content">' . $block['caption'] . '</span></div>' : '';
                $uploads_dir = wp_upload_dir()['baseurl'];
                $icon_url = $uploads_dir.'/2024/05/delimiter.svg';
                $delimiter = '<div class="tech-delimiter"><img class="tech-delimiter__picture" src="'.$icon_url.'" alt="Delimiter icon" />'.$delimiter_caption.'</div>';
            }

            $grouped_items = $this->group_items($block['links']);

            ?>
            <div class="tech-block">
                <div class="tech-block__header">
                  <?=$block['icon'] ? '<img class="tech-block__icon" src="'.$block['icon'].'" alt="Headline icon" height="24" width="24">' : ''?>
                  <?=$block['title'] ? '<span class="tech-block__headline headline-four">'.$block['title'].'</span>' : ''?>
                </div>
                <div class="tech-block__content tech-block__content--gap-<?=$block['gap']?>">
                <?php 

                foreach($grouped_items as $gitem){
                    if($gitem['type'] === 'highlight' || $gitem['type'] === 'highlight-centered' || $gitem['type'] === 'highlight-dashed') {
						echo '<div class="tech-block-highlight-outer">';
                        $mods_map = array( 
                            'highlight' => 'tech-block-highlight--default',
                            'highlight-centered' => 'tech-block-highlight--default tech-block-highlight--align-center',
                            'highlight-dashed' => ' tech-block-highlight--dotted tech-block-highlight--align-center',
                        );

                        $classes_mods = $mods_map[$gitem['type']];
                        foreach($gitem['items'] as $current_item){
                            echo $this->render_highlight($current_item,$classes_mods);
                        }
						echo '</div>';
                    }
                    if($gitem['type'] === 'numered-list'){
                        $idx = 1;
                        ?>
                        <div class="tech-block-list-overflow">
                            <div class="tech-block-list-overflow__line"></div>
                            <ul class="tech-block-list tech-block-list--type-numered">
                            
                                <?php foreach($gitem['items'] as $current_item){
                                    echo $this->render_numered_list_item($current_item, $idx);
                                    $idx += 1;
                                } ?>

                            </ul>
                        </div>
                        <?php
                    }

                    if($gitem['type'] === 'icon-list'){
                        ?>
                        <ul class="tech-block-list tech-block-list--type-default">
                            <?php foreach($gitem['items'] as $current_item){
                                echo $this->render_icon_list_item($current_item);
                            } ?>
                        </ul>
                        <?php
                    }

                    if($gitem['type'] === 'grid-item' || $gitem['type'] === 'grid-tile-item-blue'){
                        $classes_mods = '';

                        if($gitem['type'] === 'grid-item'){
                            $classes_mods = 'tech-block-item--align-default tech-block-item--type-default';
                        }

                        if($gitem['type'] === 'grid-tile-item-blue'){
                            $classes_mods = 'tech-block-item--align-center-justify-top tech-block-item--type-filled';
                        }
                        
                        ?>
                        <div class="tech-block-grid tech-block-grid--gap-default">
                            <?php foreach($gitem['items'] as $current_item){
                                echo $this->render_grid_item($current_item, $classes_mods);
                            } ?>
                        </div>
                        <?php
                    }
					
					if($gitem['type'] === 'customizeble'){
                        foreach($gitem['items'] as $current_item){
                            echo $this->render_customizeble($current_item);
                        }
					}
					
                }

                ?>
                </div>
            </div>
            <?=$delimiter?>
            <?php
        }

        function block($instance) {
            extract($instance);
            $caption_content = $caption ? '<div class="tech-compound__caption">'.$caption.'</div>' : '';
            $idx = 1;
            ?>
            <div class="tech-compound">
              <div class="tech-compound__container">
                  <?php foreach($rows as $row) { 
                      $has_delimiter = count($rows) !== $idx;
                      echo $this->render_block($row, $has_delimiter);
                      $idx += 1;
                  }?>
              </div>
              <?=$caption_content;?>
            </div>
            <?php
        }

        /* AJAX add tab */
        function add_clientbox() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';
            $uploads_dir = wp_upload_dir()['baseurl'];
            //default key/value for the tab
            $client = array(
                'title' => 'TASKS AND CHALLENGES',
                'icon' => $uploads_dir.'/2024/05/clipboard.svg',
                'gap' => 'default',
                'caption' => '',
                'links' => array(
                    1 => array(
                        'type' => 'highlight',
                        'icon' => '',
                        'name' => 'Block item',
                        'content' => '',
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
                'type' => 'highlight',
                'icon' =>'',
                'name' => 'Block item',
                'content' => '',
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