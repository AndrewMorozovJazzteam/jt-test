<?php
/** A simple text block **/
if(!class_exists('PP_Business_Domains_Block')) {
	class PP_Business_Domains_Block extends AQ_Block {
		
		//set and create block
		function __construct() {
			$block_options = array(
				'name' => __('Business Domains', 'aqpb-l10n'),
				'size' => 'span6',
			);
			
			//create the block
			parent::__construct('PP_Business_Domains_Block', $block_options);
		}
		
		function form($instance) {
			$defaults = array(
				'filter' => 0,
                'title' => '',
				'description' => '',
			);

			$instance = wp_parse_args($instance, $defaults);
			extract($instance);
			?>
			<p class="description">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title (optional)
					<?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
				</label>
			</p>

            <div class="description">
                <label for="<?php echo $this->get_field_id('description') ?>">
                    Description (optional)<br/>
					<?php echo aq_field_textarea('description', $block_id,  $description); ?>
                </label>
            </div>

			<?php
		}
		
		function block($instance) {
			extract($instance);
			global $fontawesome;

			$wp_autop = ( isset($wp_autop) ) ? $wp_autop : 0;
			$terms = get_terms(['taxonomy' => 'filters','hide_empty' => true]);
			usort($terms, function($a, $b) { return $a->term_order - $b->term_order;  });
			
			if($title) {
                echo '<h2 class="headline">'.strip_tags($title).'</h2><span class="line" style="margin-bottom:35px;"></span><div class="clearfix"></div>';
            }
			
			if($description){
				?>
					<div class="description business-domain-description">
                	<?php echo htmlspecialchars_decode($description); ?>
            		</div>
				<?php
			}
			
			$idx = 0;
			$font_counter = 2;
			$elements_in_column = 3;
			$icons = array_keys($fontawesome);
			echo '<div class="business-domain-list">';
			echo '<div class="business-domain-column">';
	
			$terms_slice = array_slice($terms, 0, 11);
			$term_details = array(
				11 => array('icon' => '/images/technologies-custom/telecom.svg','url' => 'portfolio/?selection={%22filters%22:[%2211%22]}&desc=65268b59a0fcb'),
	 			610 => array('icon' => '/images/technologies-custom/ecommerce.svg','url' => 'portfolio/?selection={%22filters%22:[%22610%22]}&desc=65268b8c30c5f'),
				605 => array('icon' => '/images/technologies-custom/it_industry.svg','url' => 'portfolio/?selection={%22filters%22:[%22605%22]}&desc=65268ba90b9bd'),
				614 => array('icon' => '/images/technologies-custom/marketing.svg','url' => 'portfolio/?selection={%22filters%22:[%22614%22]}&desc=65268bc339d7f'),
				14 => array('icon' => '/images/technologies-custom/government.svg','url' => 'portfolio/?selection={%22filters%22:[%2214%22]}&desc=65268bdd3276e'),
				607 => array('icon' => '/images/technologies-custom/media.svg','url' => 'portfolio/?selection={%22filters%22:[%22607%22]}&desc=65268bf7da90a'),
				12 => array('icon' => '/images/technologies-custom/travel.svg','url' => 'portfolio/?selection={%22filters%22:[%2212%22]}&desc=65268c189f5c8'),
				609 => array('icon' => '/images/technologies-custom/iot.svg','url' => 'portfolio/?selection={%22filters%22:[%22609%22]}&desc=65268c3885da5'),
				617 => array('icon' => '/images/technologies-custom/dollar 1.svg','url' => 'portfolio/?selection={%22filters%22:[%22617%22]}&desc=65268c581faae'),
				618 => array('icon' => '/images/technologies-custom/supply-chain.svg','url' => 'portfolio/?selection={%22filters%22:[%22618%22]}&desc=65268c7005f62'),
				13 => array('icon' => '/images/technologies-custom/health-care.svg','url' => 'portfolio/?selection={%22filters%22:[%2213%22]}&desc=65268c86e5e35'),
			);
			
			$buffer = $terms_slice[6];
			$terms_slice[6] = $terms_slice[9];
			$terms_slice[9] = $buffer;

			$buffer = $terms_slice[10];
			$terms_slice[10] = $terms_slice[9];
			$terms_slice[9] = $buffer;
			
			foreach($terms_slice as $term) {
				if($idx % $elements_in_column == 0 && $idx > 0){
					echo '</div><div class="business-domain-column">';
				}
				
				$term_name = $term -> name;
				$term_id = $term -> term_id;
				$term_name = str_replace('&amp;', '&', $term_name);
    			$term_name = str_replace('Marketing, Advertising, Sales', '<span>Marketing, Advertising, <span class="business-domains-next-line">Sales</span></span>', $term_name);
				$term_name = str_replace('Health Care. Fitness & Recreation','<span>Health Care. Fitness <span class="business-domains-next-line">& Recreation</span></span>', $term_name);
				$term_name = str_replace('Supply Chain, Inventory & Order Management','<span>Supply Chain, Inventory <span class="business-domains-next-line">& Order Management</span></span>',$term_name);

				$icon_HTML = $term_details[$term_id] ? '<img height="20" width="20" src="'.WP_CONTENT_URL.$term_details[$term_id]['icon'].'"/>' : '<i class="'.$icons[$font_counter].'"></i>';
				echo '<div class="business-domain__name"><a class="business-domain__link" href="'.$term_details[$term_id]['url'].'"><div class="icon">'.$icon_HTML.'</div>'.$term_name.'</a></div>';
				$idx += 1;
				$font_counter += 1;
			}
			
			echo '</div></div>';
		}
		
	}
}
