<?php

$layers = array();
global $wpdb;
// Table name

$table_name = $wpdb->prefix . "revslider_sliders";
// Get sliders

if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
  $sliders = $wpdb->get_results( "SELECT alias, title FROM $table_name" );
} else {
  $sliders = '';
}


// Iterate over the sliders
if($sliders) {
  foreach($sliders as $key => $item) {
    $layers[] = array(
      'label' => $item->title,
      'value' => $item->alias
      );
  }
} else {
  $layers[] = array(
    'label' => 'No Sliders Found',
    'value' => ''
    );
}

/**
 * Initialize the options before anything else.
 */
add_action( 'admin_init', '_custom_theme_options', 1 );


/**
 * Theme Mode demo code of all the available option types.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */
function _custom_theme_options() {

  global $google_fonts;
  global $layers;
  /**
   * Get a copy of the saved settings array.
   */
  $saved_settings = get_option( 'option_tree_settings', array() );

  /**
   * Create a custom settings array that we pass to
   * the OptionTree Settings API Class.
   */
  $custom_settings = array(
    'contextual_help' => array(
      'content'       => array(
        array(
          'id'        => 'general_help',
          'title'     => 'General',
          'content'   => '<p>Help content goes here!</p>'
          )
        ),
      'sidebar'       => '<p>Sidebar content goes here!</p>'
      ),
    'sections'        => array(
      array(
        'title'       => 'Slider',
        'id'          => 'slider'
        ),
      array(
        'title'       => 'Header',
        'id'          => 'header'
        ),
      array(
        'title'       => 'General',
        'id'          => 'general_default'
        ),
      array(
        'title'       => 'WooCommerce',
        'id'          => 'woocommerce'
        ),
      array(
        'title'       => 'Blog options',
        'id'          => 'blog'
        ),
      array(
        'title'       => 'Portfolio options',
        'id'          => 'portfolio'
        ),
      array(
        'id'          => 'typo',
        'title'       => 'Typography'
        ),
      array(
        'id'          => 'twitter',
        'title'       => 'Twitter OAuth'
        ),
      array(
        'id'          => 'sidebars',
        'title'       => 'Sidebars'
        ),
    /*  array(
        'id'          => 'update',
        'title'       => 'Update'
        )*/
      ),
    'settings'        => array(
      array(
        'label'       => 'Enable slider',
        'id'          => 'pp_slider_on',
        'type'        => 'select',
        'desc'        => 'Show slider on homepage',
        'choices'     => array(
          array(
            'label'       => 'Yes',
            'value'       => 'yes'
            ),
          array(
            'label'       => 'No',
            'value'       => 'no'
            )
          ),
        'std'         => 'yes',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'slider'
        ),
      array(
        'label'       => 'Choose Revolution Slider for homepage',
        'id'          => 'pp_revo_slider',
        'type'        => 'select',
        'desc'        => '',
        'choices'     => $layers,
        'std'         => 'yes',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'slider'
        ),

        array(
        'label'       => 'Header type',
        'id'          => 'pp_header_menu',
        'type'        => 'select',
        'desc'        => 'Select header type',
        'choices'     => array(
          array(
            'label'       => 'Standard header',
            'value'       => 'standard'
            ),
          array(
            'label'       => 'Alternative header',
            'value'       => 'full'
            )
          ),
        'std'         => 'true',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'header'
        ),
      array(
        'label'       => 'Header height',
        'id'          => 'pp_header_height',
        'type'        => 'numeric-slider',
        'desc'        => 'Default 86px, type just a number.',
        'std'         => '86',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'min_max_step'=> '50,400,1',
        'section'     => 'header'
        ),

      array(
        'label'       => 'Mobile menu breakpoint',
        'id'          => 'pp_menu_breakpoint',
        'type'        => 'numeric-slider',
        'desc'        => 'Set width after menu should switch to mobile version (use if you have exceptionaly wide menu).',
        'std'         => '767',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'min_max_step'=> '767,1200,1',
        'section'     => 'header'
        ),

      array(
        'label'       => 'Sticky header status',
        'id'          => 'pp_sticky_menu',
        'type'        => 'select',
        'desc'        => 'Enable/disable menu while scrolling',
        'choices'     => array(
          array(
            'label'       => 'Disable',
            'value'       => 'disable'
            ),
          array(
            'label'       => 'Enable',
            'value'       => 'enable'
            )
          ),
        'std'         => 'true',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'header'
        ),
      array(
        'label'       => 'Upload logo',
        'id'          => 'pp_logo_upload',
        'type'        => 'upload',
        'desc'        => 'For best effect logo image should be transparent png, logo from live preview has 114x24px but you can use bigger, you will probably need to adjust some margins using options below ',
        'std'         => '',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'header'
        ),
      array(
        'label'       => 'Logo area width',
        'id'          => 'pp_logo_area_width',
        'type'        => 'select',
        'desc'        => 'Full width of top area is 16 columns. Logo area by default is 9 columns, while icons and contact details area is 6 columns wide. If you want to have bigger logo, please change here number of columns for logo. ',
        'choices'     => array(
          array('label'  => '1 column','value' => '1'),
          array('label'  => '2 columns','value' => '2'),
          array('label'  => '3 columns','value' => '3'),
          array('label'  => '4 columns','value' => '4'),
          array('label'  => '5 columns','value' => '5'),
          array('label'  => '6 columns','value' => '6'),
          array('label'  => '7 columns','value' => '7'),
          array('label'  => '8 columns','value' => '8'),
          array('label'  => '9 columns','value' => '9'),
          array('label'  => '10 columns','value' => '10'),
          array('label'  => '11 columns','value' => '11'),
          array('label'  => '12 columns','value' => '12'),
          ),
        'std'         => '3',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'section'     => 'header'
        ),
array(
  'label'       => 'Logo top margin',
  'id'          => 'pp_logo_top_margin',
  'type'        => 'measurement',
  'desc'        => 'Set top margin for logo image',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),

array(
  'label'       => 'Logo top margin in compact header',
  'id'          => 'pp_logo_top_margin_sticky',
  'type'        => 'measurement',
  'desc'        => 'Set top margin for logo image when sticky header is enabled',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),

array(
  'label'       => 'Logo bottom margin',
  'id'          => 'pp_logo_bottom_margin',
  'type'        => 'measurement',
  'desc'        => 'Set bottom margin for logo image',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),
/*  array(
    'label'       => 'Menu responsiveness breakpoint',
    'id'          => 'menu_resp_width',
    'type'        => 'text',
    'desc'        => 'Set width of window after which menu will change to "select" box, by default it\'s 767px' ,
    'std'         => '767',
    'rows'        => '',
    'post_type'   => '',
    'taxonomy'    => '',
    'class'       => '',
    'section'     => 'header'
    ),*/
array(
  'label'       => 'Search form in menu',
  'id'          => 'pp_search',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'Enable',
      'value'       => 'enable'
      ),
    array(
      'label'       => 'Disable',
      'value'       => 'disable'
      )
    ),
  'std'         => 'disable',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),

array(
  'label'       => 'Enable contact details in header',
  'id'          => 'pp_contact_details',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'No',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Yes',
      'value'       => 'yes'
      )
    ),
  'std'         => 'no',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),
array(
  'label'       => 'Contact details email',
  'id'          => 'pp_cdetails_email',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),
array(
  'label'       => 'Contact details phone',
  'id'          => 'pp_cdetails_phone',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'header'
  ),

  array(
    'label'       => 'Footer social icons',
    'id'          => 'pp_headericons',
    'type'        => 'list-item',
    'desc'        => 'Manage socials icons on header.',
    'settings'    => array(


      array(
        'id'          => 'icons_service',
        'label'       => 'Choose service',
        'desc'        => '',
        'std'         => '',
        'type'        => 'select',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array(
          array('value'=> 'twitter','label' => 'Twitter','src'=> ''),
          array('value'=> 'facebook','label' => 'Facebook','src'=> ''),
          array('value'=> 'delicious','label' => 'Delicious','src'=> ''),
          array('value'=> 'paypal','label'=> 'PayPal','src'=> ''),
          array('value'=> 'gplus','label' => 'Google Plus','src'=> ''),
          array('value'=> 'stumbleupon','label' => 'StumbleUpon','src'=> ''),
          array('value'=> 'fivehundredpx','label' => '500px','src'=> ''),
          array('value'=> 'pinterest','label' => 'Pinterest','src'=> ''),
          array('value'=> 'forrst','label' => 'Forrst','src'=> ''),
          array('value'=> 'digg','label' => 'Digg','src'=> ''),
          array('value'=> 'spotify','label' => 'Spotify','src'=> ''),
          array('value'=> 'reddit','label' => 'Reddit','src'=> ''),
          array('value'=> 'appstore','label' => 'AppStore','src'=> ''),
          array('value'=> 'blogger','label' => 'Blogger','src'=> ''),
          array('value'=> 'dribbble','label' => 'Dribbble','src'=> ''),
          array('value'=> 'evernote','label' => 'Evernote','src'=> ''),
          array('value'=> 'flickr','label' => 'Flickr','src'=> ''),
          array('value'=> 'linkedin','label' => 'LinkedIN','src'=> ''),
          array('value'=> 'rss','label' => 'RSS','src'=> ''),
          array('value'=> 'youtube','label' => 'YouTube','src'=> ''),
          array('value'=> 'vimeo','label' => 'Vimeo','src'=> ''),
          array('value'=> 'xing','label' => 'Xing','src'=> ''),
          array('value'=> 'yahoo','label' => 'Yahoo','src'=> ''),
          array('value'=> 'amazon','label'=> 'Amazon','src'=> ''),
          array('value'=> 'steam','label' => 'Steam','src'=> ''),
          array('value'=> 'dropbox','label'=> 'Dropbox','src'=> ''),
          array('value'=> 'github','label' => 'GitHub','src'=> ''),
          array('value'=> 'lastfm','label' => 'LastFM','src'=> ''),
          array('value'=> 'tumblr','label' => 'Tumblr','src'=> ''),
          array('value'=> 'wordpress','label' => 'WordPress','src'=> ''),
          array('value'=> 'linkedin-rect','label' => 'LinkedIn','src'=> ''),
          array('value'=> 'instagram','label' => 'Instagram','src'=> '')
          ),
),
    array(
      'label'       => 'URL to profile page',
      'id'          => 'icons_url',
      'type'        => 'text',
      'desc'        => '',
      'std'         => '',
      'rows'        => '',
      'post_type'   => '',
      'taxonomy'    => '',
      'class'       => ''
      )

    ),
    'std'         => '',
    'rows'        => '',
    'post_type'   => '',
    'taxonomy'    => '',
    'class'       => '',
    'section'     => 'general_default'
    ),


array(
  'label'       => 'Enable breadcrumbs',
  'id'          => 'pp_breadcrumbs',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'No',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Yes',
      'value'       => 'yes'
      )
    ),
  'std'         => 'yes',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'general_default'
  ),


array(
  'label'       => 'Flexslider slideshow speed (in milliseconds)',
  'id'          => 'pp_flex_slideshowspeed',
  'type'        => 'numeric-slider',
  'min_max_step'=> '1000,20000,500',
  'desc'        => 'This setting is global, it will affect all sliders. Be sure to put here only number!.',
  'std'         => '7000',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),
array(
  'label'       => 'Flexslider animation speed (in milliseconds)',
  'id'          => 'pp_flex_animationspeed',
  'type'        => 'numeric-slider',
  'min_max_step'=> '100,2000,100',
  'desc'        => 'This setting is global, it will affect all sliders. Be sure to put here only number!.',
  'std'         => '600',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),
array(
  'label'       => 'Flexslider animation type',
  'id'          => 'pp_flex_animationtype',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'Fade',
      'value'       => 'fade'
      ),
    array(
      'label'       => 'Slide',
      'value'       => 'slide'
      )
    ),
  'std'         => 'fade',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),

array(
  'label'       => 'Enable AJAX loaded portfolio',
  'id'          => 'pp_ajax_pf',
  'type'        => 'select',
  'desc'        => 'Portfolio items are loaded on the same page instead of opening new page',
  'choices'     => array(
    array(
      'label'       => 'Disable',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Enable',
      'value'       => 'yes'
      )
    ),
  'std'         => 'no',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),

array(
  'label'       => 'Recent items on single portfolio view',
  'id'          => 'pp_related_pf',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'Show',
      'value'       => 'show'
      ),
    array(
      'label'       => 'Hide',
      'value'       => 'hide'
      )
    ),
  'std'         => 'show',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),

array(
  'label'       => 'Show recent items on single portfolio view only from the same category as the current item',
  'id'          => 'pp_samefilter_pf',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'All elements',
      'value'       => 'all'
      ),
    array(
      'label'       => 'Only the same category (filter)',
      'value'       => 'same'
      )
    ),
  'std'         => 'show',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),
array(
  'label'       => 'Recent Work text',
  'id'          => 'pp_recenttext_pf',
  'type'        => 'text',
  'desc'        => 'Title of recent work secion on single portfolio view',
  'std'         => 'Recent Work',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),
array(
  'label'       => 'Shop sidebar side',
  'id'          => 'pp_woo_layout',
  'type'        => 'radio-image',
  'desc'        => 'Choose sidebar side on shop page.',
  'std'         => 'right-sidebar',
  'rows'        => '',
  'post_type'   => '',
  'choices'     => array(
    array(
      'value'   => 'left-sidebar',
      'label'   => 'Left Sidebar',
      'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
      ),
    array(
      'value'   => 'right-sidebar',
      'label'   => 'Right Sidebar',
      'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
      )
    ),
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'WooCommerce number of items per page',
  'id'          => 'pp_wooitems',
  'type'        => 'select',
  'desc'        => 'Select how many products you want to display on shop page',
  'std'         => '9',
  'rows'        => '',
  'choices'     => array(
    array('label'=> '3','value'=> '3'),
    array('label'=> '4','value'=> '4'),
    array('label'=> '5','value'=> '5'),
    array('label'=> '6','value'=> '6'),
    array('label'=> '7','value'=> '7'),
    array('label'=> '8','value'=> '8'),
    array('label'=> '9','value'=> '9'),
    array('label'=> '10','value'=> '10'),
    array('label'=> '11','value'=> '11'),
    array('label'=> '12','value'=> '12'),
    array('label'=> '13','value'=> '13'),
    array('label'=> '14','value'=> '14'),
    array('label'=> '15','value'=> '15'),
    array('label'=> '16','value'=> '16'),
    array('label'=> '20','value'=> '20'),
    array('label'=> '32','value'=> '32'),
    array('label'=> '40','value'=> '40'),
    array('label'=> '99','value'=> '99'),
    ),
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'WooCommerce Catalog mode ',
  'id'          => 'pp_woo_catalog',
  'type'        => 'select',
  'desc'        => 'Removes all "add to cart" buttons to just showcase your products.',
  'choices'     => array(
    array(
      'label'       => 'Disable',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Enable',
      'value'       => 'yes'
      )
    ),
  'std'         => 'no',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'WooCommerce Mini Cart in header',
  'id'          => 'pp_woo_header_cart',
  'type'        => 'select',
  'desc'        => 'Removes all "add to cart" buttons to just showcase your products.',
  'choices'     => array(
    array(
      'label'       => 'Disable',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Enable',
      'value'       => 'yes'
      )
    ),
  'std'         => 'no',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'Enable slider on shop page',
  'id'          => 'pp_shop_slider_on',
  'type'        => 'select',
  'desc'        => 'Show slider on homepage',
  'choices'     => array(
    array(
      'label'       => 'Yes',
      'value'       => 'yes'
      ),
    array(
      'label'       => 'No',
      'value'       => 'no'
      )
    ),
  'std'         => 'yes',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'Choose Revolution Slider for shop page',
  'id'          => 'pp_shop_revo_slider',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => $layers,
  'std'         => 'yes',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'Number of columns on shop page',
  'id'          => 'pp_woocolumns',
  'type'        => 'select',
  'desc'        => '',
  'std'         => '3',
  'rows'        => '',
  'choices'     => array(
      array('label'=> '3','value'=> '3'),
      array('label'=> '4','value'=> '4')
   ),
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'woocommerce'
  ),
array(
  'label'       => 'Copyrights text',
  'id'          => 'pp_copyrights',
  'type'        => 'text',
  'desc'        => 'Text in footer',
  'std'         => '&copy; Theme by <a href="http://themeforest.net/user/purethemes/portfolio?ref=purethemes">Purethemes.net</a>. All Rights Reserved.',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'general_default'
  ),
array(
  'label'       => 'Blog layout',
  'id'          => 'pp_blog_layout',
  'type'        => 'radio-image',
  'desc'        => 'Choose sidebar side on blog.',
  'std'         => 'right-sidebar',
  'rows'        => '',
  'post_type'   => '',
  'choices'     => array(
    array(
      'value'   => 'left-sidebar',
      'label'   => 'Left Sidebar',
      'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
      ),
    array(
      'value'   => 'right-sidebar',
      'label'   => 'Right Sidebar',
      'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
      )
    ),
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'blog'
  ),
array(
  'label'       => 'Blog thumbs style',
  'id'          => 'pp_blog_thumbs',
  'type'        => 'select',
  'desc'        => '',
  'choices'     => array(
    array(
      'label'       => 'Large',
      'value'       => 'large'
      ),
    array(
      'label'       => 'Small',
      'value'       => 'small'
      )
    ),
  'std'         => 'large',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'blog'
  ),
array(
  'label'       => 'Post meta informations on single post view',
  'id'          => 'pp_meta_single',
  'type'        => 'checkbox',
  'desc'        => 'Set which elements of posts meta data you want to display.',
  'choices'     => array(
    array (
      'label'       => 'Author',
      'value'       => 'author'
      ),
   array (
      'label'       => 'Date',
      'value'       => 'date'
      ),
    array (
      'label'       => 'Tags',
      'value'       => 'tags'
      ),
    array (
      'label'       => 'Categories',
      'value'       => 'cat'
      ),
    array (
      'label'       => 'Comments',
      'value'       => 'com'
      )
    ),
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'blog'
  ),

array(
  'label'       => 'Post meta informations on blog and archive pages',
  'id'          => 'pp_meta_blog',
  'type'        => 'checkbox',
  'desc'        => 'Set which elements of posts meta data you want to display.',
  'choices'     => array(
    array (
      'label'       => 'Author',
      'value'       => 'author'
      ),
    array (
      'label'       => 'Date',
      'value'       => 'date'
      ),
    array (
      'label'       => 'Tags',
      'value'       => 'tags'
      ),
    array (
      'label'       => 'Categories',
      'value'       => 'cat'
      ),
    array (
      'label'       => 'Comments',
      'value'       => 'com'
      )
    ),
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'blog'
  ),

array(
  'label'       => 'Blog page title',
  'id'          => 'pp_blog_page',
  'type'        => 'text',
  'desc'        => '',
  'std'         => 'The Blog',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'blog'
  ),

array(
  'label'       => 'Portfolio columns layout',
  'id'          => 'pp_portfolio_layout',
  'type'        => 'select',
  'desc'        => 'Choose number of columns for portfolio archive page',
  'choices'     => array(
    array(
      'label'       => '3 columns',
      'value'       => '3'
      ),
    array(
      'label'       => '4 columns',
      'value'       => '4'
      )
    ),
  'std'         => '3',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),
array(
  'label'       => 'Portfolio page title',
  'id'          => 'pp_portfolio_page',
  'type'        => 'text',
  'desc'        => '',
  'std'         => 'Portfolio',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),

array(
  'label'       => 'For "video portfolio" items show image thumbnail or video in archive and /portfolio page',
  'id'          => 'pp_portfolio_videothumb',
  'type'        => 'select',
  'desc'        => 'Choose if you want to display thumbnails as image or as small embeded video',
  'choices'     => array(
    array(
      'label'       => 'Video',
      'value'       => 'video'
      ),
    array(
      'label'       => 'Image thumbnail',
      'value'       => 'thumb'
      )
    ),
  'std'         => '3',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),

array(
  'label'       => 'Number of items to display on portfolio page' ,
  'id'          => 'pp_portfolio_showpost',
  'type'        => 'select',
  'desc'        => 'Choose how many items to display on portfolio page',
  'choices'     => array(
    array('label'=> '3','value'=> '3'),
    array('label'=> '4','value'=> '4'),
    array('label'=> '5','value'=> '5'),
    array('label'=> '6','value'=> '6'),
    array('label'=> '7','value'=> '7'),
    array('label'=> '8','value'=> '8'),
    array('label'=> '9','value'=> '9'),
    array('label'=> '10','value'=> '10'),
    array('label'=> '11','value'=> '11'),
    array('label'=> '12','value'=> '12'),
    array('label'=> '13','value'=> '13'),
    array('label'=> '14','value'=> '14'),
    array('label'=> '15','value'=> '15'),
    array('label'=> '16','value'=> '16'),
    array('label'=> '20','value'=> '20'),
    array('label'=> '32','value'=> '32'),
    array('label'=> '40','value'=> '40'),
    array('label'=> '99','value'=> '99'),

    ),
  'std'         => '9',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'portfolio'
  ),

array(
  'id'          => 'sidebars_text',
  'label'       => 'About sidebars',
  'desc'        => 'All sidebars that you create here will appear both in the Appearance > Widgets, and then you can choose them for specific pages or posts.',
  'std'         => '',
  'type'        => 'textblock',
  'section'     => 'sidebars',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => ''
  ),
array(
  'label'       => 'Create Sidebars',
  'id'          => 'incr_sidebars',
  'type'        => 'list-item',
  'desc'        => 'Choose a unique title for each sidebar',
  'section'     => 'sidebars',
  'settings'    => array(
    array(
      'label'       => 'ID',
      'id'          => 'id',
      'type'        => 'text',
      'desc'        => 'Write a lowercase single world as ID (it can\'t start with a number!), without any spaces',
      'std'         => '',
      'rows'        => '',
      'post_type'   => '',
      'taxonomy'    => '',
      'class'       => ''
      )
    )
  ),
array(
  'id'          => 'pp_custom_css',
  'label'       => 'Custom CSS',
  'desc'        => 'To prevent problems with theme update, write here any custom css (or use child themes)',
  'std'         => '',
  'type'        => 'textarea-simple',
  'section'     => 'general_default',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => ''
  ),
// array(
//   'id'          => 'incr_analytics',
//   'label'       => 'Analytics code',
//   'desc'        => 'Put here your  Analytics script code',
//   'std'         => '',
//   'type'        => 'textarea-simple',
//   'section'     => 'general2_default',
//   'rows'        => '',
//   'post_type'   => '',
//   'taxonomy'    => '',
//   'class'       => ''
//   ),

  array(
      'label'       => 'Fonts stack',
      'id'          => 'phantom_font',
      'type'        => 'googlefonts',
      'desc'        => '',
      'std'         => '',
      'rows'        => '',
      'post_type'   => '',
      'taxonomy'    => '',
      'class'       => '',
      'section'     => 'typo'
      ),
    array(
  'id'          => 'fonts_info',
  'label'       => 'Fonts stack info',
  'desc'        => 'You need to Save Changes after adding new fonts to your Google Fonts Stack to be able to select them in the typography fields below',
  'std'         => '',
  'type'        => 'textblock',
  'section'     => 'typo',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => ''
  ),
array(
  'label'       => 'Body Font',
  'id'          => 'astrum_body_font',
  'type'        => 'typography',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
array(
  'label'       => 'Menu Font',
  'id'          => 'astrum_menu_font',
  'type'        => 'typography',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
array(
  'label'       => 'Logo Font',
  'id'          => 'astrum_logo_font',
  'type'        => 'typography',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
array(
  'label'       => 'Headers (h1..h6) Font',
  'id'          => 'astrum_headers_font',
  'type'        => 'typography',
  'desc'        => 'Size and related to it settings will be ignored here.',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
/*

array(
  'label'       => 'Enable custom fonts',
  'id'          => 'pp_fonts_on',
  'type'        => 'select',
  'desc'        => 'Note that this is experimental feature, not all fonts might work properly.',
  'choices'     => array(
    array(
      'label'       => 'No',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Yes',
      'value'       => 'yes'
      )
    ),
  'std'         => 'no',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
array(
  'label'       => 'Body font',
  'id'          => 'pp_body_font',
  'desc'        => 'Choose font for body (We recommend Open Sans).',
  'std'         => 'Open+Sans',
  'type'        => 'select',
  'section'     => 'typo',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'choices'     => $google_fonts
  ),
array(
  'label'       => 'Body font size',
  'id'          => 'pp_body_size',
  'type'        => 'measurement',
  'desc'        => 'Set font-size for texts',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
array(
  'label'       => 'Headings font',
  'id'          => 'pp_h_font',
  'desc'        => 'Choose font for headers h1-h6.',
  'std'         => '',
  'type'        => 'select',
  'section'     => 'typo',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'choices'     => $google_fonts
  ),
array(
  'label'       => 'Enable custom typography for logo',
  'id'          => 'pp_logofonts_on',
  'type'        => 'select',
  'desc'        => 'Select "Yes" to enable custom typo',
  'choices'     => array(
    array(
      'label'       => 'No',
      'value'       => 'no'
      ),
    array(
      'label'       => 'Yes',
      'value'       => 'yes'
      )
    ),
  'std'         => 'no',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),
array(
  'label'       => 'Logo typography',
  'id'          => 'pp_logo_typo',
  'type'        => 'typography',
  'desc'        => 'If you are using text logo here you can set font options.',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'typo'
  ),*/
/*array(
  'id'          => 'update_info',
  'label'       => 'About Update',
  'desc'        => 'Fill fields below to get notification about new version of theme. To get your API key go to ThemeForest - your profile -> Settings -> Api Keys and Generate API Key.',
  'std'         => '',
  'type'        => 'textblock',
  'section'     => 'update',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => ''
  ),
array(
  'label'       => 'Your ThemeForest username',
  'id'          => 'pp_username',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'update'
  ),*/
array(
  'label'       => 'Your ThemeForest API key',
  'id'          => 'pp_api_key',
  'type'        => 'text',
  'desc'        => 'To get your API key go to ThemeForest - your profile -> Settings -> Api Keys and Generate API Key.',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'update'
  ),
array(
  'id'          => 'twitter_info',
  'label'       => 'Twitter OAuth keys',
  'desc'        => 'From March 2013 Twitter requires authentication to access your tweets. Here are fields you need to fill if you want to use Nevia Twitter Widgets. How to do it you can find in documentation and on https://dev.twitter.com .',
  'std'         => '',
  'type'        => 'textblock',
  'section'     => 'twitter',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => ''
  ),
array(
  'label'       => 'Twitter Consumer Key',
  'id'          => 'pp_twitter_ck',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'twitter'
  ),
array(
  'label'       => 'Twitter Consumer Secret',
  'id'          => 'pp_twitter_cs',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'twitter'
  ),
array(
  'label'       => 'Twitter Access Token',
  'id'          => 'pp_twitter_at',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'twitter'
  ),
array(
  'label'       => 'Twitter Access Token Secret',
  'id'          => 'pp_twitter_ts',
  'type'        => 'text',
  'desc'        => '',
  'std'         => '',
  'rows'        => '',
  'post_type'   => '',
  'taxonomy'    => '',
  'class'       => '',
  'section'     => 'twitter'
  ),
)
);

if (function_exists('icl_get_languages')) {
  $languages = icl_get_languages('skip_missing=0&orderby=code');
    if(!empty($languages)){
      foreach($languages as $l){

         $custom_settings['settings'][]=
          array(
          'label'       => 'Revolution Slider for homepage in '.$l['native_name'].' language',
          'id'          => 'pp_revo_slider'.$l['language_code'],
          'type'        => 'select',
          'desc'        => '',
          'choices'     => $layers,
          'post_type'   => '',
          'taxonomy'    => '',
          'class'       => '',
          'section'     => 'slider'
          );
      }
  }
}
/* settings are not the same update the DB */
if ( $saved_settings !== $custom_settings ) {
  update_option( 'option_tree_settings', $custom_settings );
}

}


global $google_fonts;

$google_fonts = array(
  array('label' => 'Abel','value' => 'Abel'),
  array('label' => 'Abril Fatface','value' => 'Abril+Fatface'),
  array('label' => 'Aclonica','value' => 'Aclonica'),
  array('label' => 'Actor','value' => 'Actor'),
  array('label' => 'Adamina','value' => 'Adamina'),
  array('label' => 'Aguafina Script','value' => 'Aguafina+Script'),
  array('label' => 'Aladin','value' => 'Aladin'),
  array('label' => 'Aldrich','value' => 'Aldrich'),
  array('label' => 'Alice','value' => 'Alice'),
  array('label' => 'Alike Angular','value' => 'Alike+Angular'),
  array('label' => 'Alike','value' => 'Alike'),
  array('label' => 'Allan','value' => 'Allan'),
  array('label' => 'Allerta Stencil','value' => 'Allerta+Stencil'),
  array('label' => 'Allerta','value' => 'Allerta'),
  array('label' => 'Amaranth','value' => 'Amaranth'),
  array('label' => 'Amatic SC','value' => 'Amatic+SC'),
  array('label' => 'Andada','value' => 'Andada'),
  array('label' => 'Andika','value' => 'Andika'),
  array('label' => 'Annie Use Your Telescope','value' => 'Annie+Use+Your+Telescope'),
  array('label' => 'Anonymous Pro','value' => 'Anonymous+Pro'),
  array('label' => 'Antic','value' => 'Antic'),
  array('label' => 'Anton','value' => 'Anton'),
  array('label' => 'Arapey','value' => 'Arapey'),
  array('label' => 'Architects Daughter','value' => 'Architects+Daughter'),
  array('label' => 'Arimo','value' => 'Arimo'),
  array('label' => 'Artifika','value' => 'Artifika'),
  array('label' => 'Arvo','value' => 'Arvo'),
  array('label' => 'Asset','value' => 'Asset'),
  array('label' => 'Astloch','value' => 'Astloch'),
  array('label' => 'Atomic Age','value' => 'Atomic+Age'),
  array('label' => 'Aubrey','value' => 'Aubrey'),
  array('label' => 'Bangers','value' => 'Bangers'),
  array('label' => 'Bentham','value' => 'Bentham'),
  array('label' => 'Bevan','value' => 'Bevan'),
  array('label' => 'Bigshot One','value' => 'Bigshot+One'),
  array('label' => 'Bitter','value' => 'Bitter'),
  array('label' => 'Black Ops One','value' => 'Black+Ops+One'),
  array('label' => 'Bowlby One SC','value' => 'Bowlby+One+SC'),
  array('label' => 'Bowlby One','value' => 'Bowlby+One'),
  array('label' => 'Brawler','value' => 'Brawler'),
  array('label' => 'Bubblegum Sans','value' => 'Bubblegum+Sans'),
  array('label' => 'Buda','value' => 'Buda'),
  array('label' => 'Butcherman Caps','value' => 'Butcherman+Caps'),
  array('label' => 'Cabin Condensed','value' => 'Cabin+Condensed'),
  array('label' => 'Cabin Sketch','value' => 'Cabin+Sketch'),
  array('label' => 'Cabin','value' => 'Cabin'),
  array('label' => 'Cagliostro','value' => 'Cagliostro'),
  array('label' => 'Calligraffitti','value' => 'Calligraffitti'),
  array('label' => 'Candal','value' => 'Candal'),
  array('label' => 'Cantarell','value' => 'Cantarell'),
  array('label' => 'Cardo','value' => 'Cardo'),
  array('label' => 'Carme','value' => 'Carme'),
  array('label' => 'Carter One','value' => 'Carter+One'),
  array('label' => 'Caudex','value' => 'Caudex'),
  array('label' => 'Cedarville Cursive','value' => 'Cedarville+Cursive'),
  array('label' => 'Changa One','value' => 'Changa+One'),
  array('label' => 'Cherry Cream Soda','value' => 'Cherry+Cream+Soda'),
  array('label' => 'Chewy','value' => 'Chewy'),
  array('label' => 'Chicle','value' => 'Chicle'),
  array('label' => 'Chivo','value' => 'Chivo'),
  array('label' => 'Coda Caption','value' => 'Coda+Caption'),
  array('label' => 'Coda','value' => 'Coda'),
  array('label' => 'Comfortaa','value' => 'Comfortaa'),
  array('label' => 'Coming Soon','value' => 'Coming+Soon'),
  array('label' => 'Contrail One','value' => 'Contrail+One'),
  array('label' => 'Convergence','value' => 'Convergence'),
  array('label' => 'Cookie','value' => 'Cookie'),
  array('label' => 'Copse','value' => 'Copse'),
  array('label' => 'Corben','value' => 'Corben'),
  array('label' => 'Cousine','value' => 'Cousine'),
  array('label' => 'Coustard','value' => 'Coustard'),
  array('label' => 'Covered By Your Grace','value' => 'Covered+By+Your+Grace'),
  array('label' => 'Crafty Girls','value' => 'Crafty+Girls'),
  array('label' => 'Creepster Caps','value' => 'Creepster+Caps'),
  array('label' => 'Crimson Text','value' => 'Crimson+Text'),
  array('label' => 'Crushed','value' => 'Crushed'),
  array('label' => 'Cuprum','value' => 'Cuprum'),
  array('label' => 'Damion','value' => 'Damion'),
  array('label' => 'Dancing Script','value' => 'Dancing+Script'),
  array('label' => 'Dawning of a New Day','value' => 'Dawning+of+a+New+Day'),
  array('label' => 'Days One','value' => 'Days+One'),
  array('label' => 'Delius Swash Caps','value' => 'Delius+Swash+Caps'),
  array('label' => 'Delius Unicase','value' => 'Delius+Unicase'),
  array('label' => 'Delius','value' => 'Delius'),
  array('label' => 'Devonshire','value' => 'Devonshire'),
  array('label' => 'Didact Gothic','value' => 'Didact+Gothic'),
  array('label' => 'Dorsa','value' => 'Dorsa'),
  array('label' => 'Dr Sugiyama','value' => 'Dr+Sugiyama'),
  array('label' => 'Droid Sans Mono','value' => 'Droid+Sans+Mono'),
  array('label' => 'Droid Sans','value' => 'Droid+Sans'),
  array('label' => 'Droid Serif','value' => 'Droid+Serif'),
  array('label' => 'EB Garamond','value' => 'EB+Garamond'),
  array('label' => 'Eater Caps','value' => 'Eater+Caps'),
  array('label' => 'Expletus Sans','value' => 'Expletus+Sans'),
  array('label' => 'Fanwood Text','value' => 'Fanwood+Text'),
  array('label' => 'Federant','value' => 'Federant'),
  array('label' => 'Federo','value' => 'Federo'),
  array('label' => 'Fjord One','value' => 'Fjord+One'),
  array('label' => 'Fondamento','value' => 'Fondamento'),
  array('label' => 'Fontdiner Swanky','value' => 'Fontdiner+Swanky'),
  array('label' => 'Forum','value' => 'Forum'),
  array('label' => 'Francois One','value' => 'Francois+One'),
  array('label' => 'Gentium Basic','value' => 'Gentium+Basic'),
  array('label' => 'Gentium Book Basic','value' => 'Gentium+Book+Basic'),
  array('label' => 'Geo','value' => 'Geo'),
  array('label' => 'Geostar Fill','value' => 'Geostar+Fill'),
  array('label' => 'Geostar','value' => 'Geostar'),
  array('label' => 'Give You Glory','value' => 'Give+You+Glory'),
  array('label' => 'Gloria Hallelujah','value' => 'Gloria+Hallelujah'),
  array('label' => 'Goblin One','value' => 'Goblin+One'),
  array('label' => 'Gochi Hand','value' => 'Gochi+Hand'),
  array('label' => 'Goudy Bookletter 1911','value' => 'Goudy+Bookletter+1911'),
  array('label' => 'Gravitas One','value' => 'Gravitas+One'),
  array('label' => 'Gruppo','value' => 'Gruppo'),
  array('label' => 'Hammersmith One','value' => 'Hammersmith+One'),
  array('label' => 'Herr Von Muellerhoff','value' => 'Herr+Von+Muellerhoff'),
  array('label' => 'Holtwood One SC','value' => 'Holtwood+One+SC'),
  array('label' => 'Homemade Apple','value' => 'Homemade+Apple'),
  array('label' => 'IM Fell DW Pica SC','value' => 'IM+Fell+DW+Pica+SC'),
  array('label' => 'IM Fell DW Pica','value' => 'IM+Fell+DW+Pica'),
  array('label' => 'IM Fell Double Pica SC','value' => 'IM+Fell+Double+Pica+SC'),
  array('label' => 'IM Fell Double Pica','value' => 'IM+Fell+Double+Pica'),
  array('label' => 'IM Fell English SC','value' => 'IM+Fell+English+SC'),
  array('label' => 'IM Fell English','value' => 'IM+Fell+English'),
  array('label' => 'IM Fell French Canon SC','value' => 'IM+Fell+French+Canon+SC'),
  array('label' => 'IM Fell French Canon','value' => 'IM+Fell+French+Canon'),
  array('label' => 'IM Fell Great Primer SC','value' => 'IM+Fell+Great+Primer+SC'),
  array('label' => 'IM Fell Great Primer','value' => 'IM+Fell+Great+Primer'),
  array('label' => 'Iceland','value' => 'Iceland'),
  array('label' => 'Inconsolata','value' => 'Inconsolata'),
  array('label' => 'Indie Flower','value' => 'Indie+Flower'),
  array('label' => 'Irish Grover','value' => 'Irish+Grover'),
  array('label' => 'Istok Web','value' => 'Istok+Web'),
  array('label' => 'Jockey One','value' => 'Jockey+One'),
  array('label' => 'Josefin Sans','value' => 'Josefin+Sans'),
  array('label' => 'Josefin Slab','value' => 'Josefin+Slab'),
  array('label' => 'Judson','value' => 'Judson'),
  array('label' => 'Julee','value' => 'Julee'),
  array('label' => 'Jura','value' => 'Jura'),
  array('label' => 'Just Another Hand','value' => 'Just+Another+Hand'),
  array('label' => 'Just Me Again Down Here','value' => 'Just+Me+Again+Down+Here'),
  array('label' => 'Kameron','value' => 'Kameron'),
  array('label' => 'Kelly Slab','value' => 'Kelly+Slab'),
  array('label' => 'Kenia','value' => 'Kenia'),
  array('label' => 'Knewave','value' => 'Knewave'),
  array('label' => 'Kranky','value' => 'Kranky'),
  array('label' => 'Kreon','value' => 'Kreon'),
  array('label' => 'Kristi','value' => 'Kristi'),
  array('label' => 'La Belle Aurore','value' => 'La+Belle+Aurore'),
  array('label' => 'Lancelot','value' => 'Lancelot'),
  array('label' => 'Lato','value' => 'Lato'),
  array('label' => 'League Script','value' => 'League+Script'),
  array('label' => 'Leckerli One','value' => 'Leckerli+One'),
  array('label' => 'Lekton','value' => 'Lekton'),
  array('label' => 'Lemon','value' => 'Lemon'),
  array('label' => 'Limelight','value' => 'Limelight'),
  array('label' => 'Linden Hill', 'value' => 'Linden+Hill'),
  array('label' => 'Lobster Two','value' => 'Lobster+Two'),
  array('label' => 'Lobster','value' => 'Lobster'),
  array('label' => 'Lora','value' => 'Lora'),
  array('label' => 'Love Ya Like A Sister','value' => 'Love+Ya+Like+A+Sister'),
  array('label' => 'Loved by the King','value' => 'Loved+by+the+King'),
  array('label' => 'Luckiest Guy','value' => 'Luckiest+Guy'),
  array('label' => 'Maiden Orange','value' => 'Maiden+Orange'),
  array('label' => 'Mako','value' => 'Mako'),
  array('label' => 'Marck Script','value' => 'Marck+Script'),
  array('label' => 'Marvel','value' => 'Marvel'),
  array('label' => 'Mate SC','value' => 'Mate+SC'),
  array('label' => 'Mate','value' => 'Mate'),
  array('label' => 'Maven Pro','value' => 'Maven+Pro'),
  array('label' => 'Meddon','value' => 'Meddon'),
  array('label' => 'MedievalSharp','value' => 'MedievalSharp'),
  array('label' => 'Megrim','value' => 'Megrim'),
  array('label' => 'Merienda One','value' => 'Merienda+One'),
  array('label' => 'Merriweather','value' => 'Merriweather'),
  array('label' => 'Metrophobic','value' => 'Metrophobic'),
  array('label' => 'Michroma','value' => 'Michroma'),
  array('label' => 'Miltonian Tattoo','value' => 'Miltonian+Tattoo'),
  array('label' => 'Miltonian','value' => 'Miltonian'),
  array('label' => 'Miss Fajardose','value' => 'Miss+Fajardose'),
  array('label' => 'Miss Saint Delafield','value' => 'Miss+Saint+Delafield'),
  array('label' => 'Modern Antiqua','value' => 'Modern+Antiqua'),
  array('label' => 'Molengo','value' => 'Molengo'),
  array('label' => 'Monofett','value' => 'Monofett'),
  array('label' => 'Monoton','value' => 'Monoton'),
  array('label' => 'Monsieur La Doulaise','value' => 'Monsieur+La+Doulaise'),
  array('label' => 'Montez','value' => 'Montez'),
  array('label' => 'Mountains of Christmas','value' => 'Mountains+of+Christmas'),
  array('label' => 'Mr Bedford','value' => 'Mr+Bedford'),
  array('label' => 'Mr Dafoe','value' => 'Mr+Dafoe'),
  array('label' => 'Mr De Haviland','value' => 'Mr+De+Haviland'),
  array('label' => 'Mrs Sheppards','value' => 'Mrs+Sheppards'),
  array('label' => 'Muli','value' => 'Muli'),
  array('label' => 'Neucha','value' => 'Neucha'),
  array('label' => 'Neuton','value' => 'Neuton'),
  array('label' => 'News Cycle','value' => 'News+Cycle'),
  array('label' => 'Niconne','value' => 'Niconne'),
  array('label' => 'Nixie One','value' => 'Nixie+One'),
  array('label' => 'Nobile','value' => 'Nobile'),
  array('label' => 'Nosifer Caps','value' => 'Nosifer+Caps'),
  array('label' => 'Nothing You Could Do','value' => 'Nothing+You+Could+Do'),
  array('label' => 'Nova Cut','value' => 'Nova+Cut'),
  array('label' => 'Nova Flat','value' => 'Nova+Flat'),
  array('label' => 'Nova Mono','value' => 'Nova+Mono'),
  array('label' => 'Nova Oval','value' => 'Nova+Oval'),
  array('label' => 'Nova Round','value' => 'Nova+Round'),
  array('label' => 'Nova Script','value' => 'Nova+Script'),
  array('label' => 'Nova Slim','value' => 'Nova+Slim'),
  array('label' => 'Nova Square','value' => 'Nova+Square'),
  array('label' => 'Numans','value' => 'Numans'),
  array('label' => 'Nunito','value' => 'Nunito'),
  array('label' => 'Old Standard TT','value' => 'Old+Standard+TT'),
  array('label' => 'Open Sans Condensed','value' => 'Open+Sans+Condensed'),
  array('label' => 'Open Sans','value' => 'Open+Sans'),
  array('label' => 'Orbitron','value' => 'Orbitron'),
  array('label' => 'Oswald','value' => 'Oswald'),
  array('label' => 'Over the Rainbow','value' => 'Over+the+Rainbow'),
  array('label' => 'Ovo','value' => 'Ovo'),
  array('label' => 'PT Sans Caption','value' => 'PT+Sans+Caption'),
  array('label' => 'PT Sans Narrow','value' => 'PT+Sans+Narrow'),
  array('label' => 'PT Sans','value' => 'PT+Sans'),
  array('label' => 'PT Serif Caption','value' => 'PT+Serif+Caption'),
  array('label' => 'PT Serif','value' => 'PT+Serif'),
  array('label' => 'Pacifico','value' => 'Pacifico'),
  array('label' => 'Passero One','value' => 'Passero+One'),
  array('label' => 'Patrick Hand','value' => 'Patrick+Hand'),
  array('label' => 'Paytone One','value' => 'Paytone+One'),
  array('label' => 'Permanent Marker','value' => 'Permanent+Marker'),
  array('label' => 'Petrona','value' => 'Petrona'),
  array('label' => 'Philosopher','value' => 'Philosopher'),
  array('label' => 'Piedra','value' => 'Piedra'),
  array('label' => 'Pinyon Script','value' => 'Pinyon+Script'),
  array('label' => 'Play','value' => 'Play'),
  array('label' => 'Playfair Display','value' => 'Playfair+Display'),
  array('label' => 'Podkova','value' => 'Podkova'),
  array('label' => 'Poller One','value' => 'Poller+One'),
  array('label' => 'Poly','value' => 'Poly'),
  array('label' => 'Pompiere','value' => 'Pompiere'),
  array('label' => 'Prata','value' => 'Prata'),
  array('label' => 'Prociono','value' => 'Prociono'),
  array('label' => 'Puritan','value' => 'Puritan'),
  array('label' => 'Quattrocento Sans','value' => 'Quattrocento+Sans'),
  array('label' => 'Quattrocento','value' => 'Quattrocento'),
  array('label' => 'Questrial','value' => 'Questrial'),
  array('label' => 'Quicksand','value' => 'Quicksand'),
  array('label' => 'Radley','value' => 'Radley'),
  array('label' => 'Raleway','value' => 'Raleway'),
  array('label' => 'Rammetto One','value' => 'Rammetto+One'),
  array('label' => 'Rancho','value' => 'Rancho'),
  array('label' => 'Rationale','value' => 'Rationale'),
  array('label' => 'Redressed','value' => 'Redressed'),
  array('label' => 'Reenie Beanie','value' => 'Reenie+Beanie'),
  array('label' => 'Ribeye Marrow','value' => 'Ribeye+Marrow'),
  array('label' => 'Ribeye','value' => 'Ribeye'),
  array('label' => 'Righteous','value' => 'Righteous'),
  array('label' => 'Rochester','value' => 'Rochester'),
  array('label' => 'Rock Salt','value' => 'Rock+Salt'),
  array('label' => 'Rokkitt','value' => 'Rokkitt'),
  array('label' => 'Rosario','value' => 'Rosario'),
  array('label' => 'Ruslan Display','value' => 'Ruslan+Display'),
  array('label' => 'Salsa','value' => 'Salsa'),
  array('label' => 'Sancreek','value' => 'Sancreek'),
  array('label' => 'Sansita One','value' => 'Sansita+One'),
  array('label' => 'Satisfy','value' => 'Satisfy'),
  array('label' => 'Schoolbell','value' => 'Schoolbell'),
  array('label' => 'Shadows Into Light','value' => 'Shadows+Into+Light'),
  array('label' => 'Shanti','value' => 'Shanti'),
  array('label' => 'Short Stack','value' => 'Short+Stack'),
  array('label' => 'Sigmar One','value' => 'Sigmar+One'),
  array('label' => 'Signika Negative','value' => 'Signika+Negative'),
  array('label' => 'Signika','value' => 'Signika'),
  array('label' => 'Six Caps','value' => 'Six+Caps'),
  array('label' => 'Slackey','value' => 'Slackey'),
  array('label' => 'Smokum','value' => 'Smokum'),
  array('label' => 'Smythe','value' => 'Smythe'),
  array('label' => 'Sniglet','value' => 'Sniglet'),
  array('label' => 'Snippet','value' => 'Snippet'),
  array('label' => 'Sorts Mill Goudy','value' => 'Sorts+Mill+Goudy'),
  array('label' => 'Special Elite',/* */'value' => 'Special+Elite'),
  array('label' => 'Spinnaker','value' => 'Spinnaker'),
  array('label' => 'Spirax','value' => 'Spirax'),
  array('label' => 'Stardos Stencil',/* */'value' => 'Stardos+Stencil'),
  array('label' => 'Sue Ellen Francisco',/* */'value' => 'Sue+Ellen+Francisco'),
  array('label' => 'Sunshiney','value' => 'Sunshiney'),
  array('label' => 'Supermercado One','value' => 'Supermercado+One'),
  array('label' => 'Swanky and Moo Moo','value' => 'Swanky+and+Moo+Moo'),
  array('label' => 'Syncopate','value' => 'Syncopate'),
  array('label' => 'Tangerine','value' => 'Tangerine'),
  array('label' => 'Tenor Sans','value' => 'Tenor+Sans'),
  array('label' => 'Terminal Dosis','value' => 'Terminal+Dosis'),
  array('label' => 'The Girl Next Door','value' => 'The+Girl+Next+Door'),
  array('label' => 'Tienne','value' => 'Tienne'),
  array('label' => 'Tinos','value' => 'Tinos'),
  array('label' => 'Tulpen One','value' => 'Tulpen+One'),
  array('label' => 'Ubuntu Condensed','value' => 'Ubuntu+Condensed'),
  array('label' => 'Ubuntu Mono','value' => 'Ubuntu+Mono'),
  array('label' => 'Ubuntu','value' => 'Ubuntu'),
  array('label' => 'Ultra','value' => 'Ultra'),
  array('label' => 'UnifrakturCook','value' => 'UnifrakturCook'),
  array('label' => 'UnifrakturMaguntia','value' => 'UnifrakturMaguntia'),
  array('label' => 'Unkempt','value' => 'Unkempt'),
  array('label' => 'Unlock','value' => 'Unlock'),
  array('label' => 'Unna','value' => 'Unna'),
  array('label' => 'VT323','value' => 'VT323'),
  array('label' => 'Varela Round','value' => 'Varela+Round'),
  array('label' => 'Varela','value' => 'Varela'),
  array('label' => 'Vast Shadow','value' => 'Vast+Shadow'),
  array('label' => 'Vibur','value' => 'Vibur'),
  array('label' => 'Vidaloka','value' => 'Vidaloka'),
  array('label' => 'Volkhov','value' => 'Volkhov'),
  array('label' => 'Vollkorn','value' => 'Vollkorn'),
  array('label' => 'Voltaire','value' => 'Voltaire'),
  array('label' => 'Waiting for the Sunrise','value' => 'Waiting+for+the+Sunrise'),
  array('label' => 'Wallpoet','value' => 'Wallpoet'),
  array('label' => 'Walter Turncoat','value' => 'Walter+Turncoat'),
  array('label' => 'Wire One','value' => 'Wire+One'),
  array('label' => 'Yanone Kaffeesatz','value' => 'Yanone+Kaffeesatz'),
  array('label' => 'Yellowtail','value' => 'Yellowtail'),
  array('label' => 'Yeseva One','value' => 'Yeseva+One'),
  array('label' => 'Zeyada','value' => 'Zeyada')
  );
