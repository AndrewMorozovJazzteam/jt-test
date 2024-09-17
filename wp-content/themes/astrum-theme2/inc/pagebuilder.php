<?php

if(class_exists('AQ_Page_Builder')) {


    //include the block files

    require_once(get_template_directory() . '/inc/blocks/pp-featured-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-portfolio-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-clients-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-tabs-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-headline-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-social-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-skills-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-alert-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-testimonials-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-notice-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-pricing-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-team-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-image-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-slider-block.php');
    require_once(get_template_directory() . '/inc/blocks/pp-blog-block.php');
	/* Add new page block */
	//require_once(get_template_directory() . '/inc/blocks/pp-page-block.php');
	require_once(get_template_directory() . '/inc/blocks/pp-link-block.php');
	require_once(get_template_directory() . '/inc/blocks/pp-cases-block.php');
	require_once(get_template_directory() . '/inc/blocks/pp-ftac-block.php');
	require_once(get_template_directory() . '/inc/blocks/pp-text-block-2.php');
	require_once(get_template_directory() . '/inc/blocks/pp-otas-block.php');
	require_once(get_template_directory() . '/inc/blocks/pp-new-cases-block.php');
	require_once(get_template_directory() . '/inc/blocks/pp-video-testimonials.php');
	require_once(get_template_directory() . '/inc/blocks/pp-testimonials-block-2.php');
	require_once(get_template_directory() . '/inc/blocks/pp-portfolio-slider-block.php'); // NEW for portfolio
	require_once(get_template_directory() . '/inc/blocks/pp-business-domains-block.php'); // NEW for business 
	require_once(get_template_directory() . '/inc/blocks/pp-technologies-block.php');  // NEW for technologies
   	require_once(get_template_directory() . '/inc/blocks/modern/modern-about-block/pp-modern-about-block.php');  // About us
	require_once(get_template_directory() . '/inc/blocks/modern/modern-testimonials-block/pp-modern-testimonials-block.php');  // Testimonials
	require_once(get_template_directory() . '/inc/blocks/modern/modern-cases-block/pp-modern-cases-block.php');  // Modern Cases
	require_once(get_template_directory() . '/inc/blocks/modern/modern-technologies-block/pp-modern-technologies-block.php');  // Modern Technologies
	
// 	New Design ---
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-services-block.php');  // New Design Services block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-clients-block.php');  // New Design Clients block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-business-domains-block.php');  // New Design Business Domains block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-about-block.php');  // New Design About block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-technical-competence-block.php');  // New Design Technical Competence block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-news.php');  // New Design News block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-recent-project.php');  // New Design Recent post block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-testimonials-block.php');  // New Design testimonials block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-how-work-block.php');  // New Design How We work block
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-services-need-block.php');  // New Design Services Need
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-data-exchange-block.php');  // New Design Data Exchange
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-solution-goals-block.php');  // New Design Solution Goals
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-data-solution-block.php');  // New Design Solution
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-domain-expertise-block.php');  // New Design Domain Expertise
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-questions-block.php');  // New Design Questions
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-test-automation-grid.php');  // New Design Test Automation Grid
	require_once(get_template_directory() . '/inc/blocks/new-design/pp-new-design-scheme-block.php');  // New Design Scheme 

	
// 	 ---
	
	

    if(is_woocommerce_activated()){
        require_once(get_template_directory() . '/inc/blocks/pp-woocommerce-block.php');
        aq_register_block('PP_Woocommerce_Block');
    }
    //register the blocks
    aq_unregister_block('AQ_Tabs_Block');
    aq_register_block('PP_Tabs_Block');
    aq_register_block('PP_Headline_Block');
    aq_register_block('PP_Notice_Block');
    aq_register_block('PP_Featured_Block');
    aq_register_block('PP_Portfolio_Block');
    aq_register_block('PP_Clients_Block');
    aq_unregister_block('AQ_Alert_Block');
    aq_register_block('PP_Alert_Block');
    aq_register_block('PP_Social_Block');
    aq_register_block('PP_Skills_Block');
    aq_register_block('PP_Testimonials_Block');

    aq_register_block('PP_Pricing_Block');
    aq_register_block('PP_Team_Block');
    aq_register_block('PP_Image_Block');
    aq_register_block('PP_Slider_Block');
    aq_register_block('PP_Blog_Block');
	
	/* */
	//aq_register_block('PP_Page_Block');
	aq_register_block('PP_Link_Block');
	aq_register_block('PP_Cases_Block');
	aq_register_block('PP_Ftac_Block');
	aq_register_block('PP_Text_Block2');
	aq_register_block('PP_Otas_Block');
	aq_register_block('PP_New_Cases_Block');
	aq_register_block('PP_Video_Testimonials_Block');
	aq_register_block('PP_Testimonials_Block2');
	aq_register_block('PP_Portfolio_Slider_Block'); // NEW
	aq_register_block('PP_Business_Domains_Block'); // NEW
	aq_register_block('PP_Technologies_Block'); // NEW
  	// MODERN --------
   	aq_register_block('PP_Modern_About_Block');  // About us
   	aq_register_block('PP_Modern_Testimonials_Block');  // Testimonials
	aq_register_block('PP_Modern_Cases_Block');
	aq_register_block('PP_Modern_Technologies_Block');
	//  -------------- 

	// NEW DESIGN --------
	aq_register_block('PP_New_Design_Services_Block');
	aq_register_block('PP_New_Design_Clients_Block');
	aq_register_block('PP_New_Design_About_Block');
	aq_register_block('PP_Technical_Competence_Block');
	
	aq_register_block('PP_New_Design_Business_Domains_Block');
	aq_register_block('PP_New_Design_News');
	aq_register_block('PP_New_Design_Recent_Project');
	aq_register_block('PP_New_Design_Testimonials_Block');
	aq_register_block('PP_How_Work_Block');
	aq_register_block('PP_Services_Need_Block');
	aq_register_block('PP_Data_Exchange_Block');
	aq_register_block('PP_Solutions_Goals_Block');
	aq_register_block('PP_Data_Solution_Block');
	aq_register_block('PP_Domain_Expertise_Block');
	aq_register_block('PP_Questions_Block');
	aq_register_block('PP_Test_Automation_Grid_Block');
	aq_register_block('PP_Scheme_Block');
	
	//  -------------- 



}
 ?>