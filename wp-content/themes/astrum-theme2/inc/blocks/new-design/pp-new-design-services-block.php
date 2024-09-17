<?php
/* Aqua New Design Featured Block - PureThemes */
if(!class_exists('PP_New_Design_Services_Block')) {
    class PP_New_Design_Services_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => '(ND) Services',
                'size' => 'span16',
                'last' => ''
            );

            //create the widget
            parent::__construct('PP_New_Design_Services_Block', $block_options);

            //add ajax functions
            add_action('wp_ajax_aq_block_services_add_new', array($this, 'add_service'));
        }


        function form($instance) {
            $defaults = array(
		'title' => '',
		'anchor' => '',
                'variant' => 'first',
                'boxes' => array(
                    1 => array(
                        'title' => 'New service',
                        'description' => 'New service description',
                        'icon' => '',
                        'url' => '#',
                    )
                ),
            );

            $instance = wp_parse_args($instance, $defaults);
            extract($instance);
            
            $variant_options = array(
                'first' => 'first',
                'second-two' => 'second(tile with two columns)',
                'third' => 'third(stretch white tiles)',
                'four' => 'four(numerable stretch blue tiles with arrows)',
                'five' => 'five(numerable stretch white tiles)',
				'six' => 'six(numerable stretch blue tiles)',
            );

            ?>
			
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

            <div class="description cf">
                <ul id="aq-sortable-list-<?php echo $block_id ?>" class="aq-sortable-list" rel="<?php echo $block_id ?>">
                    <?php
                    $boxes = is_array($boxes) ? $boxes : $defaults['boxes'];
                    $count = 1;
                    foreach($boxes as $box) {
                        $this->render_admin_service($box, $count);
                        $count++;
                    }
                    ?>
                </ul>
                <a href="#" rel="services" class="aq-sortable-add-new button">Add New</a>
            </div>

            <?php
        }

        function render_admin_service($box = array(), $count = 0) {

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
                            Service Title<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-title" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][title]" value="<?php echo $box['title'] ?>" />
                        </label>
                    </p>
                    <p class="description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-icon">
                            Service Icon (prefer 60x60px)<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-icon" class="input-full input-icon" value="<?php if(isset($box['icon'])) { echo $box['icon']; } ?>" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][icon]">
                            <p> </p>
                            <a href="#" class="aq_upload_button button" rel="image">Upload Icon</a>
                        </label>
                    </p>
                    <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-description">
                            Service description<br/>
                            <textarea id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-description" class="textarea-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][description]" rows="5"><?php echo $box['description'] ?></textarea>
                        </label>
                    </p>
                     <p class="tab-desc description">
                        <label for="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url">
                            Service link<br/>
                            <input type="text" id="<?php echo $this->get_field_id('boxes') ?>-<?php echo $count ?>-url" class="input-full" name="<?php echo $this->get_field_name('boxes') ?>[<?php echo $count ?>][url]" value="<?php if(isset($box['url'])) { echo $box['url']; } ?>" />
                        </label>
                    </p>
                    <p class="tab-desc description"><a href="#" class="sortable-delete">Delete</a></p>
                </div>
            </li>
            <?php
        }

        function render_service($title, $description, $icon, $url, $custom_class = ''){
            $clean_url = $url === '' ? '#' : $url;
          ?>
              <a href="<?=$clean_url?>" class="services__item service <?=$custom_class?>">
                <div class="service__header">
                  <div class="service__icon">
                    <img loading="lazy" src="<?=$icon?>" alt="<?=$title?>" />
                  </div>
                  <span class="headline-third headline-third--color-inherit"><?=$title?></span>
                </div>
                <p class="text-second service__description">
                  <?=do_shortcode(htmlspecialchars_decode($description))?>
                </p>
              </a>
          <?php
        }

        function render_service_second($title, $description, $icon, $url, $custom_class = ''){
          ?>
              <div class="services-tile__item service-tile <?=$custom_class?>">
                <div class="service-tile__icon-container">
                    <img class="service-tile__icon" loading="lazy" src="<?=$icon?>" alt="<?=$title?>" />
                </div>
                <span class="headline-third headline-third--color-inherit"><?=$title?></span>
                <div class="service-tile__description">
                  <?=do_shortcode(htmlspecialchars_decode($description))?>
                </div>
            </div>
          <?php
        }

        function render_service_third($title, $description, $icon, $url, $custom_class = ''){
            ?>
                <div class="services-tiles-sqoosh__item services-tile-sqoosh <?=$custom_class?>">
                  <div class="services-tile-sqoosh__icon-container">
                      <img class="services-tile-sqoosh__icon" loading="lazy" src="<?=$icon?>" alt="<?=$title?>" />
                  </div>
                  <div class="services-tile-sqoosh__content">
                    <span class="headline-third headline-third--color-inherit"><?=$title?></span>
                    <div class="services-tile-sqoosh__description">
                        <?=do_shortcode(htmlspecialchars_decode($description))?>
                    </div>
                  </div>
              </div>
            <?php
        }

        function render_service_four_six($title, $description, $icon, $url, $current_iter, $custom_class = '', $modif){
          $render_iter = $current_iter < 10 ? '0' . $current_iter : $current_iter;
          $tile_tag = $url ? 'a' : 'div';
          $tile_attr = $url ? 'href="'.$url.'" target="_blank"' : '';
          $tile_modif = $url ? 'services-numerable-tile--hoverable' : '';
          $uploads_dir = wp_upload_dir()['baseurl'];
            ?>
                <<?=$tile_tag?> class="services-numerable-tiles__item services-numerable-tile <?=$tile_modif?> <?=$custom_class?> <?=$modif['class']?>" <?=$tile_attr?>>
                    <div class="services-numerable-tile__content">
                      <span class="headline-third headline-third--color-inherit"><span class="services-numerable-tile__iteration"><?=$render_iter?></span><?=do_shortcode(htmlspecialchars_decode($title))?></span>
                      <div <?=$modif['without_description_mod'] ? '' : 'class="services-numerable-tile__description"'?>>
                          <?=do_shortcode(htmlspecialchars_decode($description))?>
                      </div>
                    </div>
                    <?= $modif['without_image'] ? '' : '<img class="services-numerable-tile__background-arrow" src="'.$uploads_dir.'/2024/01/sidebar-arrow.svg" alt="arrow" height="60" width="30" />'  ?>
              </<?=$tile_tag?>>
            <?php
        }


        function render_service_five($title, $description, $icon, $url, $current_iter, $custom_class = ''){
          $render_iter = $current_iter < 10 ? '0' . $current_iter : $current_iter;
          $tile_tag = $url ? 'a' : 'div';
          $tile_attr = $url ? 'href="'.$url.'" target="_blank"' : '';
          $tile_modif = $url ? 'services-numerable-blink-tile--hoverable' : '';
            ?>
                <<?=$tile_tag?> class="services-numerable-blink-tiles__item services-numerable-blink-tile <?=$tile_modif?> <?=$custom_class?>" <?=$tile_attr?>>
                    <div class="services-numerable-blink-tile__content">
                      <span class="headline-third headline-third--color-inherit"><span class="services-numerable-blink-tile__iteration"><?=$render_iter?></span><?=$title?></span>
                      <div class="services-numerable-blink-tile__description">
                          <?=do_shortcode(htmlspecialchars_decode($description))?>
                      </div>
                    </div>
              </<?=$tile_tag?>>
            <?php
        }

        function block($instance) {
          extract($instance);
          $title_with_anchor = $anchor ? '<span id="'.$anchor.'">'.$title.'</span>'  : $title;
          $headline = $title ? '<h2 class="headline-second">'.$title_with_anchor.'</h2>' : '';
          $count = count($boxes);
          $i = 0;
          $current_variant = $variant ? $variant : 'first';
          ?>

          <?php if($current_variant === 'first') { ?>

          <section class="section services services--variant-<?=$variant_class?>">
            <?=$headline?>
            <div class="services__list">
            <?php
              foreach($boxes as $service){
                $custom_class = $i == $count - 1 && $count % 2 != 0 ? 'service--odd' : '';
                $this->render_service($service['title'], $service['description'], $service['icon'], $service['url'], $custom_class);
                $i++;
              }
            ?>
            </div>
          </section>

          <?php } ?>

          <?php if($current_variant === 'second-two') { ?>

          <section class="section services-tile">
            <?=$headline?>
            <div class="services-tile__list">
            <?php
              foreach($boxes as $service){
                $custom_class = $i == $count - 1 && $count % 2 != 0 ? 'service-tile--odd' : '';
                $this->render_service_second($service['title'], $service['description'], $service['icon'], $service['url'], $custom_class);
                $i++;
              }
            ?>
            </div>
          </section>

          <?php } ?>

          <?php if($current_variant === 'third') { ?>

          <section class="section services-tiles-sqoosh">
            <?=$headline?>
            <div class="services-tiles-sqoosh__list">
            <?php
              foreach($boxes as $service){
                $custom_class = $i == $count - 1 && $count % 2 != 0 ? 'services-tile-sqoosh--odd' : '';
                $this->render_service_third($service['title'], $service['description'], $service['icon'], $service['url'], $custom_class);
                $i++;
              }
            ?>
            </div>
          </section>

          <?php } ?>

          <?php if($current_variant === 'four' || $current_variant === 'six') { ?>

          <section class="section services-numerable-tiles">
            <?=$headline?>
            <div class="services-numerable-tiles__list <?=$current_variant === 'six' ? 'services-numerable-tiles__list--two-columns' : ''?>">
            <?php
              foreach($boxes as $service){
				$modif = $current_variant === 'six' ? array('without_image' => true, 'class' => 'services-numerable-blink-tile--bg-blue', 'without_description_mod' => true) : array();
                $custom_class = $i == $count - 1 && $count % 2 != 0 ? 'services-numerable-tile--odd' : '';
                $this->render_service_four_six($service['title'], $service['description'], $service['icon'], $service['url'], $i + 1, $custom_class, $modif);
                $i++;
              }
            ?>
            </div>
          </section>

          <?php } ?>

          <?php if($current_variant === 'five') { ?>
          
          <section class="section services-numerable-blink-tiles">
            <?=$headline?>
            <div class="services-numerable-blink-tiles__list">
            <?php
              foreach($boxes as $service){
                $custom_class = $i == $count - 1 && $count % 2 != 0 ? 'services-numerable-blink-tile--odd' : '';
                $this->render_service_five($service['title'], $service['description'], $service['icon'], $service['url'], $i + 1, $custom_class);
                $i++;
              }
            ?>
            </div>
          </section>
            
          <?php } ?>
          
          <?php
        }


        /* AJAX add tab */
        function add_service() {
            $nonce = $_POST['security'];
            if (! wp_verify_nonce($nonce, 'aqpb-settings-page-nonce') ) die('-1');

            $count = isset($_POST['count']) ? absint($_POST['count']) : false;
            $this->block_id = isset($_POST['block_id']) ? $_POST['block_id'] : 'aq-block-9999';

            //default key/value for the tab
            $box = array(
              'title' => 'New service',
              'description' => 'New service description',
              'icon' => '',
              'url' => '',
            );

            if($count) {
                $this->render_admin_service($box, $count);
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
