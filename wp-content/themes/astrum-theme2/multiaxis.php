<?php 
/**
 * Plugin Name:       Multiaxis Portfolio new prototype
 * Plugin URI:        https://jazzteam.org/en/
 * Description:       Мультиосевое портфолио
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            JazzTeam
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       multiaxis-portfolio
 * Domain Path:       /languages
 **/



/*
 * =================================
 * ======off style and scripts======
 * ================================= 
 */


$administrator = get_role( 'administrator' );
add_role( 'ceo', 'CEO', $administrator->capabilities );
$role = get_role('ceo'); 
$role->add_cap('read');


 add_filter('the_title', 'change_specific_post_title', 10, 2);

 function change_specific_post_title($post_title, $post_id) {
	global $post;

  if ($post_id == $post -> ID && (is_shortcode_used_on_page('multiaxis_portfolio') || is_shortcode_used_on_page('multiaxis_portfolio_new'))) {
	$details = $_GET['desc'];
  	$shortLink = $_GET['id'];

  if ( $details || $shortLink ) {
	  global $wpdb;

	  if(!$details && $shortLink){
		$query = 'SELECT long_url FROM ' .$wpdb->prefix. 'multiaxis_short_links WHERE short_key = %s LIMIT 1';
		$long_url = $wpdb->get_var($wpdb->prepare($query, $shortLink));
		$pattern = '/[?&]desc=([^&]+)/';
		  
		if ($long_url !== null && preg_match($pattern, $long_url, $matches)) {
			$details = $matches[1];
		}
	  }

	  $query = 'SELECT title FROM ' .$wpdb->prefix. 'multiaxis_details WHERE hash = %s LIMIT 1';
	  $title = $wpdb->get_var($wpdb->prepare($query, $details));

	   if($title){
		  $post_title = esc_html(str_replace('\\', '', $title));
	  }
    }
  }

  return $post_title;
}

function remove_number_from_string($str) {
  return preg_replace('/-\d+/', '', $str);
}
function get_number_from_string($str) {
    preg_match('/\d+/', $str, $matches);
    return isset($matches[0]) ? (int) $matches[0] : 0;
}

add_filter('dynamic_sidebar_after', 'custom_get_sidebar_content');


function custom_get_sidebar_content($content) {
	$active_widgets = wp_get_sidebars_widgets()[$content];
	
    foreach ($active_widgets as $widget) {
		$widget_id = remove_number_from_string($widget);
        $widget_raw = get_option('widget_' . $widget_id);
		$widget_text = $widget_raw[get_number_from_string($widget)];
		
		if (isset($widget_text['content']) && has_shortcode($widget_text['content'], 'multiaxis_portfolio')) {
			wp_enqueue_style('multiaxis', plugins_url('/assets/multiaxis.css', __FILE__));
			wp_enqueue_script('jquery');
			wp_enqueue_script('multiaxis-script', plugins_url('/assets/multiaxis.js', __FILE__), array('jquery'), '', true);
			wp_localize_script('multiaxis-script', 'ajaxurl', array(admin_url('admin-ajax.php')));
			wp_localize_script( 'multiaxis-script', 'wpApiSettings', array(
				'root' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			));//new_fix_rest
			
			break; // No need to process other widgets
		}
    }
	
    return $content;
}




function is_shortcode_used_on_page($shortcode) {

    global $post;
    $content = $post->post_content;

    if (has_shortcode($content, $shortcode)) {
        return true;
    }
	

    return false;
}



function multiaxis_shortcode_scripts() {
	$current_template = get_page_template_slug();
 	$uniq_id_multiaxis = uniqid();

	if( is_shortcode_used_on_page('multiaxis_portfolio') || is_shortcode_used_on_page('multiaxis_portfolio_new') ) {
		wp_enqueue_style( 'multiaxis',  plugins_url( '/assets/multiaxis.css',  __FILE__), '', $uniq_id_multiaxis );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'multiaxis-script', plugins_url( '/assets/multiaxis.js',  __FILE__), array('jquery'), $uniq_id_multiaxis, true );
    	wp_localize_script('multiaxis-script', 'ajaxurl', array(admin_url('admin-ajax.php'),)); 		 
	}

	if($current_template === 'single-portfolio-sidebar.php' || $current_template === 'single-portfolio.php'){
		wp_enqueue_style( 'multiaxis',  plugins_url( '/assets/page-templates.css',  __FILE__), '', $uniq_id_multiaxis);
	}
	
	wp_localize_script( 'multiaxis-script', 'wpApiSettings', array(
				'root' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			));//new_fix_rest
}



add_action( 'wp_enqueue_scripts', 'multiaxis_shortcode_scripts');
require_once( dirname(__FILE__).'/includes/include-functions.php');



/* ----------------------------------------------------- */
/* Portfolio Custom Post Type */
/* ----------------------------------------------------- */

if (!function_exists('register_cpt_portfolio')) {
    add_action( 'init', 'register_cpt_portfolio' );
    function register_cpt_portfolio() {

        $labels = array(
            'name' => __( 'Portfolio','purepress'),
            'singular_name' => __( 'Portfolio','purepress'),
            'add_new' => __( 'Add New','purepress' ),
            'add_new_item' => __( 'Add New Work','purepress' ),
            'edit_item' => __( 'Edit Work','purepress'),
            'new_item' => __( 'New Work','purepress'),
            'view_item' => __( 'View Work','purepress'),
            'search_items' => __( 'Search Portfolio','purepress'),
            'not_found' => __( 'No portfolio found','purepress'),
            'not_found_in_trash' => __( 'No works found in Trash','purepress'),
            'parent_item_colon' => __( 'Parent work:','purepress'),
            'menu_name' => __( 'Portfolio','purepress'),
            );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __('Display your works by filters','purepress'),
            'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail' ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => 'portfolio'),
            'capability_type' => 'post'
            );

        register_post_type( 'portfolio', $args );
    }
}




/* ----------------------------------------------------- */
/* Services Taxonomy */
/* ----------------------------------------------------- */


if (!function_exists('register_taxonomy_servicestax')) {
    add_action( 'init', 'register_taxonomy_servicestax' );

    function register_taxonomy_servicestax() {

        $labels = array(
            'name' => __( 'Services', 'purepress' ),
            'singular_name' => __( 'Services', 'purepress' ),
            'search_items' => __( 'Search Services', 'purepress' ),
            'popular_items' => __( 'Popular Services', 'purepress' ),
            'all_items' => __( 'All Services', 'purepress' ),
            'parent_item' => __( 'Parent Services', 'purepress' ),
            'parent_item_colon' => __( 'Parent Services:', 'purepress' ),
            'edit_item' => __( 'Edit Services', 'purepress' ),
            'update_item' => __( 'Update Services', 'purepress' ),
            'add_new_item' => __( 'Add New Services', 'purepress' ),
            'new_item_name' => __( 'New Services', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Services with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Services', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Services', 'purepress' ),
            'menu_name' => __( 'Services', 'purepress' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                //'rewrite' => true,
                'rewrite' => array( 'slug' => 'servicestax'),
                'query_var' => true,				
                );
            register_taxonomy( 'servicestax', array('portfolio'), $args );
    }
}





/* ----------------------------------------------------- */
/* Technologies Taxonomy */
/* ----------------------------------------------------- */


if (!function_exists('register_taxonomy_technologies')) {
    add_action( 'init', 'register_taxonomy_technologies' );

    function register_taxonomy_technologies() {

        $labels = array(
            'name' => __( 'Technologies', 'purepress' ),
            'singular_name' => __( 'Technologies', 'purepress' ),
            'search_items' => __( 'Search Technologies', 'purepress' ),
            'popular_items' => __( 'Popular Technologies', 'purepress' ),
            'all_items' => __( 'All Technologies', 'purepress' ),
            'parent_item' => __( 'Parent Technologies', 'purepress' ),
            'parent_item_colon' => __( 'Parent Technologies:', 'purepress' ),
            'edit_item' => __( 'Edit Technologies', 'purepress' ),
            'update_item' => __( 'Update Technologies', 'purepress' ),
            'add_new_item' => __( 'Add New Technologies', 'purepress' ),
            'new_item_name' => __( 'New Technologies', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Technologies with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Technologies', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Technologies', 'purepress' ),
            'menu_name' => __( 'Technologies', 'purepress' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'technologies', array('portfolio'), $args );
    }
}


/* ----------------------------------------------------- */
/* Filter Taxonomy */
/* ----------------------------------------------------- */
if (!function_exists('register_taxonomy_filters')) {
    add_action( 'init', 'register_taxonomy_filters' );

    function register_taxonomy_filters() {

        $labels = array(
            'name' => __( 'Filters', 'purepress' ),
            'singular_name' => __( 'Filter', 'purepress' ),
            'search_items' => __( 'Search Filters', 'purepress' ),
            'popular_items' => __( 'Popular Filters', 'purepress' ),
            'all_items' => __( 'All Filters', 'purepress' ),
            'parent_item' => __( 'Parent Filter', 'purepress' ),
            'parent_item_colon' => __( 'Parent Filter:', 'purepress' ),
            'edit_item' => __( 'Edit Filter', 'purepress' ),
            'update_item' => __( 'Update Filter', 'purepress' ),
            'add_new_item' => __( 'Add New Filter', 'purepress' ),
            'new_item_name' => __( 'New Filter', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Filters with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Filters', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Filters', 'purepress' ),
            'menu_name' => __( 'Filters', 'purepress' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'filters', array('portfolio'), $args );
    }
}





/* ----------------------------------------------------- */
/* Software Categories and Types Taxonomy */
/* ----------------------------------------------------- */


if (!function_exists('register_taxonomy_software_categories_and_types')) {
    add_action( 'init', 'register_taxonomy_software_categories_and_types' );

    function register_taxonomy_software_categories_and_types() {

        $labels = array(
            'name' => __( 'Software Categories and Types', 'purepress' ),
            'singular_name' => __( 'Software Categories and Types', 'purepress' ),
            'search_items' => __( 'Search Software Categories and Types', 'purepress' ),
            'popular_items' => __( 'Popular Software Categories and Types', 'purepress' ),
            'all_items' => __( 'All Software Categories and Types', 'purepress' ),
            'parent_item' => __( 'Parent Software Categories and Types', 'purepress' ),
            'parent_item_colon' => __( 'Parent Software Categories and Types:', 'purepress' ),
            'edit_item' => __( 'Edit Software Categories and Types', 'purepress' ),
            'update_item' => __( 'Update Software Categories and Types', 'purepress' ),
            'add_new_item' => __( 'Add New Software Categories and Types', 'purepress' ),
            'new_item_name' => __( 'New Software Categories and Types', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Software Categories and Types with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Software Categories and Types', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Software Categories and Types', 'purepress' ),
            'menu_name' => __( 'Software Categories and Types', 'purepress' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'software_categories_and_types', array('portfolio'), $args );
    }
}





/* ----------------------------------------------------- */
/* IT Architecture Paradigms and Approaches Taxonomy */
/* ----------------------------------------------------- */


if (!function_exists('register_taxonomy_it_architecture_paradigms')) {
    add_action( 'init', 'register_taxonomy_it_architecture_paradigms' );

    function register_taxonomy_it_architecture_paradigms() {

        $labels = array(
            'name' => __( 'IT Architecture Paradigms and Approaches', 'purepress' ),
            'singular_name' => __( 'IT Architecture Paradigms and Approaches', 'purepress' ),
            'search_items' => __( 'Search IT Architecture Paradigms and Approaches', 'purepress' ),
            'popular_items' => __( 'Popular IT Architecture Paradigms and Approaches', 'purepress' ),
            'all_items' => __( 'All IT Architecture Paradigms and Approaches', 'purepress' ),
            'parent_item' => __( 'Parent IT Architecture Paradigms and Approaches', 'purepress' ),
            'parent_item_colon' => __( 'Parent IT Architecture Paradigms and Approaches:', 'purepress' ),
            'edit_item' => __( 'Edit IT Architecture Paradigms and Approaches', 'purepress' ),
            'update_item' => __( 'Update IT Architecture Paradigms and Approaches', 'purepress' ),
            'add_new_item' => __( 'Add New IT Architecture Paradigms and Approaches', 'purepress' ),
            'new_item_name' => __( 'New IT Architecture Paradigms and Approaches', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate IT Architecture Paradigms and Approaches with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove IT Architecture Paradigms and Approaches', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used IT Architecture Paradigms and Approaches', 'purepress' ),
            'menu_name' => __( 'IT Architecture Paradigms and Approaches', 'purepress' ),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'it_architecture_paradigms', array('portfolio'), $args );
    }
}




/* ----------------------------------------------------- */
/* Technical Expertise Taxonomy */
/* ----------------------------------------------------- */


if (!function_exists('register_taxonomy_technical_expertise')) {
    add_action( 'init', 'register_taxonomy_technical_expertise' );

    function register_taxonomy_technical_expertise() {
		
        $labels = array(
            'name' => __( 'Technical Expertise', 'purepress' ),
            'singular_name' => __( 'Technical Expertise', 'purepress' ),
            'search_items' => __( 'Search Technical Expertise', 'purepress' ),
            'popular_items' => __( 'Popular Technical Expertise', 'purepress' ),
            'all_items' => __( 'All Technical Expertise', 'purepress' ),
            'parent_item' => __( 'Parent Technical Expertise', 'purepress' ),
            'parent_item_colon' => __( 'Parent Technical Expertise:', 'purepress' ),
            'edit_item' => __( 'Edit Technical Expertise', 'purepress' ),
            'update_item' => __( 'Update Technical Expertise', 'purepress' ),
            'add_new_item' => __( 'Add New Technical Expertise', 'purepress' ),
            'new_item_name' => __( 'New Technical Expertise', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Technical Expertise with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Technical Expertise', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Technical Expertise', 'purepress' ),
            'menu_name' => __( 'Technical Expertise', 'purepress' ),
            );
			
            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'technical_expertise', array('portfolio'), $args );
    }
}







/* ----------------------------------------------------- */
/* Devops expertise Taxonomy */
/* ----------------------------------------------------- */


if (!function_exists('register_taxonomy_devops_expertise')) {
    add_action( 'init', 'register_taxonomy_devops_expertise' );

    function register_taxonomy_devops_expertise() {

        $labels = array(
            'name' => __( 'DevOps Expertise', 'purepress' ),
            'singular_name' => __( 'DevOps Expertise', 'purepress' ),
            'search_items' => __( 'Search DevOps Expertise', 'purepress' ),
            'popular_items' => __( 'Popular DevOps Expertise', 'purepress' ),
            'all_items' => __( 'All DevOps Expertise', 'purepress' ),
            'parent_item' => __( 'Parent DevOps Expertise', 'purepress' ),
            'parent_item_colon' => __( 'Parent DevOps Expertise:', 'purepress' ),
            'edit_item' => __( 'Edit DevOps Expertise', 'purepress' ),
            'update_item' => __( 'Update DevOps Expertise', 'purepress' ),
            'add_new_item' => __( 'Add New DevOps Expertise', 'purepress' ),
            'new_item_name' => __( 'New DevOps Expertise', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate DevOps Expertise with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove DevOps Expertise', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used DevOps Expertise', 'purepress' ),
            'menu_name' => __( 'DevOps Expertise', 'purepress' ),
            );
			
            $args = array(
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'devops_expertise', array('portfolio'), $args );
    }
}




/* ----------------------------------------------------- */
/* country Taxonomy */
/* ----------------------------------------------------- */



if (!function_exists('register_taxonomy_country')) {
    add_action( 'init', 'register_taxonomy_country' );

    function register_taxonomy_country() {

        $labels = array(
            'name' => __( 'Country', 'purepress' ),
            'singular_name' => __( 'Country', 'purepress' ),
            'search_items' => __( 'Search Country', 'purepress' ),
            'popular_items' => __( 'Popular Country', 'purepress' ),
            'all_items' => __( 'All Country', 'purepress' ),
            'parent_item' => __( 'Parent Country', 'purepress' ),
            'parent_item_colon' => __( 'Parent Country:', 'purepress' ),
            'edit_item' => __( 'Edit Country', 'purepress' ),
            'update_item' => __( 'Update Country', 'purepress' ),
            'add_new_item' => __( 'Add New Country', 'purepress' ),
            'new_item_name' => __( 'New Country', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Country with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Country', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Country', 'purepress' ),
            'menu_name' => __( 'Country', 'purepress' ),
            );
			
            $args = array(
                'labels' => $labels,
                'public' => false,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'country', array('portfolio'), $args );
    }
}

/* ----------------------------------------------------- */
/* customer Taxonomy */
/* ----------------------------------------------------- */



if (!function_exists('register_taxonomy_customer')) {
    add_action( 'init', 'register_taxonomy_customer' );

    function register_taxonomy_customer() {

        $labels = array(
            'name' => __( 'Customer', 'purepress' ),
            'singular_name' => __( 'Customer', 'purepress' ),
            'search_items' => __( 'Search Customer', 'purepress' ),
            'popular_items' => __( 'Popular Customer', 'purepress' ),
            'all_items' => __( 'All Customer', 'purepress' ),
            'parent_item' => __( 'Parent Customer', 'purepress' ),
            'parent_item_colon' => __( 'Parent Customer:', 'purepress' ),
            'edit_item' => __( 'Edit Customer', 'purepress' ),
            'update_item' => __( 'Update Customer', 'purepress' ),
            'add_new_item' => __( 'Add New Customer', 'purepress' ),
            'new_item_name' => __( 'New Customer', 'purepress' ),
            'separate_items_with_commas' => __( 'Separate Customer with commas', 'purepress' ),
            'add_or_remove_items' => __( 'Add or remove Customer', 'purepress' ),
            'choose_from_most_used' => __( 'Choose from the most used Customer', 'purepress' ),
            'menu_name' => __( 'Customer', 'purepress' ),
            );
			
            $args = array(
                'labels' => $labels,
                'public' => false,
                'show_in_nav_menus' => true,
                'show_ui' => current_user_can('manage_options') && isCEO(), 
                'show_tagcloud' => false,
                'hierarchical' => true,
                'rewrite' => true,
                'query_var' => true
                );
            register_taxonomy( 'customer', array('portfolio'), $args );
    }
}

/* ----------------------------------------------------- */
/* Featured tags Taxonomy */
/* ----------------------------------------------------- */



// Featured tags::DEV START
if (!function_exists('register_taxonomy_featured-tags')) {
	add_action( 'init', 'register_taxonomy_featured_tags' );

	function register_taxonomy_featured_tags() {

			$labels = array(
					'name' => __( 'Featured tags', 'purepress' ),
					'singular_name' => __( 'Featured tags', 'purepress' ),
					'search_items' => __( 'Search Featured tags', 'purepress' ),
					'popular_items' => __( 'Popular Featured tags', 'purepress' ),
					'all_items' => __( 'All Featured tags', 'purepress' ),
					'parent_item' => __( 'Parent Featured tags', 'purepress' ),
					'parent_item_colon' => __( 'Parent Featured tags:', 'purepress' ),
					'edit_item' => __( 'Edit Featured tags', 'purepress' ),
					'update_item' => __( 'Update Featured tags', 'purepress' ),
					'add_new_item' => __( 'Add New Featured tags', 'purepress' ),
					'new_item_name' => __( 'New Featured tags', 'purepress' ),
					'separate_items_with_commas' => __( 'Separate Featured tags with commas', 'purepress' ),
					'add_or_remove_items' => __( 'Add or remove Featured tags', 'purepress' ),
					'choose_from_most_used' => __( 'Choose from the most used Featured tags', 'purepress' ),
					'menu_name' => __( 'Featured tags', 'purepress' ),
					);
		
					$args = array(
							'labels' => $labels,
							'public' => false,
							'show_in_nav_menus' => true,
							'show_ui' => true,
							'show_tagcloud' => false,
							'hierarchical' => true,
							'rewrite' => true,
							'query_var' => true
							);
					register_taxonomy( 'featured-tags', array('portfolio'), $args );
	}
}
// Featured tags::DEV END




/*функция проверки количества постов у дочерних терминов*/
function children_count_terms($id_term = '', $taxonomies_name = ''){
	
	$children_terms_id = get_term_children( $id_term, $taxonomies_name );
	$k=0;
	
	foreach ($children_terms_id as $children_term_id){
	
		if (get_term( $children_term_id, $taxonomies_name)->count > 0){
		
			$k++;
			
		}		 
		
	}
	
	if ($k>0){
		
		return false;
		
	} else {
		
		return true;
		
	}
	
}

function multiaxis_create() {

	global $wpdb;
	$table_name = $wpdb->prefix. "multiaxis";
	$foreign_target = $wpdb->prefix . 'multiaxis_details';

	global $charset_collate;
	$charset_collate = $wpdb->get_charset_collate();
	global $db_version;

require_once(ABSPATH . "wp-admin/includes/upgrade.php");

	if( $wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") !=  $table_name){ 
	$create_sql = "CREATE TABLE $table_name (
					multiaxis_id INT(11) NOT NULL auto_increment,
					multiaxis_key TEXT NOT NULL,
					multiaxis_value TEXT NOT NULL,
					PRIMARY KEY (multiaxis_id)
			) $charset_collate;";
 dbDelta( $create_sql );
	}

//register the new table with the wpdb object
	if (!isset($wpdb->multiaxis)){
			$wpdb->multiaxis = $table_name;
			//add the shortcut so you can use $wpdb->stats
			$wpdb->tables[] = str_replace($wpdb->prefix, '', $table_name);
	}
}

add_action( 'init', 'multiaxis_create');




// DEV:FEATURED TAGS

add_action( 'wp_ajax_multiaxis_delete_featured_tag', 'multiaxis_delete_featured_tag' );

function multiaxis_delete_featured_tag() {
  $term_id = isset($_POST['term_id']) ? $_POST['term_id'] : -1;

  if (!$term_id) {
      wp_send_json_error(array('message' => 'Tag ID is required'));
      return;
  }

  $term_exists = get_term_by('id', $term_id, 'featured-tags');
	
  if (!$term_exists || is_wp_error($term_exists)) {
      wp_send_json_error(array('message' => 'Tag not found'));
      return;
  }

	$deleted = wp_delete_term($term_id, 'featured-tags');

	if ($deleted && !is_wp_error($deleted)) {
		$total = wp_get_postcount_new('featured-tags');
    	wp_send_json_success(array('message' => 'Tag deleted successfully','total' => $total));
    	return;
	}

	wp_send_json_error(array('message' => 'Failed to delete tag'));
}



add_action( 'wp_ajax_multiaxis_create_featured_tag', 'multiaxis_create_featured_tag' );

function multiaxis_create_featured_tag() {
	
	if(!isset($_POST['name']) || trim($_POST['name']) == ''){
			wp_send_json_error(array('message' => 'Tag name cannot be empty'));
			return;
	}
	
	$name = trim($_POST['name']);
	$slug = sanitize_title($name);
	$term_exists = term_exists($name, 'featured-tags');
		
	if(strlen($name) > 48){
			wp_send_json_error(array('message' => 'Tag name must not exceed 48 characters'));
			return;
	}


	if(empty($_POST['projects_ids'])){
			wp_send_json_error(array('message' => 'Tag must have at least one related project'));
			return;
	}

	$projects_ids = explode(',', $_POST['projects_ids']);

	if ($term_exists !== 0 && $term_exists !== null || esc_html($name) !== $name) {
			wp_send_json_error(array('message' => 'Tag with this name already exists or invalid'));
			return;
	}

	$meta = array('description' => 'Created via multiaxis with name: ' . $name, 'slug' => $slug);
	$new_term = wp_insert_term( $name, 'featured-tags', $meta );

	if (!is_wp_error($new_term)) {
		$related_projects_count = 0;
		foreach ($projects_ids as $post_id) {
				if (get_post_status($post_id) && get_post_status($post_id) !== 'trash') {
						$set_term = wp_set_object_terms($post_id, [$new_term['term_id']], 'featured-tags', true);
						if (!is_wp_error($set_term)) {
								$related_projects_count++;
						}
				}
		}

			$formatted_term = array(
					'id' => $new_term['term_id'],
					'name' => $name,
					'totalRelatedProjects' => $related_projects_count,
					'meta' => $set_term
			);
			$total = wp_get_postcount_new('featured-tags');
			wp_send_json_success(array('total' => $total, 'tag' => $formatted_term));
	} else {
		wp_send_json_error(array('message' => 'Failed to create featured tag'));
	}
}


add_action( 'wp_ajax_multiaxis_get_posts_by_term_id', 'multiaxis_get_posts_by_term_id' );
function multiaxis_get_posts_by_term_id() {
	$term_id = isset($_POST['term_id']) ? $_POST['term_id'] : 0;

	$args = array(
			'post_type' => 'portfolio',
			'numberposts' => -1,
			'tax_query' => array(
					array(
							'taxonomy' => 'featured-tags',
							'field' => 'term_id',
							'terms' => $term_id,
					)
			),
	);
	
	$posts = get_posts($args);

	if ($posts) {
		$formatted_posts = array_map(function($post) {
			return array(
					'id' => $post->ID,
					'title' => $post->post_title,
					'slug' => $post->post_name
			);
		}, $posts);
			wp_send_json_success($formatted_posts);
	} else {
			wp_send_json_error(array('message' => "No posts found for term ID: {$term_id}"));
	}
}

// DEV:FEATURED TAGS END



function short_link_table_create(){
	global $wpdb;

	$table_name = $wpdb->prefix . 'multiaxis_short_links';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    	$charset_collate = $wpdb->get_charset_collate();
    	$sql = "CREATE TABLE $table_name (
        	id INT NOT NULL AUTO_INCREMENT,
        	short_key VARCHAR(20) NOT NULL,
        	long_url TEXT NOT NULL,
       		PRIMARY KEY (id),
					created_at BIGINT(20) NOT NULL DEFAULT 1697196233000,
        	UNIQUE KEY short_key (short_key)
    		) $charset_collate;";

    	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

   	 dbDelta($sql);
	}
}

add_action( 'init', 'short_link_table_create');


function details_table_create(){
	global $wpdb;

	$table_name = $wpdb->prefix . 'multiaxis_details';
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    	$charset_collate = $wpdb->get_charset_collate();
    	$sql = "CREATE TABLE $table_name (
        	id INT NOT NULL AUTO_INCREMENT,
        	title TEXT NOT NULL,
					description TEXT NOT NULL,
					hash TEXT NOT NULL,
					compact_view TINYINT(1) NOT NULL DEFAULT '0',
       		PRIMARY KEY (id)
    		) $charset_collate;";

    	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

   	 dbDelta($sql);
	}
}

add_action( 'init', 'details_table_create');

function get_millisecond_timestamp(){
	$unix_timestamp = time();
	return $unix_timestamp * 1000;
}

/* ###### Ajax function for get short links ##### */
add_action( 'wp_ajax_multiaxis_get_short_link', 'multiaxis_get_short_link' );

function multiaxis_get_short_link() {
	if (!isset($_POST['link'])) {
			wp_send_json_error(array('shortKey' => '', 'isNew' => false));
			wp_die();
			return;
	}

	$long_url = stripslashes($_POST['link']);
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'multiaxis_short_links';

			// If no existing record is found, proceed with insertion without WordPress escaping
			$short_key = $_POST['shortKey'] ? $_POST['shortKey'] : uniqid();
	
			$current_timestamp = get_millisecond_timestamp();
			
			// Construct the raw SQL query without WordPress escaping
			$sql = "INSERT INTO $table_name (short_key, long_url, created_at) VALUES ('$short_key', '$long_url', '$current_timestamp')";

			// Execute the raw SQL query
			$result = $wpdb->query($sql);

			if ($result !== false) {
					wp_send_json_success(array('shortKey' => $short_key, 'isNew' => true));
			} else {
					wp_send_json_error(array('shortKey' => '', 'isNew' => false));
			}


	wp_die();
}


/* ###### Ajax function for pagination ###### */

add_action( 'wp_ajax_multiaxis_get_all_short_links', 'multiaxis_get_all_short_links' );
add_action( 'wp_ajax_nopriv_multiaxis_get_all_short_links', 'multiaxis_get_all_short_links' ); 
// PUBLIC

function multiaxis_get_all_short_links() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'multiaxis_short_links';

    // Get pagination parameters from the AJAX request
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 5;

    $offset = ($page - 1) * $per_page;

 	$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");

    // Calculate the total number of records in the table
    $total_records = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    if ($results !== null) {
        $max_pages = ceil($total_records / $per_page); // Calculate the maximum number of pages

        // Prepare the response data
        $response_data = array(
            'results' => $results,
            'maxPages' => $max_pages,
			'currentPage' => $page,
        );

        wp_send_json_success($response_data);
    } else {
        wp_send_json_error(array('message' => 'No data found'));
    }
}
			
/* ###### Ajax function for pagination ###### */

add_action( 'wp_ajax_multiaxis_get_link', 'multiaxis_get_link' );
add_action( 'wp_ajax_nopriv_multiaxis_get_link', 'multiaxis_get_link' ); 


function multiaxis_get_link() {
	$response = '[]';


	if(isset($_POST['ha'])){
		$hidden_axis_array = $_POST['ha'];
		$hidden_axis_string = json_encode($hidden_axis_array);
		$multiaxis_id = uniqid();
		$response = $multiaxis_id;
		
		global $wpdb;
		$wpdb->insert( 
			$wpdb->prefix . 'multiaxis',
			array(
				'multiaxis_key' => (string)$multiaxis_id, 
				'multiaxis_value' => $hidden_axis_string,
			), 
				array( '%s', '%s', '%s') 
		);
	}
	
	wp_send_json_success(array( 'result' => $response ));

	wp_die();	
}

//  Details & Short link utilities START
function get_link_by_short_key($search_key){
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'multiaxis_short_links';
	$query = $wpdb->prepare("SELECT long_url FROM $table_name WHERE short_key = %s", $search_key);
	$long_url = $wpdb->get_var($query);
	
	if ($long_url) {
		return str_replace('\\','', $long_url);
	} 

	return '';
}

function get_details_by_hash($hash){
	global $wpdb;	
	$query = 'SELECT title, description, hash, compact_view FROM ' .$wpdb->prefix. 'multiaxis_details WHERE hash = %s LIMIT 1';
	$result = $wpdb->get_row($wpdb->prepare($query, $hash), OBJECT);

	if($result){
	    return json_decode(json_encode($result), true);
	}
	
	return NULL;
}


add_action( 'wp_ajax_multiaxis_get_details', 'multiaxis_get_details' );

function insert_details($title, $description, $compact_view){
	global $wpdb;
	$hash = uniqid();
	$result = $wpdb->insert( 
		$wpdb->prefix . 'multiaxis_details',
		array(
			'title' => $title,
			'description' => $description,
			'hash' => $hash,
			'compact_view' => $compact_view,
		), 
			array( '%s', '%s', '%s', '%s', '%s') 
	);

	if ($result) {
		return $hash;
	} else {
		return NULL;
	}
}


function get_save_text($text){
    $pattern = '/<script(.*?)<\/script>/i';
    $replacement = '&lt;script$1&lt;/script&gt;';
    $escaped_input = preg_replace($pattern, $replacement, $text);

    return $escaped_input;
}


function multiaxis_get_details(){
	if(isset($_POST['title']) || isset($_POST['description'])){
		$seq_title = $_POST['title'] ? (string)$_POST['title'] : '';
		$seq_description = $_POST['description'] ? get_save_text((string)$_POST['description']) : '';
		$compact_view = $_POST['compactView'];
		$seq_compact_view = $compact_view && $compact_view === 'true' ? true : false;
		$details = insert_details($seq_title, $seq_description, $seq_compact_view);
		wp_send_json_success(array( 'result' => $details, 'dfsa' => $seq_compact_view));
	}else {
		wp_send_json_error(array( 'result' => false ));
	}
}

//  Details & Short link utilities END

function get_hidden_axis_array($hidden_axis_key) {
	global $wpdb;	$query = 'SELECT multiaxis_value FROM ' .$wpdb->prefix. 'multiaxis WHERE multiaxis_key = %s LIMIT 1';
	$hidden_axis_value = $wpdb->get_var($wpdb->prepare($query, $hidden_axis_key));
	if ($hidden_axis_value !== null) {
		$hidden_axis_array = json_decode($hidden_axis_value, true);
		return $hidden_axis_array;
	} else {
		return array();
	}
}
			
			
function is_taxonomy_public( $taxonomy ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return false;
	}

	$taxonomy = get_taxonomy( $taxonomy );
	return $taxonomy->public;
}



function get_taxonomies_sql(){
	global $wpdb;
	$table_prefix = $wpdb->prefix;

	$pages = $wpdb->get_results( 
	"
	SELECT t.name, t.term_id, tt.taxonomy, p.ID, p.post_title, p.post_date
	FROM ".$table_prefix."terms t
		INNER JOIN ".$table_prefix."term_taxonomy tt ON (t.term_id = tt.term_id)
		INNER JOIN ".$table_prefix."term_relationships tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id)
		INNER JOIN ".$table_prefix."posts p ON (tr.object_id = p.ID)
	WHERE p.post_type = 'portfolio'
	ORDER BY t.term_id ASC	
	"
);

	return $pages;
		
}


function sorting_taxonomy_sql(){
	
	$pages = get_taxonomies_sql();
	$res = [];
	$terms_array = [];
	$array_id = [];	
	

	foreach ($pages as $elem) {
		$terms_array[$elem->taxonomy][$elem->term_id][] = $elem->ID;
		if (in_array($elem->term_id, $array_id[$elem->taxonomy] ) == false){
			$array_id[$elem->taxonomy][] = $elem->term_id;
		}
	}
	
	$res['array_id'] = $array_id;
	$res['terms_array'] = $terms_array;
	

	
	return $res;
	
}



function get_sorting_top_technologies(){
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	
	$pages = $wpdb->get_results( 
	"	
	SELECT DISTINCT(t.name), t.term_id, tt.taxonomy, tt.count, p.ID, tm.meta_id, tm.meta_value, tm.meta_key, tm2.term_id, tm2.meta_value AS meta_value2, tm2.meta_key AS meta_key2
	FROM ".$table_prefix."terms t
		JOIN ".$table_prefix."term_taxonomy tt ON (t.term_id = tt.term_id)
		JOIN ".$table_prefix."term_relationships tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id)
		JOIN ".$table_prefix."termmeta tm ON (tm.term_id = t.term_id)    
		JOIN ".$table_prefix."posts p ON (tr.object_id = p.ID)
		INNER JOIN ".$table_prefix."termmeta tm2 ON (tm.term_id = tm2.term_id)
	WHERE p.post_type = 'portfolio' AND tm.meta_key = 'on_top_sorting' AND tm.meta_value != '' AND tm2.meta_key = 'top_checkbox' AND tm2.meta_value = 'checked' GROUP BY t.name

	ORDER BY CAST(tm.meta_value AS SIGNED) ASC
	");

	return $pages;
		
}

function get_poste_create_date($post_id){
	global $wpdb;
	$meta_key = '_wp_old_date';
	$post_create_date = $wpdb->get_var( $wpdb->prepare(
		"
			SELECT MIN($wpdb->postmeta.meta_value) as post_create_date
			FROM $wpdb->postmeta
			WHERE $wpdb->postmeta.post_id = %d
				AND $wpdb->postmeta.meta_key = %s
		",
		$post_id,
		$meta_key
	) );

	if(is_null($post_create_date)) {
		$post_create_date = get_the_date('Y-m-d', $post_id);
	}
	
	return $post_create_date;
}




function get_in_process_duration(){

	global $wpdb;
	$table_prefix = $wpdb->prefix;
	
	$durations = $wpdb->get_results( 
	"	
	SELECT pm.post_id, p.post_title, pm.meta_value, pm.meta_key, pm2.meta_key, pm2.meta_value as duration, pm3.meta_key, pm3.meta_value as start_year
	FROM ".$table_prefix."postmeta pm
		JOIN ".$table_prefix."posts p ON (p.ID = pm.post_id)
		INNER JOIN ".$table_prefix."postmeta pm2 ON (pm.post_id = pm2.post_id)
		INNER JOIN ".$table_prefix."postmeta pm3 ON (pm.post_id = pm3.post_id)

	WHERE p.post_type = 'portfolio' AND pm.meta_key = 'in_process' AND pm2.meta_key = 'duration' AND pm3.meta_key = 'start_year'
	");
	
	return $durations;
}


function get_recalculate(){
	
	$durations = get_in_process_duration();
	$current_date = date('Y-m-d');
	
	$date2 = new DateTime($current_date);
	foreach ($durations as $duration) {
		

		$date1 = new DateTime($duration->start_year);
		$interval = $date1->diff($date2);
		$date_different = 12*$interval->y+$interval->m;
		
		update_post_meta( $duration->post_id, 'duration', $date_different);

	}

}

// регистрируем 5-минутный интервал
add_filter( 'cron_schedules', 'cron_add_five_min' );

// регистрируем событие
add_action( 'wp', 'my_activation_recalculate' );

// добавляем функцию к указанному хуку
add_action( 'get_recalculate_hook', 'get_recalculate' );

function cron_add_five_min( $schedules ) {
	$schedules['five_min'] = array(
		'interval' => 600,
		'display' => 'Тест раз в 10 минут'
	);
	return $schedules;
}

function my_activation_recalculate() {
	if ( ! wp_next_scheduled( 'get_recalculate_hook' ) ) {
		wp_schedule_event( time(), 'five_min', 'get_recalculate_hook');
	}
}





function make_tax_query_for_selection($taxonomies_object, $and_checkbox) {

	$operator = 'IN';
	$relation = 'AND';
	$include_children = true;
	$tax_query_obj = ['relation' => $relation];
	$tass = [];
	$checkbox = $and_checkbox == 'AND';
	foreach ($taxonomies_object as $tax_name => $term_obj) {		
		if (trim($tax_name) == 'technologies' && $checkbox ){				
			$operator = 'AND';	
			$include_children = false;	
		} else {
			$operator = 'IN';	
			$include_children = true;
		}
			
		$tass = [
			
			'taxonomy' => trim($tax_name),
			'field'    => 'name',
			'terms'    => $term_obj,			
			'operator' => $operator,
			'include_children' => $include_children,
		
		];
		$tax_query_obj[] = $tass; 
		
	}
	$tax_query = $tax_query_obj;

	return $tax_query;
	
}	

function make_tax_query_for_selection_id($taxonomies_object, $and_checkbox) {	
	$operator = 'IN';
	$relation = 'AND';
	$include_children = true;
	$tax_query_obj = ['relation' => $relation];
	$tass = [];
	$checkbox = $and_checkbox == 'AND';
	foreach ($taxonomies_object as $tax_name => $term_obj) {
		
		if ($tax_name == 'technologies' && $checkbox ){
				
			$operator = 'AND';	
			$include_children = false;	
		} else {
			$operator = 'IN';	
			$include_children = true;
		}
			
		$tass = [
			
			'taxonomy' => $tax_name,
			'field'    => 'id',
			'terms'    => $term_obj,			
			'operator' => $operator,
			'include_children' => $include_children,
		
		];
		$tax_query_obj[] = $tass; 
		
	}
	$tax_query = $tax_query_obj;

	return $tax_query;	
}		
			
			
function make_tax_query($taxonomies_object, $and_checkbox) {
	
	$operator = 'IN';
	$relation = 'AND';	
	$include_children = true;
	$tax_query_obj = ['relation' => $relation];
	$tass = [];
	$checkbox = $and_checkbox == 'AND';

	foreach ($taxonomies_object as $tax_name => $term_obj) {
		if ($tax_name == 'technologies' && $checkbox ){				
			$operator = 'AND';		
		} else {
			$operator = 'IN';				
		}
		$include_children = false;			
		$tass = [
			
			'taxonomy' => $tax_name,
			'field'    => 'id',
			'terms'    => $term_obj,			
			'operator' => $operator,
			'include_children' => $include_children,
		
		];
		$tax_query_obj[] = $tass;
		
	}
	$tax_query = $tax_query_obj;
	return $tax_query;
	
}

/* Альтернативный подсчёт количества с целью уменьшить нагрузку */
function make_tax_query_for_and_check_alt($taxonomies_object, $and_checkbox) {
	
	//dd($and_checkbox);
	if ($and_checkbox == true) { $and_checkbox = 'AND'; }
	if ($and_checkbox == false) { $and_checkbox = 'OR'; }
	$start_time00 = microtime( true );	
	$array_count = [];
	$new_taxonomies_object = [];
	$select_post_id = [];
	$terms_array = [];
	$array_id = [];
	
	
	$cache_key = 'sorting_taxonomy_sql_cache_key';
	$cache = wp_cache_get( $cache_key );
	
	if( false !== $cache ) {
		$res = $cache;
	}
	else {
		$res = sorting_taxonomy_sql();
		wp_cache_set( $cache_key, $res );		
	}

	$diff_time00 = microtime( true ) - $start_time00;
	$diff_time00 = sprintf( '%.6F sec.', $diff_time00 );

	$start_time01 = microtime( true );
	
		$array_id = $res['array_id'];
		$terms_array = $res['terms_array'];
		
		$array_id_new = $array_id;	
		$time_array = [];	
		$sum_time_array = [];
		foreach ($array_id_new as $key => $terms ) {
			
		
			$taxonomies_object_copy = [];
			
			foreach ($terms as $term) {
				
				$start_time11 = microtime( true );
				
				$taxonomies_object_copy = $taxonomies_object;
				$taxonomies_object_copy[$key][] = (string)$term;

				$this_array_post = [];					
				$this_array_post = adding_for_make_tax_query_alt($and_checkbox, $taxonomies_object_copy, $terms_array);
				$this_count = count(array_intersect($this_array_post, $terms_array[$key][$term]));
				$array_count[$key][$term] = $this_count;
				
				$diff_time11 = microtime( true ) - $start_time11;
				$diff_time11 = sprintf( '%.6F sec.', $diff_time11 );
				
				array_push($time_array, [$diff_time11, $term]); 
				array_push($sum_time_array, $diff_time11);				
				
			}		
			
		}		
	
	$diff_time01 = microtime( true ) - $start_time01;
	$diff_time01 = sprintf( '%.6F sec.', $diff_time01 );		

	
	
	$return_array = [];
	if ( !is_user_logged_in() ){
		unset($array_count['country']);
		unset($array_count['featured-tags']);//featured_tags
    unset($array_count['customer']);//customer
	} else {
		
		$array_count = $array_count;
	}
	
	$return_array['array_count'] = $array_count;
	$return_array['sum_time_array'] = array_sum($sum_time_array);
	$return_array['time_array'] = $time_array;
		

	return $return_array;
	
}


function adding_for_make_tax_query_alt($and_checkbox, $taxonomies_object, $terms_array) {
	$new_array_post_id = [];

	foreach ($taxonomies_object as $key_terms => $terms) {
			$new_array_post_id[$key_terms] = array_intersect_key($terms_array[$key_terms], array_flip($terms));
	}

	if ($and_checkbox === 'AND' && count($new_array_post_id['technologies']) > 1) {
			$tech_array = call_user_func_array('array_intersect', $new_array_post_id['technologies']);
			$new_array_post_id['technologies'] = ['0' => $tech_array];
	}

	$count_array_post = array_map(function ($post_id) {
			return array_unique(array_merge(...$post_id));
	}, $new_array_post_id);

	if (count($count_array_post) > 1) {
			$int_res = call_user_func_array('array_intersect', $count_array_post);
	} else {
			$int_res = reset($count_array_post);
	}

	return $int_res;
}


function make_tax_query_for_and_check($taxonomies_object, $and_checkbox) {
	
	$array_count = [];
	$new_taxonomies_object = [];
	
	$portfolio_taxonomy_names = get_object_taxonomies( 'portfolio' , 'names' );
	
	foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name){
		
		$terms = get_terms( $portfolio_taxonomy_name );
		
		foreach( $terms as $term ){				
			
			$term_id = $term->term_id;
			$new_taxonomies_object = $taxonomies_object;
			if (isset($new_taxonomies_object[$portfolio_taxonomy_name])){
				array_push($new_taxonomies_object[$portfolio_taxonomy_name], (string)$term_id);
			} else {
				$new_taxonomies_object[$portfolio_taxonomy_name] = [];
				array_push($new_taxonomies_object[$portfolio_taxonomy_name], (string)$term_id);
			}
			
			$operator = 'IN';
			$relation = 'AND';	
			$include_children = true;
			$tax_query_obj = ['relation' => $relation];
			$tass = [];
			foreach ($new_taxonomies_object as $tax_name => $term_obj) {
				
				if ($tax_name == 'technologies' && $and_checkbox == 'AND' /*&& count($taxonomies_object['technologies']) > 1*/){
						
					$operator = 'AND';	
					$include_children = false;	
				} 
				
				else {
					$operator = 'IN';	
					$include_children = true;
				}
					
				$tass = [
					
					'taxonomy' => $tax_name,
					'field'    => 'id',
					'terms'    => $term_obj,			
					'operator' => $operator,
					'include_children' => $include_children,
				
				];
				array_push($tax_query_obj, $tass); 
				
			}
			$tax_query = $tax_query_obj;
			
			$query_posts_1 = new WP_Query( array(
				'post_type'         => 'Portfolio',			
				'post_status'       => 'publish',
				'orderby'           => $order_by,
				'order'             => $order,
				'posts_per_page'    => -1,
				'tax_query' => $tax_query,
				) 
			);
			
			$z = 0;
			$array_id = [];
			if ( $query_posts_1->have_posts() ) {
				
				while ( $query_posts_1->have_posts() ) {
					
					$query_posts_1->the_post();
					array_push($array_id, $query_posts_1->posts[$z]->ID);
					$z++;
					
				}
			} else {
				
			}			
			wp_reset_postdata();
			
			for ($r = 0; $r < count($array_id); $r++) {	
			
				if( is_object_in_term( $array_id[$r], $portfolio_taxonomy_name, $term_id) ) {
					
					$count_of_terms_taxonomies[$portfolio_taxonomy_name][$term_id]++;

				} 
			}					

		}
		
	}
	

	//echo '<pre> '; var_dump($tax_query); echo '</pre>';
	
	//return $tax_query;
	return $count_of_terms_taxonomies;
	
	
}



function get_post_for_count($tax_query) {
	
		$query_posts_1 = new WP_Query( array(
			'post_type'         => 'Portfolio',			
			'post_status'       => 'publish',
			'orderby'           => $order_by,
			'order'             => $order,
			'posts_per_page'    => -1,
			'tax_query' => $tax_query,
			) 
		);

			$z = 0;
			$array_id = [];
			if ( $query_posts_1->have_posts() ) {
				
				while ( $query_posts_1->have_posts() ) {
					
					$query_posts_1->the_post();
					array_push($array_id, $query_posts_1->posts[$z]->ID);
					$z++;
					
				}
			} else {
				
			}
			
			wp_reset_postdata();
			return $array_id;
	
}

function get_this_taxonomy_term_id($taxonomy){
	$array_id_taxonomies = [];
	$term_id_taxonomies = get_terms(['taxonomy' => $taxonomy ]);
	
	foreach ($term_id_taxonomies as $term_id_taxonomy) {
		
		array_push($array_id_taxonomies, $term_id_taxonomy->term_id);
				
	} 	
	//echo '<pre> '; var_dump($array_id_taxonomies); echo '</pre>';
	
	return $array_id_taxonomies;
}

function compare_post($array_id, $array_id_taxonomies, $taxonomy, $count_of_terms_taxonomies) {	
	
	
	for ($p = 0; $p < count($array_id_taxonomies); $p++) {

		for ($r = 0; $r < count($array_id); $r++) {	
		
			if( is_object_in_term( $array_id[$r], $taxonomy, $array_id_taxonomies[$p]) ) {
			
				$count_of_terms_taxonomies[$taxonomy][$array_id_taxonomies[$p]]++;

			}

		}

	}
	
	return $count_of_terms_taxonomies;
	
}

	

function new_count_terms($axis_info, $and_checkbox){
	
	$new_count_of_terms_taxonomies = [];
	$portfolio_taxonomy_names = get_object_taxonomies( 'portfolio' , 'names' );
	if (!empty($axis_info)){
		
		

		
		foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name){
			
			
		
			$new_axis_info = $axis_info;
			/*Если проблемы с количеством, то убрать if*/
			//if ($portfolio_taxonomy_names != 'technologies' && $and_checkbox != true){
				unset($new_axis_info[$portfolio_taxonomy_name]);   
			//}/*Если проблемы с количеством, то убрать if*/
			$keys = $portfolio_taxonomy_name;
			
			$tax_query = make_tax_query($new_axis_info, $and_checkbox);
			$array_id = get_post_for_count($tax_query);


			$array_taxonomy_term_id = get_this_taxonomy_term_id($keys);
			$new_count_of_terms_taxonomies = compare_post($array_id, $array_taxonomy_term_id, $keys, $new_count_of_terms_taxonomies);
			//echo '<pre>'; var_dump($new_count_of_terms_taxonomies); echo '</pre>';

		}
	}
	
	return $new_count_of_terms_taxonomies;	
}




/* ###### Ajax function for pagination ###### */

add_action( 'wp_ajax_multiaxis', 'multiaxis_load_posts' );
add_action( 'wp_ajax_nopriv_multiaxis', 'multiaxis_load_posts' ); 


function multiaxis_load_posts() {

	$start_time0 = microtime( true );  
	//global $wpdb;   
	//global $post;		
	$start_time1 = microtime( true );  
	$multiaxis_shortcode_atts = [
		'showselectedoptions' =>'false',
		'selectotherprojects' => 'false',
		'showadditionalaxis' => 'false',
		'columns' => '4',
		'rows' => '2',
		'mode' => 'minimal',
		'selection' => '',
		'technologiesoperator' => 'OR',
		'orderby'=>'weights',
		'order'=>'DESC',
		'edit' => 'false',
		'page' => 1,
		'time' => 'false',
		'multiaxis_id' => '',
	];//new_fix 
	

	
    if( isset($_POST['page']) ){
		
        // Sanitize the received page 
        $start_time2 = microtime( true );  
		$post_id = $_POST['postId'];
		$mId = $_POST['multiaxisId'];//new_fix

		$this_shortcodes_atts = get_shortcode_on_page($post_id, 'multiaxis_portfolio')[0];
		$this_shortcodes_atts = array_merge($multiaxis_shortcode_atts, $this_shortcodes_atts);

		$d = 0;
		$r;

		$this_shortcodes_atts_array = get_shortcode_on_page($post_id, 'multiaxis_portfolio');
		
		for ($d = 0; $d< count($this_shortcodes_atts_array); $d++) {
			if ( 'multiaxis-id-'.$this_shortcodes_atts_array[$d]['multiaxis_id'] == $_POST['multiaxisId'] ) {//new_fix				
				$r = $d;
			}			
			$this_shortcodes_atts_array[$d] = array_merge($multiaxis_shortcode_atts , $this_shortcodes_atts_array[$d]);
		}
		$this_edit = $this_shortcodes_atts_array[$r]['edit'];
		
		$diff_time2 = microtime( true ) - $start_time2;
		$diff_time2 = sprintf( '%.6F sec.', $diff_time2 );
		
		
		if ($this_shortcodes_atts['edit'] != 'false' ) {
		
				
			$start_time3 = microtime( true );  
			$new_columns = transferAttribute($columns = $_POST['newColumns']);
			$int_columns = $_POST['newColumns'];
			$new_rows = $_POST['newRows'];
			
			$order_by = $_POST['orderBy'];

			$sortArr = transferSorting($_POST['orderBy']);	
			$order_by = $sortArr['orderby'];
			$meta_key = $sortArr['metakey'];
			

			$order = $_POST['order'];
			$and_checkbox = $_POST['andCheckboxSwitch'];
			$axis_array = $_POST['axisArray'];
			
			$ha = $_POST['ha'];
			
			$clear_all_filters = $_POST['clearAll'];

			if ( ($ha == null || empty($ha) || $ha == 'undefined') && !is_user_logged_in() ) {
				$ha = [];
				$this_ha = convert_name_and_id_to_id_term($this_shortcodes_atts['selection'], true)['country'];
				
				if ( isset($this_ha) && $clear_all_filters != 'clear_all' ) {
					$server_ha = true;
					$ha['country'] = $this_ha;
					$clear_all_filters = '';
				} else {
					$ha = null;
					$server_ha = false;
					$clear_all_filters = 'clear_all';
				}
				
			} else {
				$server_ha = true;
				$ha = get_hidden_axis_array($ha);
			}

			
			if ($ha == null) {				
				$taxonomies_object = $_POST['selection'];				
			} else {
				$new_ha = [];
				$new_ha = $ha;
				$no_hidden = [];
				$no_hidden = $_POST['selection'];			
				$taxonomies_object = [];
				if ($no_hidden != null){					

					$taxonomies_object = array_merge($new_ha, $no_hidden);
					
				} else {					
					$taxonomies_object = $new_ha;
				}
				
			}
			$axis_array = array_keys($taxonomies_object);
			$taxonomy_length = count($taxonomies_object);

			$operator = 'IN';
			$relation = 'AND';	
			$include_children = true;		
			$tax_query_obj = ['relation' => $relation];
			$tass = [];

			$diff_time3 = microtime( true ) - $start_time3;
			$diff_time3 = sprintf( '%.6F sec.', $diff_time3 );


			
			$tax_query = $tax_query_obj;
			
			$start_time4 = microtime( true );  
			
			$tax_query = make_tax_query($taxonomies_object, $and_checkbox);
			
			
			$diff_time4 = microtime( true ) - $start_time4;
			$diff_time4 = sprintf( '%.6F sec.', $diff_time4 );
	
			$page = sanitize_text_field($_POST['page']);
			$cur_page = $page;			
			$page -= 1;
			$per_page = (int)$_POST['newColumns']*(int)$new_rows; //set the per page limit
			$start = $page * $per_page;
			

			$start_time5 = microtime( true );  
			
				
			$return_array = make_tax_query_for_and_check_alt($taxonomies_object, $and_checkbox);
			
			$count_of_terms_taxonomies = $return_array['array_count'];
			
			$diff_time5 = microtime( true ) - $start_time5;
			$diff_time5 = sprintf( '%.6F sec.', $diff_time5 );

			$count_of_terms_taxonomies = $return_array['array_count'] ?? [''];

			$start_time6 = microtime( true );


			$diff_time6 = microtime( true ) - $start_time6;
			$diff_time6 = sprintf( '%.6F sec.', $diff_time6 );
		

			$start_time7 = microtime( true );
				
			$post_param = get_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start);
			
			$diff_time7 = microtime( true ) - $start_time7;
			$diff_time7 = sprintf( '%.6F sec.', $diff_time7 );

			
			$view_pagination = $post_param['view_pagination'];
			$count_post = $post_param['count_post'];
			$count = $count_post;

			
			$start_time8 = microtime( true );
			
			
			get_paginations($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start);
			
			$diff_time8 = microtime( true ) - $start_time8;
			$diff_time8 = sprintf( '%.6F sec.', $diff_time8 );

			
			if ($this_shortcodes_atts['time'] == 'true' && is_user_logged_in() ) { 
				$time_log = [$diff_time3, $diff_time4, $diff_time5,$diff_time7, $diff_time8];
				
			} else {
				$time_log = '';
			}
			$trueTaxName = get_label_taxonomy();
			echo(
				'<script type="text/javascript">
				window["'.$mId.'"] = {};
				window["'.$mId.'"]["columns"] = '.json_encode($int_columns).';
				window["'.$mId.'"]["rows"] = '.json_encode($new_rows).';
				window["'.$mId.'"]["editTags"] = '.json_encode($this_shortcodes_atts['edit'] ).';
				window["'.$mId.'"]["responseTaxonomiesCount"] = '.json_encode($count_of_terms_taxonomies).';
				window["'.$mId.'"]["timeLog"] = '.json_encode( $time_log ).';
				window["'.$mId.'"]["isLoggedIn"] = '.json_encode( is_user_logged_in() ).';
				window["'.$mId.'"]["trueTaxName"] = '.json_encode( $trueTaxName ).';				
				window["'.$mId.'"]["serverHa"] = '.json_encode($server_ha).';
				window["'.$mId.'"]["taxonomyLengthServer"] = '.json_encode($taxonomy_length).';
				window["'.$mId.'"]["taxonomyLength"] = '.json_encode($taxonomy_length).';
				window["'.$mId.'"]["clearAllFilters"] = '.json_encode($clear_all_filters).';
				window["'.$mId.'"]["axisArray"] = '.json_encode($axis_array).';
				window["'.$mId.'"]["multiaxisCountPosts"] = '.$count_post.';</script>'
			);//new_fix
			
			
				
			
		} else
		{	
			
			
			$order = $_POST['order'];
						
			$sortArr = transferSorting($_POST['orderBy']);	
			$order_by = $sortArr['orderby'];
			$meta_key = $sortArr['metakey'];
			
			$and_checkbox = $this_shortcodes_atts['technologiesoperator'];
			$edit = $this_shortcodes_atts['edit'];
			$new_selection_array = get_selection_params($this_shortcodes_atts['selection']);
			$tax_query = make_tax_query_for_selection($new_selection_array, $and_checkbox);
			
			$int_columns = (int)$this_shortcodes_atts['columns'];
			$page = $_POST['page'];
			$per_page = -1;
			$start = $page * $per_page;		
										
			$new_tags_array = get_selection_params_for_selected_tag($this_shortcodes_atts['selection']);							

			$new_selection_array_1 = convert_name_and_id_to_id_term($this_shortcodes_atts['selection']);
			if ($and_checkbox == 'AND') {
				$and_checkbox_shortcode = true;
			} else {
				$and_checkbox_shortcode = false;
			}
			
				$new_columns = $this_shortcodes_atts['columns'];	
				$new_rows = $this_shortcodes_atts['rows'];
				$per_page = (int)$new_columns*(int)$new_rows;
				$cur_page = $page;	
				$page -= 1;
				$start = $page * $per_page;	
				


			$new_columns = transferAttribute($this_shortcodes_atts['columns']);	
			
			$post_param = get_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start);	
			$view_pagination = $post_param['view_pagination'];
			
			$count_post = $post_param['count_post'];
			$count = $count_post;
			get_paginations($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start);
			echo( '<script type="text/javascript"> window["'.$mId.'"] = {};
				window["'.$mId.'"]["multiaxisCountPosts"] = '.$count_post.';</script>' ); //new_fix
			

		} 			
	}
	$diff_time1 = microtime( true ) - $start_time1;
	$diff_time1 = sprintf( '%.6F sec.', $diff_time1 );

    wp_die();
}

function get_shortcode_on_page($post_id, $shortcode) {
	$content = get_post_field('post_content', $post_id);
  
	if ($content) {
		$shortcode_regex = '/\['.$shortcode.'\s.*?]/';
		preg_match_all($shortcode_regex, $content, $shortcodes);
		if (!empty($shortcodes[0])) {
			$final_array = [];
			foreach ($shortcodes[0] as $s) {
				$attributes = eri_shortcode_parse_atts( $s );
				$final_array[] = $attributes;
			}
			return $final_array;
		}
	}
  
	return [];
  }

function eri_shortcode_parse_atts( $shortcode ) {
    // Store the shortcode attributes in an array here
    $attributes = [];

    // Get all attributes
    if (preg_match_all('/\w+\=\".*?\"/', $shortcode, $key_value_pairs)) {

        // Now split up the key value pairs
        foreach($key_value_pairs[0] as $kvp) {
            $kvp = str_replace('"', '', $kvp);
            $pair = explode('=', $kvp);
            $attributes[$pair[0]] = $pair[1];
        }
    }

    // Return the array
    return $attributes;
}


function trim_title_words($count, $after) {
		$title = get_the_title();
		$words = explode(' ', $title);
		if (count($words) > $count) {
			array_splice($words, $count);
			$title = implode(' ', $words);
		}
		else $after = '';
		echo $title . $after;
	}

	function count_blog_post($order_by, $order, $meta_key, $tax_query){
		$count_blog_posts = new WP_Query(
			array(
					'post_type'         => 'Portfolio',			
					'post_status'       => 'publish',
					'posts_per_page'    => -1,
					'tax_query'			=> $tax_query,
					'meta_key' 			=> $meta_key,
					'orderby'           => $order_by,
					'order'             => $order,
					'fields'            => 'ids', // вернет массив с ID постов
					'no_found_rows'     => true   // не подсчитывать количество найденных строк
			)
		);
	
		$count_post = $count_blog_posts->post_count;
	
		return $count_post;
	}

function get_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start){
?> 		
		
		<div id="portfolio-wrapperss" class="pf-col4 isotope <?php echo $new_columns; ?>-new">
		
		<?php 
	 	if (count($tax_query) > 1) {
			$all_blog_posts = new WP_Query(
				array(

						'post_type'         => 'Portfolio',			
						'post_status'       => 'publish',
						'orderby'           => array( $order_by => $order, 'title' => $order /* 'ASC' */ ),
						'meta_key' 			=> $meta_key,
						'posts_per_page'    => $per_page,
						'offset'            => $start,
						'tax_query'			=> $tax_query,
						'meta_type' => 'NUMERIC',
				)
			);
			
			
		} else {
			$all_blog_posts = new WP_Query(
				array(

						'post_type'         => 'Portfolio',			
						'post_status'       => 'publish',
						'orderby'           => array( $order_by => $order, 'title' => 'ASC' ),
						'meta_key' 			=> $meta_key,
						'meta_type' 		=> 'NUMERIC',
						'offset'            => $start,
						'posts_per_page'    => $per_page,

						
				)
			);
		}
		/*echo $int_columns.'<br>';
		echo $new_columns.'<br>';
		echo $order_by.'<br>';																											
		echo $order.'<br>';																											 
		echo $meta_key.'<br>';																											 
		echo $per_page.'<br>';																											 
		echo $start.'<br>';																											 
		echo '<br><pre>'; var_dump($tax_query); echo '</pre>';*/																												 
		?>
		<!-- Script for -->
		<?php
        if ( $all_blog_posts->have_posts() ) {
		$post_param = [];
		$view_pagination = true;			
					while ( $all_blog_posts->have_posts() ) 
					{
						$all_blog_posts->the_post(); ?>	
						<?php //get_template_part( 'multiaxis-tpl/multiaxis-heading-tpl-hover' ); 
						require( dirname(__FILE__).'/multiaxis-tpl/multiaxis-heading-tpl-hover-grid.php'); ?>												
					<?php
					//array_push($post_param['title_post'], get_the_title() );	
				}
		$count_post = count_blog_post($order_by, $order, $meta_key, $tax_query);
		} else {
			$count_post = 0;
			$view_pagination = false;
		?>
			
			<div class="search-not-found">
				<h3>According to your query, nothing was found. Try to specify other parameters</h3>
			</div>
			
			<?php 
		}

		
		$post_param['view_pagination'] = $view_pagination;
		$post_param['count_post'] = $count_post;
		wp_reset_postdata();
		?>
		</div>

		<?
		
		return $post_param;
}



function get_paginations($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start){
		
		$previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        
		$left_btn = '&#60;&#60;';
		$pre = 0;
		
		if ($int_columns != '1') {
			$pagination_class ='sixteen columns';
		} else {
			$pagination_class ='no-pagination-container';
			} 
		
	
	    // This is where the magic happens
        $no_of_paginations = ceil($count / $per_page);
        if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3)
                $end_loop = $cur_page + 3;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7) {
				if ( $int_columns != '0') {
				$end_loop = 7; } else {$end_loop = 2;}
			}
            else
                $end_loop = $no_of_paginations;
        }
        // Pagination Buttons logic    
		if ($view_pagination == true) {
        ?>
		
        <div class="text-center multiaxis-pagination <?php //echo $container_class; ?>">
		<div class="<?php //echo $pagination_class; ?>">
		
            <ul>
             <?php
         if ($first_btn && $cur_page > 1) { ?>
             <li data-pag="1" class="active"><?php echo $left_btn;?></li>
         <?php
         } else if ($first_btn) { ?>
             <li data-pag="1" class="inactive"><?php echo $left_btn;?></li>
         <?php
      }
         if ($previous_btn && $cur_page > 1) {
             $pre = $cur_page - 1; ?>
             <li data-pag="<?php echo $pre; ?>" class='active'>&#60;</li>
         <?php
         } else if ($previous_btn) { ?>
             <li class="inactive">&#60;</li>
         <?php
      }
         for ($i = $start_loop; $i <= $end_loop; $i++) {
             if ($cur_page == $i){ ?>
                 <li data-pag='<?php echo $i; ?>' class = 'selected' ><?php echo $i; ?></li>
             <?php
          }else{ ?>
                 <li data-pag='<?php echo $i; ?>' class='active'><?php echo $i; ?></li>
                 <?php
             }
         }
         if ($next_btn && $cur_page < $no_of_paginations) {
             $nex = $cur_page + 1; ?>
             <li data-pag='<?php echo $nex; ?>' class='active'>></li>
         <?php
      } else if ($next_btn) { ?>
             <li class='inactive'>></li>
         <?php 
      }
 
         if ($last_btn && $cur_page < $no_of_paginations) { ?>
             <li data-pag='<?php echo $no_of_paginations; ?>' class='active'>>></li>
         <?php 
      } else if ($last_btn) { ?>
             <li data-pag='<?php echo $no_of_paginations; ?>' class='inactive'>>></li>
         <?php 
      } ?>
            </ul>
        </div>
		</div>
		
    <?php 
		/*echo '$no_of_paginations '.$no_of_paginations.'<br>';
		echo '$start_loop '.$start_loop.'<br>';
		echo '$end_loop '.$end_loop.'<br>';
		echo '$nex '.$nex.'<br>';
		echo '$last_btn '.$last_btn.'<br>';
		echo '$page '.$page.'<br>';		
		echo '$per_page '.$per_page.'<br>';		
		echo '$count '.$count.'<br>';		
		echo '$view_pagination '.$view_pagination.'<br>';		
		echo '$count '.$count.'<br>';		
		echo '$start '.$start.'<br>';
		echo '$cur_page '.$cur_page.'<br>';
		echo '$int_columns '.$int_columns.'<br>';
		echo 'left_btn < <<'.$left_btn;*/
	}
}



function wp_get_postcount($taxonomy_name) {
	
		$args = array(
			'post_type'     => 'portfolio',
			'post_status'   => 'publish', 
			'posts_per_page' => -1,			
			'tax_query' => array(		
				array(
				'taxonomy' => $taxonomy_name, 
				'field' => 'id',
				'terms' => $taxonomy_name
				)
			)
		);
	
		$query = new WP_Query($args);						
		$d = (int)$query->post_count;
		return $d;
}

/*function wp_get_postcount_new($taxonomy_name) {
	
		$args = array(
			'post_type'     => 'portfolio',
			'post_status'   => 'publish', 
			'posts_per_page' => -1	
		);
	
		$query = new WP_Query($args);	

		if ( $query->have_posts() ) {
			
			$count_post = 0;
			while ( $query->have_posts() ) 
				{
					$query->the_post();
					$count_post++;	
				}
			
		} else {}
		wp_reset_postdata();
		$d = $count_post++;
		return $d;
}*/

function wp_get_postcount_new($taxonomy_name) {
	$terms = get_terms([
					'taxonomy' => $taxonomy_name,
					'hide_empty' => true,
					] );						

	$all_terms = [];
	foreach ($terms as $term) {		
		array_push($all_terms, $term->term_id);		
	}
    //return $count;
    $args = array(
      'post_type'     => 'portfolio', //post type, I used 'product'
      'post_status'   => 'publish', // just tried to find all published post
      'posts_per_page' => -1,  //show all
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => $taxonomy_name,  //taxonomy name  here, I used 'product_cat'
          'field' => 'id',
          'terms' => $all_terms,
        )
      )
    );
    $query = new WP_Query( $args);	
	$d = (int)$query->post_count;
	return $d;
}	



 /* Выводит категории, помещённые наверх в виде вложенного списка  */
 
function tree($parent, $i = 1, $name_taxonomy = 'technologies') {
    $terms = get_terms([
		'taxonomy' => $name_taxonomy,
        'hide_empty' => true,
        'parent' => $parent
    ]);
	if ( !empty($terms)) { 		
        $icons = [
            'plus' => '<b class="glyphicon glyphicon-plus" aria-hidden="true"></b>',
            'right' => '<i class="glyphicon glyphicon-triangle-right" aria-hidden="true"></i>',
        ];		
		?>	
			<ul class="ul-children-technologies ul-children-technologies-<?php echo $i;?>" <?php echo $parent ? '' : ' id="term-tree-widget"'; ?>>
				
				<?php $i++; $c = 0; $count_term_exp = count_in_terms_expand($terms); 
				
				foreach ($terms as $term){ ?>
					<?php
					$icon = $icons['right'];
					$checkTaxonomy = $term->taxonomy == 'technologies';
					if (getHasChildren($term->term_id, $name_taxonomy)) {
						$icon = $icons['plus'];
					}
					$checkChildren = empty(get_term_children( $term->term_id, $term->taxonomy ));
					$checkChildrenCount = children_count_terms( $term->term_id, $term->taxonomy ) == false;				

					if ($checkTaxonomy && $i < 2 && !$checkChildren && $checkChildrenCount) { 
						$check_mark = '<span class="check-mark check-mark-'.$i.'" data-depth="'.$i.'"> <img src="'.plugins_url( '/images/down-nd.svg',  __FILE__).'" data-position="closed"> </span>';
					} 					
					elseif ($checkTaxonomy && $i <= 3 && !$checkChildren && $checkChildrenCount) {
						$check_mark = '<span class="check-mark check-mark-'.$i.'" data-depth="'.$i.'"> <img class="img-check-mark-right" src="'.plugins_url( '/images/down-nd.svg',  __FILE__).'" data-position="closed"><span class="check-mark-hover">Expand</span></span>';
					}					
					else {
						$check_mark = '';
					}
					$cat_id = $term->term_id;				

					$image_id = get_term_meta($cat_id, 'id-cat-images', true);

					if ( !empty($image_id)  && wp_get_attachment_url( $image_id ) != '' ) { 
						if (wp_get_attachment_image($image_id) != null) {
							//$params = [ 'loading' => 'eager' ];
							$term_image = wp_get_attachment_image ($image_id, 'full', false);  
						} else {
							$image_url = wp_get_attachment_url( $image_id );
							$term_image = '<img loading="lazy" class="multiaxis-icon multiaxis-icon-'.$image_id.'" src="'.$image_url.'">';
						}						
							
					} else {
						$term_image = '';
					}		
					
					$on_checkbox = get_term_meta($cat_id, 'on_checkbox', true);
					if ($on_checkbox != 'checked'){
					$checkbox_no_class = '';
					$input = '<input autocomplete="off" type="checkbox" data-checked="0" data-originality="false" data-term-name="'.$term->name.'" data-taxonomy-name-id="'.$term->taxonomy.'" data-term-id="'.$term->term_id.'" class="checkbox-data-term checkbox-children-'.$term->taxonomy.' checkbox-children-'.$term->taxonomy.'-'.$i.'"><span class="input-hover"></span>';
					//'<input type="checkbox" data-depth="'.$depth.'" data-originality="true" data-term-name="'.$item->name.'" data-taxonomy-name-id="'.$item->taxonomy.'" data-term-id="'.esc_attr( $item->term_id).'" class="checkbox-data-term checkbox-children-'.$item->taxonomy.' checkbox-children-'.$item->taxonomy.'-'.$depth.'">';
					} elseif ($on_checkbox == 'checked') {
						$input = '';
						$checkbox_no_class = 'no-checkbox-margin';
					}		
					if (getHasChildren($term->term_id, $name_taxonomy) == true){
						$data_position = 'data-position="closed"';
						$data_position_class = ' parent-class';
						$no_children_cat_class = '';
						$img_ofsset = '';
						
					}
					else {
						$data_position = '';
						$data_position_class = '';
						if (getThisChildren($terms, $name_taxonomy) > 0) {
							//$no_children_cat_class = 'no-children-category-class';
							$no_children_cat_class = '';
							$img_ofsset = '<span class="span-offset" data-depth="'.$depth.'"><img class="img-offset" src="https://'.$_SERVER['SERVER_NAME'].'/en/wp-content/plugins/multiaxis/images/imgoffset.svg"></span>';
							$no_children_cat_parents_class = '';
						} else {
							
							if ($i > 2) {
								//$no_children_cat_class = 'no-children-category-class';
								$no_children_cat_class = '';
								$img_ofsset = '<span class="span-offset" data-depth="'.$depth.'"><img class="img-offset" src="https://'.$_SERVER['SERVER_NAME'].'/en/wp-content/plugins/multiaxis/images/imgoffset.svg"></span>';
								$no_children_cat_parents_class = '';
							} else 
							{
								$no_children_cat_class = '';
								$img_ofsset = '';
								$no_children_cat_parents_class = ' no-children-cat-parents-class';
							}
							//$no_children_cat_class = '';
						}
					}
					
					$this_terms = get_terms([
								'taxonomy' => $name_taxonomy,
								'hide_empty' => true,
								'parent' => $term->term_id,
							]);
					if ($checkTaxonomy && getThisChildren($this_terms, $name_taxonomy) > 0) {							
						if ($i > 1) {
							$expand_all = '<span class="expand-children expand-children-'.$i.'" data-depth="'.$i.'" data-expand="closed">Expand all <span class="expand-children-arrow-icon"></span></span>';
						} else { 
							$expand_all = '<span class="expand-children expand-children-'.$i.'" data-depth="'.$i.'" data-expand="closed"><span class="expand-children-light-arrow-icon"><span class="expand-children-light-arrow-icon-hover">Expand all</span></span></span>';
						}						
					} else {
							$expand_all = '';
					}				
					
					?>
					<li class="li-children-technologies li-children-technologies-<?php echo $i; ?> <?php echo $no_children_cat_class;?><?php echo $no_children_cat_parents_class; ?>">
						<span class="all_filters all_filters_<?php echo $i; echo $data_position_class;?> technologies" <?php echo $data_position; ?>><?php echo $img_ofsset; echo $check_mark;?>					
							<span class="portfolio-filter-name portfolio-filter-name_<?php echo $i; ?> ">
								<span class="item_filters item-filters-<?php echo $i; ?>">
									<?php echo $input;?>
								</span>
								<span class="item_filters_names">
									<span class="name-terms <?php echo $checkbox_no_class;?>">
										<?php echo $term_image ?>
										<span class="item_term_name"><?php echo $term->name; ?>&nbsp;<span class="ma-nobr">(<span class="count-terms count-terms-<?php echo $term->term_id;?>" data-item-taxonomies="technologies" data-item-count="<?php echo $term->term_id; ?>"><?php //echo $term->count;?><span class="count-terms-hover">The number of projects according to choice on other axes and to the option</span></span><span class="count-terms-slash">/</span><span class="count-terms-const"><?php echo $term->count; ?>)<span class="count-terms-const-hover">The total number of projects relevant to the option</span></span>									
												<span class="description-star description-star-<?php echo $term->term_id;?>"> *
													<span class="description-star-hover">According to your query, nothing was found. Try to specify other parameters</span>
												</span>
											</span>
											<span class="ma-nobr ma-right"><?php echo $expand_all;?></span>									
										</span>
									</span>		
	
								</span>
							</span>
													
						</span>
						<?php tree($term->term_id, $i); ?>
					</li>
				<?php $c++; } ?>
			</ul>		
        <?php
    }
}

/*
 * Возвращает true, если у категории блога есть дочерние категории
 */
function getHasChildren($id, $name_taxonomy) {
    $terms = get_terms([
		'taxonomy' => $name_taxonomy,
        'hide_empty' => true,
        'parent' => $id
    ]);
    return !empty($terms);
}	

function getThisChildren($terms, $name_taxonomy){
	$k = 0;
	foreach ($terms as $term )
	{
		if (getHasChildren($term->term_id, $name_taxonomy) == true){
			$k++;
		}
	}
	return $k;
}


function count_in_terms_expand($terms){
	
	$count = 0;
	$count_array = count($terms);
	
	foreach ($terms as $term){
		
		$term_id = $term->term_id;
		
		if (get_term_meta($term_id, 'on_checkbox', true) == 'checked'){
			
			$count++;
			
		}
		
	}
	
	$new_count = $count_array - $count;
	
	return $new_count;
	
}
function transferSorting($data){
	$new_sorting = [];
	if ($data == 'date') {
		$new_sorting['orderby'] = 'ID';
		$new_sorting['metakey'] = '';	
	} elseif ($data == 'default' || $data == 'featured') {
		$new_sorting['orderby'] = 'meta_value_num';
		$new_sorting['metakey'] = 'weights';	
	}	
	else {		
		$new_sorting['orderby'] = 'meta_value_num';
		$new_sorting['metakey'] = $data;	
	}

	return $new_sorting;
}

function transferAttribute($columns){
	
	switch ($columns) {
		case '3':
			$new_columns = 'ma-three';
		break;
		case '4':
			$new_columns = 'ma-four';
		break;
		case '2':
			$new_columns = 'ma-two';
		break;
		case '6':
			$new_columns = 'ma-six';
		break;
		case '5':
			$new_columns = 'ma-five';
		break;
		case '1':
			$new_columns = 'ma-one';
		break;
	}
	return $new_columns;
}

function trim_value($array)
{	$new_array = [];
	foreach ($array as $value){
		array_push($new_array, trim($value));
	}
	return $new_array;
}

function get_selection_params($selection_data){
	
	$selection_data = str_replace('&amp;', '&', $selection_data);
	$selection_array = [];
	$middle_selection_array = [];
	$pre_terms_array = [];
	$terms_array = [];
	$new_selection_array = [];
	$selection = $selection_data;
	$selection_array = explode(";", $selection);
	
	foreach ($selection_array as $keys){
		if ($keys != '' && $keys != ' '){		
			$middle_selection_array = [];
			$middle_selection_array = explode(":", $keys) ?? [];
			$pre_terms_array = explode("+", $middle_selection_array[1]);
			$new_terms_array = [];
			foreach ($pre_terms_array as $pre_terms){
				$terms_array = explode("{", $pre_terms);
				array_push($new_terms_array, $terms_array[0]);
			}			
			$new_selection_array[ trim( $middle_selection_array[0] ) ] = trim_value($new_terms_array);
		}		
	}
	return $new_selection_array;	
}

function is_public_taxonomy($taxonomy){
	return get_taxonomy($taxonomy)->public;	
}



function get_selection_params_for_selected_tag($selection_data){

	$selection_data = str_replace('&amp;', '&', $selection_data);	
	$selection_str = '';
	$selection_array = [];
	$middle_selection_array = [];
	$tax_name = [];
	$terms_array = [];	
	$new_selection_array = [];
	$selection = $selection_data;
	$selection_array = explode(";", $selection);
	$selection_array = array_diff($selection_array, array(''));
	foreach ($selection_array as $keys){
				
		if ($keys != '' && $keys != ' '){
			$middle_selection_array = explode(":", trim($keys));			
			$terms_array = [];
			$tax_name = $middle_selection_array[0];
			$terms_array = $middle_selection_array[1];
			$terms_array = explode("+", $terms_array);
			$new_terms_array = [];
			$new_term_info = [];	
			foreach ($terms_array as $term_info){
				
				$new_term_info = explode('{', $term_info);
				$term_name = $new_term_info[0];
				
				if (isset($new_term_info[1])){
					$term_id = substr(trim($new_term_info[1]),0, -1);
				} else {
					$term_id = (string)get_term_by( 'name', $term_name, $tax_name)->term_id;
				}
				$new_terms_array[] = [trim($term_name), $term_id];
			}
			if (is_user_logged_in() || is_public_taxonomy($middle_selection_array[0]) == '1' ){
				$new_selection_array[$middle_selection_array[0]] = $new_terms_array;		
			}			
		}
	}
	return $new_selection_array;
}

function get_label_taxonomy(){
	
	$trueName = [];
	
	if ( is_user_logged_in()  ) {
					
		$args_tax = array(						
			'_builtin' => false,
			'object_type' => array('portfolio'),
		);
	} else {					
		$args_tax = array(
		'public' => true,
		'_builtin' => false,
		'object_type' => array('portfolio'),
		);
					
	}				
	$portfolio_taxonomy_names = get_taxonomies( $args_tax, 'objects', 'and' );
	
	foreach ( $portfolio_taxonomy_names as $portfolio_taxonomy_name ) {
		if ($portfolio_taxonomy_name->label == 'Filters') {$portfolio_taxonomy_name->label = 'Industries';}
		$trueName[$portfolio_taxonomy_name->name] = $portfolio_taxonomy_name->label;
	}
	
	return $trueName;
	
}

function convert_name_and_id_to_id_term($taxonomies_object, $hide = true){
	$new_taxonomies_object = [];
	$tax_name = [];
	$selection_data = str_replace('&amp;', '&', $taxonomies_object);	
	$selection_str = '';
	$selection_array = [];
	$middle_selection_array = [];
	$terms_array = [];	
	$new_selection_array = [];
	$selection = $selection_data;
	$selection_array = explode(";", $selection_data);
	
	foreach ($selection_array as $keys){
		
		if ($keys != '' && $keys != ' '){
			$middle_selection_array = explode(":", trim($keys));			
			$terms_array = [];
			$tax_name = $middle_selection_array[0];
			$terms_array = $middle_selection_array[1];
			$terms_array = explode("+", $terms_array);
			$new_terms_array = [];
			$new_term_info = [];	
			foreach ($terms_array as $term_info){
				
				$new_term_info = explode('{', $term_info);
				$term_name = $new_term_info[0];		
				if (isset($new_term_info[1])){
					$term_id = substr(trim($new_term_info[1]),0, -1);
				} else {
					$term_id = (string)get_term_by( 'name', $term_name, $tax_name)->term_id;
				}
				$new_terms_array[] = $term_id;
			}
			if ( $hide == true){
				$new_selection_array[$middle_selection_array[0]] = $new_terms_array;
			} else {
				if (is_public_taxonomy($middle_selection_array[0]) == '1') {
					$new_selection_array[$middle_selection_array[0]] = $new_terms_array;
				}
			}			
		}
		
	}	
	
	return $new_selection_array;	
}

/* Не будет использоваться */
function convert_name_to_id_term($taxonomies_object){
	$new_taxonomies_object = [];
	$taxonomy_names = get_object_taxonomies( 'portfolio' , 'names' );
	$i = 0;
	foreach ($taxonomies_object as $tax_name => $term_obj){
		$tax_name = trim($tax_name);
		foreach ($term_obj as $term_name){
			$term = get_term_by( 'name', $term_name, $tax_name);
			$term_id = $term->term_id;
			if (isset($new_taxonomies_object[$tax_name])){
				array_push($new_taxonomies_object[$tax_name], $term_id);
			} else {
				$new_taxonomies_object[$tax_name] = [];
				array_push($new_taxonomies_object[$tax_name], $term_id);				
			}
		}		
	}	
	return $new_taxonomies_object;	
}

function convert_id_to_name_term($taxonomies_object){
	$new_taxonomies_object = [];	
	$taxonomy_names = get_object_taxonomies( 'portfolio' , 'names' );
	$t_array = [];
	foreach ($taxonomy_names as $taxonomy_name){		
		$new_taxonomies_object[$taxonomy_name] = $t_array;		
	}	
	foreach ($taxonomies_object as $tax_name => $term_obj){

		foreach ($term_obj as $term_id){
			$term = get_term_by( 'id', $term_id, $tax_name);
			$term_name = $term->name;		
			
			$new_taxonomies_object[$tax_name][] = [$term_name, $term_id];
		}
		
	}	
	return $new_taxonomies_object;
}

function get_url_param_array($url_array){	
	
	$new_url_array = explode("&", $url_array[1]);
	$url_param_array = [];	
	foreach ($new_url_array as $new_url){
		$time_param = [];
		$time_param = explode("=", $new_url);
		$url_param_array[$time_param[0]] = $time_param[1]; 		
	}		
	return $url_param_array;	
}




/* ###### Shortcode  for pagination ######### */
function multiaxis_portfolio_shortcode($atts)
{	
	$atts = shortcode_atts([
		'showselectedoptions' =>'false',
		'selectotherprojects' => 'false',
		'showadditionalaxis' => 'false',
		'columns' => '4',
		'rows' => '2',
		'mode' => 'minimal',
		'selection' => '',
		'technologiesoperator' => 'OR',
		'orderby'=>'weights',
		'order'=>'DESC',
		//'meta_key'=>'weights',
		'edit' => 'false',
		'page' => 1,
		'time' => 'false',
		'multiaxis_id' => '',
	], $atts);	//new_fix
    ob_start();   
    multiaxis_portfolio_function($atts);
	return ob_get_clean();  
}
add_shortcode('multiaxis_portfolio', 'multiaxis_portfolio_shortcode');


function get_order($url_order, $shortcode_order){
	
	if ( isset( $url_order ) ) {
		if ( $url_order == 'DESC' ) {
			$get_order = '<div class="multiaxis-order multiaxis-order-down" data-order="DESC"></div>';
		} else { 
			$get_order = '<div class="multiaxis-order multiaxis-order-up" data-order="ASC"></div>'; 
		}				
	} else { 
		if ( $shortcode_order == 'DESC' ) {
			$get_order = '<div class="multiaxis-order multiaxis-order-down" data-order="DESC"></div>';
		} else {
			$get_order = '<div class="multiaxis-order multiaxis-order-up" data-order="ASC"></div>'; 
		}
	}
	
	return $get_order;
	
}

function get_order_by($url_order_by, $shortcode_order_by){ 
	$sorting_array = ['featured', 'date', 'duration', 'year', 'team_size'];
														  
	if ( isset( $url_order_by ) ) {
		$this_order_by = $url_order_by;
	} else {
		$this_order_by = $shortcode_order_by;		
	}
	
	$output = '<select class="select-sort" name="multiaxis-select-sort">'; //new fix
	foreach ( $sorting_array as $sorting_el) {
		if ( $sorting_el == $this_order_by ){			
			$selected = 'selected';
		} else {
			$selected = '';
		}
		
		$sorting_el_new = ucfirst( str_replace('_', ' ', $sorting_el) );
		
		if (is_user_logged_in() && $sorting_el == 'featured' ) {
			$sorting_el_new = 'Default (Weight)';
		}
			 
		$output .= '<option data-orderby="'.$sorting_el.'" '.$selected.'>'.$sorting_el_new.'</option>';
	}
	$output .= '</select>';													  
	
	return $output;
} 

// Details & short link START

function explode_multiaxis_url($url){
	$url_array = explode("?" , $url);
	$url_raw = get_url_param_array($url_array);
	$id = isset($url_raw["id"]) ? $url_raw["id"] : '';
	$url_to_parse = $id ? get_link_by_short_key($url_raw['id']) : $url;	

	return array('url_to_parse' => $url_to_parse, 'id' => $id);
}

function get_dynamic_description($is_current_multiaxis) {//new_fix
  $details = $_GET['desc'];
  $shortLink = $_GET['id'];

  if ( ($details || $shortLink) && $is_current_multiaxis ) { //new_fix
	  global $wpdb;

	  if(!$details && $shortLink){
		$query = 'SELECT long_url FROM ' .$wpdb->prefix. 'multiaxis_short_links WHERE short_key = %s LIMIT 1';
		$long_url = $wpdb->get_var($wpdb->prepare($query, $shortLink));
		$pattern = '/[?&]desc=([^&]+)/';
		  
		if ($long_url !== null && preg_match($pattern, $long_url, $matches)) {
			$details = $matches[1];
		}
	  }
	  

	  $query = 'SELECT description FROM ' .$wpdb->prefix. 'multiaxis_details WHERE hash = %s LIMIT 1';
	  $description = $wpdb->get_var($wpdb->prepare($query, $details));

	   if($description){
		  $desc = str_replace('\\', '', $description);
		   
		  $lines = explode("\n", $desc);

		  // Wrap each line in <p> tag
		  foreach ($lines as &$line) {
			  $line = '<p class="dynamic-description--row">' . $line . '</p>';
		  }

		  // Join the lines back into the description
		  $desc = implode("\n", $lines);
		   
		  return '<div class="dynamic-description-custom">' . $desc .'</div>';
	  }
  }

  return '';
}

function to_save_transfer($text, $symbol = '`') {
    return str_replace($symbol, '\\'.$symbol, $text);
}



// Featured tags::DEV
function render_featured_tags_tab($parent, $name_taxonomy = 'technologies'){
	$terms = get_terms([
		 'taxonomy' => $name_taxonomy,
		 'hide_empty' => true,
		 'parent' => $parent,
 ]);
 

 usort($terms, function($f, $s) {
	 return $f->term_id <=> $s->term_id;
 });

?>


<div class="modern-featured-tags">
<div class="modern-featured-tags__tags-list">
 <?php
	 foreach ($terms as &$term) {
		 ?> 
<div class="modern-featured-tags__tag" data-tag-id="<?= $term -> term_id;?>">
<div class="modern-featured-tags__tag-inner">
<input autocomplete="off" type="checkbox" data-checked="0" data-originality="true" data-taxonomy-public="false" data-term-name="<?= $term->name; ?>" data-taxonomy-name-id="<?= $name_taxonomy; ?>" data-term-id="<?= $term -> term_id;?>" class="modern-featured-tags__tag-checkbox checkbox-data-term checkbox-children-<?= $name_taxonomy; ?> checkbox-children-<?= $name_taxonomy; ?>-0">
	 <div class="modern-featured-tags__tag-info">
		<span class="modern-featured-tags__tag-name"><?= $term->name; ?> (<?=$term->count;?>)
		<span class="modern-featured-tags__tag-toggle-dropdown">
		<div class="modern-featured-tags__related-projects modern-related-projects modern-featured-tags__related-projects--dark">
		 <div class="modern-related-projects__content">
			 <div class="modern-related-projects__slot">
				 <div class="modern-related-projects__skeleton">
					 <div class="modern-related-projects__skeleton-box-small"></div>
					 <div class="modern-related-projects__skeleton-box-medium"></div>
				 </div>
			 </div>
			 <button class="modern-related-projects__delete-tag">Delete Tag</button>
		 </div>
	 </div>
		</span>
	</span>

	 </div>
</div>

 </div>
		 <?php
 }
 ?>
</div>
<div class="modern-featured-tags__info">
 <div class="modern-featured-tags__cart">
	 <div class="modern-featured-tags__cart-dropdown">
	 <div class="modern-featured-tags__cart-slot">
		 <p class="modern-featured-tags__cart-empty">Your cart is empty</p>
	 </div>
	 <div class="modern-featured-tags__cart-form">
	 	<form class="modern-featured-tags__cart-controls">
		 	<input class="modern-featured-tags__tag-field" type="text" placeholder="Enter tag name"/>
		 	<button class="modern-featured-tags__cart-submit" type="submit">Create Tag</button>
	 	</form>
		 <span class="modern-featured-tags__clear-cart-container">You can also <button class="modern-featured-tags__clear-cart">clear</button> your cart</span>
		<span class="modern-featured-tags__error"></span>
	 </div>
	 </div>
	 <button class="modern-featured-tags__cart-toggle modern-featured-tags__cart-toggle--hidden">Selection cart (<span class="modern-featured-tags__cart-toggle-amount">empty</span>)</button>
 </div>
</div>
</div>

<?php
}
// Featured tags::DEV END


// customer

function isCEO(){

// 	return is_user_logged_in() && get_userlogin() == 'TestRefactoring';
	return is_user_logged_in() && get_userlogin() == 'CEO';
}

// customer end

// Details $ Short link End

function multiaxis_portfolio_function($atts){	

	$post_id = get_the_ID();		
	$new_columns = $atts['columns'];	
	$new_rows = $atts['rows'];
	$url = $_SERVER['REQUEST_URI'];

	$url = str_replace('&amp;','&',$url);
	$url = urldecode($url);

		/*$atts = ['showselectedoptions' =>'true',
		'selectotherprojects' => 'true',
		'showadditionalaxis' => 'true',
		'columns' => '4',
		'rows' => '2',
		'mode' => 'full',
		'selection' => '',
		'technologiesoperator' => 'OR',
		'orderby'=>'weights',
		'order'=>'DESC',
		//'meta_key'=>'weights',
		'edit' => 'true',
		'page' => 1,
		'time' => 'false',
		'multiaxis_id' => '',];	//new_fix
		$atts['multiaxis_id'] = 'jt01aj';*/

	$exploded_url = explode_multiaxis_url($url);

	$url_array = explode("?" , $exploded_url['url_to_parse']);
	$url_param_array = get_url_param_array($url_array);
	$is_current_multiaxis = $url_param_array['multiaxisId'] == $atts['multiaxis_id'];//new_fix
	
	$request_details = $url_param_array['desc'];
	$details = ( $request_details) ? get_details_by_hash($request_details) : array('title' => '', 'description' =>'', 'hash' =>'', 'compact_view' => '0');

	$details['id'] = $exploded_url['id'];


			$defaults = array(
				'pages' => $atts['page'],
				'order' => $atts['order'],
				'orderBy' => $atts['orderby'],
				'technologiesOperator' => $atts['technologiesOperator'],
			);//new_fix
	
	foreach ($defaults as $key => $value) {
			if (!isset($url_param_array[$key])) {
					$url_param_array[$key] = $value;
			}
	}

	$url_param_array['selection'] = str_replace( '%22', '"',$url_param_array['selection'] );
	$new_selection_array = get_selection_params($atts['selection']);


  $selection_no_hidden = $new_selection_array;
  unset($selection_no_hidden['country']);
  unset($selection_no_hidden['featured-tags']);//featured_tags	
  unset($array_count['customer']);//customer

	$buffer_selection = json_decode(str_replace('%22', '"', $url_param_array['selection']), true);
	if($url_param_array['ha'] && is_user_logged_in()){
		$hidden_axis_array = get_hidden_axis_array($url_param_array['ha']);
		$buffer_selection = array_merge($buffer_selection, $hidden_axis_array);
	}
	
	

	$highlight_axis =  key($buffer_selection);
	
			//new_fix
			if ($atts['multiaxis_id'] != '') {
				$data_multiaxis_id = 'data-multiaxis-id="'.$atts['multiaxis_id'].'"';
				$multiaxis_id = $atts['multiaxis_id'];
			} else {
				$data_multiaxis_id = uniqid();
				$multiaxis_id = uniqid();
			}//new_fix
	
			if ( isset($url_param_array['multiaxisId']) && $is_current_multiaxis ) {
				$mId = $url_param_array['multiaxisId'];
			} else {
				$mId = $atts['multiaxis_id'];
			} //new_fix	

	$all_count = '<span class="double-count">Results: <span style="font-weight: 700;"><span class="multiaxis-count-posts">'.$published_posts = wp_count_posts('portfolio')->publish.'</span> projects</span></span>';
	
	//$is_compact_view = $details['compact_view'] === '1';
	
	$is_compact_view = $details['compact_view'] === '1' && $is_current_multiaxis; //new_fix
	
	$compact_view_attrs = $is_compact_view ? 'style="display:none;"' : '';

	$dynamic_description = get_dynamic_description($is_current_multiaxis);//new_fix
  //new_fix	

//echo get_multiaxis();	
  
?>
<div class="multiaxis multiaxis-id" id="multiaxis-id-<?php echo $multiaxis_id; ?>" <?php echo $data_multiaxis_id; ?>>
	<?php echo $dynamic_description; ?>
	<script>
				var newColumns = '<?php echo $new_columns; ?>';
				var newRows = '<?php echo $new_rows; ?>';
				var description = `<?php echo to_save_transfer($details['description']); ?>`;
				var title = `<?php echo to_save_transfer(get_save_text($details['title'])); ?>`;
				var hash =  `<?php echo $details['hash']; ?>`;
				var shortLink = `<?php echo $details['id']; ?>`;
				var compactView = <?php echo $is_compact_view ? 'true' : 'false'; ?>;
			</script>	
  <div class="multiaxis-panel-container">
  <div class="portfolio-taxonomy-container">
  <?php  //new_fix	
    if ($atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full' /*&& $atts['selection'] != ''*/ && !empty( $selection_no_hidden ) || /*isset($url_array[1])*/ !empty( json_decode($url_param_array['selection'], true ) ) && $multiaxis_id == $url_param_array['multiaxisId'] || !empty($url_param_array['ha']) && is_user_logged_in() || !empty( json_decode($url_param_array['selection'], true ) ) && !empty($url_param_array['ha']) ) { $portfolio_display_tag_style = 'style="display: flex;"'; }
    elseif  ( $atts['showselectedoptions'] == 'true' && $atts['mode'] == 'minimal' || $atts['selection'] == '' && $atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full' || !empty($url_param_array['ha']) && empty( json_decode($url_param_array['selection'], true ) ) ||  $atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full' && empty( $selection_no_hidden ) && !is_user_logged_in()  ) {$portfolio_display_tag_style = 'style="display: none;"'; } 
  ?>
  <?php if ($atts['showselectedoptions'] == 'false' && $atts['mode'] == 'minimal' && $atts['selectotherprojects'] == 'false' && $atts['showadditionalaxis'] == 'false' )  { } else {?>
		<div class="portfolio-tag-list-container" <?php echo $compact_view_attrs; ?> <?php echo $portfolio_display_tag_style ?>><div class="portfolio-tag-list-row"><div class="portfolio-tag-list"></div></div></div>
  <?php } ?>
  <?php 
    
    if ($atts['selectotherprojects'] == 'true' && $atts['mode'] == 'full' && $atts['edit'] == 'true') { $portfolio_display_style = 'style="display: block;"';}
    elseif ($atts['selectotherprojects'] == 'false' && $atts['mode'] == 'full' && $atts['edit'] == 'true' || $atts['selectotherprojects'] == 'true' && $atts['mode'] == 'minimal' && $atts['edit'] == 'true')  { $portfolio_display_style = 'style="display: none;"';} 
    
    if ($atts['selectotherprojects'] == 'true' && $atts['mode'] == 'full' && $atts['edit'] == 'true' || $atts['selectotherprojects'] == 'false' && $atts['mode'] == 'full' && $atts['edit'] == 'true' || $atts['selectotherprojects'] == 'true' && $atts['mode'] == 'minimal' && $atts['edit'] == 'true') {
    ?>

<div class="portfolio-axis-container" <?php echo $compact_view_attrs; ?> <?php echo $portfolio_display_style; ?> >
    <?php 

    $a = 0;
    $b = 0;
    
    if ( is_user_logged_in()  ) {
      
      $args_tax = array(						
        '_builtin' => false,
        'object_type' => array('portfolio'),
      );
    } else {					
      $args_tax = array(
        'public' => true,
        '_builtin' => false,
        'object_type' => array('portfolio'),
      );
      
    }				
    $output_tax = 'objects'; // или objects
    $operator_tax = 'and'; // 'and' или 'or'
    $portfolio_taxonomy_names = get_taxonomies( $args_tax, $output_tax, $operator_tax );

		// customers

		if(!isCEO()){
			unset($portfolio_taxonomy_names['customer']);
		}

		// customers end
    
    $default_max_axies = 4;
    $show_max_axies = $default_max_axies;
    if($highlight_axis){
      $inner_idx = 0;
      foreach($portfolio_taxonomy_names as $portfolio_taxonomy_name){
        $inner_idx += 1;
        if(($highlight_axis === $portfolio_taxonomy_name->name) && ($inner_idx > $show_max_axies)){
          $show_max_axies = PHP_INT_MAX; // SET TO MAXIMUM INT VALUE
          break;
        }
      }
    }

      echo '<div class="portfolio-taxonomy-name-list">';					
      foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name) {	
          if ($b > $show_max_axies - 1) {
            $hidden_class = 'portfolio-taxonomy-name-hidden';
          } else {
            $hidden_class = '';
          }
        
          if($b > $default_max_axies - 1){
            $marked_class = 'portfolio-taxonomy-name-marked';
          }else{
            $marked_class = '';
          }
          
          if ((!$highlight_axis && $b == 0) || ($highlight_axis && $highlight_axis == $portfolio_taxonomy_name->name)) {
            $active_class = 'item_filters_active';
          } else {
            $active_class = '';
          }
          
          $output =  '<div class="'.$active_class.' portfolio_taxonomy_name '.$hidden_class.' '.$marked_class.' portfolio-taxonomy-name-'.$portfolio_taxonomy_name->name.'" id="taxonomy-'.$portfolio_taxonomy_name->name.'" data-taxonomy-name-list="'.$portfolio_taxonomy_name->name.'">';
          if ($portfolio_taxonomy_name->name == 'filters') 
          {
            $portfolio_taxonomy_name->label = 'Industries';
          }

          $output .= str_replace('_', ' ', $portfolio_taxonomy_name->label);
          if ($portfolio_taxonomy_name->name == 'technologies')
          {
            $output .= '<span class="exclamation-icon"><span class="exclamation-icon-text"><span class="text-bold">By default, logical OR is used on Technologies axis</span>. That is, if you select two or more options, the filter will choose  projects that correspond to at least one of the selected options. 
You also have the option <span class="text-bold">to switch the logic from OR to AND</span> for those cases when you want to select projects that correspond to all selected options.</span></span>';
          }
          
          $output .= '</div>';
          
          echo $output;

          $b++;
      }
      
      
      $clear_all_filters = '<div class="portfolio-clear-all-filters-btn"  >Clear all filters<span class="closed-cross-icon"></span></div></div>';
      if ($atts['showadditionalaxis'] == 'true' && count($portfolio_taxonomy_names) > $default_max_axies - 1) {
        if($show_max_axies > $default_max_axies){
          echo '<div class="show-adding-axis-btn" data-show-adding-axis-btn="true"><span class="show-adding-axis-btn-left-icon"></span>Hide additional axes</div>'.$clear_all_filters;
        }else{
          echo '<div class="show-adding-axis-btn" data-show-adding-axis-btn="false">Show additional axes<span class="show-adding-axis-btn-right-icon"></span></div>'.$clear_all_filters;
        }
      } else {
        echo $clear_all_filters;
      }
      

      $start_time1 = microtime( true );	

      foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name) {
        
        
        if ((!$highlight_axis && $a == 0) || ($highlight_axis && $highlight_axis == $portfolio_taxonomy_name->name)) {
          $active_class = 'taxonomy-list-active';
        } else {
          $active_class = '';
        }
        if ($portfolio_taxonomy_name->name == 'technologies') {
            
          $and_checkbox = '<div class="switch"><input class="checkbox-switch" type="checkbox"><span class="switch-hover">
          <b>Logical OR:</b>  if you select two or more options, the filter will choose projects that correspond to at least one of the selected options. 
          <br><br>
          <b>Logical AND:</b> if you select two or more options, the filter will choose  projects that correspond to all selected options.
          </span></div>';
          $search_input = '<div class="technologies-search-container"><input class="technologies-search" placeholder="Search"><div class="technologies-search-result"></div></div>';	
          $all_collapse = '<div class="closed-all-categories-btn">Collapse all</div>';
        } else {
          $and_checkbox = '';
          $search_input = '';
          $all_collapse = '';
        };

            $all_item = 
            $and_checkbox.'<div class="all-count-taxonomy li-children-'.$portfolio_taxonomy_name->name.'">
            <span class="all_filters all_filters_0 filters" data-position="closed">
            <span class="portfolio-filter-name portfolio-filter-name_0 portfolio-filter-name-no-grid">
            <span class="item_filters item-filters-0">								
            </span>All ('.wp_get_postcount_new($portfolio_taxonomy_name->name).')  
            </span></span></div>'.$search_input.$all_count;	

          echo '<div class="taxonomy-list '.$active_class.'" data-taxonomy-name="taxonomy-'.$portfolio_taxonomy_name->name.'">';
          $portfolio_taxonomy_name_new = $portfolio_taxonomy_name->label;
          if ($portfolio_taxonomy_name_new == 'Filters' ) {
            $portfolio_taxonomy_name_new = 'Industries';
          }
          if ($portfolio_taxonomy_name_new == 'Servicestax' ) {
            $portfolio_taxonomy_name_new = 'Services';
          }
          

          echo $all_item.'<div class="clear-technologies-btn clear-taxonomy-'.$portfolio_taxonomy_name->name.'" data-clear-taxonomy-name="'.$portfolio_taxonomy_name->name.'">Clear '.str_replace('_', ' ', $portfolio_taxonomy_name_new).'<span class="closed-cross-icon"></span></div>'.$all_collapse;	
          echo '<div class="ul-list-taxonomy ul-list-taxonomy-'.$portfolio_taxonomy_name->name.'">';
          
          
          if ($portfolio_taxonomy_name->name == 'technologies') {
            
            
             $top_terms1 = get_sorting_top_technologies();
            
            ?>
            <div class="technologies-heading featured-technologies-heading">Featured technologies:</div>
            <div><ul class="top-technologies "> 
            <?php
            $q = 0;
            $i = 1;
            foreach ( $top_terms1 as $top_term ) { 								
            if ( $i < 2 && !empty( get_term_children( $top_term->term_id, $top_term->taxonomy) ) && children_count_terms( $top_term->term_id, $top_term->taxonomy ) == false) {
              $icon_path = plugins_url( '/images/down-nd.svg',  __FILE__);
              
              $check_mark = '<span class="check-mark check-mark-'.$i.'" data-depth="'.$i.'" ><img src="'.$icon_path.'" data-position="closed"><span class="check-mark-hover">Expand</span></span>';
            } 

            else {
              $check_mark = '';
            }
            $head_term = $top_term;	
            $this_terms = get_terms([
                'taxonomy' => $portfolio_taxonomy_name->name,
                'hide_empty' => true,
                'parent' => $head_term->term_id,
                ]);
              
            if ($portfolio_taxonomy_name->name == 'technologies' && getThisChildren($this_terms, $portfolio_taxonomy_name->name) > 0) { 
                                
              if ($i > 1) {
                $expand_all = '<span class="expand-children expand-children-'.$i.'" data-depth="'.$i.'" data-expand="closed">Expand all <span class="expand-children-arrow-icon"></span></span>';			
              } else {
                $expand_all = '<span class="expand-children expand-children-'.$i.'" data-depth="'.$i.'" data-expand="closed"><span class="expand-children-light-arrow-icon"><span class="expand-children-light-arrow-icon-hover">Expand all</span></span></span>';
              }
            } else {
              $expand_all = '';
            }
            ?>	
              <li class="li-children-technologies li-children-technologies-1">
                <?php 
                  
                  $image_id = get_term_meta($top_term->term_id, 'id-cat-images', true);
                    if ( !empty($image_id)  && wp_get_attachment_url( $image_id ) != '' ) {
                      
                      if (wp_get_attachment_image( $image_id) != null) {
						//$params = [ 'loading' => 'eager' ];
						$term_image = wp_get_attachment_image ($image_id, 'full', false );    
                        //$term_image = wp_get_attachment_image ( $image_id, 'full');  
                      } else {
                        $image_url = wp_get_attachment_url( $image_id );
                        $term_image = '<img loading="lazy" class="multiaxis-icon multiaxis-icon-'.$image_id.'" src="'.$image_url.'">';
                      }
                      
                      
                    } else {
                      $term_image = '';
                    }
                    $cat_id = $head_term->term_id;				
                    $on_checkbox = get_term_meta($cat_id, 'on_checkbox', true);
                    
                    if ($on_checkbox != 'checked')
                    {
                    $checkbox_no_class = '';
                    $input = '<input autocomplete="off" type="checkbox" data-checked="0" data-originality="false" data-term-name="'.$head_term->name.'" data-taxonomy-name-id="'.$head_term->taxonomy.'" data-term-id="'.$head_term->term_id.'" class="checkbox-data-term checkbox-children-'.$head_term->taxonomy.' checkbox-children-'.$head_term->taxonomy.'-'.$q.'"><span class="input-hover"></span>';
                    } elseif ($on_checkbox == 'checked') {
                      $input = '';
                      $checkbox_no_class = 'no-checkbox-margin';
                    }
                    $q++;
                    
                    if (getHasChildren($head_term->term_id, $top_term->taxonomy) == true){
                      $data_position = 'data-position="closed"';
                      $data_position_class = ' parent-class';
                      
                    }
                    else {
                      $data_position = '';
                      $data_position_class = '';
                    }
                ?>
                <span class="all_filters all_filters_1 technologies <?php echo $data_position_class; ?>" <?php echo $data_position; ?>>
                  <span class="portfolio-filter-name portfolio-filter-name_1">
                    <span class="item_filters item-filters-1">
                      <?php echo $input;?>
                    </span>
                    
                    <span class="item_filters_names">												
                    <span class="name-terms">
                    <?php echo $term_image; ?>
                      <span class="item_term_name"><?php echo $head_term->name; ?>
                        <span class="ma-nobr">(<span class="count-terms count-terms-<?php echo $head_term->term_id;?>" data-item-taxonomies="technologies" data-item-count="<?php echo $head_term->term_id;?>"><?php //echo $head_term->count; ?><span class="count-terms-hover">The number of projects according to choice on other axes and to the option</span></span><span class="count-terms-slash">/</span><span class="count-terms-const"><?php echo $head_term->count; ?>)<span class="count-terms-const-hover">The total number of projects relevant to the option</span></span>

                          <span class="description-star description-star-<?php echo $head_term->term_id;?>"> *
                            <span class="description-star-hover">According to your query, nothing was found. Try to specify other parameters</span>
                          </span>													
                        </span>		
                        <span class="ma-nobr ma-right"><?php echo $check_mark.$expand_all; ?></span>
                      </span>											
                      
                    </span>
                    </span>
                  </span>											
                </span>				
                <?php tree($top_term->term_id); ?>
              </li>										
              <?php } ?> 
            </ul>
            </div>
            <div class="technologies-heading">All technologies:</div>
            <?php } ?>							
          <div class="other-technologies <?php echo $portfolio_taxonomy_name->name;?>">
						<?php $portfolio_taxonomy_name->name === 'featured-tags' ? render_featured_tags_tab($portfolio_taxonomy_name->ID, $portfolio_taxonomy_name->name) : 		tree_new($portfolio_taxonomy_name->ID, $i = -1, $portfolio_taxonomy_name->name); //featured_tags ?>
          </div>
          <?php							
          echo '</div></div>';							
        $a++;
      }
      
      $diff_time1 = microtime( true ) - $start_time1;
      $diff_time1 = sprintf( '%.6F sec.', $diff_time1 );	
      //(Метка) 
      //echo "<br>Время выполнения 1: $diff_time1"; // Время выполнения: 0.000014 sec.
  ?></div>
  <?php }?>			
  </div>
  <div class="multiaxis-btn-options">		
    <div class="multiaxis-btn-hide-panel">
    <?php if ($atts['showselectedoptions'] == 'true' && $atts['selection'] != '' &&  $atts['mode'] != 'full' /*$atts['mode'] == 'minimal'*/) {	?> 		
      <div class="show-selected-options"><span class="show-selected-options-icon"></span>Show selected options</div>						
    <?php } elseif ($atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full') { ?>
      <div class="show-selected-options" <?php if ($atts['selection'] == '') {echo 'style="display:none;"';}?> ><span class="show-selected-options-icon"></span><?php echo $is_compact_view ? 'Show selected options': 'Hide selected options'; ?></div>					
    <?php } 
    if ($atts['selectotherprojects'] == 'false' && $atts['mode'] == 'full' && $atts['edit'] == 'true' || $atts['selectotherprojects'] == 'true' && $atts['mode'] == 'minimal' && $atts['edit'] == 'true') {  ?>
      <div class="select-other-projects"><span class="select-other-projects-icon"></span>Select other projects</div>
    <?php } elseif ($atts['selectotherprojects'] == 'true' && $atts['mode'] == 'full' && $atts['edit'] == 'true') { ?>	
      <div class="select-other-projects"><span class="select-other-projects-icon"></span><?php echo $is_compact_view ? 'Select other projects': 'Hide projects choice'; ?></div>
    <?php } ?>
    </div>
    <div class="multiaxis-sort-btns">
      <div class="multiaxis-sorting-post"><span class="multiaxis-sorting-text">Sort by&nbsp;</span><?php echo get_order_by($url_param_array['orderBy'], $atts['orderby']); echo get_order( $url_param_array['order'], $atts['order'] ); ?><div class="multiaxis-count-posts-div"><span class="multiaxis-count-posts"><?php echo $published_posts = wp_count_posts('portfolio')->publish; ?></span></div><span class="multiaxis-sorting-text"> projects</span>
      <?php if ( is_user_logged_in()  ) { ?> 
      <div class="multiaxis-admin-btn">										
        <div class="portfolio-link-container" data-button-open="closed"><img src="<?php echo plugins_url( '/images/multiaxis-avatar.png',  __FILE__); /*get_template_directory_uri().'/images/multiaxis-avatar.png'*/ ?>">
          <div class="button-link-container">							
            <div class="generate-shortcode-btn">Get shortcode</div>
            <div class="get-link-btn">Get link</div>            
            <?php if ($atts['time'] == 'true') {?>
            <div class="get-time-log-btn">Logging information</div>
            <?php }?>
          </div>						
        </div>					
      </div>      
      <?php } //new_fix for div down ?>	
	  </div>
    </div>
  </div>
  </div>
  <?php 

  
  /*$args1 = [
    'taxonomy'      => 'filters',
    'hide_empty'    => true,
    'meta_key'		=> 'main_checkbox',
  ];



  $terms1 = get_terms( $args1 );*/

  

  /*foreach( $terms1 as $term ){
    echo $term->name.'<br>';
  }*/
  //echo '<br>';

  //var_dump($terms2);
  //$terms_result = array_diff($terms1, $terms2);
  
  

  /*$terms2 = get_secondary_category('filters', 'portfolio');

  foreach( $terms2 as $term ){				
    echo $term->name.'<br>';				
  }*/

  $terms_children = get_terms([
    'taxonomy' => 'technologies',
    'hide_empty' => true,
    'parent' => '1589',
  ]);
  $is_logged_in = is_user_logged_in();
  

  //$rental_features = get_taxonomy( 'devops_expertise' );
  //print_r($rental_features);
  //echo $rental_features->label;
  

  ?>
    <div class = "inner-box no-right-margin darkviolet" data-post-id="<?php echo $post_id;?>">
      <div class = "multiaxis_pag_loading">						
        <div class = "multiaxis_container">
          <div class="multiaxis-content">
          <?php
          if ( is_user_logged_in() ) {
            $shortcode_params = 'var selectionOptions ="'.$atts['selection'].'"; var showSelectedOptions ='.$atts['showselectedoptions'].'; var selectOtherProjects='.$atts['selectotherprojects'].'; var showAdditionalAxis ='.$atts['showadditionalaxis'].'; var mode ="'.$atts['mode'].'";';
          } else {
            $shortcode_params = '';
          }
          //$url_array = explode("?" , $url);
          if ($atts['selection'] == '' || isset($url_array[1]) && $is_current_multiaxis ){//new_fix
          
            $start_time0 = microtime( true );
			
			
			
            if (isset($url_array[1]) && $is_current_multiaxis ) {//new_fix
              //$url_param_array = get_url_param_array($url_array);									
              $page = $url_param_array['pages'];									
              $order = $url_param_array['order'];									
              $order_by_url = $url_param_array['orderBy'];									
              //$meta_key = $url_param_array['metaKey'];
              $sortArr = transferSorting($order_by_url);	
              $order_by = $sortArr['orderby'];
              $meta_key = $sortArr['metakey'];
              
              $and_checkbox = $url_param_array['technologiesOperator'];
              
              $axis_array = $url_param_array['axisArray'];									
              $axis_array = explode(',', $axis_array);
              $select_checkbox_array = str_replace('%22', '"', $url_param_array['selectCheckboxArray']);
              $ha = str_replace('%22', '"',$url_param_array['ha']);
              $taxonomies_object = json_decode(str_replace('%22', '"',$url_param_array['selection']), true);
              // = $url_param_array['taxonomyLength'];
              //var_dump( $taxonomies_object );
				
              $start_time1 = microtime( true );
              $tax_query = make_tax_query($taxonomies_object, $and_checkbox);
			  //echo '<pre>'; var_dump($tax_query); echo '</pre>';
              $diff_time1 = microtime( true ) - $start_time1;
              $diff_time1 = sprintf( '%.6F sec.', $diff_time1 );	
              //echo "<br>Время выполнения 1: $diff_time1"; 	
              
              $start_time2 = microtime( true );
              //$count_of_terms_taxonomies = new_count_terms($taxonomies_object, $and_checkbox);
              $diff_time2 = microtime( true ) - $start_time2;
              $diff_time2 = sprintf( '%.6F sec.', $diff_time2 );	
              //echo "<br>Время выполнения 2: $diff_time2";		
              
              $start_time3 = microtime( true );
              $new_taxonomies_object = convert_id_to_name_term($taxonomies_object);
			  //echo '<pre>'; var_dump($new_taxonomies_object); echo '</pre>';	
              $diff_time3 = microtime( true ) - $start_time3;
              $diff_time3 = sprintf( '%.6F sec.', $diff_time3 );	
              //echo "<br>Время выполнения 3: $diff_time3";									
				
                if ($ha == null || !isset($ha) || $ha == 'undefined') {											
                  $taxonomies_object = $taxonomies_object;
                  $taxonomy_length = count($taxonomies_object);
                  $axis_array = array_keys($taxonomies_object);
                  $return_array = make_tax_query_for_and_check_alt($taxonomies_object, $and_checkbox);
                  $count_of_terms_taxonomies = $return_array['array_count'];
                  $server_ha = false;
				 
                } else {	
                  $new_ha = [];
                  
                  $new_ha = get_hidden_axis_array($ha);
                  $server_ha = true;
                 
                  $no_hidden = [];
                  $no_hidden = $taxonomies_object;			
                  $taxonomies_object = [];											
                  if ($no_hidden != null){	
                    $taxonomies_object = array_merge($new_ha, $no_hidden);
                    $tax_query = make_tax_query($taxonomies_object, $and_checkbox);
                    $return_array = make_tax_query_for_and_check_alt($taxonomies_object, $and_checkbox);
                    
                    if ( is_user_logged_in() ) {
                      $taxonomies_object = array_merge($new_ha, $no_hidden);
                      $taxonomy_length = count($taxonomies_object);
                      $new_taxonomies_object = convert_id_to_name_term($taxonomies_object);													
                      $axis_array = array_keys($taxonomies_object);
                    } else {
                      
                      //$taxonomies_object = json_decode(str_replace('%22', '"',$url_param_array['selection']), true);												
                      $taxonomies_object = $no_hidden;
                      $taxonomies_object_for_count = array_merge($new_ha, $no_hidden);
                      $taxonomy_length = count($taxonomies_object_for_count);		
                      $new_taxonomies_object = convert_id_to_name_term($taxonomies_object);
                      unset($new_taxonomies_object['country']);
                      unset($array_count['customer']);//customer									
                      $axis_array = array_keys($taxonomies_object);
                      //unset($axis_array['country']);
                    }												
                    //$taxonomies_object = array_merge($new_ha, $no_hidden);
                    //var_dump($axis_array);
                    
                    
                    $count_of_terms_taxonomies = $return_array['array_count'];
                    if ($count_of_terms_taxonomies == null) {
                      $count_of_terms_taxonomies = [''];
                    }
                  } else {
                    $taxonomies_object = $new_ha;
                    //echo '<pre>'; var_dump($taxonomies_object); echo '</pre>';
                    $taxonomy_length = count($taxonomies_object);
                    $tax_query = make_tax_query($taxonomies_object, $and_checkbox);
                    $return_array = make_tax_query_for_and_check_alt($taxonomies_object, $and_checkbox);
                    
                    if ( is_user_logged_in() ) {
                      $new_taxonomies_object = convert_id_to_name_term($taxonomies_object);
                      $axis_array = ['country'];
                    } else {
                      //$new_taxonomies_object = convert_id_to_name_term($taxonomies_object);
                      $new_taxonomies_object = [];
                      //unset($new_taxonomies_object['country']);
                      //$taxonomy_length = 0;
                      $axis_array = array_keys($taxonomies_object);
                      //var_dump($axis_array);
                      $axis_array = [''];
                      //unset($axis_array['country']);
                    }
                    $count_of_terms_taxonomies = $return_array['array_count'];
                  }												
                }
              if ($order_by_url != null) {
                $new_order_by = $order_by_url;									
              } else {
                $new_order_by = 'default';
              }									
            } else {
              $edit = $atts['edit'];
              $new_sorting = transferSorting($atts['orderby']);
              $order_by = $new_sorting['orderby'];//'meta_value_num';								
              $order = $atts['order'];//'DESC';
              $meta_key = $new_sorting['metakey'];//'weights';
              $tax_query = [];
              $page = 1;
              $taxonomy_length = [];
              $and_checkbox = $atts['technologiesoperator'];//$atts['operator']
            }								
            $edit = $atts['edit'];
            $cur_page = $page;		
            $page -= 1;
            $per_page = (int)$new_columns*(int)$new_rows; //set the per page limit
            $start = $page * $per_page;									
            
            $int_columns = $new_columns;
            $new_columns = transferAttribute($new_columns);
            //echo '<pre>'; var_dump($tax_query); echo '</pre>';	
            $post_param = get_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start);
            $count_post = $post_param['count_post'];
            //$count = get_count($order, $meta_key, $tax_query);
            $count = $count_post;
            $view_pagination = $post_param['view_pagination'];
            
            //var newMetaKey = '.json_encode($meta_key).';
            // axisArray = '.json_encode($axis_array).';
            /*if (isset($count_of_terms_taxonomies)) {
              $rtc = "";
            } else {
              $rtc = '';
            }*/
            
            
            
            $trueTaxName = get_label_taxonomy();
            echo(
              '<script type="text/javascript">	
              '.$shortcode_params.'
			  window["multiaxis-id-'.$mId.'"] = {};
			  window["multiaxis-id-'.$mId.'"]["columns"] = '.json_encode($atts['columns']).';
			  window["multiaxis-id-'.$mId.'"]["rows"] = '.json_encode($atts['rows']).';	
              window["multiaxis-id-'.$mId.'"]["newOrderBy"] = '.json_encode($new_order_by).';
			  window["multiaxis-id-'.$mId.'"]["editTags"] = '.json_encode($edit).'; 									
			  window["multiaxis-id-'.$mId.'"]["newOrder"] = '.json_encode($order).';			  
			  window["multiaxis-id-'.$mId.'"]["taxonomyLength"] = '.json_encode($taxonomy_length).';
			  window["multiaxis-id-'.$mId.'"]["axisArray"] = '.json_encode($axis_array).';
			  window["multiaxis-id-'.$mId.'"]["axisArrayServer"] = '.json_encode($axis_array).';
			  window["multiaxis-id-'.$mId.'"]["taxonomyLengthServer"] = '.json_encode($taxonomy_length).';
			  window["multiaxis-id-'.$mId.'"]["responseTaxonomiesCount"] = '.json_encode($count_of_terms_taxonomies).';
			  window["multiaxis-id-'.$mId.'"]["newTagsArray"] = '.json_encode($new_taxonomies_object).';			  
			  window["multiaxis-id-'.$mId.'"]["newAndCheckbox"] = '.json_encode($and_checkbox).';
			  window["multiaxis-id-'.$mId.'"]["isLoggedIn"] = '.json_encode($is_logged_in).';
			  window["multiaxis-id-'.$mId.'"]["trueTaxName"] = '.json_encode( $trueTaxName ).';
			  window["multiaxis-id-'.$mId.'"]["serverHa"] = '.json_encode($server_ha).';
			  window["multiaxis-id-'.$mId.'"]["clearAllFilters"] = "";
			  window["multiaxis-id-'.$mId.'"]["multiaxisCountPosts"] = '.$count_post.';
			  window["multiaxis-id-'.$mId.'"]["multiaxisId"] = "multiaxis-id-'.$mId.'";</script>'
            );
            get_paginations($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start);
            $diff_time0 = microtime( true ) - $start_time0;
            $diff_time0 = sprintf( '%.6F sec.', $diff_time0 );	
            //echo "<br>Время выполнения 0: $diff_time0"; 
          } elseif ($atts['selection'] != '' ) {
            
            $and_checkbox = $atts['technologiesoperator'];
            if ($and_checkbox == 'AND') {
              $and_checkbox_shortcode = true;
            } else {
              $and_checkbox_shortcode = false;
            }
            
            $new_sorting = transferSorting($atts['orderby']);
            //$atts['orderby'];
            $order_by = $new_sorting['orderby'];//$atts['orderby'];//$new_sorting['orderby'];//'meta_value_num';								
            $order = $atts['order'];//'DESC';
            $meta_key = $new_sorting['metakey'];//'weights';
            
            
            $edit = $atts['edit'];
            //var_dump( count( $new_selection_array ) );
            //echo count( $new_selection_array ) == 1 && isset( $new_selection_array['country']) ;
            $tax_query = make_tax_query_for_selection($new_selection_array, $and_checkbox);
            
            $int_columns = $new_columns;
            $page = $atts['page'];		
            $new_selection_array_1 = convert_name_and_id_to_id_term($atts['selection'], true);
            $tax_query_1 = make_tax_query_for_selection_id($new_selection_array_1, $and_checkbox_shortcode);	
            //if ($atts['mode'] == 'full'){								
              $per_page = (int)$new_columns*(int)$new_rows;
              $cur_page = $page;	
              $page -= 1;
              $count = get_count($order, $meta_key, $tax_query_1);
            //} elseif ($atts['mode'] == 'minimal') {
              //$per_page = -1;
            //}					
            if ( isset($new_selection_array['country']) || isset($new_selection_array['customer']) ) {
              $server_ha = true;
              $clear_all_filters = false;
            } else {
              $server_ha = false;
              $clear_all_filters = '';
            }
            
            $start = $page * $per_page;							
            $new_tags_array = get_selection_params_for_selected_tag($atts['selection']);							
            
            //$new_selection_array_1 = convert_name_to_id_term($new_selection_array);	
            
            
            $count_of_terms_taxonomies = make_tax_query_for_and_check_alt($new_selection_array_1, $and_checkbox_shortcode);								
            if ($count_of_terms_taxonomies == null) {
              $count_of_terms_taxonomies = [''];
            }
            $new_columns = transferAttribute($new_columns);
            //echo '<pre>'; var_dump($tax_query_1); echo '</pre>';
            //echo '<pre>'; var_dump($tax_query); echo '</pre>';
			
            $post_param = get_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query_1, $start);
            
            $view_pagination = $post_param['view_pagination'];
            $count_post = $post_param['count_post'];
            
            $taxonomy_length = count($new_selection_array_1);
            

            $axis_array = array_keys( convert_name_and_id_to_id_term($atts['selection'], is_user_logged_in() ) );
            
            
            if ($edit == 'false'){
              $new_tax_query = 'var ntq = '.json_encode($tax_query);
              //.$new_tax_query.
              $count_of_terms_taxonomies = [''];
            } else {
              $new_tax_query = '';
            }
            //echo '<pre>'; var_dump($tax_query_1); echo '</pre>';
            $trueTaxName = get_label_taxonomy();
            echo '<script id="multiaxis-script-id-'.$mId.'">
				'.$shortcode_params.'
				window["multiaxis-id-'.$mId.'"] = {};	
				window["multiaxis-id-'.$mId.'"]["columns"] = '.json_encode($atts['columns']).';
				window["multiaxis-id-'.$mId.'"]["rows"] = '.json_encode($atts['rows']).';
				window["multiaxis-id-'.$mId.'"]["editTags"] = '.json_encode($edit).';
				window["multiaxis-id-'.$mId.'"]["newMetaKey"] = '.json_encode($meta_key).';
				window["multiaxis-id-'.$mId.'"]["newOrderBy"] = '.json_encode($atts['orderby'])/*json_encode($order_by)*/.';
				window["multiaxis-id-'.$mId.'"]["newOrder"] = '.json_encode($order).';
				window["multiaxis-id-'.$mId.'"]["taxonomyLength"] = '.json_encode($taxonomy_length).';
				window["multiaxis-id-'.$mId.'"]["taxonomyLengthServer"] = '.json_encode($taxonomy_length).';
				window["multiaxis-id-'.$mId.'"]["axisArray"] = '.json_encode($axis_array).';
				window["multiaxis-id-'.$mId.'"]["axisArrayServer"] = '.json_encode($axis_array).';
				window["multiaxis-id-'.$mId.'"]["responseTaxonomiesCount"] = '.json_encode($count_of_terms_taxonomies['array_count']).';
				window["multiaxis-id-'.$mId.'"]["newAndCheckbox"] = '.json_encode($and_checkbox_shortcode).';
				window["multiaxis-id-'.$mId.'"]["newTagsArray"] = '.json_encode($new_tags_array).';
				window["multiaxis-id-'.$mId.'"]["trueTaxName"] = '.json_encode( $trueTaxName ).';
				window["multiaxis-id-'.$mId.'"]["isLoggedIn"] = '.json_encode($is_logged_in).';
				window["multiaxis-id-'.$mId.'"]["multiaxisCountPosts"] = '.$count_post.';
				window["multiaxis-id-'.$mId.'"]["serverHa"] = '.json_encode($server_ha).';
				window["multiaxis-id-'.$mId.'"]["clearAllFilters"] = '.json_encode($clear_all_filters).';
				window["multiaxis-id-'.$mId.'"]["multiaxisId"] = "multiaxis-id-'.$mId.'";								
			</script>';//new_fix
            //if ($atts['mode'] == 'full'){
            //var clearAllFilters = "";
              get_paginations($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start);
            //}
          }
          
            ?>
          </div>
        </div>
      </div>
    </div>

</div>
<?php 




}


 /* Загрузка изображений */
add_action( 'technologies_edit_form_fields', 'multiaxis_update_category_image' , 10, 2 );
function multiaxis_update_category_image ( $term, $taxonomy ) {
?>
	<style>
	/*img{border:3px solid #ccc;} */


	.term-group-wrap p{float:left;}
	.term-group-wrap input{font-size:18px;font-weight:bold;width:40px;}
	#button_images{font-size:18px;}
	#button_images_remove{font-size:18px;margin:5px 5px 0 0;}
	#cat-image-miniature img, img.multiaxis-icon-admin { width: 50px; margin-top: 3px;}
	</style>

<tr class="form-field term-group-wrap">
	<th scope="row">
		<label for="id-cat-images">Images</label>
	</th>
	<td>
	<p>
		<input type="button" class="button button_images" id="button_images" name="button_images" value="+" />
		<input type="button" class="button button_images_remove" id="button_images_remove" name="button_images_remove" value="&ndash;" />
	</p>
	<?php $id_images = get_term_meta( $term -> term_id, 'id-cat-images', true ); ?>
	<input type="hidden" id="id-cat-images" name="id-cat-images" value="<?php echo $id_images; ?>">
		<div id="cat-image-miniature">
		<?php if (empty($id_images )) { ?>
		<img src="<?php echo plugins_url( '/images/noimg.jpg',  __FILE__); /*get_template_directory_uri(). '/images/noimg.jpg';*/ ?>" alt="no_image" width="84" height="89"/>
		<?php } else {
			if (wp_get_attachment_image ( $id_images) != null) {
				echo wp_get_attachment_image ( $id_images, 'full');  
			} else {
				$image_url = wp_get_attachment_url( $id_images );
				echo '<img class="multiaxis-icon-admin multiaxis-icon-admin-'.$id_images.'" src="'.$image_url.'">';
			}	
		} ?>
		</div>
	</td>
</tr>


<?php
}


/* чекбокс для добавления включения/отключения чекбокса */
add_action( 'technologies_edit_form_fields', 'multiaxis_category_on_checkbox' , 10, 2 );
function multiaxis_category_on_checkbox( $term, $taxonomy ) {
?>
   
    <tr class="form-field">
		<th scope="row">
		   <label for="on_checkbox">Off/On checkbox</label>
		</th>
		<td>
		<?php $on_checkbox = get_term_meta( $term->term_id, 'on_checkbox', true ); ?>
		   <p><input type="checkbox" class="on_checkbox" id="on_checkbox" name="on_checkbox" data-term-id="<?php echo $term->term_id ?>"  <?php echo $on_checkbox; checked( get_term_meta($term->term_id, 'on_checkbox', true), true )?> />Deactivate checkbox<br></p>
		</td>
	</tr>
<?php 
}


add_action( 'edited_technologies','multiaxis_category_on_checkbox_save' , 10, 2 );
function multiaxis_category_on_checkbox_save ( $term_id ) {
	if( isset( $_POST['on_checkbox'] ) && '' !== $_POST['on_checkbox'] ){
    
		$on_checkbox = 'checked';
		update_term_meta( $term_id, 'on_checkbox', $on_checkbox );
		
	} else {
		update_term_meta( $term_id, 'on_checkbox', '' );
	}
}









//if(preg_match("#tag_ID=([0-9.]+)#", $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'])) {
	//add_action( 'admin_footer', 'multiaxis_loader' );	
//}

 /* Загрузка изображений скрипт
function multiaxis_loader() { ?>
<script>



</script>
<?php }*/



add_action( 'edited_technologies','multiaxis_updated_category_image' , 10, 2 );
function multiaxis_updated_category_image ( $term_id, $tt_id ) {
	if( isset( $_POST['id-cat-images'] ) && '' !== $_POST['id-cat-images'] ){

		$image = $_POST['id-cat-images'];
			update_term_meta( $term_id, 'id-cat-images', $image );

		} else {
			update_term_meta( $term_id, 'id-cat-images', '' );
		}
}

add_action( 'wp_ajax_multiaxis_save_chexbox', 'multiaxis_save_chexbox');
function multiaxis_save_chexbox() {
	if(isset($_POST['id_cat_checkbox'])){
		$checkbox = $_POST['id_cat_checkbox'];
		$term_id = $_POST['term_id'];
		update_term_meta( $term_id, 'id_cat_checkbox', $checkbox );
	}

}
add_action( 'edited_technologies','multiaxis_updated_category_checkbox' , 10, 2 );
function multiaxis_updated_category_checkbox ( $term_id, $tt_id ) {
update_term_meta( $term_id, 'id_cat_checkbox', true );
	if( isset( $_POST['id_cat_checkbox'] ) && '' !== $_POST['id_cat_checkbox'] ){

		$checkbox = $_POST['id_cat_checkbox'];
		update_term_meta( $term_id, 'id_cat_checkbox', $checkbox );

	} else {
		update_term_meta( $term_id, 'id_cat_checkbox', '' );
	}
}






// подключаем функцию активации мета блока (my_extra_fields)
add_action('add_meta_boxes', 'my_sorting_field');

function my_sorting_field() {
	add_meta_box( 'sorting_field', 'Additional sorting', 'sorting_fields_box_func', 'portfolio', 'side',  'high'  );
}

// код блока
function sorting_fields_box_func( $post ){
	
	$duration   = get_post_meta($post->ID, 'duration', 1);
	$year 	    = get_post_meta($post->ID, 'year', 1);
	$start_year = get_post_meta($post->ID, 'start_year', 1);
	$weights    = get_post_meta($post->ID, 'weights', 1);
	?>


	<p>  
		<div style="width: 220px;">Start year and month:</div>
		<input required type="date" name="sorting[start_year]" value="<?php if ( $start_year != '' ) { echo $start_year; } else {/*echo 0;*/} ?>" />
	</p>

	<p>  
		<div style="width: 220px;">Completion year and month:</div>
		<input type="date" name="sorting[year]" value="<?php if ( $year != '' ) { echo $year; } else {echo 0;} ?>" /> 
	</p>

	<p>  
<div style="width: 240px;">Duration in months <br>(calculated automatically): <b><?php if ( $duration != '' ) { echo $duration; } else {echo 0;} ?></b></div>
		
	</p>
	
	<p>  
		<div style="width: 220px;">Weight (influences default sorting):</div>
		<input required type="number" name="sorting[weights]" value="<?php if ( $weights != '' ) { echo $weights; } else {echo 0;} ?>" /> 
	</p>
	
	<input type="hidden" name="sorting_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}


// включаем обновление полей при сохранении
add_action( 'save_post_portfolio', 'my_sorting_fields_update', 0 );

## Сохраняем данные, при сохранении поста
function my_sorting_fields_update( $post_id ){
	//var_dump($_POST['sorting']);
	//die();
	// базовая проверка
	if (
		   empty( $_POST['sorting'] )
		|| ! wp_verify_nonce( $_POST['sorting_fields_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	) {	
		
		return false;	
	}
	$date2 = $_POST['sorting']['year'];
	//$_POST['sorting']['duration'] = '';
	
	if (!$date2) {
		$date2 = date('Y-m-d');
		//die($date2);
		$_POST['sorting']['in_process'] = 'in_process';
		
		//$_POST['sorting']['year'] = $date2;		
	} else {
		delete_post_meta( $post_id, 'in_process' );
	}
	
	//die();
	//die('123 '.$date2);
	$date_different = round(date_different($_POST['sorting']['start_year'], $date2));
	
	
	//die($date_different);
	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['sorting'] = array_map( 'sanitize_text_field', $_POST['sorting'] ); // чистим все данные от пробелов по краям
	foreach( $_POST['sorting'] as $key => $value ){
		if( empty($value) ){
			delete_post_meta( $post_id, $key );// удаляем поле если значение пустое	
			continue;
		} 
		
		
		//if ($key == 'duration' ){			
						
		//} 
		if ($key == 'year') {
			update_post_meta( $post_id, $key, $date2 );
		}
		else {
			update_post_meta( $post_id, $key, $value );
		}// add_post_meta() работает автоматически		
	}
	update_post_meta( $post_id, 'duration', $date_different );
	
	return $post_id;
}



function get_userlogin(){
	$current_user = wp_get_current_user();
	$userlogin = $current_user->user_login;
	return $userlogin;
}


add_action('add_meta_boxes', 'my_additional_information');

function my_additional_information() {

	if ( get_userlogin() == 'CEO'){
		add_meta_box( 'additional_information', 'Additional information', 'additional_information_box_func', 'portfolio', 'side',  'high'  );
	}
}

// код блока
function additional_information_box_func( $post ){	
	$additional_description = get_post_meta($post->ID, 'additional_description', 1);
	$killing_features = get_post_meta($post->ID, 'killing_features', 1);
	$customer = get_post_meta($post->ID, 'customer', 1);

	?>




		<p>  
			<div style="width: 220px;">Customer:</div>
			<input type="text" name="additional_information[customer]" value="<?php if ( $customer != '' ) { echo $customer; } else {echo '';} ?>" > 
		</p>
		<p>  
			<div style="width: 220px;">Additional description:</div>
			<textarea rows="7" name="additional_information[additional_description]" style="width: 100%;"><?php if ( $additional_description != '' ) { echo $additional_description; } else {echo '';} ?></textarea>
		</p>
		<p>  
			<div style="width: 220px;">Killing features:</div>
			<textarea rows="7" name="additional_information[killing_features]" style="width: 100%;" ><?php if ( $killing_features != '' ) { echo $killing_features; } else {echo '';} ?></textarea>
		</p>
	
	
	<input type="hidden" name="additional_information_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}


// включаем обновление полей при сохранении
add_action( 'save_post_portfolio', 'my_additional_information_update', 0 );

## Сохраняем данные, при сохранении поста
function my_additional_information_update( $post_id ){
	//var_dump($_POST['sorting']);
	//die();
	// базовая проверка
	if (
		   empty( $_POST['additional_information'] )
		|| ! wp_verify_nonce( $_POST['additional_information_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	) {	
		
		return false;	
	}

	
	
	//die($date_different);
	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['additional_information'] = array_map( 'sanitize_text_field', $_POST['additional_information'] ); // чистим все данные от пробелов по краям
	foreach( $_POST['additional_information'] as $key => $value ){
		if( empty($value) ){
			delete_post_meta( $post_id, $key );// удаляем поле если значение пустое	
			continue;
		} 	

		update_post_meta( $post_id, $key, $value );
		
	}
	//update_post_meta( $post_id, 'duration', $date_different );
	
	return $post_id;
}



/*Считаем разность дат*/
function date_different($date1, $date2)
{
	$date2 = new DateTime($date2);
	$date1 = new DateTime($date1);
	$interval = $date1->diff($date2);
	//$date_different 
	$diff = 12*$interval->y+$interval->m;
	//$diff = abs(strtotime($date2) - strtotime($date1));
	return $diff;
}


/* чекбокс для добавления категорий наверх  */
add_action( 'technologies_edit_form_fields', 'multiaxis_category_to_top' , 10, 2 );
function multiaxis_category_to_top ( $term, $taxonomy ) {
?>
   
    <tr class="form-field">
		<th scope="row">
		   <label for="top_checkbox">Add a category to the top</label>
		</th>
		<td>
		<?php  $top_checkbox = get_term_meta( $term->term_id, 'top_checkbox', true ); ?>
		   <p><input type="checkbox" class="top_checkbox" id="top_checkbox" name="top_checkbox" data-term-id="<?php echo $term->term_id ?>"  <?php echo $top_checkbox; checked( get_term_meta($term->term_id, 'top_checkbox', true), true )?> />Activate for add a category to the top<br></p>
		</td>
	</tr>
<?php 
}

add_action( 'edited_technologies','multiaxis_category_to_top_updated' , 10, 2 );
function multiaxis_category_to_top_updated ( $term_id ) {
	if( isset( $_POST['top_checkbox'] ) && '' !== $_POST['top_checkbox'] ){
    
		$top_checkbox = 'checked';
		update_term_meta( $term_id, 'top_checkbox', $top_checkbox );
		
	} else {
		update_term_meta( $term_id, 'top_checkbox', '' );
	}
}


/* чекбокс для добавления категории в главные технологии */
add_action( 'filters_edit_form_fields', 'multiaxis_category_to_main' , 10, 2 );
function multiaxis_category_to_main ( $term, $taxonomy ) {
?>
   
    <tr class="form-field">
		<th scope="row">
		   <label for="main_checkbox">Add a category to the main</label>
		</th>
		<td>
		<?php  $main_checkbox = get_term_meta( $term->term_id, 'main_checkbox', true ); ?>
		   <p><input type="checkbox" class="main_checkbox" id="main_checkbox" name="main_checkbox" data-term-id="<?php echo $term->term_id ?>"  
					 <?php echo $main_checkbox; checked( get_term_meta($term->term_id, 'main_checkbox', true), true )?> />Activate for add a category to the main<br></p>
		</td>
	</tr>
<?php 
}

add_action( 'edited_filters','multiaxis_category_to_main_updated' , 10, 2 );
function multiaxis_category_to_main_updated ( $term_id ) {
	if( isset( $_POST['main_checkbox'] ) && '' !== $_POST['main_checkbox'] ){
    
		$main_checkbox = 'checked';
		update_term_meta( $term_id, 'main_checkbox', $main_checkbox );
		
	} else {
		update_term_meta( $term_id, 'main_checkbox', '' );
	}
}



/* инпут для сортировки */
add_action( 'technologies_edit_form_fields', 'multiaxis_top_sorting_input' , 10, 2 );
function multiaxis_top_sorting_input( $term, $taxonomy ) {
	
	$top_checkbox = get_term_meta( $term->term_id, 'top_checkbox', true );
	$on_top_sorting = get_term_meta( $term->term_id, 'on_top_sorting', true ); 
?>
    <tr class="form-field">
		<th scope="row">
		   <label for="on_top_sorting">Sorting</label>
		</th>
		<td>
		   <p><input type="text" class="on_top_sorting" id="on_top_sorting" name="on_top_sorting" data-term-id="<?php echo $term->term_id ?>"  value="<?php echo $on_top_sorting; ?>" /></p>
		</td>
	</tr>
<?php 
	//} //else {}
}


add_action( 'edited_technologies','multiaxis_top_sorting_input_save' , 10, 2 );
function multiaxis_top_sorting_input_save ( $term_id ) {
	if( isset( $_POST['on_top_sorting'] ) && '' !== $_POST['on_top_sorting'] ){
    
		$on_top_sorting =  $_POST['on_top_sorting'];
		update_term_meta( $term_id, 'on_top_sorting', $on_top_sorting );
		
	} else {
		update_term_meta( $term_id, 'on_top_sorting', '' );
	}
}

/* инпут для provided services  */
add_action( 'servicestax_edit_form_fields', 'multiaxis_provided_services_input' , 10, 2 );
function multiaxis_provided_services_input ( $term, $taxonomy ) {
?>
  
    <tr class="form-field">
		<th scope="row">
		   <label for="provided_services">Provided services</label>
		</th>
		<td>
		<?php $provided_services = get_term_meta($term->term_id, 'provided_services', true); ?>
		<p><textarea class="provided_services" id="provided_services" name="provided_services" data-term-id="<?php echo $term->term_id ?>"><?php echo $provided_services; ?></textarea></p>
		</td>
	</tr>
<?php 
}

add_action( 'edited_servicestax','multiaxis_provided_services_updated' , 10, 2 );
function multiaxis_provided_services_updated ( $term_id ) {
	if( isset( $_POST['provided_services'] ) && '' !== $_POST['provided_services'] ){
   
		//$top_checkbox = 'checked';
		$provided_services = $_POST['provided_services'];
		update_term_meta( $term_id, 'provided_services', $provided_services );
		
	} else {
		update_term_meta( $term_id, 'provided_services', '' );
	}
}




new My_Best_Metaboxes;

class My_Best_Metaboxes {

	public $post_type = 'portfolio';
	static $quantity_key = 'quantity';
	static $position_key = 'position';
	static $team_size_key = 'team_size';

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'show_assets' ), 10, 999 );
	}

	## Добавляет матабоксы
	public function add_metabox() {
		add_meta_box( 'box_info_company', 'Team information', array( $this, 'render_metabox' ), $this->post_type, 'side', 'high' );
	}

	## Отображает метабокс на странице редактирования поста
	public function render_metabox( $post ) {

		?>
		<table class="form-table company-info">

			<tr>

				<td class="company-address-list">
				<span class="position-title">Position <span class="dashicons dashicons-plus-alt add-company-address"></span></span>
					<?php

					
					$quantity_key_arr = get_post_meta( $post->ID, self::$quantity_key, true );
					$position_key_arr = get_post_meta( $post->ID, self::$position_key, true );
					//delete_post_meta( $post->ID, self::$position_key );
	
					//var_dump( $position_key_arr );

					$all_position_arr = ['Project Manager', 'QA Manual', 'QA Automation', 'Frontend', 'Backend', 'DevOps', 'Business Analyst'];
					if ($position_key_arr != null) {
						$remains = array_diff($all_position_arr, $position_key_arr);
						//var_dump($remains);
					}
					else {
						
						$remains = $all_position_arr;
						//var_dump($remains);
					}
					

					if ( is_array( $position_key_arr ) && is_array( $quantity_key_arr ) ) {
						foreach ( array_combine($position_key_arr, $quantity_key_arr) as $position_key_el => $quantity_key_el ) {
							//printf( $input, esc_attr( $position_key_el ) );
							echo '<span class="item-address">				
								<input required min="1" class="team-number" type="number" name="'. self::$quantity_key .'[]" value="'.$quantity_key_el.'">
								<select required class="select-position" name="'. self::$position_key .'[]" > 
									<option >'.$position_key_el.'</option>';
									foreach ($remains as $remain) {
										echo '<option>'.$remain.'</option>';
									}									
							echo '</select>											
								<span class="dashicons dashicons-trash remove-company-address"></span>
							</span>';
							//$f++;							
						}
					} else { 
					
						
							echo '<span class="item-address">								
								<input required min="1" type="number" name="'. self::$quantity_key .'[]" >						
								<select required class="select-position" name="'. self::$position_key .'[]">'; 
								foreach ($remains as $remain) {
										echo '<option>'.$remain.'</option>';
									}	
							echo'</select>
								<span class="dashicons dashicons-trash remove-company-address"></span>
							</span>';
						 
					}
					
					?>
					
				</td>
			</tr>

		</table>
		<h3 class="team-size">Team size: <?php echo get_post_meta( $post->ID, 'team_size', 'true' ); ?></h3>
		<?php
	}

	## Очищает и сохраняет значения полей
	public function save_metabox( $post_id ) {

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		if ( isset( $_POST[self::$position_key] ) && is_array( $_POST[self::$position_key] ) && isset( $_POST[self::$quantity_key] ) && is_array( $_POST[self::$quantity_key] ) ) {
			$position_key_arr = $_POST[self::$position_key];
			$quantity_key_arr = $_POST[self::$quantity_key];
			$team_size_key_arr = array_sum($_POST[self::$quantity_key]);
			
			$position_key_arr = array_map( 'sanitize_text_field', $position_key_arr ); // очистка
			$quantity_key_arr = array_map( 'sanitize_text_field', $quantity_key_arr ); // очистка
			//$team_size_key_arr = array_map( 'sanitize_text_field', $team_size_key_arr ); 

			$position_key_arr = array_filter( $position_key_arr ); // уберем пустые адреса
			$quantity_key_arr = array_filter( $quantity_key_arr ); // уберем пустые адреса
			//$team_size_key_arr = array_filter( $team_size_key_arr);
 
			if ( $position_key_arr && $quantity_key_arr ) {
				update_post_meta( $post_id, self::$position_key, $position_key_arr );
				update_post_meta( $post_id, self::$quantity_key, $quantity_key_arr );
				update_post_meta( $post_id, self::$team_size_key, $team_size_key_arr);
			}
			else {
				delete_post_meta( $post_id, self::$position_key );
				delete_post_meta( $post_id, self::$quantity_key );
				update_post_meta( $post_id, self::$team_size_key);
			}
		}
	}

	## Подключает скрипты и стили
	public function show_assets() {
		if ( is_admin() && get_current_screen()->id == $this->post_type ) {
			$this->show_styles();
			//$this->show_scripts();
		}
	}

	## Выводит на экран стили
	public function show_styles() {
		?>
		<style>
			.team-number {
				width: 30%!important;
			}
			.position-title {
				display: block;
				margin-bottom: 15px;
			}
			.add-company-address {
				color: #00a0d2;
				cursor: pointer;
			}
			.company-address-list .item-address {
				display: flex;
				align-items: center;
				margin-bottom: 10px;
			}
			.company-address-list .item-address input {
				width: 100%;
				max-width: 21%;
			}
			.remove-company-address {
				color: brown;
				cursor: pointer;
			}
			.select-position {
				width: 80%;
			}
		</style>
		<?php
	}



}


function show_scripts() {
	wp_enqueue_script('multiaxis-admin', get_template_directory_uri() . '/js/multiaxis-admin.js');
}

add_action( 'admin_enqueue_scripts', 'multiaxis_admin_scripts' );
function multiaxis_admin_scripts() {
		wp_enqueue_script( 'multiaxis-admin', plugins_url( '/assets/multiaxis-admin.js',  __FILE__) );
		wp_localize_script('multiaxis-admin', 'sitenameurl', array( get_site_url() ) );
	}


function my_enqueue_media() {
	wp_enqueue_media();
}		
add_action( 'admin_enqueue_scripts', 'my_enqueue_media' );

/*Добавление чекбокса для скрытия таксономии*/

function custom_content() {
    ?> 
	<th scope="row">
		   <label for="hidden_checkbox">Make this axis hidden</label>
		</th>
		<td>
		<?php echo $hidden_checkbox = get_term_meta( $term->term_id, 'hidden_checkbox', true ); ?>
		   <p><input type="checkbox" class="hidden_checkbox" id="hidden_checkbox" name="hidden_checkbox" data-term-id="<?php echo $term->term_id ?>"  <?php echo $hidden_checkbox; checked( get_term_meta($term->term_id, 'hidden_checkbox', true), true )?> />Activate to make this axis hidden<br></p>
	</td>
<?php
}

function hasChildrenTerms($term, $name_taxonomy) {
	
	$terms_children = get_terms([
		'taxonomy' => $name_taxonomy,
		'hide_empty' => true,
		'parent' => $term,
	]);
	
	$terms_count = count($terms_children);
	
	return $terms_count;
	
}

function tree_new($parent, $i = 1, $name_taxonomy = 'technologies') {
    $terms = get_terms([
		'taxonomy' => $name_taxonomy,
        'hide_empty' => true,
        'parent' => $parent
    ]);
	

	$settings = get_multiaxis_tax_settings();
	if ( isset($settings) ){
		$settings_decode = json_decode($settings);
	}
	
	$checkNameTaxonomy = $name_taxonomy == 'technologies';

	if ( !empty($terms)) { 		

		if ( $name_taxonomy != 'technologies' ){
			$ul_list_class = ' ul-list-no-tree-taxonomy';
			$li_list_class = ' li-list-no-tree-taxonomy';
		} else {
			$ul_list_class = '';
			$li_list_class = '';			
		}
		?>	
			<ul class="ul-list-tree-taxonomy ul-children-<?php echo $name_taxonomy; ?> ul-children-<?php echo $name_taxonomy;?>-<?php echo $i;echo $ul_list_class;?>">				
				<?php 

					$c = 0;
					if ($i == 0 && $name_taxonomy == 'technologies') {
						echo $block_div = '<div class="closed-block-container"><img src="'.plugins_url( '/images/close.svg',  __FILE__).'"></div>'; 
					} 
				
				$i++; 
				
				foreach ($terms as $term){				
					$cat_id = $term->term_id;
					$image_id = get_term_meta($cat_id, 'id-cat-images', true);
					
					$on_checkbox = get_term_meta($cat_id, 'on_checkbox', true);
					
					if (is_taxonomy_public($name_taxonomy) != 1){
						$no_public = 'data-taxonomy-public="false"';
					} else {
						$no_public = '';
					}
					
					if ($on_checkbox != 'checked')
					{
						$checkbox_no_class = '';
						//<span class="input-hover"></span>
						$input = '<input autocomplete="off" type="checkbox" data-checked="0" data-originality="true" '.$no_public.' data-term-name="'.$term->name.'" data-taxonomy-name-id="'.$term->taxonomy.'" data-term-id="'.$term->term_id.'" class="checkbox-data-term checkbox-children-'.$term->taxonomy.' checkbox-children-'.$term->taxonomy.'-'.$i.'"><span class="input-hover"></span>';
						//'<input type="checkbox" data-depth="'.$depth.'" data-originality="true" data-term-name="'.$item->name.'" data-taxonomy-name-id="'.$item->taxonomy.'" data-term-id="'.esc_attr( $item->term_id).'" class="checkbox-data-term checkbox-children-'.$item->taxonomy.' checkbox-children-'.$item->taxonomy.'-'.$depth.'">';
					} elseif ($on_checkbox == 'checked') {
						$input = '';
						$checkbox_no_class = 'no-checkbox-margin';
					}
					
					if ( !empty($image_id)  && wp_get_attachment_url( $image_id ) != '' ) { 
						if (wp_get_attachment_image($image_id) != null) {
							//$params = [ 'loading' => 'eager' ];
							$term_image = wp_get_attachment_image ($image_id, 'full', false );  
							//$term_image = wp_get_attachment_image ($image_id, 'full');  
						} else {
							$image_url = wp_get_attachment_url( $image_id );
							$term_image = '<img loading="lazy" class="multiaxis-icon multiaxis-icon-'.$image_id.'" src="'.$image_url.'">';
						}						
						//$term_image =  wp_get_attachment_image($image_id, 'thumbnail');
					} else {
						$term_image = '';
					}
					$checkTaxonomy = $term->taxonomy == 'technologies';
					
					if ($checkTaxonomy) { 
						$li_all_class = '';
					} else {
						$li_all_class = 'li-all-taxonomy';
					}

					
					$checkChildren = empty(get_term_children( $term->term_id, $term->taxonomy ));
					$checkChildrenCount = children_count_terms( $term->term_id, $term->taxonomy ) == false;

					if ($checkTaxonomy && $i < 2 && !$checkChildren && $checkChildrenCount) {
						$check_mark = '<span class="check-mark check-mark-'.$i.'" data-depth="'.$i.'"><img src="'.plugins_url( '/images/down-nd.svg',  __FILE__).'" data-position="closed"><span class="check-mark-hover">Expand</span></span>';
					} 
					
					elseif ($checkTaxonomy && $i <= 3 && !$checkChildren && $checkChildrenCount) {
						$check_mark = '<span class="check-mark check-mark-'.$i.'" data-depth="'.$i.'"> <img class="img-check-mark-right" src="'.plugins_url( '/images/down-nd.svg',  __FILE__).'" data-position="closed"><span class="check-mark-hover">Expand</span></span>';
					} 
					
					else {
						$check_mark = '';
					}
					if ($c > (int)$settings_decode->$name_taxonomy-1 && $i <= 1 && !$checkTaxonomy && (int)$settings_decode->$name_taxonomy != 0) {
						//$li_display_none = 'li-display li-display-none li-display-none-'.$term->taxonomy; 
						$open_btn = '<div class="portfolio-show-additional-btn-wrap"><div class="portfolio-show-additional-btn" data-show-toggle="true" data-taxonomy-name="'.$term->taxonomy.'"> <span class="for-underline-text">Show all<span class="additional-btn-icon"></span></span></div></div>';
						
					}
					else { 
						
						//$li_display_none = '';
						$open_btn = '';
					}
					if (getHasChildren($term->term_id, $name_taxonomy) == true){
						$data_position = 'data-position="closed"';
						$data_position_class = ' parent-class';
						$no_children_cat_class = '';
						$img_ofsset = '';
					}
					else {
						if (getThisChildren($terms, $name_taxonomy) > 0) {
							//if ($i > 1) {b
								$no_children_cat_parents_class = '';
								//$no_children_cat_class = 'no-children-category-class';
								$no_children_cat_class = '';
								$img_ofsset = '<span class="span-offset" data-depth="'.$depth.'"><img class="img-offset" src="https://'.$_SERVER['SERVER_NAME'].'/en/wp-content/plugins/multiaxis/images/imgoffset.svg"></span>';
							//}
						} else {
							if ($i > 2) {
								$no_children_cat_parents_class = '';
								//$no_children_cat_class = 'no-children-category-class';
								$no_children_cat_class = '';
								$img_ofsset = '<span class="span-offset" data-depth="'.$depth.'"><img class="img-offset" src="https://'.$_SERVER['SERVER_NAME'].'/en/wp-content/plugins/multiaxis/images/imgoffset.svg"></span>';
							} else 
							{
								$no_children_cat_class = '';
								$img_ofsset = '';
								$no_children_cat_parents_class = ' no-children-cat-parents-class';
							}
						}						
						$data_position = '';
						$data_position_class = '';
					}
					?>
				

					<?php 
						$this_terms = get_terms([
							'taxonomy' => $name_taxonomy,
							'hide_empty' => true,
							'parent' => $term->term_id,
						]);
						if ($checkTaxonomy && getThisChildren($this_terms, $name_taxonomy) > 0) {
							
							if ($i > 1) { 
								$expand_all = '<span class="expand-children expand-children-'.$i.'" data-depth="'.$i.'" data-expand="closed">Expand all <span class="expand-children-arrow-icon"></span></span>';
							 } else { 
								$expand_all = '<span class="expand-children expand-children-'.$i.'" data-depth="'.$i.'" data-expand="closed"><span class="expand-children-light-arrow-icon"><span class="expand-children-light-arrow-icon-hover">Expand all</span></span></span>';
						}
					} else {$expand_all = '';} ?>
				
					<li class="li-children-<?php echo $term->taxonomy;?> li-children-<?php echo $term->taxonomy;?>-<?php echo $i;echo $li_list_class;?> <?php echo $li_all_class;?><?php echo $no_children_cat_class;?><?php echo $no_children_cat_parents_class; ?>">
						<span class="all_filters all_filters_<?php echo $i; ?> <?php echo $term->taxonomy; echo $data_position_class;?>" <?php echo $data_position;?>><?php if ($i >= 2) { echo $img_ofsset; echo $check_mark; } ?>
							<span class="portfolio-filter-name portfolio-filter-name_<?php echo $i; ?> ">
								<span class="item_filters item-filters-<?php echo $i; ?>">
									<?php echo $input;?>
								</span>
								
								<span class="item_filters_names">
									<span class="name-terms <?php echo $checkbox_no_class;?>">
									<?php echo $term_image; ?>										
									<span class="item_term_name"><?php echo $term->name; ?>&nbsp;<span class="ma-nobr">(<span class="count-terms count-terms-<?php echo $term->term_id;?>" data-item-taxonomies="<?php echo $term->taxonomy;?>" data-item-count="<?php echo $term->term_id; ?>">
										<?php //echo $term->count;?><span class="count-terms-hover">The number of projects according to choice on other axes and to the option</span>
									</span><span class="count-terms-slash">/</span><span class="count-terms-const"><?php echo $term->count; ?>)<span class="count-terms-const-hover">The total number of projects relevant to the option</span></span>									
										<span class="description-star description-star-<?php echo $term->term_id;?>"> *
											<span class="description-star-hover">According to your query, nothing was found. Try to specify other parameters</span>
										</span></span>								
									<span class="ma-nobr ma-right"><?php if ($i < 2) { echo $check_mark; } ?><?php echo $expand_all;?></span></span>
									</span>
								</span>
							</span>
						</span>						
						<?php tree_new($term->term_id, $i); ?>
					</li>
				<?php

					if ( (int)$settings_decode->$name_taxonomy-1 == $c && !$checkNameTaxonomy){
							echo '</ul><div class="multiaxis-more-category more-display-none more-display-'.$name_taxonomy.'"><span class="multiaxis-more-category-text">More</span><span class="multiaxis-more-category-line"></span></div><ul class="ul-list-tree-taxonomy ul-display ul-display-none ul-display-none-'.$name_taxonomy.' ul-children-'.$name_taxonomy.' ul-children-'.$name_taxonomy.'-'.$i.' '.$ul_list_class.'" >';
					}
					
					$c++;
				} ?>
			</ul><?php echo $open_btn;?>
		
        <?php
    }
}

// Добавим подменю в меню админ-панели "Инструменты" (tools):
add_action('admin_menu', 'register_tax_setting_submenu_page');

function register_tax_setting_submenu_page() {
	add_submenu_page(
		'edit.php?post_type=portfolio',
		'Taxonomies settings',
		'Taxonomies settings',
		'manage_options',
		'taxonomies_settings',
		'taxonomies_settings_submenu_page_callback'
	);
}

function get_multiaxis_tax_settings(){
	
		global $wpdb;
		$table_name = $wpdb->prefix. "multiaxis_settings";
		$settings = $wpdb->get_var( "SELECT multiaxis_value FROM ".$table_name." WHERE multiaxis_key = 'taxonomies_setting_arr'" );
	
		return $settings;
	
}

function taxonomies_settings_submenu_page_callback() {
do_action('before_form_rendered');  
$settings = get_multiaxis_tax_settings();
	if ( isset($settings) ){
		$settings_decode = json_decode($settings);
	}
// контент страницы
?>	
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>
		<h3>Number of main categories</h3>
		<?php	
		// settings_errors() не срабатывает автоматом на страницах отличных от опций
		if( get_current_screen()->parent_base !== 'options-general' )
			settings_errors('название_опции');
													  
			$args_tax = array(						
				'_builtin' => false,
				'object_type' => array('portfolio'),
			);	

			$output_tax = 'objects'; // или objects
			$operator_tax = 'and'; // 'and' или 'or'
													  
			$portfolio_taxonomy_names = get_taxonomies( $args_tax, $output_tax, $operator_tax );
						

		?>
		<style>
			.tax-list {
				font-size: 18px;
				display: inline-block;
				width: 350px;
				margin-top: 10px;
			}		
		</style>
		<form action="edit.php?post_type=portfolio&page=taxonomies_settings" method="POST">
			<?php

				foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name) {
					$output = '';	
					if ($portfolio_taxonomy_name->name == 'filters') 
							{
								$portfolio_taxonomy_name->label = 'Industries';
							}

					$output .= str_replace('_', ' ', $portfolio_taxonomy_name->label);
					$string_name = (string)$portfolio_taxonomy_name->name;
					
					echo '<div class="tax-list">'.$output.'</div> <input type="number" min="0" value="'.$settings_decode->$string_name.'" name="taxonomies_setting_arr['.$portfolio_taxonomy_name->name.']"><br>';
				
				}
													  
				settings_fields("taxonomies_main_settings");     // скрытые защитные поля
				do_settings_sections("taxonomies_main"); // секции с настройками (опциями).
				submit_button();

			?>
		</form>
		
	</div>
<?php
}


function multiaxis_settings_create() {

    global $wpdb;
    $table_name = $wpdb->prefix. "multiaxis_settings";
    global $charset_collate;
    $charset_collate = $wpdb->get_charset_collate();
    global $db_version;

    if( $wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") !=  $table_name)
    {   $create_sql = "CREATE TABLE " . $table_name . " (
            multiaxis_id INT(11) NOT NULL auto_increment,
			multiaxis_key TEXT NOT NULL,
			multiaxis_value TEXT NOT NULL,
            PRIMARY KEY (multiaxis_id))$charset_collate;";
	 
	 dbDelta( $create_sql );
    }
    require_once(ABSPATH . "wp-admin/includes/upgrade.php");

    //register the new table with the wpdb object
    if (!isset($wpdb->multiaxis_settings))
    {
        $wpdb->multiaxis_settings = $table_name;
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $table_name);
    }

}
add_action( 'init', 'multiaxis_settings_create');

add_action('before_form_rendered','validate_tax_settings_form');
function validate_tax_settings_form(){
    if(isset($_POST['taxonomies_setting_arr']) && !empty($_POST['taxonomies_setting_arr'])){
		
		global $wpdb;
		$taxonomies_setting_arr = json_encode($_POST['taxonomies_setting_arr']);
		
		$settings = get_multiaxis_tax_settings();
		if ( isset($settings) ){		
		
			$wpdb->update( 
				$wpdb->prefix . 'multiaxis_settings', // указываем таблицу
				array( // 'название_колонки' => 'значение'				
					'multiaxis_value' => (string)$taxonomies_setting_arr,
				),
				array('multiaxis_key' => 'taxonomies_setting_arr'),
				array( '%s'),
				array( '%s')
			);
		} else {

			$wpdb->insert( 
				$wpdb->prefix . 'multiaxis_settings', // указываем таблицу
				array( // 'название_колонки' => 'значение'				
					'multiaxis_value' => (string)$taxonomies_setting_arr,
					'multiaxis_key' => 'taxonomies_setting_arr'
				),
				//array(),
				array( '%s'),
				array( '%s')
			);
			
		}
   }
}

add_filter( 'theme_portfolio_templates', 'multiaxis_template' );
add_filter( 'template_include', 'multiaxis_template_to_portfolio' );
define( 'ML_TEMPLATES_DIR', WP_PLUGIN_DIR . '/multiaxis/page-templates' );

## выбор шаблонов из плагина в атрибутах страницы
function multiaxis_template( $templates ) {

	## сканируем папку шаблонов в плагине	
	$array_templates = array_diff( scandir( ML_TEMPLATES_DIR ), array('.', '..') );

	foreach( $array_templates as $plugin_template ) {

		$str_template = str_replace( ".php", "", $plugin_template ); // очищаем от .php

		$str_template = str_replace( "-", " ", $str_template ); // очищаем от "-"

		// Выводим имя в формате: My Template
		$template_name = mb_convert_case( $str_template, MB_CASE_TITLE, 'UTF-8' );

		// Подключаем выбор шаблона в атрибутах
		$templates[ $plugin_template ] = $template_name;

	}

	return $templates;

}

## подключение шаблонов из плагина
function multiaxis_template_to_portfolio( $template ) {

	// запрашиваем активный шаблон текущей страницы
	$page_template = get_page_template_slug();

	## подключение шаблонов
	// сканируем папку с шаблонами
	$mland_templates = array_diff( scandir( ML_TEMPLATES_DIR ), array('.', '..') );

	foreach( $mland_templates as $mland_template ) {

		// сравнение активного шаблона и шаблона выбранного в атрибутах
		if ( $mland_template == basename ( $page_template )  && !is_archive() && !is_search() ) {

			// подключение шаблона
			return wp_normalize_path( ML_TEMPLATES_DIR . '/' . $mland_template );

		}

	}

	return $template;
}



// *********************************************
// ********************************************* 
// Получает посты
// *********************************************
// *********************************************

/**
 * @param string $info_level shows additional info. Can have values of 'basic', 'partical', or 'full'.
 */
function get_rest_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start, $info_level, $is_logged, $is_lock_feature_tag_edit){
	$args = array(
		'post_type'         => 'Portfolio',
		'post_status'       => 'publish',
		'orderby'           => array( $order_by => $order, 'title' => 'ASC' ),
		'meta_key'          => $meta_key,
		'posts_per_page'    => (int)$per_page,
		'offset'            => (int)$start,
		'meta_type'         => 'NUMERIC',
	);

	if ( count($tax_query) > 1 ) {
		$args['tax_query'] = $tax_query;
    	$args['orderby'] = array( $order_by => $order, 'title' => $order );
	}

	$posts = get_posts( $args );
	
	if ( empty( $posts ) )
		return new WP_Error( 'no_author_posts', 'Записей не найдено', [ 'status' => 404 ] );
	
	$projects = [];	
	$i = 0;
	foreach ($posts as $post) {
    $team_size = get_post_meta( $post->ID, 'team_size', true );
    $duration = get_duration( $post->ID );

    $info = [
      'duration' => str_replace(array('years', 'months', 'days', 'hours', 'minutes', 'seconds'), array('yrs', 'mo', 'd', 'h', 'm', 's'), $duration),
      'teamSize' => $team_size . ' ' . pluralize_text($team_size, ['member', 'members', 'members']),
      'weights' => '',
      'completionYear' => '',
      'ai' => ''
    ];


    if($info_level === 'partical' || $info_level == 'full'){
      $info = array_merge($info,[
        'completionYear' => get_completion_year( $post->ID ),
        'weights' => get_post_meta( $post->ID, 'weights', true )
      ]);
    }

    if($info_level == 'full'){
      $additional_description = get_post_meta( $post->ID, 'additional_description', true );
			$killing_features = get_post_meta( $post->ID, 'killing_features', true );
			$customer = get_post_meta( $post->ID, 'customer', true );

      $info = array_merge($info,[
        'ai' => [
           'ad' => [$additional_description, 'Additional Desc'],
           'kf' => [$killing_features, 'Killing Features'],
           'cus' => [$customer, 'Customers'] ]
      ]);
    }

		if ( $is_logged && !$is_lock_feature_tag_edit ) {
			$checkbox_post_id = $post->ID;
		} else {
			$checkbox_post_id = '';
		}
		
		$projects[] = ['title' => $post->post_title, 'img' => get_the_post_thumbnail_url( $post->ID, 'full' ), 'info' => $info, 'cpid' => $checkbox_post_id, 'link' => get_permalink( $post->ID, false ) ];
		$i++;
	}
	return $projects;
}
  
function get_multiaxis_post_rest( WP_REST_Request $request ){
  $time_stamp = hrtime(true);	
  
  $page = $request['page'];//page
  $multiaxis_id = $request['multiaxisId']; //multiaxisId	
  $order = $request['order'];//order
  $order_by = $request['orderBy'];//orderBy
  $selection = /*json_decode(*/$request['selection'];/*, true);*///selection
  $and_checkbox = $request['andCheckboxSwitch'];//andCheckboxSwitch
  $axis_array = $request['axisArray'];//axisArray
  $ha = $request['ha'];//ha	
  $post_id = $request['postId'];//postId
  $new_columns = $request['newColumns'];//newColumns
  $new_rows = $request['newRows'];//newRows
  $clear_all = $request['clearAll'];//clearAll
  $multiaxis_id = $request['multiaxisId'];//multiaxisId
  $int_columns = $request['newColumns'];
  $per_page = (int)$new_columns*(int)$new_rows;	
  $cur_page = $page;	
  $new_columns = transferAttribute($columns = $new_columns);	
  $page -= 1;
  $sortArr = transferSorting($order_by);	
  $order_by = $sortArr['orderby'];
  $meta_key = $sortArr['metakey'];
  $start = $page * $per_page;
  	
  
  $DEBUG = [];

  $multiaxis_shortcode_atts = [
  	'showselectedoptions' =>'false',
  	'selectotherprojects' => 'false',
  	'showadditionalaxis' => 'false',
  	'columns' => '4',
  	'rows' => '2',
  	'mode' => 'minimal',
  	'selection' => '',
  	'technologiesoperator' => 'OR',
  	'orderby'=>'weights',
  	'order'=>'DESC',
  	'edit' => 'false',
  	'page' => 1,
  	'time' => 'false',
	'multiaxis_id' => 'jt01',
  ];
  
  $start_time1 = microtime( true );	
  $this_shortcodes_atts = get_shortcode_on_page($post_id, 'multiaxis_portfolio')[0];
  if ( empty($this_shortcodes_atts) ) {
	$this_shortcodes_atts = get_shortcode_on_page($post_id, 'multiaxis_portfolio_new')[0];
  }
  $diff_time1 = microtime( true ) - $start_time1;
  $diff_time1 = sprintf( '%.6F', $diff_time1 );
	
	
  $start_time2 = microtime( true );	
  $this_shortcodes_atts = array_merge($multiaxis_shortcode_atts, $this_shortcodes_atts);
  $diff_time2 = microtime( true ) - $start_time2;
  $diff_time2 = sprintf( '%.6F', $diff_time2 );  

  $clear_all_filters = $clear_all;

  $start_time3 = microtime( true );	
	
  if ( ($ha == null || empty($ha) || $ha == 'undefined') && !is_user_logged_in() ) {
  		$ha = [];
  		$this_ha = convert_name_and_id_to_id_term($this_shortcodes_atts['selection'], true)['country'];
		
  	if ( isset($this_ha) && $clear_all_filters != 'clear_all' ) {
  		$server_ha = true;
  		$ha['country'] = $this_ha;
  		$clear_all_filters = '';
		$rr = '1.1';
  	} else {
		$rr = '1.2';
  		$ha = null;
  		$server_ha = false;
  		$clear_all_filters = 'clear_all';		
  	}
  } else {
	$rr = '2';  
  	$server_ha = true;
  	$ha = get_hidden_axis_array($ha);
  }	
  $diff_time3 = microtime( true ) - $start_time3;
  $diff_time3 = sprintf( '%.6F', $diff_time3 );  	

	
  $start_time4 = microtime( true );	
  if ($ha == null || $ha == 'null') {				
  	$taxonomies_object = $selection;
  } else {
  	$new_ha = [];
  	$new_ha = $ha;
  	$no_hidden = [];
  	$no_hidden = $selection;	
  	$taxonomies_object = [];
  	if ($no_hidden != null){
  		$taxonomies_object = array_merge($new_ha, $no_hidden);
  	} else {
  		$taxonomies_object = $new_ha;
  	}
  }
  $axis_array = array_keys($taxonomies_object);//new_rest_fix	
  $diff_time4 = microtime( true ) - $start_time4;
  $diff_time4 = sprintf( '%.6F', $diff_time4 );  	

  $start_time5 = microtime( true );	
  $taxonomy_length = count($taxonomies_object);
  $diff_time5 = microtime( true ) - $start_time5;
  $diff_time5 = sprintf( '%.6F', $diff_time5 );

  $start_time6 = microtime( true );	
  $operator = 'IN';
  $relation = 'AND';	
  $include_children = true;		
  $tax_query_obj = ['relation' => $relation];
  $tax_query = $tax_query_obj;
  //var_dump($taxonomies_object);
  $tax_query = make_tax_query($taxonomies_object, $and_checkbox);
  $diff_time6 = microtime( true ) - $start_time6;
  $diff_time6 = sprintf( '%.6F', $diff_time6 );	

  //var_dump( $tax_query );
  $start_time7 = microtime( true );  		
  $rtc = make_tax_query_for_and_check_alt($taxonomies_object, $and_checkbox)['array_count'];
  $diff_time7 = microtime( true ) - $start_time7;
  $diff_time7 = sprintf( '%.6F', $diff_time7 );

  $start_time8 = microtime( true ); 	
  $taxonomy_length = count($taxonomies_object);
  $diff_time8 = microtime( true ) - $start_time8;
  $diff_time8 = sprintf( '%.6F', $diff_time8 );	

  $start_time9 = microtime( true ); 	
  $count_post = count_blog_post($order_by, $order, $meta_key, $tax_query);
  $diff_time9 = microtime( true ) - $start_time9;
  $diff_time9 = sprintf( '%.6F', $diff_time9 );
	
  if ( $count_post == 0 ) { $view_pagination = false; } else { $view_pagination = true;}

  $DEBUG['this_ha'] = $this_ha;
  $DEBUG['rce'] = $rce;
  $DEBUG['HTTP_HOST'] = $request['HTTP_HOST'];
  $DEBUG['HEADERS'] = $request->get_headers();
  $DEBUG['timestamp'] = $time_stamp;

  $max_pag = ceil($count_post / $per_page);
  $is_lock_feature_tag_edit = $atts['mode'] == 'minimal' || ($atts['edit'] == 'false' && $atts['selectotherprojects'] == 'false');
  
  $is_logged = is_user_logged_in();

  $role = array_shift(wp_get_current_user()->roles);
  $info_level = 'basic';  

  if($is_logged && get_userlogin() == 'CEO'){
    $info_level = 'full';
  } else if($is_logged){
    $info_level = 'partical';
  }


  //=-================= 
  if($this_shortcodes_atts['edit'] == 'false'){
	  $start_time10 = microtime( true ); 
      $order = $request['order'];

			
			$and_checkbox = $this_shortcodes_atts['technologiesoperator'];
			$new_selection_array = get_selection_params($this_shortcodes_atts['selection']);
			$tax_query = make_tax_query_for_selection($new_selection_array, $and_checkbox);
   			
			$int_columns = (int)$this_shortcodes_atts['columns'];

      $page = $request['page'];
			$per_page = -1;
			$start = $page * $per_page;		
			
				$new_columns = $this_shortcodes_atts['columns'];	
				$new_rows = $this_shortcodes_atts['rows'];
				$per_page = (int)$new_columns*(int)$new_rows;
				$cur_page = $page;	
				$page -= 1;
				$start = $page * $per_page;	
				

      $count_post = count_blog_post($order_by, $order, $meta_key, $tax_query);
	  $new_columns = transferAttribute($this_shortcodes_atts['columns']);	
	  $diff_time10 = microtime( true ) - $start_time10;
  	  $diff_time10 = sprintf( '%.6F', $diff_time10 );
  }

  // =================
  $start_time11 = microtime( true ); 
  $DEBUG['feef'] = [$int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start, $info_level, $is_logged, $is_lock_feature_tag_edit];
  $diff_time11 = microtime( true ) - $start_time11;
  $diff_time11 = sprintf( '%.6F', $diff_time11 );	
	
  $start_time12 = microtime( true );	
  $projects = get_rest_multiaxis_post($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start, $info_level, $is_logged, $is_lock_feature_tag_edit);
  $diff_time12 = microtime( true ) - $start_time12;
  $diff_time12 = sprintf( '%.6F', $diff_time12 );	

  $timeLog = [
	  ['name' => 'Время работы функции get_shortcode_on_page', 'timelapse' => $diff_time1.' sec / '.$diff_time1*1000 .' ms'],
	  ['name' => 'Время смердживания дефолтных параметров шорткода с параметрами из запроса', 'timelapse' => $diff_time2.' sec / '.$diff_time2*1000 .' ms'],
	  ['name' => 'Время работы функции по получению скрытой оси', 'timelapse' => $diff_time3.' sec / '.$diff_time3*1000 .' ms'],
	  ['name' => 'Создание taxonomies_object', 'timelapse' => $diff_time4.' sec / '.$diff_time4*1000 .' ms'],
	  ['name' => 'Получение количества taxonomy_length', 'timelapse' => $diff_time5.' sec / '.$diff_time5*1000 .' ms'],
	  ['name' => 'Время работы функции по получению tax_query', 'timelapse' => $diff_time6.' sec / '.$diff_time6*1000 .' ms'],
	  ['name' => 'Время работы функции make_tax_query_for_and_check_alt', 'timelapse' => $diff_time7.' sec / '.$diff_time7*1000 .' ms'],
	  ['name' => 'Получение количества $taxonomy_length', 'timelapse' => $diff_time8.' sec / '.$diff_time8*1000 .' ms'],
	  ['name' => 'Время работы функции count_blog_post', 'timelapse' => $diff_time9.' sec / '.$diff_time9*1000 .' ms'],
	  ['name' => 'Время для получении параметров при edit=false', 'timelapse' => $diff_time10.' sec / '.$diff_time10*1000 .' ms'],
	  ['name' => 'Время получения параметров debug', 'timelapse' => $diff_time11.' sec / '.$diff_time11*1000 .' ms'],
	  ['name' => 'Время работы функции get_rest_multiaxis_post', 'timelapse' => $diff_time12.' sec / '.$diff_time12*1000 .' ms' ],
  ];	
  $DEBUG['request']	= $request;
  $DEBUG['this_shortcodes_atts'] = $this_shortcodes_atts;
  $return_array = [
  'responseTaxonomiesCount' => $rtc,
  'timeLog' => $timeLog,
  'trueTaxName' => get_label_taxonomy(),
  'serverHa' => $server_ha,
  'axisArray' => $axis_array,
  'axisArrayServer' =>  $axis_array_server,	  
  'taxonomyLengthServer' => $taxonomy_length,
  'taxonomyLength' => $taxonomy_length,
  'clearAllFilters' => $clear_all,
  'multiaxisCountPosts' => $count_post,
  'pagination' => ['page' => $page, 'perPage' => (int)$per_page, 'curPage' => (int)$cur_page, 'count' => $count_post, 'viewPagination' => $view_pagination, 'intColumns' => $int_columns, 'start' => $start],
  'projects' => $projects,
  'DEBUG' => $DEBUG,
  //'caf' => $clear_all_filters,
  'ha' => $ha,
  'rr' => $rr,
  ];

  return $return_array;//$projects;
}


add_action( 'rest_api_init', function(){

	register_rest_route( 'multiaxis/v2', '/get-posts/', [
		'methods'  => 'GET',
		'callback' => 'get_multiaxis_post_rest',
	] );

} );




add_shortcode('multiaxis_portfolio_new', 'get_multiaxis');

function get_multiaxis($atts){ 
	$post_id = get_the_ID();
	$atts = shortcode_atts( default_shortcode_atts(), $atts );

	['is_current_multiaxis' => $is_current_multiaxis,	'current_multiaxis_id' => $current_multiaxis_id] = get_current_multiaxis($post_id, $atts);
	dd($is_current_multiaxis);
	dd( $current_multiaxis_id);

	$int_columns = (int)$atts['columns'];
	$int_rows = (int)$atts['rows'];
	$new_columns = transferAttribute($int_columns);
	//$order_by = 'meta_value_num';
	$order = $atts['order'];
	//$meta_key = 'weights';

	$per_page = $int_columns * $int_rows;
	//tax_shortcode $selection = $atts['selection'];
	//tax_shortcode $tech_operator = $atts['technologiesoperator'];
	//tax_shortcode $tax_query = get_tax_query_shortcode($selection, $tech_operator);
	$request_uri = $_SERVER['REQUEST_URI'];
	$exploded_url = get_exploded_url($request_uri);

	$url  = explode("?" , $exploded_url['url_to_parse']);
	$url_params = get_url_param_array($url);
	if ( empty($url_params['multiaxisId']) ){
		$url_params['multiaxisId'] = $current_multiaxis_id;
	}
	
	 

	$request_details = $url_params['desc'];
	$details =  $request_details ? get_details_by_hash($request_details) : array('title' => '', 'description' =>'', 'hash' =>'', 'compact_view' => '0');
	
	$details['id'] = $exploded_url['id'];

	$is_compact_view = $details['compact_view'] === '1';

	$dynamic_description = get_dynamic_description(true);

	
	$new_order_by = empty( $url_params['orderBy']) ? $atts['orderby'] : $url_params['orderBy'];
		 
	
	if ( $atts['selection'] == '' || isset($url_params) && $is_current_multiaxis) {
		$tech_operator = $url_params['technologiesOperator'];	
		$no_hidden_axis = get_no_hidden_axis_array($url_params['selection']);
		$hidden_axis = get_hidden_axis_array($url_params['ha']);
		$taxonomies_object = array_merge($no_hidden_axis, $hidden_axis);
		//dd($taxonomies_object);
		$sortArr = transferSorting($new_order_by);	
		
        
	} else {
		$selection = $atts['selection'];
		$tech_operator = $atts['technologiesoperator'];
		$taxonomies_object = convert_name_and_id_to_id_term($selection, true);  
		$hidden_axis = get_hidden_axis_selection_array($taxonomies_object);

		$sortArr = transferSorting($atts['orderby']);	
	}
		//dd($sortArr);
	$order_by = $sortArr['orderby'];//$atts['orderby'];
	$meta_key = $sortArr['metakey'];


	$script_params = get_script_params($taxonomies_object, $tech_operator, $hidden_axis, $atts);


	$tax_query = make_tax_query($taxonomies_object, $tech_operator);
	
	$current_state = get_current_state($atts, $url_params, $atts['multiaxis_id'], $is_compact_view);
	//dd($current_state);
	//$tax_query = [];
	//$tax_query = get_tax_query_url($selection_url, $tech_operator);	

	$start = 0;
	$page = $atts['page'];
	$cur_page = $page;
	$multiaxis_id = $atts['multiaxis_id'];

	$args_tax = get_args_tax();

	$active_axis_name = get_active_axis_name($tax_query, $script_params['axis_array']);

    $portfolio_taxonomy_names = get_taxonomies( $args_tax, 'objects', 'and' );
	$img_folder = plugins_url( '/images/',  __FILE__);
	//$clear_all_filters = null;
	//$count_portfolio_publish = wp_count_posts('portfolio')->publish;
	//dd($tax_query);
	$icw = $is_compact_view ? 'true' : 'false';
	$script = '<script>
		var newColumns = '.$atts["columns"].';
		var newRows = '.$atts["rows"].';
		var description = `'.to_save_transfer($details['description']).'`;
		var title = `'.to_save_transfer(get_save_text($details["title"])).'`;
		var hash =  `'.$details["hash"].'`;
		var shortLink = `'.$details["id"].'`;
		var compactView = '.$icw.';			  
	  </script>';
				
				
	$count = get_count($order, $meta_key, $tax_query);
	$view_pagination = ($count > 0) ? true : false;	
	//var_dump( get_url_params() );

	$output = '<div class="multiaxis multiaxis-id" id="multiaxis-id-'.$multiaxis_id.'" data-multiaxis-id="'.$multiaxis_id.'">';
	$output .= $dynamic_description;
	$output .= $script;	
	$output .= '<div class="multiaxis-panel-container"><div class="portfolio-taxonomy-container">';
		$output .= get_tag_container($current_state['tag_list']); 
		$output .= get_axes_container($portfolio_taxonomy_names, $img_folder, $count, $current_state['axis_container'], $atts['showadditionalaxis'], $active_axis_name);
	$output .= '</div>';
	$output .= get_controls($img_folder, $count, $order, $order_by, $current_state, $url_params, $atts, $is_current_multiaxis);
	$output .= '</div>';
	$output .= get_project_container($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start, $count, $page, $cur_page, $view_pagination, $multiaxis_id, $script_params, $post_id);
	$output .= '</div>';

	//ob_get_clean();
	return $output;
}

function get_axes_container($portfolio_taxonomy_names, $img_folder, $count, $axis_state, $show_additional_axis, $active_axis_name){

	if ( !$axis_state['exist_dom'] ) return '';

	$display = $axis_state['visibility'] ? 'block':'none';
	if ($show_additional_axis == 'false') {
		$portfolio_taxonomy_names = array_slice($portfolio_taxonomy_names, 0, 4);
	}

	$output .= '<div class="portfolio-axis-container" style="display:'.$display.';">';
		$output .= get_taxonomies_axis_btns($portfolio_taxonomy_names, $show_additional_axis, $active_axis_name);
		$output .= get_taxonomy_list($portfolio_taxonomy_names, 0, $img_folder, $count, $active_axis_name);
	$output .= '</div>';
	
	return $output;
}

function get_project_container($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start, $count, $page, $cur_page, $view_pagination, $multiaxis_id, $script_params, $post_id) {
	$get_projects = get_projects($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start);
	
	$get_pagination = get_pagination_new($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start);
	$script = get_scripts($int_columns, $per_page, $order_by, $order,$script_params, $count, $multiaxis_id, $meta_key);
	$before_cont = '<div class="inner-box no-right-margin darkviolet" data-post-id="'.$post_id.'"><div class="multiaxis_pag_loading"><div class="multiaxis_container"><div class="multiaxis-content">';
	$after_cont = '</div></div></div></div>';

	return $before_cont.$get_projects.$get_pagination.$script.$after_cont;
	
}

function get_projects($int_columns, $new_columns, $order_by, $order, $meta_key, $per_page, $tax_query, $start){	
	
	//$post_param = [];
	$args = [
				'post_type'         => 'Portfolio',			
				'post_status'       => 'publish',
				'orderby'           => [ $order_by => $order, 'title' => $order ],
				'meta_key' 			=> $meta_key,
				'posts_per_page'    => $per_page,
				'offset'            => $start,				
				'meta_type' 		=> 'NUMERIC',
				'tax_query'			=> $tax_query,
			];
	
	if (count($tax_query) == 1) {
		unset($args['tax_query']); 
		$args['orderby'] = [ $order_by => $order, 'title' => 'ASC' ];
	}
	
	$output = '<div id="portfolio-wrapperss" class="pf-col4 isotope '.$new_columns.'-new">';
	
	$all_blog_posts = new WP_Query($args);	

    if ( $all_blog_posts->have_posts() ) {
		
		//$view_pagination = true;			
		while ( $all_blog_posts->have_posts() ) {
			
			$all_blog_posts->the_post();			
			require( dirname(__FILE__).'/multiaxis-tpl/multiaxis-heading-tpl-hover-grid-new.php'); 
					
		}
		//$count_post = count_blog_post($order_by, $order, $meta_key, $tax_query);
	} else {
		//$count_post = 0;
		//$view_pagination = false;		
			
		$output .= '<div class="search-not-found"><h3>According to your query, nothing was found. Try to specify other parameters</h3></div>';	
			 
	}
	wp_reset_postdata();
	
	//$post_param['view_pagination'] = $view_pagination;
	//$post_param['count_post'] = $count_post;
		
	$output .= '</div>';

		
	return $output;//$post_param;
	
}


function get_pagination_new($page, $per_page, $cur_page, $count, $view_pagination, $int_columns, $start){
		
	$previous_btn = true;
    $next_btn = true;
    $first_btn = true;
    $last_btn = true;
        
	$left_btn = '&#60;&#60;';
	$pre = 0;
		
    $no_of_paginations = ceil($count / $per_page);

    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7) {
			if ( $int_columns != '0') {
				$end_loop = 7; } else {$end_loop = 2;}
		}
        else $end_loop = $no_of_paginations;
    }

	if ($view_pagination == true) {
		
		$output = '<div class="text-center multiaxis-pagination"><div><ul>';				
				
		if ($first_btn && $cur_page > 1) {
			$output .= '<li data-pag="1" class="active">'.$left_btn.'</li>';
		} else if ($first_btn) {
			$output .= '<li data-pag="1" class="inactive">'.$left_btn.'</li>';
		}
		if ($previous_btn && $cur_page > 1) {
			$pre = $cur_page - 1;
			$output .= '<li data-pag="'.$pre.'" class="active">&#60;</li>';
		} else if ($previous_btn) {
			$output .= '<li class="inactive">&#60;</li>';
		}
		for ($i = $start_loop; $i <= $end_loop; $i++) {
			if ($cur_page == $i){
				$output .= '<li data-pag="'.$i.'" class="selected">'.$i.'</li>';
			} else {
				$output .= '<li data-pag="'.$i.'" class="active">'.$i.'</li>';
			}
		}
		if ($next_btn && $cur_page < $no_of_paginations) {
			$nex = $cur_page + 1;
			$output .= '<li data-pag="'.$nex.'" class="active">></li>';        
		} else if ($next_btn) {
			$output .= '<li class="inactive">></li>';
			
		}
	
		if ($last_btn && $cur_page < $no_of_paginations) {
			$output .= '<li data-pag="'.$no_of_paginations.'" class="active">>></li>';
		
		} else if ($last_btn) {
			$output .= '<li data-pag="'.$no_of_paginations.'" class="inactive">>></li>';
		}
			$output .= '</ul></div></div>';
	}

	return $output;
}


function get_tag_container($tag_list_state){

	if ( !$tag_list_state['exist_dom'] ) return '';

	$display = $tag_list_state['visibility'] ? 'flex':'none';
	return '<div class="portfolio-tag-list-container" style="display:'.$display.';"><div class="portfolio-tag-list-row"><div class="portfolio-tag-list"></div></div></div>';
}

function get_controls($img_folder, $count_portfolio_publish, $order, $order_by, $current_state, $url_params, $atts, $is_current_multiaxis){

	$selected_options_btn = ''; //for tag list
	$tag_list_btn = $current_state['tag_list_btn'];
	$heading_tag_list = $tag_list_btn['show'] ? 'Show selected options':'Hide selected options';

	$display_tag_list_btn = $tag_list_btn['visibility'] ? 'inline-block':'none';

	$selected_options_btn = '<div class="show-selected-options" style="display:'.$display_tag_list_btn.';"><span class="show-selected-options-icon"></span>'.$heading_tag_list.'</div>'; 
	
	

	$projects_choice_btn = '';  // for axis container
	$axis_container = $current_state['axis_container'];
	if ( $axis_container['exist_dom'] ) {
		//$display_axis_container = $axis_container['visibility'] ? 'inline-block':'none';
		$heading_axis_container = $axis_container['visibility'] ? 'Hide projects choice':'Select other projects'; //Hide selected options
		$projects_choice_btn = '<div class="select-other-projects"><span class="select-other-projects-icon"></span>'.$heading_axis_container.'</div>'; // for axis container
	} 
	

	$order_by_btn = get_order_by_new($url_params['orderBy'], $atts['orderby'], $is_current_multiaxis);
	$order_btn = get_order_new( $url_params['order'], $order, $is_current_multiaxis);
	$sort_by = '<span class="multiaxis-sorting-text">Sort by&nbsp;</span>';
	$count_container = '<div class="multiaxis-count-posts-div"><span class="multiaxis-count-posts">'.$count_portfolio_publish.'</span></div>';
	$projects = '<span class="multiaxis-sorting-text"> projects</span>';

	$output = '<div class="multiaxis-btn-options">';		
    $output .= '<div class="multiaxis-btn-hide-panel">';
    $output .= $selected_options_btn;
	$output .= $projects_choice_btn;
	$output .= '</div>';
  
    
    $output .= '<div class="multiaxis-sort-btns">';
    $output .= '<div class="multiaxis-sorting-post">';
	$output .= $sort_by . $order_by_btn . $order_btn;
	$output .= $count_container;
	$output .= $projects;
    if ( is_user_logged_in()  ) {
        $output .= get_admin_btn($img_folder, $atts['time']);
	}
	$output .= '</div></div></div>';
	
	return $output;
}

function get_taxonomy_list($portfolio_taxonomy_names, $depth = 0, $img_folder, $count_portfolio_publish, $active_axis_name){
	$output = '';	
	$active_class = '';
	$originality = 'true';
	$k = 0;	
	
	foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name) {
		
		$all_tech = get_tech_heading('All technologies:', '', $depth, $portfolio_taxonomy_name->name);
		$feat_tech = get_tech_heading('Featured technologies:', '', $depth, $portfolio_taxonomy_name->name);
		
		$active_class = ($portfolio_taxonomy_name->name == $active_axis_name) ? ' taxonomy-list-active' : '';
		$tech_btn = ($portfolio_taxonomy_name->name == 'technologies') ? get_tech_btn() : [];
		$top_tech_div = ($portfolio_taxonomy_name->name == 'technologies') ? '<div>'.get_tree($portfolio_taxonomy_name->ID, 1, $portfolio_taxonomy_name->name, $img_folder, 'false', false).'</div>' : '';
		$other = true;
		
		$all_count = '<div class="all-count-taxonomy li-children-'.$portfolio_taxonomy_name->name.'"><span class="all_filters all_filters_'.$depth.' filters" data-position="closed">
					  <span class="portfolio-filter-name portfolio-filter-name_'.$depth.' portfolio-filter-name-no-grid">
					  <span class="item_filters item-filters-'.$depth.'"></span>All ('.wp_get_postcount_new($portfolio_taxonomy_name->name).')</span></span></div>';
		$double_count = '<span class="double-count">Results: <span style="font-weight: 700;"><span class="multiaxis-count-posts">'.$count_portfolio_publish.'</span> projects</span></span>';
		$clear_tech_btn = '<div class="clear-technologies-btn clear-taxonomy-'.$portfolio_taxonomy_name->name.'" data-clear-taxonomy-name="'.$portfolio_taxonomy_name->name.'">Clear '.$portfolio_taxonomy_name->label.'<span class="closed-cross-icon"></span></div>';
		
		$output .= '<div class="taxonomy-list'.$active_class.'" data-taxonomy-name="taxonomy-'.$portfolio_taxonomy_name->name.'">';
		$output .= $tech_btn['tech_operator'] . $all_count  .  $tech_btn['search'] . $tech_btn['all_collapse'] . $double_count; 
		$output .= $clear_tech_btn .'<div class="ul-list-taxonomy ul-list-taxonomy-'.$portfolio_taxonomy_name->name.'">';
		$output .= $feat_tech;
		$output .= $top_tech_div;
		$output .= $all_tech . '<div class="other-technologies '.$portfolio_taxonomy_name->name.'">';
		$output .= get_tree($portfolio_taxonomy_name->ID, $depth, $portfolio_taxonomy_name->name, $img_folder, $originality, $other);
		$output .= '</div></div></div>';
		$k++;
	}
	
	return $output;
}

function get_taxonomies_axis_btns($portfolio_taxonomy_names, $show_additional_axis, $active_axis_name){
	$hide_add_axis = '';
	$show_add_axis =  '';
	
	if ($show_additional_axis == 'true' ) {
		$hide_add_axis =  '<div class="show-adding-axis-btn" data-show-adding-axis-btn="true"><span class="show-adding-axis-btn-left-icon"></span>Hide additional axes</div>';
		$show_add_axis = '<div class="show-adding-axis-btn" data-show-adding-axis-btn="false">Show additional axes<span class="show-adding-axis-btn-right-icon"></span></div>';
	}

	$clear_all = '<div class="portfolio-clear-all-filters-btn">Clear all filters<span class="closed-cross-icon"></span></div>';
	$exclamation_with_description = '<span class="exclamation-icon"><span class="exclamation-icon-text"><span class="text-bold">By default, logical OR is used on Technologies axis</span>. That is, if you select two or more options, the filter will choose  projects that correspond to at least one of the selected options. 
You also have the option <span class="text-bold">to switch the logic from OR to AND</span> for those cases when you want to select projects that correspond to all selected options.</span></span>';
 
 	$idx_of_current_active_axis = array_search($active_axis_name, array_keys($portfolio_taxonomy_names));
	$c = 3;
	

	$output = '<div class="portfolio-taxonomy-name-list">';
	$k = 0;
	foreach ($portfolio_taxonomy_names as $portfolio_taxonomy_name) {
		
		$active_class = ($portfolio_taxonomy_name->name == $active_axis_name) ? 'item_filters_active ' : ' ';
		
		$is_visible_additional_btns = $k > $c  && $idx_of_current_active_axis <= $c ;


		$hidden_class = $k > $c ? ' portfolio-taxonomy-name-show' : '';
		$new_show_add_axis = $hide_add_axis;
		
		if ($is_visible_additional_btns) {
			$hidden_class = ' portfolio-taxonomy-name-hidden';
			$new_show_add_axis = $show_add_axis;
		}
	

		//$hidden_class =  $is_visible_additional_btns ? ' portfolio-taxonomy-name-hidden' : ' ';
		//$show_add_axis1 = $is_visible_additional_btns ? $show_add_axis : $hide_add_axis;
		$tech_info = ($portfolio_taxonomy_name->name == 'technologies') ? $exclamation_with_description : '';
		$portfolio_taxonomy_name->label = ($portfolio_taxonomy_name->name == 'filters') ?  'Industries' : $portfolio_taxonomy_name->label;
		
		$output .= '<div class="'.$active_class.'portfolio_taxonomy_name portfolio-taxonomy-name-'.$portfolio_taxonomy_name->name.$hidden_class.'" id="taxonomy-'.$portfolio_taxonomy_name->name.'" data-taxonomy-name-list="'.$portfolio_taxonomy_name->name.'">'.$portfolio_taxonomy_name->label.$tech_info.'</div>';
		
		$k++;
	}
	
	$output .= $new_show_add_axis;
	$output .= $clear_all;
	$output .= '</div>';
	
	return $output;
}

function get_current_state($atts, $url_param_array, $multiaxis_id, $is_compact_view){
    //if ( $panel_container ['visibility'] == true )
	

	// btn select other projects
	/*$select_other_projects = [
		'exist_dom' => true, 
		'visibility' => true 
	];*/

	/*if ($atts['selectotherprojects'] == 'false' && $atts['mode'] == 'full' && $atts['edit'] == 'true' 
	|| $atts['selectotherprojects'] == 'true' && $atts['mode'] == 'minimal' && $atts['edit'] == 'true' 
	|| $is_compact_view) { 
		$output = '<div class="select-other-projects"><span class="select-other-projects-icon"></span>Select other projects</div>';		
	 } elseif ($atts['selectotherprojects'] == 'true' && $atts['mode'] == 'full' && $atts['edit'] == 'true') {			
		$output = '<div class="select-other-projects"><span class="select-other-projects-icon"></span>Hide projects choice</div>';
	 } */


	


	//  --------

	 //if tag_list['visibility'] == true

	//dd($is_compact_view);

	$selection_no_hidden = get_selection_params($atts['selection']);
	unset($selection_no_hidden['country']);
	unset($selection_no_hidden['featured-tags']);//featured_tags	
	unset($array_count['customer']);//customer


	// tag-list btn
	$tag_list_btn = [
		'show' => false, 
		'visibility' => true
	];
  
	if ($atts['showselectedoptions'] == 'true' && $atts['selection'] != '' &&  $atts['mode'] != 'full' || $is_compact_view) {	
		$tag_list_btn['show'] = true;				
	} elseif ($atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full') {
		$tag_list_btn['show'] = false;

		if ($atts['selection'] == ''){
			$tag_list_btn['visibility'] = false;
		}

	}

	/*<?php if (
		$atts['showselectedoptions'] == 'true' && $atts['selection'] != '' &&  $atts['mode'] != 'full') {	?> 		
		<div class="show-selected-options"><span class="show-selected-options-icon"></span>Show selected options</div>						
	  <?php } elseif (
		$atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full'
		) { ?>
		<div class="show-selected-options" <?php if ($atts['selection'] == '') {echo 'style="display:none;"';}?> ><span class="show-selected-options-icon"></span><?php echo $is_compact_view ? 'Show selected options': 'Hide selected options'; ?></div>					
	  <?php } */

	// --------------
	
	// panel container
	$axis_container = [
		'exist_dom' => false, 
		'visibility' => true
	];

	if (
		$atts['selectotherprojects'] == 'true' && $atts['mode'] == 'full' && $atts['edit'] == 'true' || 
		$atts['selectotherprojects'] == 'false' && $atts['mode'] == 'full' && $atts['edit'] == 'true' || 
		$atts['selectotherprojects'] == 'true' && $atts['mode'] == 'minimal' && $atts['edit'] == 'true'
		) {
		$axis_container['exist_dom'] = true;
	}

	

	if ($atts['selectotherprojects'] == 'true' && $atts['mode'] == 'full' && $atts['edit'] == 'true' ) { 
		$axis_container['visibility'] = true;
	} elseif (  $atts['edit'] == 'true' && ($atts['selectotherprojects'] == 'false' && $atts['mode'] == 'full'  || 
				$atts['selectotherprojects'] == 'true' && $atts['mode'] == 'minimal')  
		)  { 
		$axis_container['visibility'] = false;
	}

	


	// tag list
	$tag_list = [
		'exist_dom' => true, 
		'visibility' => true 
	];

	if ($atts['showselectedoptions'] == 'false' && $atts['mode'] == 'minimal' && $atts['selectotherprojects'] == 'false' && $atts['showadditionalaxis'] == 'false' )  {
		$tag_list['exist_dom'] = false;
	} 

	if (
		
		$atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full' && !empty( $selection_no_hidden ) ||
		!empty( json_decode($url_param_array['selection'], true ) ) && $multiaxis_id == $url_param_array['multiaxisId'] ||
		!empty($url_param_array['ha']) && is_user_logged_in() ||
		!empty( json_decode($url_param_array['selection'], true ) ) && !empty($url_param_array['ha']) ) { 	
			$tag_list['visibility'] = true;	
	} elseif  ( 
		
			$atts['showselectedoptions'] == 'true' && $atts['mode'] == 'minimal' || 
			$atts['selection'] == '' && $atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full' || 
			!empty($url_param_array['ha']) && empty( json_decode($url_param_array['selection'], true ) ) ||  
			$atts['showselectedoptions'] == 'true' && $atts['mode'] == 'full' && empty( $selection_no_hidden ) && !is_user_logged_in()  
		) {
		$tag_list['visibility'] = false;
	}

	if($is_compact_view){
		$axis_container['visibility'] = false;
		$tag_list_btn['show'] = true;
		$tag_list['visibility'] = false;
	}

	
	$current_state = [
		//'select_other_projects'=> $select_other_projects,
		'tag_list'=> $tag_list,
		'axis_container' => $axis_container,
		'tag_list_btn' => $tag_list_btn,
	];

	//dd($current_state);

	return $current_state;
}


// Скорее всего не будет использоваться
function get_tax_query_shortcode($selection, $tech_operator){
	//$new_selection_array = get_selection_params($selection);	
	$new_selection_array = convert_name_and_id_to_id_term($selection, true);            
	//$tech_operator_shortcode = ($tech_operator == "AND") ? true : false;
	//$tech_operator_shortcode = false;	
	//$tax_query = make_tax_query_for_selection($new_selection_array, $tech_operator_shortcode);
	$tax_query = make_tax_query_for_selection_id($new_selection_array, $tech_operator);
	return $tax_query;
}

function get_tax_query_url($selection, $tech_operator){
	
	$taxonomies_object = json_decode(str_replace('%22', '"', $selection), true);
	$tax_query =  make_tax_query($taxonomies_object, $tech_operator);         
	
	return $tax_query;
}

function get_args_tax(){

	$args_tax = [
		'public' => true,
        '_builtin' => false,
        'object_type' => ['portfolio'],
    ];

    if ( is_user_logged_in() ) unset($args_tax['public']); 	

	return $args_tax;
}

function default_shortcode_atts(){
	return [
		'showselectedoptions' =>'false',
		'selectotherprojects' => 'false',
		'showadditionalaxis' => 'false',
		'columns' => '4',
		'rows' => '2',
		'mode' => 'minimal',
		'selection' => '',
		'technologiesoperator' => 'OR',
		'orderby'=>'weights',
		'order'=>'DESC',
		'edit' => 'false',
		'page' => 1,
		'time' => 'false',
		'multiaxis_id' => 'jt01',
	];
}

function get_current_multiaxis($post_id, $atts){	
	 $shortcodes_params_arr = get_shortcode_on_page($post_id, 'multiaxis_portfolio_new' );
	 $is_current_multiaxis = false;
	
	foreach ($shortcodes_params_arr as $shortcode) {
		if ( $shortcode['mode'] == 'full' && $shortcode['edit'] == 'true' && $atts['multiaxis_id'] == $shortcode['multiaxis_id']) {			
			$is_current_multiaxis = true;
			$current_multiaxis_id = $shortcode['multiaxis_id'];
			break;
		}		
	}
	$return_array = ['is_current_multiaxis' => $is_current_multiaxis, 'current_multiaxis_id' => $current_multiaxis_id];
	return $return_array;
}


function get_exploded_url($request_uri){

	//$url = $_SERVER['REQUEST_URI'];
	$url = str_replace('&amp;','&', $request_uri);
	$url = urldecode($url);
	$exploded_url = explode_multiaxis_url($url);	
	return $exploded_url;

}

function get_no_hidden_axis_array($selection_url){

	$no_hidden_axis = json_decode($selection_url, true);	
	$no_hidden_axis = is_array($no_hidden_axis) ? $no_hidden_axis : [];
	return $no_hidden_axis;
}

function get_active_axis_name($tax_query, $axis_array) {
	//$ss = count($tax_query);
	//unset($tax_query['relation']);
	if ( count($axis_array) > 0 ){
		$active_axis_name = $axis_array[0];
	} else {
		$active_axis_name = 'servicestax';
	}
	//dd($axis_array);

	return $active_axis_name; 
}

function get_scripts($int_columns, $per_page, $order_by, $order, $script_params, $count, $multiaxis_id, $meta_key){
	$script = '<script>';
	$script .= 'var selectionOptions = '.json_encode($script_params["selection_options"]).';'.PHP_EOL; 
	$script .= 'var showSelectedOptions = '.json_encode($script_params["show_selected_options"]).';'.PHP_EOL;
	$script .= 'var selectOtherProjects= '.json_encode($script_params["select_other_projects"]).';'.PHP_EOL;
	$script .= 'var showAdditionalAxis = '.json_encode($script_params["show_additional_axis"]).';'.PHP_EOL;
	$script .= 'var mode = '.json_encode($script_params["mode"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"] = {columns: "'.$int_columns.'"};'.PHP_EOL;
	//$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["newColumns"] = "'.$int_columns.'";'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["rows"] = "'.$per_page/$int_columns.'";'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["editTags"] = '.json_encode($script_params["edit"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["newMetaKey"] = '.json_encode($meta_key).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["newOrderBy"] = '.json_encode($script_params["order_by"]).';'.PHP_EOL;	
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["newOrder"] = "'.$order.'";'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["taxonomyLength"] = '.json_encode($script_params["taxonomy_length"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["taxonomyLengthServer"] = '.json_encode($script_params["taxonomy_length"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["axisArray"] = '.json_encode($script_params["axis_array"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["axisArrayServer"] = '.json_encode($script_params["axis_array"]).';'.PHP_EOL;	
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["responseTaxonomiesCount"] = '.json_encode($script_params["count_of_terms_taxonomies"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["newAndCheckbox"] = '.json_encode($script_params["tech_operator"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["newTagsArray"] = '.json_encode($script_params["new_tags_array"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["trueTaxName"] = '.json_encode($script_params['true_tax_name']).PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["isLoggedIn"] = '.json_encode( is_user_logged_in() ).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["multiaxisCountPosts"] = '.$count.';'.PHP_EOL;
	//$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["mode"] = '.json_encode($script_params["mode"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["serverHa"] = '.json_encode($script_params["server_ha"]).';'.PHP_EOL;
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["clearAllFilters"] = '.json_encode($script_params["clear_all_filters"]).';'.PHP_EOL;	
	$script .= 'window["multiaxis-id-'.$multiaxis_id.'"]["multiaxisId"] = "multiaxis-id-'.$multiaxis_id.'";'.PHP_EOL;
	$script .= '</script>';

	return $script;
}

function get_script_params($taxonomies_object, $tech_operator, $ha, $atts){
	
	
	$server_ha = !empty($ha);	 
	$taxonomy_length = count($taxonomies_object); 
    $count_of_terms_taxonomies = make_tax_query_for_and_check_alt($taxonomies_object, $tech_operator)['array_count'];
	$new_taxonomies_object = convert_id_to_name_term($taxonomies_object);
	$new_taxonomies_object = array_filter($new_taxonomies_object);
	$is_user_logged_in = is_user_logged_in();
	
	if ( !$is_user_logged_in ) {		
		$new_taxonomies_object = delete_hidden_axes($new_taxonomies_object);
		//dd( $new_taxonomies_object ); 		
    	$axis_array = array_keys($new_taxonomies_object);  
	} else {
		$axis_array = array_keys($taxonomies_object);
	}

	$hidden_axes_arr = get_hidden_axis_selection_array($taxonomies_object);	
	$clear_all_filters = get_clear_all($hidden_axes_arr);
	$true_tax_name = get_label_taxonomy();

	//dd( $tech_operator );
	//dd( $taxonomies_object ); 
	//dd( $count_of_terms_taxonomies );
	$tech_operator = ($tech_operator == 'AND') ? true : false;
	$return_array['selection_options'] = $is_user_logged_in ? $atts['selection'] : [];
	$return_array['show_selected_options'] = $atts['showselectedoptions'];
	$return_array['select_other_projects'] = $atts['selectotherprojects'];
	$return_array['show_additional_axis'] = $atts['showadditionalaxis'];
	$return_array['mode']= $atts['mode'];
	$return_array['edit']= $atts['edit'];
	$return_array['order_by']= $atts['orderby'];
	$return_array['server_ha'] = $server_ha;
	$return_array['taxonomy_length'] = $taxonomy_length;
	$return_array['axis_array'] = $axis_array;
	$return_array['count_of_terms_taxonomies'] = $count_of_terms_taxonomies;
	$return_array['new_tags_array'] = $new_taxonomies_object;
	$return_array['tech_operator'] = $tech_operator;
	$return_array['clear_all_filters'] = $clear_all_filters;
	$return_array['true_tax_name'] = $true_tax_name;
	return $return_array;
	
}

function get_clear_all($hidden_axes_arr){

	$clear_all_filters = !empty($hidden_axes_arr) ? false : '';

	return $clear_all_filters;

}

function delete_hidden_axes($taxonomies_object){
	$hidden_axes = get_hidden_axis();		
	
	foreach ($hidden_axes as $hidden_axis) {
		if ( array_key_exists($hidden_axis, $taxonomies_object ) ) {
			unset($taxonomies_object[$hidden_axis]);
		}
	}
	
	return $taxonomies_object;
}

function get_hidden_axis_selection_array($taxonomies_object){
	$hidden_axes = get_hidden_axis();
	return array_intersect_key($taxonomies_object, array_flip($hidden_axes));
}

function get_hidden_axis(){
	$args_tax = [
		'public' => false,
        '_builtin' => false,
        'object_type' => ['portfolio'],
    ];

    $portfolio_taxonomy_names = array_values(get_taxonomies( $args_tax, 'names', 'and' ));

	return $portfolio_taxonomy_names;
}

function get_count($order, $meta_key, $tax_query){
	$count = new WP_Query(
		[
			'post_type'         => 'Portfolio',			
			'post_status'      	=> 'publish',
			'posts_per_page'    => -1,
			'order'             => $order,
			'meta_key' 			=> $meta_key,
			'tax_query'			=> $tax_query,			
			]
	);	
	 
	$count = $count->post_count;
	return $count;
}


function get_admin_btn($img_folder, $time){
	
	$shotcode_btn = '<div class="generate-shortcode-btn">Get shortcode</div>';
	$link_btn = '<div class="get-link-btn">Get link</div>';
	$time_log_btn = '<div class="get-time-log-btn">Logging information</div>';
	$img_btn = '<img src="'.$img_folder.'multiaxis-avatar.png">';

	$output .= '<div class="multiaxis-admin-btn">';								
    $output .= '<div class="portfolio-link-container" data-button-open="closed">'.$img_btn;
    $output .= '<div class="button-link-container">';		
    $output .= $shotcode_btn;
    $output .= $link_btn;        
    if ($time == 'true') {
     	$output .= $time_log_btn;
	}  
    $output .= '</div></div></div>';

	return $output;
}


function get_expand($term, $depth, $check_taxonomy, $img_folder){
	
	$is_check_children = empty(get_term_children( $term->term_id, $term->taxonomy ));
	$check_children_count = children_count_terms( $term->term_id, $term->taxonomy ) == false;
	$img_down = $img_folder.'down-nd.svg';
	$span = '<span class="check-mark check-mark-'.$depth.'" data-depth="'.$depth.'">';
	
	if ($check_taxonomy && $depth < 2 && !$is_check_children && $check_children_count) {
		$check_mark = $span.'<img src="'.$img_down.'" data-position="closed"><span class="check-mark-hover">Expand</span></span>';
	} 	
	elseif ($check_taxonomy && $depth <= 3 && !$is_check_children && $check_children_count) {
		$check_mark = $span.'<img class="img-check-mark-right" src="'.$img_down.'" data-position="closed"><span class="check-mark-hover">Expand</span></span>';
	}
	else {
		$check_mark = '';
	}
	
	return $check_mark;
}


function get_tech_heading($heading, $heading_class=  '', $depth, $name_taxonomy){
	
	if ($depth == 0 && $name_taxonomy == 'technologies') {
		$tech_heading = '<div class="technologies-heading '.$heading_class.'">'.$heading.'</div>';
	} else {
		$tech_heading = '';
	}
	
	return $tech_heading;
}

function get_expand_all($term, $depth, $name_taxonomy, $check_taxonomy){
	
	$this_terms = get_terms([
					'taxonomy' => $name_taxonomy,
					'hide_empty' => true,
					'parent' => $term->term_id,
					]);
	
	$span_expand = '<span class="expand-children expand-children-'.$depth.'" data-depth="'.$depth.'" data-expand="closed">';
	
	if ($check_taxonomy && getThisChildren($this_terms, $name_taxonomy) > 0) {
							
		if ($depth > 1) { 
			$expand_all = $span_expand.'Expand all <span class="expand-children-arrow-icon"></span></span>';
		} else { 
			$expand_all = $span_expand.'<span class="expand-children-light-arrow-icon"><span class="expand-children-light-arrow-icon-hover">Expand all</span></span></span>';
		}
	} else $expand_all = '';
	
	return $expand_all;
	
}

function get_term_image($term){
	
		$term_id = $term->term_id;
		$image_id = get_term_meta($term_id, 'id-cat-images', true);
		$attach_url = wp_get_attachment_url( $image_id );
		
		
		if ( !empty($image_id)  && $attach_url != '' ) {

			$attach_img = wp_get_attachment_image($image_id, 'full');

			if ( $attach_img != null) {
				
				$term_image = $attach_img;  
				  
			} else {
				$image_url = $attach_url;
				$term_image = '<img loading="lazy" class="multiaxis-icon multiaxis-icon-'.$image_id.'" src="'.$image_url.'">';
			}						
				
		} else {
			$term_image = '';
		}
	
	return $term_image;
}

function get_tech_btn(){

	$return_array = [];
	
	
	$tech_operator = '<div class="switch"><input class="checkbox-switch" type="checkbox"><span class="switch-hover">
    <b>Logical OR:</b>  if you select two or more options, the filter will choose projects that correspond to at least one of the selected options. 
    <br><br>
    <b>Logical AND:</b> if you select two or more options, the filter will choose  projects that correspond to all selected options.
    </span></div>';
    $search = '<div class="technologies-search-container"><input class="technologies-search" placeholder="Search"><div class="technologies-search-result"></div></div>';	
    $all_collapse = '<div class="closed-all-categories-btn">Collapse all</div>';
	
	
	$return_array['tech_operator'] = $tech_operator;
	$return_array['search'] = $search;
	$return_array['all_collapse'] = $all_collapse;
	
	return $return_array;
}

function get_close_btn($depth, $name_taxonomy, $img_folder, $other){
	
	$closed_cross = '';
	$img_src = $img_folder.'close.svg';
	if ($depth == 0 && $name_taxonomy == 'technologies' && $other == true) {
		$closed_cross = '<div class="closed-block-container"><img src="'.$img_src.'"></div>'; 
	} 
	
	return $closed_cross;
	
}

function get_show_all_btn($k, $term, $settings_decode, $depth, $check_taxonomy, $name_taxonomy){
	
	if ($k > (int)$settings_decode->$name_taxonomy-1 && $depth <= 1 && !$check_taxonomy && (int)$settings_decode->$name_taxonomy != 0) {
		
		$show_all_btn = '<div class="portfolio-show-additional-btn-wrap"><div class="portfolio-show-additional-btn" data-show-toggle="true" data-taxonomy-name="'.$term->taxonomy.'"> <span class="for-underline-text">Show all<span class="additional-btn-icon"></span></span></div></div>';
	}
	else { 					
		
		$show_all_btn = '';
	}
	
	return $show_all_btn;
	
}

function get_more_line($k, $term, $settings_decode, $depth, $check_taxonomy, $name_taxonomy){
	$ul_list_class = ' ul-list-no-tree-taxonomy';
	
	if ( (int)$settings_decode->$name_taxonomy-1 == $k && !$check_taxonomy){
		return '</ul><div class="multiaxis-more-category more-display-none more-display-'.$name_taxonomy.'"><span class="multiaxis-more-category-text">More</span><span class="multiaxis-more-category-line"></span></div><ul class="ul-list-tree-taxonomy ul-display ul-display-none ul-display-none-'.$name_taxonomy.' ul-children-'.$name_taxonomy.' ul-children-'.$name_taxonomy.'-'.$depth.' '.$ul_list_class.'" >';
	}
	
}

function get_class_data($term, $name_taxonomy, $depth, $terms, $img_folder){
	
	$return_array = [];
	$img_src = $img_folder.'imgoffset.svg';
	$img_span = '<span class="span-offset" data-depth="'.$depth.'"><img class="img-offset" src="'.$img_src.'"></span>';
	
	if (getHasChildren($term->term_id, $name_taxonomy) == true){
		$data_position = 'data-position="closed"';
		$data_position_class = ' parent-class';
		$no_children_cat_class = '';
		$img_ofsset = '';
	}
	else {
		if (getThisChildren($terms, $name_taxonomy) > 0) {
			
			$no_children_cat_parents_class = '';
			
			$no_children_cat_class = '';
			$img_ofsset = $img_span;
			
		} else {
			if ($depth > 2) {
				$no_children_cat_parents_class = '';				
				$no_children_cat_class = '';
				$img_ofsset = $img_span;
			} else {
				$no_children_cat_class = '';
				$img_ofsset = '';
				$no_children_cat_parents_class = ' no-children-cat-parents-class';
			}
		}						
		$data_position = '';
		$data_position_class = '';
	}
	
	$return_array['data_pos'] = $data_position;
	$return_array['data_pos_class'] = $data_position_class;
	$return_array['img_offset'] = $img_ofsset;
	$return_array['no_chld_cat_class'] = $no_children_cat_class;
	$return_array['no_chld_cat_par_class'] = $no_children_cat_parents_class;
	
	return $return_array;
	
}


function get_tree($term_id, $depth, $name_taxonomy, $img_folder, $originality, $other){
	
	$terms = ( $other ) ? get_terms(['taxonomy' => $name_taxonomy, 'hide_empty' => true, 'parent' => $term_id ]) : get_sorting_top_technologies();
	
	if ( !empty($terms) ) { 
	
		$settings = get_multiaxis_tax_settings();
		if ( isset($settings) ){ $settings_decode = json_decode($settings); }


		$additional_class = '';
		$additional_class2 = '';
		if ($name_taxonomy != 'technologies') {
			$additional_class = ' li-list-no-tree-taxonomy li-all-taxonomy';
			$additional_class2 = ' ul-list-no-tree-taxonomy';
		}
		$first_ul = ( $other ) ? '<ul class="ul-list-tree-taxonomy ul-children-'.$name_taxonomy.' ul-children-'.$name_taxonomy.'-'.($depth-1).$additional_class2.'">' : '<ul class="top-technologies ">';
		
		$checkbox_hover = '<span class="input-hover"></span>';
		$count_terms_hover = '<span class="count-terms-hover">The number of projects according to choice on other axes and to the option</span>';
		$count_terms_slash = '<span class="count-terms-slash">/</span>';
		$count_terms_const_hover = '<span class="count-terms-const-hover">The total number of projects relevant to the option</span>';
		$description_star_hover = '<span class="description-star-hover">According to your query, nothing was found. Try to specify other parameters</span>';

		$output = $first_ul;
		$output .= get_close_btn($depth-1, $name_taxonomy, $img_folder, $other);

		$k = 0;
		foreach ($terms as $term) {

			$check_taxonomy = $term->taxonomy == 'technologies';
			$expand = get_expand($term, $depth, $check_taxonomy, $img_folder);
			$exp_all = get_expand_all($term, $depth, $name_taxonomy, $check_taxonomy);
			$class_data_arr = get_class_data($term, $name_taxonomy, $depth, $terms, $img_folder);

			if ($depth < 2) {
				$expand_right = $expand;
				$expand_left = '';
			} else {
				$expand_right = '';
				$expand_left = $class_data_arr['img_offset'].$expand;
			}

			$term_image = get_term_image($term);

			$taxonomy_public = is_taxonomy_public($term->taxonomy) ? 'true' : 'false';

			
			
			$checkbox = '<input autocomplete="off" type="checkbox" data-checked="0" data-taxonomy-public="'.$taxonomy_public.'" data-originality="'.$originality.'" data-term-name="'.$term->name.'" data-taxonomy-name-id="'.$name_taxonomy.'" data-term-id="'.$term->term_id.'" class="checkbox-data-term checkbox-children-'.$name_taxonomy.' checkbox-children-'.$name_taxonomy.'-'.$depth.'">';
			$count_terms_const = '<span class="count-terms-const">'.$term->count.')'.$count_terms_const_hover.'</span>';
			$description_star = '<span class="description-star description-star-'.$term->term_id.'"> *'.$description_star_hover.'</span>';
			$count_terms = '<span class="count-terms count-terms-'.$term->term_id.'" data-item-taxonomies="'.$name_taxonomy.'" data-item-count="'.$term->term_id.'">'.$count_terms_hover.'</span>';
			

			$output .= '<li class="li-children-'.$name_taxonomy.' li-children-'.$name_taxonomy.'-'.$depth.$additional_class.$class_data_arr['no_chld_cat_class'].$class_data_arr['no_chld_cat_par_class'].'">';
				$output .= '<span class="all_filters all_filters_'.$depth.' '.$name_taxonomy.' '.$class_data_arr['data_pos_class'].'" '.$class_data_arr['data_pos'].'>'.$expand_left;
					$output .= '<span class="portfolio-filter-name portfolio-filter-name_'.$depth.' ">';
						$output .= '<span class="item_filters item-filters-'.$depth.'">';
							$output .= $checkbox.$checkbox_hover.'</span>';
							$output .= '<span class="item_filters_names">';
							$output .= '<span class="name-terms ">';
							$output .= $term_image;
							$output .= '<span class="item_term_name">'.$term->name.'&nbsp;<span class="ma-nobr">(';							
							$output .= $count_terms;
							$output .= $count_terms_slash.$count_terms_const;
							$output .= $description_star.'</span>';
							$output .= '<span class="ma-nobr ma-right">'.$expand_right.$exp_all.'</span>';
							$output .= '</span>';
						$output .= '</span>';
					$output .= '</span>';
				$output .= '</span>';
			$output .= '</span>';			
			$output .= get_tree($term->term_id, $depth+1, $name_taxonomy, $img_folder, $originality, 'true');			
			$output .= '</li>';
			$output .= get_more_line($k, $term, $settings_decode, $depth, $check_taxonomy, $name_taxonomy);


			$k++;
		}

		$output .= '</ul>';
		$output .= get_show_all_btn($k, $term, $settings_decode, $depth, $check_taxonomy, $name_taxonomy);
	
	}
	
	return $output;
	
}

function get_order_by_new($url_order_by, $shortcode_order_by, $is_current_multiaxis){ 
	$sorting_array = ['featured', 'date', 'duration', 'year', 'team_size'];
														  
	if ( isset( $url_order_by ) && $is_current_multiaxis ) {
		$this_order_by = $url_order_by;
	} else {
		$this_order_by = $shortcode_order_by;		
	}
	
	$output = '<select class="select-sort" name="multiaxis-select-sort">'; //new fix
	foreach ( $sorting_array as $sorting_el) {
		if ( $sorting_el == $this_order_by ){			
			$selected = 'selected';
		} else {
			$selected = '';
		}
		
		$sorting_el_new = ucfirst( str_replace('_', ' ', $sorting_el) );
		
		if (is_user_logged_in() && $sorting_el == 'featured' ) {
			$sorting_el_new = 'Default (Weight)';
		}
			 
		$output .= '<option data-orderby="'.$sorting_el.'" '.$selected.'>'.$sorting_el_new.'</option>';
	}
	$output .= '</select>';													  
	
	return $output;
}


function get_order_new($url_order, $shortcode_order, $is_current_multiaxis){
	
	if ( isset( $url_order ) && $is_current_multiaxis ) {
		if ( $url_order == 'DESC' ) {
			$get_order = '<div class="multiaxis-order multiaxis-order-down" data-order="DESC"></div>';
		} else { 
			$get_order = '<div class="multiaxis-order multiaxis-order-up" data-order="ASC"></div>'; 
		}				
	} else { 
		if ( $shortcode_order == 'DESC' ) {
			$get_order = '<div class="multiaxis-order multiaxis-order-down" data-order="DESC"></div>';
		} else {
			$get_order = '<div class="multiaxis-order multiaxis-order-up" data-order="ASC"></div>'; 
		}
	}
	
	return $get_order;
	
}



function dd($var){
	echo '<pre>';var_dump($var); echo '</pre>';
}