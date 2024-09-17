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
	'element' => '',
	'without_top_margin' => 'false',
    'type' => 'default',
    ), $atts));
	if ($toc == 'true') {$dash = '';} else {$dash = '-';}
$portfolio_page = [
	'/portfolio/monitoring-and-management-system-of-state-procurement-for-tender-contractual-department/',
	'/portfolio/tariff-plans-and-services-management-system-for-mobile-operator/',
	'/portfolio/transferring-commercial-integration-mule-applications-from-on-premise-to-the-cloud-environment/',
	'/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/portfolio/the-social-network-for-creative-people/',
	'/portfolio/preparing-microservice-infrastructure-for-transition-to-k8s/',
	'/portfolio/role-playing-game-mechanics/',
	'/portfolio/backend-complex-travel-system/',
	'/portfolio/creation-of-commercial-mule-connector-for-ibm-i-as-400/',
	'/portfolio/online-professional-translation-service/',
	'/portfolio/ci-cd-for-engineering-application/',
	'/portfolio/web-interface-of-the-system-for-geographical-location-tracking-of-objects-on-google-maps/',
	'/portfolio/integration-of-distribution-management-system-with-regional-distributors-erp-systems/',
	'/portfolio/frontend-development-of-web-application-to-maintain-a-healthy-lifestyle/',
	'/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/portfolio/automated-testing-applications-for-managing-products-services-mobile-operator/',
	'/portfolio/mule-collecting-processing-calls-information-transferring-webbing-service/',
	'/portfolio/consulting-on-complex-demanded-project-in-e-commerce/',
	'/portfolio/electronic-product-inventory-sync-erp/',
	'/portfolio/consulting-stabilization-software-development-process-improve-quality/',
	'/portfolio/big-telecom-company-crm/',
	'/portfolio/enterprise-resource-management-system/',
	'/portfolio/accounts-aggregator-for-web-application/',
	'/portfolio/application-for-estimating-and-managing-the-implementation-cost-of-large-scale-industrial-projects/',
	'/portfolio/automation-testing-webapp-options-of-bank-deposits/',
	'/portfolio/mouse-emulator-application-for-android-devices/',
	'/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization',
	'/portfolio/telecommunication-data-visualization-and-management-center/',
	'/portfolio/management-center-of-telecom-data/',
	'/portfolio/development-telecommunication-system-for-audio-data-processing/',
	'/portfolio/inventory-system-of-logical-resources-for-mobile-operator/',
	'/portfolio/development-service-for-interaction-with-energy-accounting-platforms/',
	'/portfolio/jde-integration-with-magento-ecommerce-platform/',
	'/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization/',
	'/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/portfolio/application-for-automating-data-collection-from-business-portals/',
	'/portfolio/auto-testing-car-service-web-application/',
	'/portfolio/completion-of-the-electronic-laboratory-passport-web-application/',
	'/portfolio/development-front-end-platform-for-supporting-iot-applications/',
	'/portfolio/development-integration-between-ecommerce-platform-magento-epicor-prophet-21/',
	'/portfolio/development-low-code-trend-at-jazzteam/',
	'/portfolio/the-system-of-accounting-and-control-of-the-costs-of-telecommunications-services/',
	'/portfolio/business-processes-automation-in-environmental-activities/',
	'/portfolio/ticket-online-sale-system/',
	'/portfolio/contact-management-system/',
	'/portfolio/setting-up-processes-and-comprehensive-testing-of-a-product-designed-for-complex-calculations-in-the-scientific-field/',
	'/portfolio/voip-integration/',
	'/portfolio/integration-system-for-trading-company/',
	'/portfolio/product-quality-assurance-service/',
	'/portfolio/implementing-ci-cd-on-high-resistance-project/',
	'/portfolio/consulting-project-developing-integrated-software-solution-security-monitoring-industrial-civil-facilities/',
	'/portfolio/the-project-resources-and-costs-monitoring-system/',
	'/portfolio/the-system-for-cryptocurrency-rate-forecasting-based-on-text-analysis/',
	'/portfolio/web-antivirus-api/',
	'/portfolio/system-of-cloud-storages-unification/',
	'/portfolio/product-development-for-creating-advertising-banners/',
	'/portfolio/solution-for-automatic-information-collection/',
	'/portfolio/system-automation-processes-control-accounting-activities-supervisory-service/',
	'/portfolio/telecom-oriented-information-migration-project-on-clients-payments-from-one-system-to-another-with-complex-transformation-of-data-structure/',
	'/portfolio/testing-system-for-automated-accounting-of-information-about-industrial-infrastructure-facility/',
	'/portfolio/development-of-an-application-for-generating-pay-sheets/',
	'/portfolio/development-of-educational-online-platform-module/',
	'/portfolio/development-web-application-for-conducting-scientific-experiments/',
	'/portfolio/gaming-computer-club-management-system/',
	'/portfolio/implementation-process-manual-testing-online-platform-localization-github-projects/',
	'/portfolio/keep-them-all/',
	'/portfolio/mule-software-for-integrating-internal-systems/',
	'/portfolio/redesign-of-the-portal-for-cost-accounting-and-control-system-of-telecommunications-services/',
	'/portfolio/solution-for-data-migration-synchronization/',
	'/portfolio/system-for-calculating-pile-foundations-in-extreme-conditions/',
	'/portfolio/testing-company-procurement-management-system/',
	'/portfolio/testing-unified-international-electronic-system-recording-movable-property/',
	'/portfolio/time-reporting-automation/',
	'/portfolio/web-application-for-education/',
	'/portfolio/website-of-the-translation-service/',
	'/portfolio/development-erp-product-customer-consulting-and-system-design-transformations/',
	'/portfolio/creation-of-commercial-mule-connector-to-system-of-control-of-master-data/',
	'/portfolio/account-register-in-the-cloud-services/',
	'/portfolio/platform-for-building-products-based-on-service-oriented-architecture/',
	'/portfolio/development-service-for-organizing-targeted-ads/',
	'/portfolio/document-signature-applet/',
	'/portfolio/automated-testing-web-application-related-to-geofence-marketing/',
	'/portfolio/big-data-reporting-system/',
	'/portfolio/development-of-saas-solution-for-xml2selenium-framework/',
	'/portfolio/integration-layer-interact-with-existing-solutions-ibm-as400-system/',
	'/portfolio/automated-competitors-price-monitoring-and-self-optimizing-system/',
	'/portfolio/getaddressbyip-service-determining-device-geolocation-by-ip/',
	'/portfolio/migrate-to-another-platform-for-stable-web-application/',
	'/portfolio/medication-search-service/',
	'/portfolio/integration-proxy/',
	'/portfolio/billing-system-for-the-multimedia-channels-provider/',
	'/TestDesignrtfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/portfolio/complex-android-ui-customization/',
	'/portfolio/xml2selenium-the-jazzteam-companys-product/',
	'/portfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/portfolio/modernization-corporate-portal-jive-based-for-personal-growth/',
	'/portfolio/mobile-application-network-beauty-salons/',
	'/portfolio/frontend-development-new-version-web-portal-for-interior-design/',
	'/portfolio/development-of-a-cross-platform-voip-client/',
	'/portfolio/management-center-of-telecommunication-information/',
	'/portfolio/databases-updates-management-application/',
	'/portfolio/development-of-cloud-iot-platform-components-for-energy-resources-accounting-and-management-of-energy-meters/',
	'/portfolio/web-application-for-buying-and-selling-real-estate/',
	'/portfolio/integration-system-application-localization-customer-service-platform/',
	'/portfolio/social-media-brand-analyzer/',
	'/portfolio/marketing-platform-of-data-analysis-site-stackexchange-com/',
	'/portfolio/web-application-for-viewing-personal-files-of-employees-and-students/',
	'/portfolio/management-transformation-long-history-project/',
	'/portfolio/automated-ui-tests-for-travel-industry/',
	'/portfolio/corporate-mobile-application-for-traffic-accounting-and-management-of-communication-costs/',
	'/portfolio/learning-and-testing-java-applet/',
	'/front-end-development-of-a-logic-resource-recording-system/',
	'/integration-of-the-distribution-management-system-with-erp-systems-of-regional-distributors/',
	'/development-of-a-service-for-communication-with-the-iot-platform/',
	'/extension-of-functionality-and-re-engineering-of-an-application-for-estimating-and-managing-the-implementation-cost-of-projects/',
	'/ensuring-rapid-extensibility-of-the-ui-part-of-the-system/',
	'/custom-system-for-specific-business-needs/',
	'/development-of-a-web-application-for-digital-signature/',
	'/system-with-parallel-training-of-a-specialist/',
	'/product-stabilization-through-data-driven-testing/',
	'/consulting-on-a-science-intensive-product-development-and-building-a-qa-process/',
	'/test-automation-optimization-accompanied-by-ci-cd-integration-for-the-product-with-sophisticated-build-and-test-approaches/',
	'/full-fledged-test-automation-of-a-product-with-complex-architecture/',
	'/manual-testing-of-a-complex-internet-of-things-iot-product/',
	'/building-the-process-of-manual-testing-in-a-scientific-startup/',
	'/services/software-development/',
	'/audit-of-the-manual-testing-process/',
	'/creation-of-a-large-system-of-immersion-in-the-most-complex-project-by-a-manual-tester/',
	'/comprehensive-consulting-on-the-automation-of-development-and-testing-processes/',
	'/implementing-a-testing-process-for-a-large-erp-system/',
	'/building-it-processes/',
	'/development-of-relations-with-the-customer/',
	'/best-development-and-testing-practices/',
	'/business-component-of-the-customers-application/',
	'/automatic-deployment-of-the-system-in-the-field-of-telecommunications/',
	'/implementing-continuous-integration-to-test-automation/',
	'/reducing-costs-through-the-implementation-of-ci-cd/',
	'/full-cycle-of-ci-cd-implementation/',
	'/frontend-development-2/',
	'/test-automation-2/',
	'/manual-testing-2/',
	'/it-consulting-2-2/',
	'/project-management-2/',
	'/roadmap-for-working-with-jazzteam-2/',
	'/about-jazzteam-2/',
	'/industries-2/',
	'/agile-java-development-2/',
	'/portfolio/remote-rd-team-for-optimizing-tariff-plans-system-for-mobile-operators/',
	'/top-5-jazzteam-solutions-2/',
	'/why-we-focus-on-java-2/',
	'/why-is-it-worth-joining-jazzteam-7-facts-for-engineers-2/',
	'/how-we-work-2/',
	'/jazzteam-products-2/',
	'/ci-cd-implementation-and-modernization-devops-services-2/',
	'/why-choose-jazzteam-2/',
	'/about-company/',
	'/about-company/why-choose-jazzteam/',
	'/services/how-we-work/',
	'/about-company/for-it-specialists/',
	'/services/frontend-development/',
	'/services/test-automation/',
	'/services/manual-testing/',
	'/services/it-consulting/',
	'/services/project-management/',
	'/services/roadmap/',
	'/products/',
	'/services/top-5-jazzteam-solutions/',
	'/expertise/knowledge-business-area/',
	'/services/',
	'/about-company/agile-java-development/',
	'/expertise/what-we-use-to-develop/',
		'/about-company/jobs-vacancy/senior-java-developer/',
	'/about-company/jobs-vacancy/middle-java-developer/',
	'/business-articles/task-estimation-and-decomposition/',
	'/custom-software-development/',
	'/business-articles/learned-helplessness/',
	'/25633-2/',
	'/business-articles/technical-debt-and-its-consequences-for-the-company/',
	'/technical-articles/experience-of-voip-integration-into-salesforce/',
	'/services/ci-cd/'
	

];
	$dash = $toc === 'true' ? '' : '-';
// 	if ( in_array( preg_replace('/\\?.*/', '', $_SERVER['REQUEST_URI']), $portfolio_page ) ){	
	if ( true ){
		$custom_element = $element ? $element : 'h' . $level;
		$without_top_margin_class = $without_top_margin === 'true' ? ' project-headline--without-top-margin' : '';
        $type_class = $type === 'promo' ? ' project-headline-type--promo' : '';
		
		if ( $level == '2' ) { 
			$ph = ''; 

		} else { 
			$ph = '-h'.$level; 
		}

  		return '<div class="project-headline'.$ph.$without_top_margin_class.$type_class.'"><' . $custom_element . ' class="head'.$dash.'line" data-toc-numbers="'.$numbers.'" data-alt="'.$alt.'">'.do_shortcode( $content ).'</' . $custom_element . '></div>';
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
	'/portfolio/monitoring-and-management-system-of-state-procurement-for-tender-contractual-department/',
	'/portfolio/tariff-plans-and-services-management-system-for-mobile-operator/',
	'/portfolio/transferring-commercial-integration-mule-applications-from-on-premise-to-the-cloud-environment/',
	'/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/portfolio/the-social-network-for-creative-people/',
	'/portfolio/preparing-microservice-infrastructure-for-transition-to-k8s/',
	'/portfolio/role-playing-game-mechanics/',
	'/portfolio/backend-complex-travel-system/',
	'/portfolio/creation-of-commercial-mule-connector-for-ibm-i-as-400/',
	'/portfolio/online-professional-translation-service/',
	'/portfolio/ci-cd-for-engineering-application/',
	'/portfolio/web-interface-of-the-system-for-geographical-location-tracking-of-objects-on-google-maps/',
	'/portfolio/integration-of-distribution-management-system-with-regional-distributors-erp-systems/',
	'/portfolio/frontend-development-of-web-application-to-maintain-a-healthy-lifestyle/',
	'/portfolio/the-system-of-accounting-analysis-and-decision-making-on-the-financial-condition-of-customers-and-subscribers/',
	'/portfolio/automated-testing-applications-for-managing-products-services-mobile-operator/',
	'/portfolio/mule-collecting-processing-calls-information-transferring-webbing-service/',
	'/portfolio/consulting-on-complex-demanded-project-in-e-commerce/',
	'/portfolio/electronic-product-inventory-sync-erp/',
	'/portfolio/consulting-stabilization-software-development-process-improve-quality/',
	'/portfolio/big-telecom-company-crm/',
	'/portfolio/enterprise-resource-management-system/',
	'/portfolio/accounts-aggregator-for-web-application/',
	'/portfolio/application-for-estimating-and-managing-the-implementation-cost-of-large-scale-industrial-projects/',
	'/portfolio/automation-testing-webapp-options-of-bank-deposits/',
	'/portfolio/mouse-emulator-application-for-android-devices/',
	'/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization',
	'/portfolio/telecommunication-data-visualization-and-management-center/',
	'/portfolio/management-center-of-telecom-data/',
	'/portfolio/development-telecommunication-system-for-audio-data-processing/',
	'/portfolio/inventory-system-of-logical-resources-for-mobile-operator/',
	'/portfolio/development-service-for-interaction-with-energy-accounting-platforms/',
	'/portfolio/jde-integration-with-magento-ecommerce-platform/',
	'/portfolio/frontend-custom-web-application-managing-business-processes-translation-localization/',
	'/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/portfolio/application-for-automating-data-collection-from-business-portals/',
	'/portfolio/auto-testing-car-service-web-application/',
	'/portfolio/completion-of-the-electronic-laboratory-passport-web-application/',
	'/portfolio/development-front-end-platform-for-supporting-iot-applications/',
	'/portfolio/development-integration-between-ecommerce-platform-magento-epicor-prophet-21/',
	'/portfolio/development-low-code-trend-at-jazzteam/',
	'/portfolio/the-system-of-accounting-and-control-of-the-costs-of-telecommunications-services/',
	'/portfolio/business-processes-automation-in-environmental-activities/',
	'/portfolio/ticket-online-sale-system/',
	'/portfolio/contact-management-system/',
	'/portfolio/setting-up-processes-and-comprehensive-testing-of-a-product-designed-for-complex-calculations-in-the-scientific-field/',
	'/portfolio/voip-integration/',
	'/portfolio/integration-system-for-trading-company/',
	'/portfolio/product-quality-assurance-service/',
	'/portfolio/implementing-ci-cd-on-high-resistance-project/',
	'/portfolio/consulting-project-developing-integrated-software-solution-security-monitoring-industrial-civil-facilities/',
	'/portfolio/the-project-resources-and-costs-monitoring-system/',
	'/portfolio/the-system-for-cryptocurrency-rate-forecasting-based-on-text-analysis/',
	'/portfolio/web-antivirus-api/',
	'/portfolio/system-of-cloud-storages-unification/',
	'/portfolio/product-development-for-creating-advertising-banners/',
	'/portfolio/solution-for-automatic-information-collection/',
	'/portfolio/system-automation-processes-control-accounting-activities-supervisory-service/',
	'/portfolio/telecom-oriented-information-migration-project-on-clients-payments-from-one-system-to-another-with-complex-transformation-of-data-structure/',
	'/portfolio/testing-system-for-automated-accounting-of-information-about-industrial-infrastructure-facility/',
	'/portfolio/development-of-an-application-for-generating-pay-sheets/',
	'/portfolio/development-of-educational-online-platform-module/',
	'/portfolio/development-web-application-for-conducting-scientific-experiments/',
	'/portfolio/gaming-computer-club-management-system/',
	'/portfolio/implementation-process-manual-testing-online-platform-localization-github-projects/',
	'/portfolio/keep-them-all/',
	'/portfolio/mule-software-for-integrating-internal-systems/',
	'/portfolio/redesign-of-the-portal-for-cost-accounting-and-control-system-of-telecommunications-services/',
	'/portfolio/solution-for-data-migration-synchronization/',
	'/portfolio/system-for-calculating-pile-foundations-in-extreme-conditions/',
	'/portfolio/testing-company-procurement-management-system/',
	'/portfolio/testing-unified-international-electronic-system-recording-movable-property/',
	'/portfolio/time-reporting-automation/',
	'/portfolio/web-application-for-education/',
	'/portfolio/website-of-the-translation-service/',
	'/portfolio/development-erp-product-customer-consulting-and-system-design-transformations/',
	'/portfolio/creation-of-commercial-mule-connector-to-system-of-control-of-master-data/',
	'/portfolio/account-register-in-the-cloud-services/',
	'/portfolio/platform-for-building-products-based-on-service-oriented-architecture/',
	'/portfolio/development-service-for-organizing-targeted-ads/',
	'/portfolio/document-signature-applet/',
	'/portfolio/automated-testing-web-application-related-to-geofence-marketing/',
	'/portfolio/big-data-reporting-system/',	
	'/portfolio/development-of-saas-solution-for-xml2selenium-framework/',
	'/portfolio/integration-layer-interact-with-existing-solutions-ibm-as400-system/',
	'/portfolio/automated-competitors-price-monitoring-and-self-optimizing-system/',
	'/portfolio/getaddressbyip-service-determining-device-geolocation-by-ip/',
	'/portfolio/migrate-to-another-platform-for-stable-web-application/',
	'/portfolio/medication-search-service/',
	'/portfolio/integration-proxy/',
	'/portfolio/billing-system-for-the-multimedia-channels-provider/',
	'/TestDesignrtfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/portfolio/complex-android-ui-customization/',
	'/portfolio/xml2selenium-the-jazzteam-companys-product/',
	'/portfolio/decentralized-platform-based-on-ethereum-smart-contract/',
	'/portfolio/implementation-ci-cd-into-existing-software-project/',
	'/portfolio/modernization-corporate-portal-jive-based-for-personal-growth/',
	'/portfolio/mobile-application-network-beauty-salons/',
	'/portfolio/frontend-development-new-version-web-portal-for-interior-design/',
	'/portfolio/development-of-a-cross-platform-voip-client/',
	'/portfolio/management-center-of-telecommunication-information/',
	'/portfolio/databases-updates-management-application/',
	'/portfolio/development-of-cloud-iot-platform-components-for-energy-resources-accounting-and-management-of-energy-meters/',
	'/portfolio/web-application-for-buying-and-selling-real-estate/',
	'/portfolio/integration-system-application-localization-customer-service-platform/',
	'/portfolio/social-media-brand-analyzer/',
	'/portfolio/marketing-platform-of-data-analysis-site-stackexchange-com/',
	'/portfolio/web-application-for-viewing-personal-files-of-employees-and-students/',
	'/portfolio/management-transformation-long-history-project/',
	'/portfolio/automated-ui-tests-for-travel-industry/',
	'/portfolio/corporate-mobile-application-for-traffic-accounting-and-management-of-communication-costs/',
	'/portfolio/learning-and-testing-java-applet/',
	'/front-end-development-of-a-logic-resource-recording-system/',
	'/integration-of-the-distribution-management-system-with-erp-systems-of-regional-distributors/',
	'/development-of-a-service-for-communication-with-the-iot-platform/',
	'/extension-of-functionality-and-re-engineering-of-an-application-for-estimating-and-managing-the-implementation-cost-of-projects/',
	'/ensuring-rapid-extensibility-of-the-ui-part-of-the-system/',
	'/custom-system-for-specific-business-needs/',
	'/development-of-a-web-application-for-digital-signature/',
	'/system-with-parallel-training-of-a-specialist/',
	'/product-stabilization-through-data-driven-testing/',
	'/consulting-on-a-science-intensive-product-development-and-building-a-qa-process/',
	'/test-automation-optimization-accompanied-by-ci-cd-integration-for-the-product-with-sophisticated-build-and-test-approaches/',
	'/full-fledged-test-automation-of-a-product-with-complex-architecture/',
	'/manual-testing-of-a-complex-internet-of-things-iot-product/',
	'/building-the-process-of-manual-testing-in-a-scientific-startup/',
	'/services/software-development/',
	'/audit-of-the-manual-testing-process/',
	'/creation-of-a-large-system-of-immersion-in-the-most-complex-project-by-a-manual-tester/',
	'/comprehensive-consulting-on-the-automation-of-development-and-testing-processes/',
	'/implementing-a-testing-process-for-a-large-erp-system/',
	'/building-it-processes/',
	'/development-of-relations-with-the-customer/',
	'/best-development-and-testing-practices/',
	'/business-component-of-the-customers-application/',
	'/automatic-deployment-of-the-system-in-the-field-of-telecommunications/',
	'/implementing-continuous-integration-to-test-automation/',
	'/reducing-costs-through-the-implementation-of-ci-cd/',
	'/full-cycle-of-ci-cd-implementation/',
	'/frontend-development-2/',
	'/test-automation-2/',
	'/manual-testing-2/',
	'/it-consulting-2-2/',
	'/project-management-2/',
	'/roadmap-for-working-with-jazzteam-2/',
	'/portfolio/remote-rd-team-for-optimizing-tariff-plans-system-for-mobile-operators/',
	'/top-5-jazzteam-solutions-2/',
	'/why-we-focus-on-java-2/',
	'/why-is-it-worth-joining-jazzteam-7-facts-for-engineers-2/',
	'/how-we-work-2/',
	'/ci-cd-implementation-and-modernization-devops-services-2/',
	'/why-choose-jazzteam-2/',
	'/about-company/',
		'/about-company/why-choose-jazzteam/',
	'/services/how-we-work/',
	'/about-company/for-it-specialists/',
	'/services/frontend-development/',
	'/services/test-automation/',
	'/services/manual-testing/',
	'/services/it-consulting/',
	'/services/project-management/',
	'/services/roadmap/',
	'/products/',
	'/services/top-5-jazzteam-solutions/',
	'/expertise/knowledge-business-area/',
	'/services/',
	'/about-company/agile-java-development/',
	'/expertise/why-java/',
	'/expertise/what-we-use-to-develop/',
	'/about-company/history/',
	'/about-company/jobs-vacancy/senior-java-developer/',
	'/about-company/jobs-vacancy/middle-java-developer/',
	'/business-articles/task-estimation-and-decomposition/',
	'/custom-software-development/',
	'/business-articles/learned-helplessness/',
	'25633-2',
	'/business-articles/technical-debt-and-its-consequences-for-the-company/',
	'/technical-articles/experience-of-voip-integration-into-salesforce/',
	'/services/ci-cd/'
	



];

	if ($toc == 'true') {$dash = '';} else {$dash = '-';}

//     if ( in_array( preg_replace('/\\?.*/', '', $_SERVER['REQUEST_URI']), $portfolio_page ) ){
	if ( true ){
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
		  '/?page_id=25633'
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

    // $forms - массив, содержащий три формы слова для склонения. единственное число, множественное число, а третий - множественное число для остальных чисел
    // $n -  число, для которого нужно склонить слово
function pluralize_text($n, $forms) {
   return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
}

/**
* Recent projects shortcode
* Usage: [nd_recent_projects limit="4" title="Recent Projects" orderby="date" order="DESC" filters="" carousel="yes"] [/recent_projects]
*/

function pp_nd_recent_projects($atts, $content ) {
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

   ?>
		<section class="section recent-project">
            <div class="heading-wrapper">
              <h2 class="headline-second"><?php echo $title; ?></h2>
              <div class="slider-double-controls">
                <button class="btn slider-double-controls__control sdc---prev">
                  <svg height="16" viewBox="0 0 11 16" fill="none">
                    <path
                      d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
                      fill="currentColor"
                    />
                  </svg>
                </button>
                <button class="btn slider-double-controls__control slider-double-controls__control--next sdc---next">
                  <svg height="16" viewBox="0 0 11 16" fill="none">
                    <path
                      d="M9.10823 0.16032C9.00042 0.05344 8.87644 -2.04891e-07 8.73629 -2.04891e-07C8.59613 -2.04891e-07 8.47215 0.05344 8.36434 0.16032L0.828462 7.63126C0.720653 7.73814 0.666748 7.86106 0.666748 8C0.666748 8.13894 0.720653 8.26186 0.828462 8.36874L8.36434 15.8397C8.47215 15.9466 8.59613 16 8.73629 16C8.87644 16 9.00042 15.9466 9.10823 15.8397L9.9168 15.0381C10.0246 14.9312 10.0785 14.8083 10.0785 14.6693C10.0785 14.5304 10.0246 14.4075 9.9168 14.3006L3.56143 8L9.9168 1.6994C10.0246 1.59252 10.0785 1.46961 10.0785 1.33066C10.0785 1.19172 10.0246 1.0688 9.9168 0.961924L9.10823 0.16032Z"
                      fill="currentColor"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <div class="recent-project__slider">
              <div
                class="recent-project__slider-container"
                data-slider-container
                data-slider-setup='{"slidesPerView":1,"spaceBetween":20,"navigation":{"nextEl":".recent-project .sdc---next","prevEl":".recent-project .sdc---prev"},"breakpoints":{"565":{"slidesPerView":2},"768":{"slidesPerView":3},"1024":{"slidesPerView":4}}}'
              >
                <div class="recent-project__slider-wrapper swiper-wrapper swiper-wrapper--auto-height">

                        <?php
                        $wp_query = new WP_Query( $args );
                        if ( $wp_query->have_posts() ):
                            while( $wp_query->have_posts() ) : $wp_query->the_post();
						$post_id = get_the_ID();
		
						$additional_description = get_post_meta($post_id, 'additional_description', 1);
						$killing_features = get_post_meta($post_id, 'killing_features', 1);
						$customer = get_post_meta($post_id, 'customer', 1);
						$default_weight = is_user_logged_in() ? get_post_meta( $post_id, 'weights', 'true' ) : '';
	
	            		$team_size = array_sum(get_post_meta( $post_id, 'quantity', 'true' ));
            			$team_size_prettified = $team_size . ' ' . pluralize_text($team_size, ['member', 'members', 'members']);
            
            			$duration = get_duration($post_id);
           				 $duration_prettified = str_replace(array('years', 'months', 'days', 'hours', 'minutes', 'seconds'), array('yrs', 'mo', 'd', 'h', 'm', 's'), $duration);
		
					?>
					
					<?php  $thumb = get_post_thumbnail_id();
                           $img_url = wp_get_attachment_url($thumb);
					?>
					  <div class="recent-project__slider-slide swiper-slide">
						<a href="<?php echo get_permalink();?>" class="recent-project__slider-slide-content">
							<div class="recent-project__slider-slide-img-wrapper">
							  <div class="recent-project__slider-slide-img-bg"></div>
							  	<img
									class="recent-project__slider-slide-img"
									src="<?php echo $img_url; ?>"
									alt="<?php the_title(); ?>"
									height="204"
									width="285"									
							  	/></div>
	
						  <div class="recent-project__slider-slide-text-wrapper">

<div class="recent-project__slider-info">
								<div class="recent-project__slider-info-splitted">
									<div class="recent-project__slider-info-column">
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Team size"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=$team_size_prettified;?>"></span>
										</div>
										<?php if ( is_user_logged_in() ) { ?>
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Completion year"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=get_completion_year($post_id);?>"></span>
										</div>
										<?php } ?>
									</div>
									<div class="recent-project__slider-divider"></div>
									<div class="recent-project__slider-info-column">
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Duration"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=$duration_prettified;?>"></span>
										</div>
										<?php if ( is_user_logged_in() ) { ?>
										<div class="recent-project__slider-info-unit">
											<span class="recent-project__slider-info-unit-title" data-title="Default (weight)"></span>
											<span class="recent-project__slider-info-unit-value" data-value="<?=get_post_meta( $post_id, 'weights', 'true' );?>"></span>
										</div>
										<?php } ?>
									</div>

								</div>

                <?php if ( get_userlogin() == 'CEO' && ($customer || $killing_features || $additional_description) ) { ?>
										<div class="recent-project__slider-admin-info">
										<?php if ( $customer != ''){ ?>
											<div class="recent-project__slider-info-unit">
												<span class="recent-project__slider-info-unit-title" data-title="Customer"></span>
												<span class="recent-project__slider-info-unit-value" data-value="<?=$customer;?>"></span>
											</div>
										<?php } ?>
										<?php if ( $killing_features != ''){ ?>
											<div class="recent-project__slider-info-unit">
												<span class="recent-project__slider-info-unit-title" data-title="Killing Features"></span>
												<span class="recent-project__slider-info-unit-value" data-value="<?=$killing_features;?>"></span>
											</div>
										<?php } ?>
											<?php if ( $additional_description != ''){ ?>
												<div class="recent-project__slider-info-unit">
													<span class="recent-project__slider-info-unit-title" data-title="Additional Desc"></span>
													<span class="recent-project__slider-info-unit-value" data-value="<?=$additional_description;?>"></span>
												</div>
											<?php } ?>
										</div>
									<?php } ?>

							</div> 
							  
							  
							<span class="headline-four headline-four--color-inherit recent-project__title">
							  <?php the_title(); ?>
							</span>
							  
							<div class="recent-project__slider-slide-text__tags">
								<?php
                                  $terms = get_the_terms( $wp_query->post->ID, 'filters' );
                                    $output = '';
                                      if ( $terms && ! is_wp_error( $terms ) ) : 
                                         $filters = array();
                                          $i = 0;
                                          foreach ( $terms as $term ) {
                                            $filters[] = $term->name;
                                              if ($i++ > 0) break;
                                              }
                                             $outputfilters = join( ", ", $filters );
                                           $output .= $outputfilters;                                       
                                      endif;
                                   echo $output;
                            ?></div>
						  </div>
						</a>
					  </div>
						
                        
                    <?php   endwhile;  // close the Loop
                        endif; ?>
  

		       </div>
            </div>
		</div>  
	</section>		
            <?php
	
//     return $output;
}

add_shortcode('nd_recent_projects', 'pp_nd_recent_projects');


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
		"size" => 'false',
		"size_bottom" => 'false',
        "weight" => '600',
    ), $atts));
	$columns_class = $columns === '1' ? '' : 'exp-modern-list-'.$columns.'-columns';
	if ( $size_bottom == 'true') {
		$size_bottom_class = ' exp-modern-list--size-bottom';
	} 
	if ( $size == 'big') {
		$big_class = ' exp-modern-list--size-big';
	} else {
		$big_class = '';
	}

    $weight_class= $weight !== '600' ? 'exp-modern-list--weight-'.$weight : '';

    return '<div class="exp-modern-list'.$big_class.$size_bottom_class.' exp-modern-list--'.$list.' exp-modern-list-level--'.$level.' '.$columns_class.' '.$weight_class.'">'.do_shortcode($content).'</div>';
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
		"scroll" => 'false',
        'strict_remove_transform' => 'false',
        ), $atts));
		if ( !empty($target) ) {
			$target = 'target="_blank" rel="noopener" ';
		} else {
			$target = '';
		}
		$scroll_class = '';
        $remove_transform_class = $strict_remove_transform == 'true' ? 'modern-button__content--strict-transform-none' : '';
		if ( $scroll == 'true' ){
			$scroll_class = ' lwptoc_itemWrap';
		}
		
		if (!empty($margin_top) || !empty($margin_bottom) ) {

			$marginTop = $margin_top !== '' ? 'margin-top:' . $margin_top . 'px;' : '';
			$marginBottom = $margin_bottom !== '' ? 'margin-bottom:' . $margin_bottom . 'px;' : '';
			$styles = $marginTop . $marginBottom;
	
			$style='style="'.$styles.'"';
		} else {
			$style='';
		}
    $output = '<div class="modern-button'.$scroll_class.'" '.$style.'><a class="headline-four modern-button__content btn btn--orange '.$remove_transform_class.'" '.$target.'  href="'.$url.'">'.$content.'</a></div>';

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
    'type' => '',
    ), $atts));
	$ru_type = ''; 
	if ($type != '') {$ru_type = ' modern-ry-quote'; }
	if (!empty($margin_top) || !empty($margin_bottom) ) {

			$marginTop = $margin_top !== '' ? 'margin-top:' . $margin_top . 'px;' : '';
			$marginBottom = $margin_bottom !== '' ? 'margin-bottom:' . $margin_bottom . 'px;' : '';
			$styles = $marginTop . $marginBottom;
	
			$style='style="'.$styles.'"';
		} else {
			$style='';
		}

  return '<blockquote class="modern-blockquote'.$ru_type.'"  '.$style.'>'.do_shortcode( $content ).'</blockquote>';
}
add_shortcode( 'modern_blockquote', 'pp_modern_blockquote' );


/**
* Grid
* Usage: [grid] [/grid] 
*/
function pp_grid( $atts, $content ) {
  extract(shortcode_atts(array(
    'margin_top' => '',
    'margin_bottom' => '',
	'columns' => '',
	'style' => '',
	'custom_class' => '',
    ), $atts));
	
  if ( $style != ''){ 
    $newstyle = 'style="'.$style.'"';
  }

  return '<div class="modern-grid modern-grid-columns-'.$columns.' '.$custom_class.'" '.$newstyle.'>'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'grid', 'pp_grid' );

/**
* Grid_item
* Usage: [grid_item] [/grid_item] 
*/
function pp_grid_item( $atts, $content ) {
  extract(shortcode_atts(array(
    'margin_top' => '',
    'margin_bottom' => '',
    ), $atts));
	


  return '<div class="modern-grid-item">'.do_shortcode( $content ).'</div>';
}
add_shortcode( 'grid_item', 'pp_grid_item' );



/**
* Preview img shortcode
* Usage: [preview_img] [/preview_img] 
*/
function pp_preview_img( $atts ) {
  extract(shortcode_atts(array(
    //'margintop' => 0,
    //'marginbottom' => 0,
    'align' => '',
	'url' => '',
	'width' => '',
	'height' => '',  
    ), $atts));
  if ( $url != '') {
	  $new_url = $url;
  } else {
	  global $post;
	  $id = $post->ID;
	  $new_url = get_the_post_thumbnail_url( $id, 'full' );
  }
  if ( $align != '') {
	$new_align = ' align'.$align;	  
  } else {
	  $new_align = '';
  }
	if ( $width != '' ||  $height != '' ){
		$style='style="max-width:initial;max-height:initial;"';
	} else {
	}
	if ( $width != '') {
		$new_width = 'width="'.$width.'"';
	} else {
		$new_width = '';
	}
	
	if ( $height != '') {
		$new_height = 'height="'.$height.'"';
	} else {
		$new_height = '';
	}
    
  return '<img src="'.$new_url.'" alt="thumbnail-image" class="postproject-preview-custom'.$new_align.'" '.$style.' '.$new_height.' '.$new_width.'>';
}
add_shortcode( 'preview_img', 'pp_preview_img' );




/**
* Caption button shortcode
* Usage: [caption_button][/caption_button]
*/
if (!function_exists('pp_caption_button')) {
    function pp_caption_button($atts, $content) {
       return '<p class="modern-caption-button"><strong>'.$content.'</strong></p>';
   }
   add_shortcode( 'caption_button', 'pp_caption_button' );
}


/**
* Modern business domains shortcode
* Usage: [modern_business_domains] [/modern_business_domains] // margin-down margin-both
*/
function pp_modern_business_domains($atts, $content){
	extract(shortcode_atts(array(
    "list" => '4',
    "columns" => '2',
    ), $atts));
	
	ob_start();
	
	$columns_class = $columns === '1' ? '' : 'exp-modern-list-'.$columns.'-columns';
	$terms = get_terms(['taxonomy' => 'filters']);
	$count_bd = count($terms);
	$k = 0;
	
	$terms_wrapped = array_map(function($term) {
		return '<li><a href="'.get_term_link( $term ).'" target="_blank">'.$term->name.'</a></li>';
	}, $terms);


	echo '<div class="exp-modern-list exp-modern-list--'.$list.' exp-modern-list-level--1 '.$columns_class.'"><ul>'.do_shortcode(implode("", $terms_wrapped)).'</ul></div>';
	return ob_get_clean();
}

add_shortcode('modern_business_domains','pp_modern_business_domains');


/**
* Tagline shortcode
* Usage: [tagline] [/tagline]
*/
if (!function_exists('pp_tagline')) {
    function pp_tagline($atts, $content) {
    extract(shortcode_atts(array(
          'description'=>'',
    ), $atts));
  
  if ( $description != '') {
  $new_desc = '<span class="tagline-description">'.$description.'</span>';
  }
      return '<div class="tagline"><strong class="tagline-content">'.$content.'</strong>'.$new_desc.'</div>';
   }
   add_shortcode( 'tagline', 'pp_tagline' );
  }

  /**
* Promo banner shortcode
* Usage: [promo_banner] [/promo_banner]
*/
  if (!function_exists('pp_promo_banner')) {
    function pp_promo_banner($atts, $content) {
    extract(shortcode_atts(array(
          'type'=>'1',
          'variant' => 'first',
          'modify_class' => '',
    ), $atts));
        
  if ( $type == '1') {
    return '<div class="promo-banner-container lwptoc_itemWrap promo-section-split"><a href="#Contact_Us" class="promo-banner promo-banner-first '.$modify_class.'"><span class="promo-banner-first__headline headline-third">Start your software development project faster with JazzTeam.</span><span class="btn btn--orange promo-banner-first__link"><span class="headline-four">Request project estimate</span></span></a></div>';
  }
    
  else if ( $type == '2') {
    return '<div class="promo-banner-container lwptoc_itemWrap promo-section-split"><a href="#Contact_Us" class="promo-banner promo-banner-second '.$modify_class.'"><div class="promo-banner-second__content-wrapper"><span class="promo-banner-second__headline headline-third">Are you facing organizational challenges with your product development team?</span><span class="text-second text-second--dark">Request a consulting service with our expert!</span></div><span class="btn btn--orange promo-banner-second__link"><span class="headline-four">Let\'s talk</span></span></a></div>';
  }
    
  else if ($type == '3'){
    return '<div class="promo-banner-container lwptoc_itemWrap"><a href="#Contact_Us" class="promo-banner promo-banner-third '.$modify_class.'">
      <div class="promo-banner-third__content-wrapper">
        <span class="promo-banner-third__headline headline-third">Do you need qualified software engineers for your project? Let us help!</span>
      <span class="btn btn--orange promo-banner-third__link">
        <span class="headline-four">Schedule a call</span>
      </span>
      <div class="promo-banner-third__icon"><img  class="promo-banner-third__icon-picture" src="https://jazzteam.org/wp-content/uploads/2024/02/promo-banner.svg" alt="java"/></div>
      </div>
      <div class="promo-banner-third__backdrop"></div>
    </a></div>';
  }
    
  else if ($type == '4' ){
  $variant_headline_map = [
      'first' => 'Are you considering a Mule ESB data migration project?',
      'second' => 'Core principles for cost-saving software development',
      'third' => '<span class="br-desktop">Introducing XML2Selenium,</span> JazzTeam’s own test automation product'];
      
  $variant_description_map = [
  'first' => 'Let us know your requirements!',
  'second' => 'We at JazzTeam employ a value-based approach that helps to significantly reduce product development costs, improve product quality, and simplify its further support.',
  'third' => '<span class="br-desktop">XML2Selenium is a Java-based platform built on Selenium WebDriver for creating and managing automated tests.</span> <span class="br-desktop">This framework allows QA engineers, even those with no programming experience, to create UI tests.</span>The tool is integrated with JUnit and runs on top of Selenium.'];
      
  $variant_class_map = [
      'first' => 'promo-banner-four--variant-first',
      'second' =>  'promo-banner-four--variant-second',
      'third' =>  'promo-banner-four--variant-third'];
      
  $variant_contact_map = [
      'first' => "Contact us",
      'second' => "Learn more",
      'third' => 'Try it out'];
      
  $variant_description_class_map =  [
      'first' =>'',
      'second'=>'promo-banner-four__variant-second',
      'third' => ''];
      
    $variant_url_map = [
      'first' => "#Contact_Us",
      'second' => "#Contact_Us",
      'third' => 'https://www.xml2selenium.com/'
  ];
      
  $variant_description_class_map =  [
      'first' =>'',
      'second'=>'promo-banner-four__variant-second'];
      
    return '<div class="promo-banner-container lwptoc_itemWrap"><a href="'.$variant_url_map[$variant].'" class="promo-banner promo-banner-four '.$modify_class.' '.$variant_class_map[$variant].'">
      <div class="promo-banner-four__container">
          <span class="headline-second">'.$variant_headline_map[$variant].'</span>
            <span class="text-first text-first--dark '.$variant_description_class_map[$variant].'">'.$variant_description_map[$variant].'</span>
            <span class="btn btn--orange btn--default-space promo-banner__contact-us"><span class="headline-four">'.$variant_contact_map[$variant].'</span></span>
      </div>
  </a></div>';
  }
    
  return '<div>INVALID BANNER</div>';
   }
  
   add_shortcode( 'promo_banner', 'pp_promo_banner' );
  }



/* ------ NEW TAG CLOUD --------*/
add_shortcode('tagscloud', 'get_new_tag_cloud_2');
function get_new_tag_cloud_2($atts){
	extract(shortcode_atts(array(
	    'max_count' => '50',
		'heading' => 'Tags',
		'count' => 'true',
    ), $atts));

	
	$get_queried_object = get_queried_object();
		
	if (isset( $get_queried_object->slug ) && $get_queried_object->taxonomy == 'category'){
		$cat_id = $get_queried_object->term_id;		
		[$tags_name, $tags_slug, $tags_count] = get_tags_in_category($cat_id);		
	} else {		
		[$tags_name, $tags_slug, $tags_count] = get_all_tags();
	}
	
    $output = outputs_tags_in_cat($tags_count, $tags_slug, $tags_name, $tag_id, $heading, $max_count, $count);
	
    return $output;	
}

function get_all_tags(){
	$tags = [			
		'orderby'      => 'count',
		'order'        => 'DESC',
		'hide_empty'   => true,
		'fields'       => 'all',
		'posts_per_page' => -1,
	];
		
	$tags = get_tags( $args );
		
	foreach($tags as $tag){
		$tags_name[$tag->term_id]  = $tag->name;
		$tags_slug[$tag->term_id]  = $tag->slug;
		$tags_count[$tag->term_id] = $tag->count;
	}
	arsort($tags_count);
	
	$return_array = [$tags_name, $tags_slug, $tags_count];
	
	return $return_array;
}

function get_tags_in_category($cat_id){
	
	$posts  = get_posts( ['category' => $cat_id, 'posts_per_page' => -1] );//

	$return_array = [];
    $tags_name  = [];
	$tags_slug  = [];
	$tags_count = [];	
    foreach($posts as $post) {
    	$post_tags = get_the_tags($post->ID);
        if( !empty($post_tags) ) {
            foreach($post_tags as $tag){
				$tags_name[$tag->term_id]  = $tag->name;
				$tags_slug[$tag->term_id]  = $tag->slug;
				$tags_count[$tag->term_id] = $tag->count;
			}
		}		
    }
	
    arsort($tags_count);
	
	$return_array = [$tags_name, $tags_slug, $tags_count];
	
	return $return_array;
	
}

function outputs_tags_in_cat($tags_count, $tags_slug, $tags_name, $tag_id, $head, $c, $count){
	$return_tag = [];
	$site_url = 'https://'.$_SERVER['HTTP_HOST'];
	$output = '<div id="tag_cloud-2" class="widget widget_tag_cloud"><span class="h3-headline">'.$head.'</span><span class="line"></span><div class="clearfix"></div>';
	$output .= '<div class="tagcloud">';
	$k = 0;	
	foreach ($tags_count as $tag_id => $tag_count) {
		
		if ($k < $c){
			$output_count = $count =='true' ? ' ('.$tags_count[$tag_id].')' : '';
			$output .= '<a href="'.$site_url.'/tag/'.$tags_slug[$tag_id].'/" class="tag-cloud-link tag-link-'.$tag_id.' tag-link-position-1" style="font-size: 14px;" data-wpel-link="internal">'.$tags_name[$tag_id].$output_count.'</a>';
		}
		$k++;
	}
	
	$output .= '</div></div>';
	
	return $output;
}

/* ------ END NEW TAG CLOUD --------*/  

?>