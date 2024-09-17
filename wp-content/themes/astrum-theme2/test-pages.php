<?php 

/* ----------------------------------------------------- */
/* Test pages Custom Post Type */
/* ----------------------------------------------------- */

if (!function_exists('register_cpt_test_pages')) {
    add_action( 'init', 'register_cpt_test_pages' );
    function register_cpt_test_pages() {

        $labels = [
            'name' => __( 'Test pages','purepress'),
            'singular_name' => __( 'Test pages','purepress'),
            'add_new' => __( 'Add New','purepress' ),
            'add_new_item' => __( 'Add New Test pages','purepress' ),
            'edit_item' => __( 'Edit Test pages','purepress'),
            'new_item' => __( 'New Test pages','purepress'),
            'view_item' => __( 'View Test pages','purepress'),
            'search_items' => __( 'Search Test pages','purepress'),
            'not_found' => __( 'No portfolio found','purepress'),
            'not_found_in_trash' => __( 'No Test pages found in Trash','purepress'),
            'parent_item_colon' => __( 'Parent Test pages:','purepress'),
            'menu_name' => __( 'Test pages','purepress'),
            ];

        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __('Display your works by filters','purepress'),
            'supports' => [ 'title', 'editor', 'excerpt', 'revisions', 'thumbnail' ],
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => [ 'slug' => 'test-pages'],
            'capability_type' => 'post'
            ];

        register_post_type( 'test-pages', $args );
    }
}