<?php
/**
 * Custom shortcodes for astrum theme
 *
 *
 * @package astrum
 * @since astrum 1.0
 */

/**
* Clear shortcode
* Usage: [clear]
*/
if (!function_exists('pp_clear')) {
    function pp_clear() {
       return '<div class="clear"></div>';
   }
   add_shortcode( 'clear', 'pp_clear' );
}


/*
 * sidebar banner
 * 
 * */
if (!function_exists('sidebar_banners')) {
    function sidebar_banners($atts) {
		global $post;
		extract(shortcode_atts(array(
        	'name'=>'sidebar',
		), $atts));

		//
    	return '<div class="sidebar-banners name-'.$atts['name'].'">'.get_post_meta($post->ID, 'imagebanner', 1).'</div>';
   }
   add_shortcode( 'sidebar_banners', 'sidebar_banners' );
}


/*
 * sidebar banner new
 * 
 * */
if (!function_exists('sidebar_banners_new')) {
    function sidebar_banners_new() {
		global $post;
		$post_content = get_post( $post->ID )->post_content;
		$the_content = apply_filters( 'the_content', $post_content );		
		
		$dom = new DomDocument();
		$dom->loadHTML( utf8_decode ($the_content) );
	
		$xpath = new DOMXPath( $dom );
		$headingNodes = $xpath->query('//div[@class="sidebar-image-banner"]');
		$i = 0;
		$innerHTML = '';
		foreach ($headingNodes as $e){
			$children = $headingNodes->item($i)->childNodes;
			foreach ($children as $child) {
				$innerHTML .= $headingNodes->item($i)->ownerDocument->saveHTML($child);				
			}
			$i++;
		}		

		$contactForms = $xpath->query('//div[@class="sidebar_form"]');
		$j = 0;
		$formHTML = '';
		foreach ($contactForms as $e){
			$children = $contactForms->item($j)->childNodes;
			foreach ($children as $child) {
				$formHTML .= $contactForms->item($j)->ownerDocument->saveHTML($child);				
			}
			$j++;
		}		
		
		
		if ( !empty($innerHTML) || !empty($formHTML) ) {
    			return '<div class="sidebar-banners sidebar-banners-new">'.$innerHTML.$formHTML.'</div>';
		}
   }
   add_shortcode( 'sidebar_banners_new', 'sidebar_banners_new' );
}


/*
 * 
 * 
 * sidebar form
 * 
 * 
 * */

if (!function_exists('sidebar_form')) {
    function sidebar_form($atts) {
		extract(
		shortcode_atts(
				array(
				'id'=>'',
				'html_class'=>'',			
				'margintop' => '',	
				'marginbottom' => '',
			), 
		$atts)
		);
		if ( isset( $atts['margintop'] ) ) {
			$margintop = 'margin-top:'.$atts['margintop'].'px;';
		}
		
		if ( isset( $atts['marginbottom'] ) ) {
			$marginbottom = 'margin-bottom:'.$atts['marginbottom'].'px;';
		}
		
		
		return '<div class="sidebar_form" style="'.$margintop.$marginbottom.'">'.do_shortcode('[contact-form-7 id="'.$atts['id'].'" html_class="form_class '.$atts['html_class'].'"]').'</div>';
   }
   add_shortcode( 'sidebar_form', 'sidebar_form' );
}




/*
 * sidebar banner 2
 * 
 * */
if (!function_exists('tag_sidebar_banners')) {
    function tag_sidebar_banners() {
		if ( is_tag() ) {
			$tags_str = single_term_title('', 0);
			$term = get_term_by( 'name', $tags_str, 'post_tag');
			//var_dump();
			//.get_term_meta( $term_id, 'imagebanners_for_tag', true ).
			return '<div class="tag-sidebar-banners">'.get_term_meta( $term->term_id, 'imagebanners_for_tag', true ).'</div>';
		}
   }
   add_shortcode( 'tag_sidebar_banners', 'tag_sidebar_banners' );
}



/*
 * view image in banner
 * 
 * */
if (!function_exists('image_banner')) {
    function image_banner($atts) {
		extract(shortcode_atts(array(
        	'src'=>'',
		'alt'=>'',
		'link'=>'',
		'onclick'=>'',
		), $atts));
	$onesymbol = "'";	
	if ($atts['onclick'] != '') {
		$onclick = 'onclick="ym(24294061, '.$onesymbol.'reachGoal'.$onesymbol.','.$onesymbol.$atts['onclick'].$onesymbol.')"';
	} else {
		$onclick = '';
	}
    	return '<a href="'.$atts['link'].'" class="interactive-banner"   target="_blank" '.$onclick.'><img src="'.$atts['src'].'" alt="'.$atts['alt'].'"></a>';
   }
   add_shortcode( 'image_banner', 'image_banner' );
}

/*
 * view new image in banner 
 * 
 * */
if (!function_exists('image_banner_new')) {
    function image_banner_new($atts) {
		extract(shortcode_atts(array(
        	'src'=>'',
		'alt'=>'',
		'link'=>'',
		'onclick'=>'',
		'margintop' => '',	
		'marginbottom' => '',
			), 
		$atts)
		);
		if ( isset( $atts['margintop'] ) ) {
			$margintop = 'margin-top:'.$atts['margintop'].'px;';
		}
		
		if ( isset( $atts['marginbottom'] ) ) {
			$marginbottom = 'margin-bottom:'.$atts['marginbottom'].'px;';
		}

	$onesymbol = "'";	
	if ($atts['onclick'] != '') {
		$onclick = 'onclick="ym(24294061, '.$onesymbol.'reachGoal'.$onesymbol.','.$onesymbol.$atts['onclick'].$onesymbol.')"';
	} else {
		$onclick = '';
	}
    	return '<div class="sidebar-image-banner" style="'.$margintop.$marginbottom.'"><a href="'.$atts['link'].'" class="interactive-banner interactive-banner-unit" target="_blank" '.$onclick.'><img src="'.$atts['src'].'" alt="'.$atts['alt'].'"></a></div>';
   }
   add_shortcode( 'image_banner_new', 'image_banner_new' );
}



/*TOC padding shortcode*/
if (!function_exists('toc_padding')) {
    function toc_padding($atts) {
	   extract(shortcode_atts(array(
            'padding'=>''), $atts));
       return '<span class="padding-for-lwptoc" data-toc-padding="'.$atts['padding'].'"></span>';
   }
   add_shortcode( 'toc', 'toc_padding' );
}

/**
* Dropcap shortcode
* Usage: [dropcap color="gray"] [/dropcap]// margin-down margin-both
*/
if (!function_exists('pp_dropcap')) {
    function pp_dropcap($atts, $content = null) {
        extract(shortcode_atts(array(
            'color'=>''), $atts));
        return '<span class="dropcap '.$color.'">'.$content.'</span>';
    }
    add_shortcode('dropcap', 'pp_dropcap');
}

function pp_accordion( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Tab'
        ), $atts));
    return '<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span>'.$title.'</h3><div><p>'.do_shortcode( $content ).'</p></div>';
}
add_shortcode( 'accordion', 'pp_accordion' );

function pp_accordion_wrap( $atts, $content ) {
    extract(shortcode_atts(array(), $atts));
    return '<div class="accordion">'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'accordionwrap', 'pp_accordion_wrap' );

function pp_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "url" => '',
        "color" => 'color',  //gray color light
        "customcolor" => '',
        "iconcolor" => 'white',
        "icon" => '',
        "size" => '',
        "target" => '',
        "customclass" => '',
        ), $atts));
    $output = '<a class="button '.$size.' '.$color.' '.$customclass.'" href="'.$url.'" ';
    if(!empty($target)) { $output .= 'target="'.$target.'"'; }
    if(!empty($customcolor)) { $output .= 'style="background-color:'.$customcolor.'"'; }
    $output .= '>';
    if(!empty($icon)) { $output .= '<i class="'.$icon.'  '.$iconcolor.'"></i> '; }
    $output .= $content.'</a>';

    return $output;
}
add_shortcode('button', 'pp_button');

function etdc_tab_group( $atts, $content ) {
    $GLOBALS['pptab_count'] = 0;
    do_shortcode( $content );
    $count = 0;
    if( is_array( $GLOBALS['tabs'] ) ) {
        foreach( $GLOBALS['tabs'] as $tab ) {
            $count++;
            $tabs[] = '<li><a href="#tab'.$count.'">'.$tab['title'].'</a></li>';
            $panes[] = '<div class="tab-content" id="tab'.$count.'">'.$tab['content'].'</div>';
        }
        $return = "\n".'<ul class="tabs-nav">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="tabs-container">'.implode( "\n", $panes ).'</div>'."\n";
    }
    return $return;
}

/**
* Usage: [tab title="" ] [/tab]
*/
function etdc_tab( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Tab %d',
        ), $atts));

    $x = $GLOBALS['pptab_count'];
    $GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['pptab_count'] ), 'content' =>  do_shortcode( $content ) );
    $GLOBALS['pptab_count']++;
}
add_shortcode( 'tabgroup', 'etdc_tab_group' );

add_shortcode( 'tab', 'etdc_tab' );


/**
* Line shortcode
* Usage: [line]
*/
function pp_line() {
    return '<div class="line" style="margin-top: 25px; margin-bottom: 40px;"></div>';
}
add_shortcode( 'line', 'pp_line' );


/**
* 
 shortcode
* Usage: [headline ] [/headline] // margin-down margin-both
*/
/*function pp_headline( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0,
	'alt' => '',
	'numbers' => 'true',	
    ), $atts));	
	if ($margintop == 0) {$important = '';} else {$important = '!important';}
	if ($marginbottom == 0) {$important1 = '';} else {$important1 = '!important';}
  return '<h2 class="head'.$dash.'line" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'"  style="margin-top:'.$margintop.'px'.$important.';">'.do_shortcode( $content ).'</h2><span class="line" style="margin-bottom:'.$marginbottom.'px'.$important1.';"></span><div class="clearfix"></div>';
}
add_shortcode( 'headline', 'pp_headline' );*/

function pp_headline( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0,
	'alt' => '',
	'numbers' => 'true',
	'toc' => 'true',
	'level' => '2',
    ), $atts));
	if ($toc == 'true') {$dash = '';} else {$dash = '-';}
$portfolio_page = [
	'/TestDesign/portfolio/monitoring-and-management-system-of-state-procurement-for-tender-contractual-department/',
	'/TestDesign/portfolio/tariff-plans-and-services-management-system-for-mobile-operator/',
	'/TestDesign/portfolio/transferring-commercial-integration-mule-applications-from-on-premise-to-the-cloud-environment/',
	'/TestDesign/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/TestDesign/portfolio/the-social-network-for-creative-people/',
	'/TestDesign/portfolio/preparing-microservice-infrastructure-for-transition-to-k8s/',
	'/TestDesign/portfolio/role-playing-game-mechanics/',
	'/TestDesign/portfolio/backend-complex-travel-system/',
	'/TestDesign/portfolio/creation-of-commercial-mule-connector-for-ibm-i-as-400/',
	'/TestDesign/portfolio/online-professional-translation-service/',
	'/TestDesign/portfolio/ci-cd-for-engineering-application/',
	'/TestDesign/portfolio/web-interface-of-the-system-for-geographical-location-tracking-of-objects-on-google-maps/',
	'/TestDesign/portfolio/integration-of-distribution-management-system-with-regional-distributors-erp-systems/',
	'/TestDesign/portfolio/frontend-development-of-web-application-to-maintain-a-healthy-lifestyle/',
	'/TestDesign/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/TestDesign/portfolio/automated-testing-applications-for-managing-products-services-mobile-operator/',
	'/TestDesign/portfolio/mule-collecting-processing-calls-information-transferring-webbing-service/',
	'/TestDesign/portfolio/consulting-on-complex-demanded-project-in-e-commerce/',
	'/TestDesign/portfolio/electronic-product-inventory-sync-erp/',
	'/TestDesign/portfolio/consulting-stabilization-software-development-process-improve-quality/',
	'/TestDesign/portfolio/big-telecom-company-crm/',
	'/TestDesign/portfolio/enterprise-resource-management-system/',
	'/TestDesign/portfolio/accounts-aggregator-for-web-application/',
	'/TestDesign/portfolio/application-for-estimating-and-managing-the-implementation-cost-of-large-scale-industrial-projects/',
	'/TestDesign/portfolio/automation-testing-webapp-options-of-bank-deposits/',
	'/TestDesign/portfolio/mouse-emulator-application-for-android-devices/',
	'/TestDesign/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization',
	'/TestDesign/portfolio/telecommunication-data-visualization-and-management-center/',
	'/TestDesign/portfolio/management-center-of-telecom-data/',
	'/TestDesign/portfolio/development-telecommunication-system-for-audio-data-processing/',
	'/TestDesign/portfolio/inventory-system-of-logical-resources-for-mobile-operator/',
	'/TestDesign/portfolio/development-service-for-interaction-with-energy-accounting-platforms/',
	'/TestDesign/portfolio/jde-integration-with-magento-ecommerce-platform/',
	'/TestDesign/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization/',
	'/TestDesign/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/TestDesign/portfolio/application-for-automating-data-collection-from-business-portals/',
	'/TestDesign/portfolio/auto-testing-car-service-web-application/',
	'/TestDesign/portfolio/completion-of-the-electronic-laboratory-passport-web-application/',
	'/TestDesign/portfolio/development-front-end-platform-for-supporting-iot-applications/',
	'/TestDesign/portfolio/development-integration-between-ecommerce-platform-magento-epicor-prophet-21/',
	'/TestDesign/portfolio/development-low-code-trend-at-jazzteam/',
	'/TestDesign/portfolio/the-system-of-accounting-and-control-of-the-costs-of-telecommunications-services/',
	'/TestDesign/portfolio/business-processes-automation-in-environmental-activities/',
	'/TestDesign/portfolio/ticket-online-sale-system/',
	'/TestDesign/portfolio/contact-management-system/',
	'/TestDesign/portfolio/setting-up-processes-and-comprehensive-testing-of-a-product-designed-for-complex-calculations-in-the-scientific-field/',
	'/TestDesign/portfolio/voip-integration/',
	'/TestDesign/portfolio/integration-system-for-trading-company/',
	'/TestDesign/portfolio/product-quality-assurance-service/',
	'/TestDesign/portfolio/implementing-ci-cd-on-high-resistance-project/',
	'/TestDesign/portfolio/consulting-project-developing-integrated-software-solution-security-monitoring-industrial-civil-facilities/',
	'/TestDesign/portfolio/the-project-resources-and-costs-monitoring-system/',
	'/TestDesign/portfolio/the-system-for-cryptocurrency-rate-forecasting-based-on-text-analysis/',
	'/TestDesign/portfolio/web-antivirus-api/',
	'/TestDesign/portfolio/system-of-cloud-storages-unification/',
	'/TestDesign/portfolio/product-development-for-creating-advertising-banners/',
	'/TestDesign/portfolio/solution-for-automatic-information-collection/',
	'/TestDesign/portfolio/system-automation-processes-control-accounting-activities-supervisory-service/',
	'/TestDesign/portfolio/telecom-oriented-information-migration-project-on-clients-payments-from-one-system-to-another-with-complex-transformation-of-data-structure/',
	'/TestDesign/portfolio/testing-system-for-automated-accounting-of-information-about-industrial-infrastructure-facility/',
	'/TestDesign/portfolio/development-of-an-application-for-generating-pay-sheets/',
	'/TestDesign/portfolio/development-of-educational-online-platform-module/',
	'/TestDesign/portfolio/development-web-application-for-conducting-scientific-experiments/',
	'/TestDesign/portfolio/gaming-computer-club-management-system/',
	'/TestDesign/portfolio/implementation-process-manual-testing-online-platform-localization-github-projects/',
	'/TestDesign/portfolio/keep-them-all/',
	'/TestDesign/portfolio/mule-software-for-integrating-internal-systems/',
	'/TestDesign/portfolio/redesign-of-the-portal-for-cost-accounting-and-control-system-of-telecommunications-services/',
	'/TestDesign/portfolio/solution-for-data-migration-synchronization/',
	'/TestDesign/portfolio/system-for-calculating-pile-foundations-in-extreme-conditions/',
	'/TestDesign/portfolio/testing-company-procurement-management-system/',
	'/TestDesign/portfolio/testing-unified-international-electronic-system-recording-movable-property/',
	'/TestDesign/portfolio/time-reporting-automation/',
	'/TestDesign/portfolio/web-application-for-education/',
	'/TestDesign/portfolio/website-of-the-translation-service/',
	'/TestDesign/portfolio/development-erp-product-customer-consulting-and-system-design-transformations/',
	'/TestDesign/portfolio/creation-of-commercial-mule-connector-to-system-of-control-of-master-data/',
	'/TestDesign/portfolio/account-register-in-the-cloud-services/',
	'/TestDesign/portfolio/platform-for-building-products-based-on-service-oriented-architecture/',
	'/TestDesign/portfolio/development-service-for-organizing-targeted-ads/',
	'/TestDesign/portfolio/document-signature-applet/',
	'/TestDesign/portfolio/automated-testing-web-application-related-to-geofence-marketing/',
	'/TestDesign/portfolio/big-data-reporting-system/',
	'/TestDesign/portfolio/development-of-saas-solution-for-xml2selenium-framework/',
	'/TestDesign/portfolio/integration-layer-interact-with-existing-solutions-ibm-as400-system/',
	'/TestDesign/portfolio/automated-competitors-price-monitoring-and-self-optimizing-system/',
	'/TestDesign/portfolio/getaddressbyip-service-determining-device-geolocation-by-ip/',
	'/TestDesign/portfolio/migrate-to-another-platform-for-stable-web-application/',
	'/TestDesign/portfolio/medication-search-service/',
	'/TestDesign/portfolio/integration-proxy/',
	'/TestDesign/portfolio/billing-system-for-the-multimedia-channels-provider/',
	'/TestDesignrtfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/TestDesign/portfolio/complex-android-ui-customization/',
	'/TestDesign/portfolio/xml2selenium-the-jazzteam-companys-product/',
	'/TestDesign/portfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/TestDesign/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/TestDesign/portfolio/modernization-corporate-portal-jive-based-for-personal-growth/',
	'/TestDesign/portfolio/mobile-application-network-beauty-salons/',
	'/TestDesign/portfolio/frontend-development-new-version-web-portal-for-interior-design/',
	'/TestDesign/portfolio/development-of-a-cross-platform-voip-client/',
	'/TestDesign/portfolio/management-center-of-telecommunication-information/',
	'/TestDesign/portfolio/databases-updates-management-application/',
	'/TestDesign/portfolio/development-of-cloud-iot-platform-components-for-energy-resources-accounting-and-management-of-energy-meters/',
	'/TestDesign/portfolio/web-application-for-buying-and-selling-real-estate/',
	'/TestDesign/portfolio/integration-system-application-localization-customer-service-platform/',
	'/TestDesign/portfolio/social-media-brand-analyzer/',
	'/TestDesign/portfolio/marketing-platform-of-data-analysis-site-stackexchange-com/',
	'/TestDesign/portfolio/web-application-for-viewing-personal-files-of-employees-and-students/',
	'/TestDesign/portfolio/management-transformation-long-history-project/',
	'/TestDesign/portfolio/automated-ui-tests-for-travel-industry/',
	'/TestDesign/portfolio/corporate-mobile-application-for-traffic-accounting-and-management-of-communication-costs/',
	'/TestDesign/portfolio/learning-and-testing-java-applet/',
	'/TestDesign/front-end-development-of-a-logic-resource-recording-system/',
	'/TestDesign/integration-of-the-distribution-management-system-with-erp-systems-of-regional-distributors/',
	'/TestDesign/development-of-a-service-for-communication-with-the-iot-platform/',
	'/TestDesign/extension-of-functionality-and-re-engineering-of-an-application-for-estimating-and-managing-the-implementation-cost-of-projects/',
	'/TestDesign/ensuring-rapid-extensibility-of-the-ui-part-of-the-system/',
	'/TestDesign/custom-system-for-specific-business-needs/',
	'/TestDesign/development-of-a-web-application-for-digital-signature/',
	'/TestDesign/system-with-parallel-training-of-a-specialist/',
	'/TestDesign/product-stabilization-through-data-driven-testing/',
	'/TestDesign/consulting-on-a-science-intensive-product-development-and-building-a-qa-process/',
	'/TestDesign/test-automation-optimization-accompanied-by-ci-cd-integration-for-the-product-with-sophisticated-build-and-test-approaches/',
	'/TestDesign/full-fledged-test-automation-of-a-product-with-complex-architecture/',
	'/TestDesign/manual-testing-of-a-complex-internet-of-things-iot-product/',
	'/TestDesign/building-the-process-of-manual-testing-in-a-scientific-startup/',
	'/TestDesign/services/software-development/',
	'/TestDesign/audit-of-the-manual-testing-process/',
	'/TestDesign/creation-of-a-large-system-of-immersion-in-the-most-complex-project-by-a-manual-tester/',
	'/TestDesign/comprehensive-consulting-on-the-automation-of-development-and-testing-processes/',
	'/TestDesign/implementing-a-testing-process-for-a-large-erp-system/',
	'/TestDesign/building-it-processes/',
	'/TestDesign/development-of-relations-with-the-customer/',
	'/TestDesign/best-development-and-testing-practices/',
	'/TestDesign/business-component-of-the-customers-application/',
	'/TestDesign/automatic-deployment-of-the-system-in-the-field-of-telecommunications/',
	'/TestDesign/implementing-continuous-integration-to-test-automation/',
	'/TestDesign/reducing-costs-through-the-implementation-of-ci-cd/',
	'/TestDesign/full-cycle-of-ci-cd-implementation/',
	'/TestDesign/frontend-development-2/',
	'/TestDesign/test-automation-2/',


];
	if ( $level == '2' ) { $ph = ''; $span_line = '<span class="project-headline__line"></span>';} else { $ph = '-h'.$level; $span_line = '';}

	if ( in_array( preg_replace('/\\?.*/', '', $_SERVER['REQUEST_URI']), $portfolio_page ) ){
		return '<div class="project-headline'.$ph.'"><h'.$level.' class="head'.$dash.'line" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'">'.do_shortcode( $content ).'</h'.$level.'>'.$span_line.'</div>';
	} else {
		if ($margintop == 0) {$important = '';} else {$important = '!important';}
		if ($marginbottom == 0) {$important1 = '';} else {$important1 = '!important';}
	  return '<h'.$level.' class="headline" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'"  style="margin-top:'.$margintop.'px'.$important.';">'.do_shortcode( $content ).'</h'.$level.'><span class="line" style="margin-bottom:'.$marginbottom.'px'.$important1.';"></span><div class="clearfix"></div>';
	}

}
add_shortcode( 'headline', 'pp_headline' );


/**
* Headline2 shortcode
* Usage: [headline2] [/headline2] // margin-down margin-both
*/
/*function pp_headline2( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0,	  
	'alt' => '',
	'numbers' => 'true',

    ), $atts));
  return '<h3 class="headline" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'" style="margin-top:'.$margintop.'px; margin-bottom:'.$marginbottom.'px;">'.do_shortcode( $content ).'</h3><div class="clearfix"></div>';
}
add_shortcode( 'headline2', 'pp_headline2' );*/


function pp_headline2( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0,	  
	'alt' => '',
	'numbers' => 'true',
	'toc' => 'true',
    ), $atts));	

$portfolio_page = [
	'/TestDesign/portfolio/monitoring-and-management-system-of-state-procurement-for-tender-contractual-department/',
	'/TestDesign/portfolio/tariff-plans-and-services-management-system-for-mobile-operator/',
	'/TestDesign/portfolio/transferring-commercial-integration-mule-applications-from-on-premise-to-the-cloud-environment/',
	'/TestDesign/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/TestDesign/portfolio/the-social-network-for-creative-people/',
	'/TestDesign/portfolio/preparing-microservice-infrastructure-for-transition-to-k8s/',
	'/TestDesign/portfolio/role-playing-game-mechanics/',
	'/TestDesign/portfolio/backend-complex-travel-system/',
	'/TestDesign/portfolio/creation-of-commercial-mule-connector-for-ibm-i-as-400/',
	'/TestDesign/portfolio/online-professional-translation-service/',
	'/TestDesign/portfolio/ci-cd-for-engineering-application/',
	'/TestDesign/portfolio/web-interface-of-the-system-for-geographical-location-tracking-of-objects-on-google-maps/',
	'/TestDesign/portfolio/integration-of-distribution-management-system-with-regional-distributors-erp-systems/',
	'/TestDesign/portfolio/frontend-development-of-web-application-to-maintain-a-healthy-lifestyle/',
	'/TestDesign/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/TestDesign/portfolio/automated-testing-applications-for-managing-products-services-mobile-operator/',
	'/TestDesign/portfolio/mule-collecting-processing-calls-information-transferring-webbing-service/',
	'/TestDesign/portfolio/consulting-on-complex-demanded-project-in-e-commerce/',
	'/TestDesign/portfolio/electronic-product-inventory-sync-erp/',
	'/TestDesign/portfolio/consulting-stabilization-software-development-process-improve-quality/',
	'/TestDesign/portfolio/big-telecom-company-crm/',
	'/TestDesign/portfolio/enterprise-resource-management-system/',
	'/TestDesign/portfolio/accounts-aggregator-for-web-application/',
	'/TestDesign/portfolio/application-for-estimating-and-managing-the-implementation-cost-of-large-scale-industrial-projects/',
	'/TestDesign/portfolio/automation-testing-webapp-options-of-bank-deposits/',
	'/TestDesign/portfolio/mouse-emulator-application-for-android-devices/',
	'/TestDesign/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization',
	'/TestDesign/portfolio/telecommunication-data-visualization-and-management-center/',
	'/TestDesign/portfolio/management-center-of-telecom-data/',
	'/TestDesign/portfolio/development-telecommunication-system-for-audio-data-processing/',
	'/TestDesign/portfolio/inventory-system-of-logical-resources-for-mobile-operator/',
	'/TestDesign/portfolio/development-service-for-interaction-with-energy-accounting-platforms/',
	'/TestDesign/portfolio/jde-integration-with-magento-ecommerce-platform/',
	'/TestDesign/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization/',
	'/TestDesign/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/TestDesign/portfolio/application-for-automating-data-collection-from-business-portals/',
	'/TestDesign/portfolio/auto-testing-car-service-web-application/',
	'/TestDesign/portfolio/completion-of-the-electronic-laboratory-passport-web-application/',
	'/TestDesign/portfolio/development-front-end-platform-for-supporting-iot-applications/',
	'/TestDesign/portfolio/development-integration-between-ecommerce-platform-magento-epicor-prophet-21/',
	'/TestDesign/portfolio/development-low-code-trend-at-jazzteam/',
	'/TestDesign/portfolio/the-system-of-accounting-and-control-of-the-costs-of-telecommunications-services/',
	'/TestDesign/portfolio/business-processes-automation-in-environmental-activities/',
	'/TestDesign/portfolio/ticket-online-sale-system/',
	'/TestDesign/portfolio/contact-management-system/',
	'/TestDesign/portfolio/setting-up-processes-and-comprehensive-testing-of-a-product-designed-for-complex-calculations-in-the-scientific-field/',
	'/TestDesign/portfolio/voip-integration/',
	'/TestDesign/portfolio/integration-system-for-trading-company/',
	'/TestDesign/portfolio/product-quality-assurance-service/',
	'/TestDesign/portfolio/implementing-ci-cd-on-high-resistance-project/',
	'/TestDesign/portfolio/consulting-project-developing-integrated-software-solution-security-monitoring-industrial-civil-facilities/',
	'/TestDesign/portfolio/the-project-resources-and-costs-monitoring-system/',
	'/TestDesign/portfolio/the-system-for-cryptocurrency-rate-forecasting-based-on-text-analysis/',
	'/TestDesign/portfolio/web-antivirus-api/',
	'/TestDesign/portfolio/system-of-cloud-storages-unification/',
	'/TestDesign/portfolio/product-development-for-creating-advertising-banners/',
	'/TestDesign/portfolio/solution-for-automatic-information-collection/',
	'/TestDesign/portfolio/system-automation-processes-control-accounting-activities-supervisory-service/',
	'/TestDesign/portfolio/telecom-oriented-information-migration-project-on-clients-payments-from-one-system-to-another-with-complex-transformation-of-data-structure/',
	'/TestDesign/portfolio/testing-system-for-automated-accounting-of-information-about-industrial-infrastructure-facility/',
	'/TestDesign/portfolio/development-of-an-application-for-generating-pay-sheets/',
	'/TestDesign/portfolio/development-of-educational-online-platform-module/',
	'/TestDesign/portfolio/development-web-application-for-conducting-scientific-experiments/',
	'/TestDesign/portfolio/gaming-computer-club-management-system/',
	'/TestDesign/portfolio/implementation-process-manual-testing-online-platform-localization-github-projects/',
	'/TestDesign/portfolio/keep-them-all/',
	'/TestDesign/portfolio/mule-software-for-integrating-internal-systems/',
	'/TestDesign/portfolio/redesign-of-the-portal-for-cost-accounting-and-control-system-of-telecommunications-services/',
	'/TestDesign/portfolio/solution-for-data-migration-synchronization/',
	'/TestDesign/portfolio/system-for-calculating-pile-foundations-in-extreme-conditions/',
	'/TestDesign/portfolio/testing-company-procurement-management-system/',
	'/TestDesign/portfolio/testing-unified-international-electronic-system-recording-movable-property/',
	'/TestDesign/portfolio/time-reporting-automation/',
	'/TestDesign/portfolio/web-application-for-education/',
	'/TestDesign/portfolio/website-of-the-translation-service/',
	'/TestDesign/portfolio/development-erp-product-customer-consulting-and-system-design-transformations/',
	'/TestDesign/portfolio/creation-of-commercial-mule-connector-to-system-of-control-of-master-data/',
	'/TestDesign/portfolio/account-register-in-the-cloud-services/',
	'/TestDesign/portfolio/platform-for-building-products-based-on-service-oriented-architecture/',
	'/TestDesign/portfolio/development-service-for-organizing-targeted-ads/',
	'/TestDesign/portfolio/document-signature-applet/',
	'/TestDesign/portfolio/automated-testing-web-application-related-to-geofence-marketing/',
	'/TestDesign/portfolio/big-data-reporting-system/',	
	'/TestDesign/portfolio/development-of-saas-solution-for-xml2selenium-framework/',
	'/TestDesign/portfolio/integration-layer-interact-with-existing-solutions-ibm-as400-system/',
	'/TestDesign/portfolio/automated-competitors-price-monitoring-and-self-optimizing-system/',
	'/TestDesign/portfolio/getaddressbyip-service-determining-device-geolocation-by-ip/',
	'/TestDesign/portfolio/migrate-to-another-platform-for-stable-web-application/',
	'/TestDesign/portfolio/medication-search-service/',
	'/TestDesign/portfolio/integration-proxy/',
	'/TestDesign/portfolio/billing-system-for-the-multimedia-channels-provider/',
	'/TestDesignrtfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/TestDesign/portfolio/complex-android-ui-customization/',
	'/TestDesign/portfolio/xml2selenium-the-jazzteam-companys-product/',
	'/TestDesign/portfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/TestDesign/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/TestDesign/portfolio/modernization-corporate-portal-jive-based-for-personal-growth/',
	'/TestDesign/portfolio/mobile-application-network-beauty-salons/',
	'/TestDesign/portfolio/frontend-development-new-version-web-portal-for-interior-design/',
	'/TestDesign/portfolio/development-of-a-cross-platform-voip-client/',
	'/TestDesign/portfolio/management-center-of-telecommunication-information/',
	'/TestDesign/portfolio/databases-updates-management-application/',
	'/TestDesign/portfolio/development-of-cloud-iot-platform-components-for-energy-resources-accounting-and-management-of-energy-meters/',
	'/TestDesign/portfolio/web-application-for-buying-and-selling-real-estate/',
	'/TestDesign/portfolio/integration-system-application-localization-customer-service-platform/',
	'/TestDesign/portfolio/social-media-brand-analyzer/',
	'/TestDesign/portfolio/marketing-platform-of-data-analysis-site-stackexchange-com/',
	'/TestDesign/portfolio/web-application-for-viewing-personal-files-of-employees-and-students/',
	'/TestDesign/portfolio/management-transformation-long-history-project/',
	'/TestDesign/portfolio/automated-ui-tests-for-travel-industry/',
	'/TestDesign/portfolio/corporate-mobile-application-for-traffic-accounting-and-management-of-communication-costs/',
	'/TestDesign/portfolio/learning-and-testing-java-applet/',
	'/TestDesign/front-end-development-of-a-logic-resource-recording-system/',
	'/TestDesign/integration-of-the-distribution-management-system-with-erp-systems-of-regional-distributors/',
	'/TestDesign/development-of-a-service-for-communication-with-the-iot-platform/',
	'/TestDesign/extension-of-functionality-and-re-engineering-of-an-application-for-estimating-and-managing-the-implementation-cost-of-projects/',
	'/TestDesign/ensuring-rapid-extensibility-of-the-ui-part-of-the-system/',
	'/TestDesign/custom-system-for-specific-business-needs/',
	'/TestDesign/development-of-a-web-application-for-digital-signature/',
	'/TestDesign/system-with-parallel-training-of-a-specialist/',
	'/TestDesign/product-stabilization-through-data-driven-testing/',
	'/TestDesign/consulting-on-a-science-intensive-product-development-and-building-a-qa-process/',
	'/TestDesign/test-automation-optimization-accompanied-by-ci-cd-integration-for-the-product-with-sophisticated-build-and-test-approaches/',
	'/TestDesign/full-fledged-test-automation-of-a-product-with-complex-architecture/',
	'/TestDesign/manual-testing-of-a-complex-internet-of-things-iot-product/',
	'/TestDesign/building-the-process-of-manual-testing-in-a-scientific-startup/',
	'/TestDesign/services/software-development/',
	'/TestDesign/audit-of-the-manual-testing-process/',
	'/TestDesign/creation-of-a-large-system-of-immersion-in-the-most-complex-project-by-a-manual-tester/',
	'/TestDesign/comprehensive-consulting-on-the-automation-of-development-and-testing-processes/',
	'/TestDesign/implementing-a-testing-process-for-a-large-erp-system/',
	'/TestDesign/building-it-processes/',
	'/TestDesign/development-of-relations-with-the-customer/',
	'/TestDesign/best-development-and-testing-practices/',
	'/TestDesign/business-component-of-the-customers-application/',
	'/TestDesign/automatic-deployment-of-the-system-in-the-field-of-telecommunications/',
	'/TestDesign/implementing-continuous-integration-to-test-automation/',
	'/TestDesign/reducing-costs-through-the-implementation-of-ci-cd/',
	'/TestDesign/full-cycle-of-ci-cd-implementation/',
	'/TestDesign/frontend-development-2/',
	'/TestDesign/test-automation-2/',


];

	if ($toc == 'true') {$dash = '';} else {$dash = '-';}

	if ( in_array( preg_replace('/\\?.*/', '', $_SERVER['REQUEST_URI']), $portfolio_page ) ){
		return '<div class="project-headline-h3"><h3 class="head'.$dash.'line" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'" style="margin-top: 0px; margin-bottom: 0px">'.do_shortcode( $content ).'</h3></div>';
	} else {
  		return '<h3 class="head'.$dash.'line" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'" style="margin-top:'.$margintop.'px; margin-bottom:'.$marginbottom.'px;">'.do_shortcode( $content ).'</h3><div class="clearfix"></div>';
	}
}
add_shortcode( 'headline2', 'pp_headline2' );



/**
* Headline3 shortcode
* Usage: [headline3] [/headline3] // margin-down margin-both
*/
function pp_headline3( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0,	  
	'alt' => '',
	'numbers' => 'true',
	
    ), $atts));
  return '<h4 class="headline" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'" style="margin-top:'.$margintop.'px; margin-bottom:'.$marginbottom.'px;">'.do_shortcode( $content ).'</h4><div class="clearfix"></div>';
}
add_shortcode( 'headline3', 'pp_headline3' );


/**
* Icon shortcode
* Usage: [icon icon="icon-exclamation"]
*/
function pp_icon($atts) {
    extract(shortcode_atts(array(
        'icon'=>''), $atts));
    return '<i class="'.$icon.'"></i>';
}
add_shortcode('icon', 'pp_icon');


/**
* Highlight shortcode
* Usage: [highlight style="gray"] [/highlight] // color, gray, light
*/
function pp_highlight($atts, $content = null) {
    extract(shortcode_atts(array(
        'style' => 'gray'
        ), $atts));
    return '<span class="highlight '.$style.'">'.$content.'</span>';
}
add_shortcode('highlight', 'pp_highlight');


/**
* Icon box shortcode
* Usage: [iconbox column="one-third" title="" link="" icon=""] [/iconbox]
*/
function pp_iconbox( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => '',
        'link' => '',
        'icon' => '',
        'place' => '',
        'width' => 'one-third'
        ), $atts));

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $output = '<div class="columns '.$width.' '.$p.'">';
    $output .= '<div class="featured-box"><div class="circle"><i class="'.$icon.'"></i><span></span></div>';
    $output .= '<div class="featured-desc">';

    if($link) {
        $output .= '<h3><a href="'.$link.'">'.$title.'</a></h3>';
    }
    else {
        $output .= '<h3>'.$title.'</h3>';
    }

    $output .= '<p>'.do_shortcode( $content ).'</p></div></div></div>';
    return $output;
}
add_shortcode( 'iconbox', 'pp_iconbox' );


/**
*  Usage: [iconwrapper] [/iconwrapper]
*/
function pp_iconbox_wrapper( $atts, $content ) {
    $output = '<div class="featured-boxes homepage">'.do_shortcode( $content ).'</div>';
    return $output;
}
add_shortcode( 'iconwrapper', 'pp_iconbox_wrapper' );


/**
* Recent work shortcode
* Usage: [recent_work limit="4" title="Recent Work" orderby="date" order="DESC" filters="" carousel="yes"] [/recent_work]
*/
function pp_recent_work($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'title' => 'Recent Work',
        'orderby'=> 'date',
        'order'=> 'DESC',
        'filters' => '',
        'width' => 'sixteen',
        'place' => 'center',
        'exclude_posts' => '',
        'include_posts' => '',
        ), $atts));

    $output = '';
    $randID = rand(1, 99); // Get unique ID for carousel

    if(empty($width)) { $width = "sixteen"; } //set width to 16 even if empty value

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $output .= '
    <div class="'.$width.' columns '.$p.'">
    <div>
    <h3 class="headline">'.$title.'</h3>
    <span class="line" style="margin-bottom:0;"></span>
    </div>

    <!-- ShowBiz Carousel -->
    <div id="recent-work" class="showbiz-container recent-work ">

    <!-- Navigation -->
    <div class="showbiz-navigation">
    <div id="showbiz_left_'.$randID.'" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
    <div id="showbiz_right_'.$randID.'" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
    </div>
    <div class="clearfix"></div>

    <div class="showbiz" data-left="#showbiz_left_'.$randID.'" data-right="#showbiz_right_'.$randID.'">
    <!-- Portfolio Entries -->
    <div class="overflowholder">
    <ul>';
    $args = array(
        'post_type' => 'portfolio',
        'posts_per_page' => $limit,
        'orderby' => $orderby,
        'order' => $order,
        );
    if(!empty($exclude_posts)) {
        $exl = explode(",", $exclude_posts);
        $args['post__not_in'] = $exl;
    }
    if(!empty($include_posts)) {
        $inc = explode(",", $include_posts);
        $args['post__in'] = $inc;
    }

    if(!empty($filters)) {
        $filters = explode(",", $filters);
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'filters',
                'field' => 'slug',
                'terms' => $filters
                )
            );
    }
    $wp_query = new WP_Query( $args );
    if($wp_query->found_posts > 1) { $mfpclass= "mfp-gallery2"; } else { $mfpclass= "mfp-image"; }
    if ( $wp_query->have_posts() ):
        while( $wp_query->have_posts() ) : $wp_query->the_post();
    $output .= '<li>
    <div class="portfolio-item media">
    <figure>
    <div class="mediaholder">';
    $thumb = get_post_thumbnail_id();
    $img_url = wp_get_attachment_url($thumb);
    $lightbox = get_post_meta($wp_query->post->ID, 'pp_pf_lightbox', true);
    if($lightbox == 'lightbox') {
        $fullsize = wp_get_attachment_image_src($thumb, 'full');
        $output .= '<a href="'.$fullsize[0].'" class="'.$mfpclass.'" title="'.get_the_title().'">';
        $output .= get_the_post_thumbnail($wp_query->post->ID,'portfolio-4col');
        $output .= '
        <div class="hovercover">
        <div class="hovericon"><i class="hoverzoom"></i></div>
        </div>
        </a>';
    } else {
        $output .= '<a href="'.get_permalink().'"  title="'.get_the_title().'">';
        $output .= get_the_post_thumbnail($wp_query->post->ID,'portfolio-4col');
        $output .= '
        <div class="hovercover">
        <div class="hovericon"><i class="hoverlink"></i></div>
        </div>
        </a>';
    }
    $output .= '</div>
    <a href="'.get_permalink().'">
    <figcaption class="item-description">
    <p class="item-h5">'.get_the_title().'</p>';
    $terms = get_the_terms( $wp_query->post->ID, 'filters' );
    if ( $terms && ! is_wp_error( $terms ) ) : $output .= '<span>';
    $filters = array();
    $i = 0;
    foreach ( $terms as $term ) {
        $filters[] = $term->name;
        if ($i++ > 0) break;
    }
    $outputfilters = join( ", ", $filters );
    $output .= $outputfilters;
    $output .= '</span>';
    endif;
    $output .= '</figcaption>
    </a>
    </figure>
    </div>
    </li>';
    endwhile;  // close the Loop
    endif;
    $output .= '</ul>
    <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    </div>
    </div>
    </div>';
    wp_reset_query();
    return $output;
}

add_shortcode('recent_work', 'pp_recent_work');

/**
* Recent work shortcode
* Usage: [recent_blog limit="4" title="Recent Work" orderby="date" order="DESC"  carousel="yes"] [/recent_blog]
*/
function pp_recent_blog($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'title' => 'Recent Posts',
        'orderby'=> 'date',
        'order'=> 'DESC',
        'categories' => '',
        'tags' => '',
        'width' => 'sixteen',
        'place' => 'center',
        'exclude_posts' => '',
        'include_posts' => '',
        ), $atts));

    $output = '';
    $randID = rand(1, 99); // Get unique ID for carousel

    if(empty($width)) { $width = "sixteen"; } //set width to 16 even if empty value

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $output .= '
    <div class="'.$width.' columns '.$p.'">
    <div>
    <h3 class="headline">'.$title.'</h3>
    <span class="line" style="margin-bottom:0;"></span>
    </div>

    <!-- ShowBiz Carousel -->
    <div id="recent-work" class="showbiz-container recent-work ">

    <!-- Navigation -->
    <div class="showbiz-navigation">
    <div id="showbiz_left_'.$randID.'" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
    <div id="showbiz_right_'.$randID.'" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
    </div>
    <div class="clearfix"></div>

    <div class="showbiz" data-left="#showbiz_left_'.$randID.'" data-right="#showbiz_right_'.$randID.'">
    <!-- Portfolio Entries -->
    <div class="overflowholder">
    <ul>';
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'orderby' => $orderby,
        'order' => $order,
        );
    if(!empty($exclude_posts)) {
        $exl = explode(",", $exclude_posts);
        $args['post__not_in'] = $exl;
    }
    if(!empty($include_posts)) {
        $inc = explode(",", $include_posts);
        $args['post__in'] = $inc;
    }


    if(!empty($categories)) {
        $categories = explode(",", $categories);
        $args['category__in'] = $categories;
    }
    if(!empty($tags)) {
        $tags = explode(",", $tags);
        $args['tag__in'] = $tags;
    }
    $wp_query = new WP_Query( $args );

    if ( $wp_query->have_posts() ):
        while( $wp_query->have_posts() ) : $wp_query->the_post();
    $output .= '<li>
    <div class="blog-item media">
    <figure>
    <div class="mediaholder ';
    if(!has_post_thumbnail()) { $output .= "textholder"; }
    $output .= '">';
    $thumb = get_post_thumbnail_id();
    $img_url = wp_get_attachment_url($thumb);

    if(has_post_thumbnail()){
        $output .= '<a href="'.get_permalink().'"  title="'.get_the_title().'">';
        $output .= get_the_post_thumbnail($wp_query->post->ID,'portfolio-4col');
        $output .= '<div class="hovercover">
        <div class="hovericon"><i class="hoverlink"></i></div>
        </div>
        </a>';
    }
    if(!has_post_thumbnail()){
        $excerpt = get_the_excerpt();
        $short_excerpt = string_limit_words($excerpt,30); $output .= '<p>'.$short_excerpt.'..</p>';
    }

    $output .= '</div>
    <a href="'.get_permalink().'">
    <figcaption class="item-description">
    <h5>'.get_the_title().'</h5>';
    $output .= '<span>';
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    $time_string = sprintf( $time_string,esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );
    $output .=  $time_string;
    $output .= '</span>';
    $output .= '</span>';

    $output .= '</figcaption>
    </a>
    </figure>
    </div>
    </li>';
    endwhile;  // close the Loop
    endif;
    $output .= '</ul>
    <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    </div>
    </div>
    </div>';
    wp_reset_query();
    return $output;
}

add_shortcode('recent_blog', 'pp_recent_blog');


/**
* Tesimonials shortcode
* Usage: [testimonials limit="4" title="Testimonials" ]
*/

function pp_testimonials($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'title' => 'Testimonials',
        'orderby' => 'date',
        'order' => 'DESC',
        'width' => 'eight',
        'place' => 'none',
        'exclude_posts' => '',
        'include_posts' => '',
        ), $atts));

    $randID = rand(1, 99);
    if(empty($width)) { $width = "sixteen"; }

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $args = array(
        'post_type' => array('testimonial'),
        'showposts' => $limit,
        'orderby' => $orderby,
        'order' => $order
    );
    if(!empty($exclude_posts)) {
        $exl = explode(",", $exclude_posts);
        $args['post__not_in'] = $exl;
    }
    if(!empty($include_posts)) {
        $inc = explode(",", $include_posts);
        $args['post__in'] = $inc;
    }


    $wp_query = new WP_Query( $args );
    $output = '
    <div class="testimonials_wrap showbiz-container '.$width.' '.$p.' columns">
    <h3 class="headline">'.$title.'</h3>
    <span class="line" style="margin-bottom:0;"></span>

    <!-- Navigation -->
    <div class="showbiz-navigation">
    <div id="showbiz_left_'.$randID.'" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
    <div id="showbiz_right_'.$randID.'" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
    </div>
    <div class="clearfix"></div>

    <!-- Entries -->
    <div class="showbiz" data-left="#showbiz_left_'.$randID.'" data-right="#showbiz_right_'.$randID.'">
    <div class="overflowholder">
    <ul>';
    if ( $wp_query->have_posts() ):
        while( $wp_query->have_posts() ) : $wp_query->the_post();

    $id = $wp_query->post->ID;
    $author = get_post_meta($id, 'pp_author', true);
    $link = get_post_meta($id, 'pp_link', true);
    $position = get_post_meta($id, 'pp_position', true);

    $output .= '<li class="testimonial">';
    $output .= '<div class="testimonials">'.get_the_content().'</div><div class="testimonials-bg"></div>';
    if($link) {
        $output .= ' <div class="testimonials-author"><a href="'.$link.'">'.$author.'</a>';
    } else {
        $output .= ' <div class="testimonials-author">'.$author;
    }
    if($position) { $output .= ', <span>'.$position.'</span>'; }
    $output .= '</div></li>';
    endwhile;  // close the Loop
    endif;
    $output .='</ul>
    <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    </div>
    </div>';
    wp_reset_query();
    return $output;
}

add_shortcode('testimonials', 'pp_testimonials');


/**
* Happy Tesimonials shortcode
* Usage: [happytestimonials limit="4" title="Testimonials" ]
*/

function pp_happytestimonials($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'title' => 'Clients',
        'orderby' => 'date',
        'order' => 'DESC',
        'width' => 'sixteen',
        'place' => 'none',
        'exclude_posts' => '',
        'include_posts' => '',
        ), $atts));

    $randID = rand(1, 99);


    $args = array(
        'post_type' => array('testimonial'),
        'showposts' => $limit,
        'orderby' => $orderby,
        'order' => $order
    );
    if(!empty($exclude_posts)) {
        $exl = explode(",", $exclude_posts);
        $args['post__not_in'] = $exl;
    }
    if(!empty($include_posts)) {
        $inc = explode(",", $include_posts);
        $args['post__in'] = $inc;
    }
    $wp_query = new WP_Query( $args );

    if(empty($width)) { $width = "sixteen"; }

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $output = '<!-- Headline -->
    <div class="'.$width.' '.$p.' columns happywrapper">
    <div style="margin-top: -5px;">
    <h3 class="headline">'.$title.'</h3>
    <span class="line" style="margin-bottom: 20px;"></span>
    </div>

    <!-- Navigation / Left -->
    <div id="showbiz_left_'.$randID.'" class="sb-navigation-left-2 alt"><i class="icon-angle-left"></i></div>

    <!-- ShowBiz Carousel -->
    <div id="happy-clients" class="happy-clients showbiz-container  carousel " >

    <!-- Portfolio Entries -->
    <div class="showbiz our-clients" data-left="#showbiz_left_'.$randID.'" data-right="#showbiz_right_'.$randID.'">
    <div class="overflowholder">
    <ul>';
    if ( $wp_query->have_posts() ):
        while( $wp_query->have_posts() ) : $wp_query->the_post();

    $id = $wp_query->post->ID;
    $author = get_post_meta($id, 'pp_author', true);
    $link = get_post_meta($id, 'pp_link', true);
    $position = get_post_meta($id, 'pp_position', true);
    $output .= '<li>';
    $output .= '<div class="happy-clients-photo">'. get_the_post_thumbnail($wp_query->post->ID,'portfolio-thumb').'</div>';
    $output .= '<div class="happy-clients-cite">'.get_the_content().'</div>';
    if($link) {
        $output .= ' <div class="happy-clients-author"><a href="'.$link.'">'.$author.'</a>';
    } else {
        $output .= ' <div class="happy-clients-author">'.$author;
    }
    $output .= '</li>';
                endwhile;  // close the Loop
                endif;
                $output .='</ul>
                <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                </div>
                </div>
                <div id="showbiz_right_'.$randID.'" class="sb-navigation-right-2 alt"><i class="icon-angle-right"></i></div>
                </div>';
                wp_reset_query();
                return $output;
            }

            add_shortcode('happytestimonials', 'pp_happytestimonials');


/**
* Team members shortcode
* Usage: [team title="Team" ]
*/

function pp_team($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'title' => 'Team',
        'members' => '',
        ), $atts));

    $randID = rand(1, 99);

    if(!empty($members)) {
        $members = explode(",", $members);
        $args = array(
            'post_type' => array('team'),
            'showposts' => $limit,
            'post__in' => $members,
            );
    } else {
       $args = array(
        'post_type' => array('team'),
        'showposts' => $limit,

        );
   }
   $wp_query = new WP_Query($args);

   $output = '
   <div class="team showbiz-container">
   <!-- Headline -->
   <h3 class="headline">'.$title.'</h3>
   <span class="line" style="margin-bottom:0;"></span>

   <!-- Navigation -->
   <div class="showbiz-navigation">
   <div id="showbiz_left_'.$randID.'" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
   <div id="showbiz_right_'.$randID.'" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
   </div>
   <div class="clearfix"></div>

   <!-- Entries -->
   <div class="showbiz" data-left="#showbiz_left_'.$randID.'" data-right="#showbiz_right_'.$randID.'">

   <div class="overflowholder">
   <ul>';
   if ( $wp_query->have_posts() ):
    while( $wp_query->have_posts() ) : $wp_query->the_post();

$id = $wp_query->post->ID;
$position = get_post_meta($id, 'pp_position', true);
$social = get_post_meta($id, 'pp_socialicons', true);

$output .= '<li>';
if ( has_post_thumbnail() ) {
    $output .= get_the_post_thumbnail($wp_query->post->ID,'portfolio-3col', array('class' => 'mediaholder team-img'));
}
$output .= '<div class="team-name"><h5>'.get_the_title().'</h5> <span>'.$position.'</span></div>
<div class="team-about"><p>'.get_the_content().'</p></div>';

if(!empty($social)){
    $output .= '<ol class="social-icons">';
    foreach ($social as $icon) {
        $output .= '<li><a class="'.$icon['icons_service'].'" href="'.$icon['icons_url'].'"><i class="icon-'.$icon['icons_service'].'"></i></a></li>';
    }
    $output .= '</ol>';
}

$output .= '<div class="clearfix"></div></li>';
                endwhile;  // close the Loop
                endif;

                $output .='</ul>
                <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                </div>
                </div>';
                wp_reset_query();
                return $output;
            }

            add_shortcode('team', 'pp_team');


/**
* Columns shortcode
* Usage: [column width="eight" place="" custom_class=""] [/column]
*/

function pp_column($atts, $content = null) {
    extract( shortcode_atts( array(
        'width' => 'eight',
        'place' => '',
        'custom_class' => ''
        ), $atts ) );

    switch ( $width ) {
        case "1/3" :
        $w = "column one-third";
        break;

        case "2/3" :
        $w = "column two-thirds";
        break;

        case "one" : $w = "one columns"; break;
        case "two" : $w = "two columns"; break;
        case "three" : $w = "three columns"; break;
        case "four" : $w = "four columns"; break;
        case "five" : $w = "five columns"; break;
        case "six" : $w = "six columns"; break;
        case "seven" : $w = "seven columns"; break;
        case "eight" : $w = "eight columns"; break;
        case "nine" : $w = "nine columns"; break;
        case "ten" : $w = "ten columns"; break;
        case "eleven" : $w = "eleven columns"; break;
        case "twelve" : $w = "twelve columns"; break;
        case "thirteen" : $w = "thirteen columns"; break;
        case "fourteen" : $w = "fourteen columns"; break;
        case "fifteen" : $w = "fifteen columns"; break;
        case "sixteen" : $w = "sixteen columns"; break;

        default :
        $w = 'columns eight';
    }

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $column ='<div class="'.$w.' '.$custom_class.' '.$p.'">'.do_shortcode( $content ).'</div>';
    if($place=='last') {
        $column .= '<br class="clear" />';
    }
    return $column;
}

add_shortcode('column', 'pp_column');


/**
* Notice shortcode
* Usage: [noticebox title="Notice" icon="" link=""] [/noticebox]
*/
function pp_noticebox( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Notice',
        'icon' => '',
        'link' => ''
        ), $atts));
    $output = '';
    if($link) {
        $output .= '<a href="'.$link.'">';
    }

    $output .= '<div class="notice-box"><h3>'.$title.'</h3>';
    if($icon) {
        $output .= '<i class="'.$icon.'"></i>';
    }
    $output .= '<p>'.do_shortcode( $content ).'</p></div>';
    if($link) {
        $output .= '</a>';
    }
    return $output;
}

add_shortcode( 'noticebox', 'pp_noticebox' );


/**
* Tooltip shortcode
* Usage: [tooltip title="" url=""] [/tooltip] // color, gray, light
*/
function pp_tooltip($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'url' => '',
        'target' => '_self',
        'side' => 'top'
        ), $atts));
    return '<a href="'.$url.'" target="'.$target.'" class="tooltip '.$side.'" title="'.$title.'">'.$content.'</a>';
}

add_shortcode('tooltip', 'pp_tooltip');


/**
* Skillbars shortcode
* Usage: [skillbars] [/skillbars]
*/

function pp_skillbars( $atts, $content ) {
    extract(shortcode_atts(array(), $atts));
    return '<div id="skillzz">'.do_shortcode( $content ).'</div>';
}

add_shortcode( 'skillbars', 'pp_skillbars' );


/**
* Usage: [skillbar title="Web Design 80%" value="80"]
*/
function pp_skillbar( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Web Design',
        'value' => '80',
        'icon' => ''
        ), $atts));
    return '<div class="skill-bar"><span class="skill-title"><i class="'.$icon.'"></i> '.$title.' </span><div class="skill-bar-value" style="width: '.$value.'%;"></div></div>';
}

add_shortcode( 'skillbar', 'pp_skillbar' );


/**
* Pricing table shortcode
* Usage: [pricing_table featured="no" color="" header="" price="" per=""] [/pricing_table]
*/


function pp_pricing_table($atts, $content) {
    extract(shortcode_atts(array(
        "type" => '',
        "width" => 'four',
        "title" => '',
        "currency" => '$',
        "price" => '',
        "per" => '',
        "buttonstyle" => '',
        "buttonlink" => '',
        "buttontext" => 'Sign Up',
        "place" =>''
        ), $atts));

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $output ='
    <div class="'.$type.' plan '.$width.' '.$p.' columns">
    <h3>'.$title.'</h3>
    <div class="plan-price">
    <span class="plan-currency">'.$currency.'</span>
    <span class="value">'.$price.'</span>
    <span class="period">'.$per.'</span>
    </div>
    <div class="plan-features">'.do_shortcode( $content );
    if($buttonlink) {
        $output .=' <a class="button '.$buttonstyle.'" href="'.$buttonlink.'">'.$buttontext.'</a>';
    }
    $output .=' </div>
    </div>';
    return $output;
}

add_shortcode('pricing_table', 'pp_pricing_table');


/**
* Box shortcodes
* Usage: [box type=""] [/box]
*/

function pp_box($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => ''
        ), $atts));
    return '<div class="notification closeable '.$type.'"><p>'.do_shortcode( $content ).'</p><a class="close" href="#"></a></div>';
}

add_shortcode('box', 'pp_box');


/**
* Social icons shortcodes
*
*/

function pp_social_icon($atts) {
    extract(shortcode_atts(array(
        "service" => 'facebook',
        "url" => '',
        "target" => '_blank'
        ), $atts));
    $output = '<li><a class="'.$service.'" target="'.$target.'" href="'.$url.'"><i class="icon-'.$service.'"></i></a></li>';
    return $output;
}

add_shortcode('social_icon', 'pp_social_icon');


function pp_social_icons($atts,$content ) {
   extract(shortcode_atts(array( 'title'=>"Social Icons"), $atts));
   $output = '<ul class="social-icons clearfix">'.do_shortcode( $content ).'</ul>';
   return $output;
}

add_shortcode('social_icons', 'pp_social_icons');


/**
* Toggle shortcodes
* Usage: [toggle title="" open="no"] [/toggle]
*/

function pp_toggle( $atts, $content ) {
    extract(shortcode_atts(array(
        'title' => '',
        'open' => 'no'
        ), $atts));
    if($open != 'no') { $opclass = "opened"; } else { $opclass = ''; }
    return ' <div class="toggle-wrap"><span class="trigger '.$opclass.'"><a href="#"><i class="toggle-icon"></i> '.$title.'</a></span><div class="toggle-container"><p>'.do_shortcode( $content ).'</p></div></div>';
}

add_shortcode( 'toggle', 'pp_toggle' );


/**
* List style shortcode
* Usage: [list type="check"] [/list] // check, arrow, checkbg, arrowbg
*/
function pp_liststyle($atts, $content = null) {
    extract(shortcode_atts(array(
        "type" => 'check'
        ), $atts));

    switch ($type) {
        case 'check':
        $list = 'list-1';
        break;
        case 'arrow':
        $list = 'list-2';
        break;
        case 'checkbg':
        $list = 'list-3';
        break;
        case 'arrowbg':
        $list = 'list-4';
        break;
        default:
        $list = 'list-1';
        break;
    }
    return '<div class="'.$list.'">'.$content.'</div>';
}

add_shortcode('list', 'pp_liststyle');


/**
* Google map shortcodes
* Usage: [googlemap width="100%" height="250px" address="New York, United States"]
*/

function fn_googleMaps($atts, $content = null) {
    extract(shortcode_atts(array(
      "width" => '100%',
      "height" => '250px',
      "address" => 'New York, United States',
      "zoom" => 13
      ), $atts));
    $output ='<section class="google-map-container"><div id="googlemaps" class="google-map google-map-full" style="height:'.$height.'; width:'.$width.'"></div>
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script src="'.get_template_directory_uri().'/js/jquery.gmaps.min.js"></script>
    <script type="text/javascript">
    jQuery("#googlemaps").gMap({
        maptype: "ROADMAP",
        scrollwheel: false,
        zoom: '.$zoom.',
        markers: [
        {
            address: \''.$address.'\',
            html: "",
            popup: false,
        }
        ],
    });
</script></section>';
return $output;
}

add_shortcode("googlemap", "fn_googleMaps");

/**
* Recent work shortcode
* Usage: [clients_carousel title="Recent Work" ] [/clients_carousel]
*/
function pp_clients_carousel($atts, $content ) {
    extract(shortcode_atts(array(
        'title' => 'Clients',
        'subtitle' => 'Check for who we worked!',
        'width' => 'sixteen',
        'place' => 'center'
        ), $atts));

    $output = '';
    $width_arr = array(
        'sixteen' => 16, 'fifteen' => 15, 'fourteen' => 14, 'thirteen' => 13, 'twelve' => 12, 'eleven' => 11, 'ten' => 10, 'nine' => 9,
        'eight' => 8, 'seven' => 7, 'six' => 6, 'five' => 5, 'four' => 4, 'three' => 3
        );

    if(empty($width)) { $width = "sixteen"; }

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $carousel_width = $width_arr[$width] - 2;
    $carousel_key_width = array_search ($carousel_width, $width_arr);
    $output .= '
    <div class="'.$width.' columns '.$p.'">
    <div>
    <h3 class="headline">'.$title.'</h3>
    <span class="line" style="margin-bottom:0;"></span>
    </div>

    <!-- Navigation / Left -->
    <div class="one carousel column alpha"><div id="showbiz_left_2" class="sb-navigation-left-2"><i class="icon-angle-left"></i></div></div>

    <!-- ShowBiz Carousel -->
    <div id="our-clients" class="our-clients-cont showbiz-container '.$carousel_key_width.' carousel columns" >

    <!-- Portfolio Entries -->
    <div class="showbiz our-clients" data-left="#showbiz_left_2" data-right="#showbiz_right_2">
    <div class="overflowholder">';
    $output .= do_shortcode( $content );
    $output .='<div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    </div>
    </div>
    <!-- Navigation / Right -->
    <div class="one carousel column omega"><div id="showbiz_right_2" class="sb-navigation-right-2"><i class="icon-angle-right"></i></div></div></div>';
    return $output;
}

add_shortcode('clients_carousel', 'pp_clients_carousel');


//woocommerce custom shortcodes


/**
 * Recent Products shortcode
 *
 * @access public
 * @param array $atts
 * @return string
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    function astrum_woocommerce_recent_products( $atts, $content ) {
        extract(shortcode_atts(array(
            'title' => 'Recent Products',
            'orderby'=> 'date',
            'order'=> 'DESC',
            'per_page'  => '12',
            'width' => 'sixteen',
            'place' => 'center',
            ), $atts));

    $randID = rand(1, 99); // Get unique ID for carousel

    if(empty($width)) { $width = "sixteen"; } //set width to 16 even if empty value

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }
    $args = array(
        'suppress_filters' => false,
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page' => $per_page,
        'orderby' => $orderby,
        'order' => $order,
        'meta_query' => array(
            array(
                'key' => '_visibility',
                'value' => array('catalog', 'visible'),
                'compare' => 'IN'
                )
            )
        );
    $output = '
    <div class="'.$width.' columns '.$p.'">
    <div>
    <h3 class="headline">'.$title.'</h3>
    <span class="line" style="margin-bottom:0;"></span>
    </div>
    <!-- ShowBiz Carousel -->
    <div id="recent-work" class="showbiz-container recent-work ">

    <!-- Navigation -->
    <div class="showbiz-navigation">
    <div id="showbiz_left_'.$randID.'" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
    <div id="showbiz_right_'.$randID.'" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
    </div>
    <div class="clearfix"></div>

    <div class="showbiz" data-left="#showbiz_left_'.$randID.'" data-right="#showbiz_right_'.$randID.'">
    <!-- Portfolio Entries -->
    <div class="overflowholder">
    <ul>';
    $products = get_posts( $args );
    if ( $products ) :
        foreach( $products as $productshop ) : setup_postdata($productshop);
    $output .= '
    <li>
    <div class="portfolio-item media">
    <figure>
    <div class="mediaholder">';
    if ( has_post_thumbnail($productshop->ID)) {
        $output .=  '<a href="'.get_permalink($productshop->ID).'" >';
        $output .= get_the_post_thumbnail($productshop->ID,'portfolio-4col');
        $output .=  '
        <div class="hovercover">
        <div class="hovericon"><i class="hoverlink"></i></div>
        </div>
        </a>';
    }
    $output .= '
    </div>
    <a href="'.get_permalink($productshop->ID).'" >
    <figcaption class="item-description">
    <h5>'.get_the_title($productshop->ID).'</h5>';
    $product = get_product( $productshop->ID );
    $output .=  $product->get_price_html();
    $output .= '
    </figcaption>
    </a>
    </figure>
    </div>
    </li>';
    endforeach; // end of the loop.
    endif;
    $output .='</ul>
    <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    </div>
    </div>
    </div>';
    return $output;
}
add_shortcode('astrum_recent_products', 'astrum_woocommerce_recent_products');
}

/* shortcode business domains */
function business_domains_function(){
?>
<div class="container">
	<div class="list-4 five alpha columns">
	
		<ul class="no-margin">
		<?php 
		$terms = get_terms(['taxonomy' => 'filters']);
		$count_bd = count($terms);
		
		$k = 0;
		foreach ($terms as $term) { 
			
			?>
			
			<li>
				<a href="<?php echo get_term_link( $term ); ?>" target="_blank">
					<?php echo $term->name; ?>
				</a>
			</li>
			
			<?php
			$k++;
			if (ceil($count_bd/2) == $k) {
				
				echo '</ul></div><div class="list-4 five columns"><ul class="no-margin">';
				
			}
			
		
		} ?>
		</ul>
	</div>		
</div>			
<?php } 


function business_domains_shortcode()
{
    ob_start();             // turn on output buffering
    business_domains_function();// put the output to the buffer
    return ob_get_clean();  // capture and return the buffer
}
add_shortcode('business_domains','business_domains_shortcode');

/**
* Сaption shortcode
* Usage: [caption] [/caption] // margin-down margin-both
*/
function pp_caption( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0
    ), $atts));
	if (!empty($margintop) || !empty($marginbottom) ) {
		$style = 'style="margin-bottom:'.$marginbottom.'px; margin-top:'.$margintop.'px;"';
	}
  return '<div class="images-caption" '.$style.'>'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'caption', 'pp_caption' );


/**
* Orange_table shortcode
* Usage: [orange_table] [/orange_table] // margin-down margin-both
*/
function pp_orange_table( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0
    ), $atts));

  return '<div class="orange-table-container">'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'orange_table', 'pp_orange_table' );


/**
* Blockquote shortcode
* Usage: [blockquote] [/blockquote] 
*/
function pp_blockquote( $atts, $content ) {
  extract(shortcode_atts(array(
    'margintop' => 0,
    'marginbottom' => 0
    ), $atts));

  return '<blockquote class="ru-quote" >'.do_shortcode( $content ).'</blockquote>';
}
add_shortcode( 'blockquote', 'pp_blockquote' );



function vizor($target_images){
  $new_class = 'interactive-banner';
	
  $args = array(
      'post_type' => 'page',
      'posts_per_page' => -1,
// 	  'page_id' => 10844,
  );
  
  $query = new WP_Query($args);
  
  $mutated = '';
  $total_indexed = 0;
  
  if ($query->have_posts()) {
      while ($query->have_posts()) {
          $query->the_post();
          $post_id = get_the_ID();
          $content = get_the_content();
          $doc = new DOMDocument();
          $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
          $xpath = new DOMXPath($doc);
		  
		  $total_changes = 0;
		  $total_indexed += 1;
		  
		  if (empty($content)) {
              continue; // skip if doesnt have content
          }

          foreach ($target_images as $target_link) {
              $images = $xpath->query('//a/img[@src="' . $target_link . '"]');

              foreach ($images as $image) {
                  $parentA = $image->parentNode;
                  if ($parentA->nodeName === 'a') {
                      $existing_class = $parentA->getAttribute('class');
                      if(str_contains($existing_class, $new_class)){
                          continue; // skip when already have					   
						  /*$cc = str_replace($new_class, '', $existing_class);
						  $parentA->setAttribute('class', $cc);
						  $total_changes++;*/
                      }
  
                      else if (empty($existing_class)) {
                          $parentA->setAttribute('class', $new_class);
						  $total_changes++;
                      } else {
                          $parentA->setAttribute('class', $existing_class . ' ' . $new_class);
                          $total_changes++;
                      }
                  }
              }
          }          
  
          $updated_content = $doc->saveHTML();
          if ($content !== $updated_content && $total_changes !== 0) {
              //wp_update_post(array('ID' => $post_id, 'post_content' => $updated_content));
			  
			  $mutated .= 'Title: '.get_the_title().' Link: <a href="'.get_permalink().'">'.get_permalink().'</a> Total Changes: '.$total_changes.'</br></br>';
          }
      }
  }
  
  return $mutated . '<br/> Total parsed pages: '.$total_indexed;
  
  }
  
  if (!function_exists('one_way')) {
  
  function one_way($atts) {
      extract(
          shortcode_atts(
              array(
                  'id' => '',
                  'html_class' => '',
                  'margintop' => '',
                  'marginbottom' => '',
              ),
              $atts
          )
      );
  
      $target_images = array(
        'https://jazzteam.org/en/wp-content/uploads/2022/04/banner_services.svg',
		'https://jazzteam.org/en/wp-content/uploads/2022/04/banner_software_development.svg',
		'https://jazzteam.org/en/wp-content/uploads/2022/04/banner_test_automation.svg',
		'https://jazzteam.org/en/wp-content/uploads/2022/04/banner_IT_consulting.svg',
		'https://jazzteam.org/en/wp-content/uploads/2022/04/banner_services.svg',
		'https://jazzteam.org/en/wp-content/uploads/2022/11/manual-testing-banner.svg',
		'https://jazzteam.org/en/wp-content/uploads/2022/11/manual-testing-banner-3.svg',
		 'https://jazzteam.org/en/wp-content/uploads/2022/12/strong_management_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/consulting_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/management_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Product_development_by_JazzTeam_professionals.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Product_development_Service.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Stable_and_Coordinated_Team.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Technology_Research.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Consulting_Services.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Solve_a_Business_Problem.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Integration_andTechnical_Support.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/Banner_Improve_processes_and_Technical_component%20.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2021/10/How_we_work.jpg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/07/agile-java-dev.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/07/banner-ddt.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/07/banner_software_development.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/07/software-dev-why-java.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/08/case_007.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/08/case_008.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/banner_software_dewelopment.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/banner_services_jazzteam.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/xml2selenium.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/rpa_crawler.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/jazzteam_time_reporting.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/lifeline.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/banner_sd_2.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/05/banner_ta_1.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2021/10/Careers.jpg',
		  'https://jazzteam.org/en/wp-content/uploads/2023/05/check-list-how-to-understand.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2023/06/project_evaluation_checklist_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2023/04/manual_download.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2023/05/check-tech-debt.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/xml2selenium.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/rpa_crawler.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/jazzteam_time_reporting.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/lifeline.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/strong_management_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/consulting_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/12/management_banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/11/manual-testing-banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/11/manual-testing-banner-3.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/03/service-banner.svg',
		  'https://jazzteam.org/en/wp-content/uploads/2022/11/banner-services.svg',
      );
      $mutated = vizor($target_images);
 	  var_dump(ABSPATH);
 	  file_put_contents(__DIR__.'/banners-'.uniqid().'.txt', $mutated, LOCK_EX);
      return '<div class="one_way">'.$mutated.'</div>';
  }
  
  add_shortcode( 'one_way', 'one_way' );
}



// -------------------------- Slick slider for project page START ---------------------
/**
* Recent projects shortcode
* Usage: [recent_projects limit="4" title="Recent Projects" orderby="date" order="DESC" filters="" carousel="yes"] [/recent_projects]
*/
function pp_recent_projects($atts, $content ) {
    extract(shortcode_atts(array(
        'limit'=>'4',
        'title' => 'Recent Work',
        'orderby'=> 'date',
        'order'=> 'DESC',
        'filters' => '',
        'width' => 'sixteen',
        'place' => 'center',
        'exclude_posts' => '',
        'include_posts' => '',
        ), $atts));
	
	$randID = uniqid();
    $output = '';


    if(empty($width)) { $width = "sixteen"; } //set width to 16 even if empty value

    switch ( $place ) {
        case "last" :
        $p = "omega";
        break;

        case "center" :
        $p = "alpha omega";
        break;

        case "none" :
        $p = " ";
        break;

        case "first" :
        $p = "alpha";
        break;
        default :
        $p = ' ';
    }

    $args = array(
      'post_type' => 'portfolio',
      'posts_per_page' => $limit,
      'orderby' => $orderby,
      'order' => $order,
  );

  if(!empty($exclude_posts)) {
      $exl = explode(",", $exclude_posts);
      $args['post__not_in'] = $exl;
  }
	
  if(!empty($include_posts)) {
      $inc = explode(",", $include_posts);
      $args['post__in'] = $inc;
  }

  if(!empty($filters)) {
      $filters = explode(",", $filters);
      $args['tax_query'] = array(
          array(
              'taxonomy' => 'filters',
              'field' => 'slug',
              'terms' => $filters
              )
          );
  }


  $output .= '
<div class="'.$width.' columns '.$p.'">
  <div>
    <h3 class="headline ov">'.$title.'</h3>
    <span class="line" style="margin-bottom: 0"></span>
  </div>
  <div class="clearfix"></div>

  <div class="showbiz-navigation">
    <div class="sb-navigation-left slider-experiment__left" data-slick-left data-control-id="'.$randID.'" ><i class="icon-angle-left"></i></div>
    <div class="sb-navigation-right slider-experiment__right" data-slick-right data-control-id="'.$randID.'"><i class="icon-angle-right"></i></div>
  </div>
  <div class="clearfix"></div>
</div>

<div class="slider-slick slider--default-gap slider--auto-height slick-slider--portfolio" data-slick-slider data-controls-id="'.$randID.'">
';


  $wp_query = new WP_Query( $args );
    if ( $wp_query->have_posts() ){
      while($wp_query->have_posts()){
        $wp_query->the_post();

        $output .= '<div data-slick-slide>
        <div class="portfolio-item media">
        <figure>
        <div class="mediaholder">';
        $thumb = get_post_thumbnail_id();
        $img_url = wp_get_attachment_url($thumb);
        $lightbox = get_post_meta($wp_query->post->ID, 'pp_pf_lightbox', true);
        if($lightbox == 'lightbox') {
            $fullsize = wp_get_attachment_image_src($thumb, 'full');
            $output .= '<a href="'.$fullsize[0].'" class="'.$mfpclass.'" title="'.get_the_title().'">';
            $output .= get_the_post_thumbnail($wp_query->post->ID,'portfolio-4col');
            $output .= '
            <div class="hovercover">
            <div class="hovericon"><i class="hoverzoom"></i></div>
            </div>
            </a>';
        } else {
            $output .= '<a href="'.get_permalink().'"  title="'.get_the_title().'">';
            $output .= get_the_post_thumbnail($wp_query->post->ID,'portfolio-4col');
            $output .= '
            <div class="hovercover">
            <div class="hovericon"><i class="hoverlink"></i></div>
            </div>
            </a>';
        }
        $output .= '</div>
        <a href="'.get_permalink().'">
        <figcaption class="item-description">
        <p class="item-h5">'.get_the_title().'</p>';
        $terms = get_the_terms( $wp_query->post->ID, 'filters' );
        if ( $terms && ! is_wp_error( $terms ) ) : $output .= '<span>';
        $filters = array();
        $i = 0;
        foreach ( $terms as $term ) {
            $filters[] = $term->name;
            if ($i++ > 0) break;
        }
        $outputfilters = join( ", ", $filters );
        $output .= $outputfilters;
        $output .= '</span>';
        endif;
        $output .= '</figcaption>
        </a>
        </figure>
        </div>
        </div>';
      }
    }



    $output .= '</div>';
    return $output;
}

add_shortcode('recent_projects', 'pp_recent_projects');

// -------------------------- Slick slider for project page END ---------------------


/**
* Figure shortcode
* Usage: [figure caption=""] [/figure]// 
*/
if (!function_exists('pp_figure')) {
    function pp_figure($atts, $content = null) {
        extract(shortcode_atts(array(
            'caption' => '', 
		), $atts));
		if ($caption == "" ) {
			$caption_container = '';
			$caption_class = ' figure-no-caption';
		} else {
			$caption_container = '<figcaption>'.$caption.'</figcaption>';
			$caption_class = '';
		}
		$return = '<figure class="project--diagram'.$caption_class.'">'.do_shortcode($content).$caption_container.'</figure>';
        return $return;
    }
    add_shortcode('figure', 'pp_figure');
}


/**
* Modern list style shortcode
* Usage: [modernlist list="1" level="1" columns="1"] [/modern-list] // check, arrow, checkbg, arrowbg
*/

function pp_modernlist($atts, $content = null) {
    extract(shortcode_atts(array(
        "list" => '1',
		"level" => '1',
		"columns" => '1',		
    ), $atts));
	$columns_class = $columns === '1' ? '' : 'exp-modern-list-'.$columns.'-columns';
    return '<div class="exp-modern-list exp-modern-list--'.$list.' exp-modern-list-level--'.$level.' '.$columns_class.'">'.do_shortcode($content).'</div>';
}

add_shortcode('modernlist', 'pp_modernlist');



/**
* Modern button shortcode
* Usage: [modern_button]
*/
if (!function_exists('pp_modern_button')) {
function pp_modern_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "url" => '#',
		"target" => '',
		"margin_top" => '',
		"margin_bottom" => '',
        ), $atts));
		if ( !empty($target) ) {
			$target = 'target="_blank" rel="noopener" ';
		} else {
			$target = '';
		}
		
		if (!empty($margin_top) || !empty($margin_bottom) ) {

			$marginTop = $margin_top !== '' ? 'margin-top:' . $margin_top . 'px;' : '';
			$marginBottom = $margin_bottom !== '' ? 'margin-bottom:' . $margin_bottom . 'px;' : '';
			$styles = $marginTop . $marginBottom;
	
			$style='style="'.$styles.'"';
		} else {
			$style='';
		}
    $output = '<div class="modern-button" '.$style.'><a class="modern-button__content button medium color" '.$target.'  href="'.$url.'">'.$content.'</a></div>';

    return $output;
}
add_shortcode('modern_button', 'pp_modern_button');
}


/**
* Modern Blockquote shortcode
* Usage: [modern_blockquote] [/modern_blockquote] 
*/
function pp_modern_blockquote( $atts, $content ) {
  extract(shortcode_atts(array(
    'margin_top' => '',
    'margin_bottom' => '',
    ), $atts));
	
	if (!empty($margin_top) || !empty($margin_bottom) ) {

			$marginTop = $margin_top !== '' ? 'margin-top:' . $margin_top . 'px;' : '';
			$marginBottom = $margin_bottom !== '' ? 'margin-bottom:' . $margin_bottom . 'px;' : '';
			$styles = $marginTop . $marginBottom;
	
			$style='style="'.$styles.'"';
		} else {
			$style='';
		}

  return '<blockquote class="modern-blockquote"  '.$style.'>'.do_shortcode( $content ).'</blockquote>';
}
add_shortcode( 'modern_blockquote', 'pp_modern_blockquote' );


?>