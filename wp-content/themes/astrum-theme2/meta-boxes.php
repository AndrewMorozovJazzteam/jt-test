<?php
/**
 * Initialize the meta boxes.
 */
add_action( 'admin_init', '_custom_meta_boxes' );

/**
 * Meta Boxes demo code.
 *
 * You can find all the available option types
 * in demo-theme-options.php.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */
function _custom_meta_boxes() {

  /**
   * Create a custom meta boxes array that we pass to
   * the OptionTree Meta Box API Class.
   */
  $meta_box_layout = array(
    'id'        => 'pp_metabox_sidebar',
    'title'     => 'Layout',
    'desc'      => 'If you choose the sidebar layout, please choose a sidebar from the list below. Sidebars can be created in the Theme Options and configured in the Theme Widgets.',
    'pages'     => array( 'post' ),
    'context'   => 'normal',
    'priority'  => 'high',
    'fields'    =>   array(
      array(
        'id'          => 'pp_sidebar_layout',
        'label'       => 'Layout',
        'desc'        => '',
        'std'         => 'right-sidebar',
        'type'        => 'radio_image',
        'class'       => '',
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
        ),
      array(
        'id'          => 'pp_sidebar_set',
        'label'       => 'Sidebar',
        'desc'        => '',
        'std'         => '',
        'type'        => 'sidebar-select',
        'class'       => '',

        )
      )
    );

$meta_box_layout_page = array(
  'id'        => 'pp_metabox_sidebar',
  'title'     => 'Layout',
  'desc'      => 'If you choose the sidebar layout, please choose a sidebar from the list below. Sidebars can be created in the Theme Options and configured in the Theme Widgets.',
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_sidebar_layout',
      'label'       => 'Layout',
      'desc'        => '',
      'std'         => 'full-width',
      'type'        => 'radio_image',
      'class'       => '',
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
          ),
        array(
          'value'   => 'full-width',
          'label'   => 'Full Width (no sidebar)',
          'src'     => OT_URL . '/assets/images/layout/full-width.png'
          )
        ),
      ),
    array(
      'id'          => 'pp_sidebar_set',
      'label'       => 'Sidebar',
      'desc'        => '',
      'std'         => '',
      'type'        => 'sidebar-select',
      'class'       => '',
      )
    )
);

$post_options = array(
  'id'        => 'pp_metabox_featue',
  'title'     => 'Post options',
  'desc'      => 'Select post display options (Option depends on Post\'s Format, so be sure to select one.',
    'pages'     => array( 'post' ),
    'context'   => 'normal',
    'priority'  => 'high',
    'fields'    => array(

      array(
        'id'          => 'pp_thumb_status',
        'label'       => 'Hide thumbnail:',
        'desc'        => '',
        'std'         => 'show_thumb',
        'type'        => 'checkbox',
        'class'       => '',
        'choices' => array(
          array(
            'label' => 'on single post',
            'value' => 'hide_single'
            ),
          array(
            'label' => 'on blog/archive/category page',
            'value' => 'hide_blog'
            )
          )
        ),
      array(
        'label' => 'Gallery slider (use when Post Type is set to Gallery)',
        'id' => 'pp_gallery_slider',
        'type' => 'gallery',
        'desc' => 'Click Create Slider to create your gallery for slider.',
        'post_type' => 'post',
        ),
      array(
        'id'          => 'pp_video_link',
        'label'       => 'Link to Video',
        'desc'        => 'Just link, not embed code, this field uses oEmbed.',
        'std'         => '',
        'type'        => 'text',
        'class'       => '',
        ),
      array(
        'id'          => 'pp_video_embed',
        'label'       => 'Embed code for Video',
        'desc'        => 'Place here embed code for videos services that do not support oEmbed',
        'std'         => '',
        'type'        => 'textarea',
        'class'       => '',
        ),
      )
);


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


$slider = array(
  'id'        => 'pp_metabox_slider',
  'title'     => 'Slider settings',
  'desc'      => 'If you want to use Revolution Slider on this page, select page template "Revolution Page" and choose slider you want to display',
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_page_layer',
      'label'       => 'Revolution Slider',
      'desc'        => '',
      'std'         => '',
      'type'        => 'select',
      'choices'     => $layers,
      'class'       => '',
      )
    )
  );

/*$gallerypage = array(
  'id'        => 'pp_metabox_gallerypage',
  'title'     => 'Gallery slider',
  'desc'      => 'If you want to use flexslider gallery like on Portfolio item, just create gallery using button below',
  'pages'     => array( 'page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'label' => 'Gallery slider ',
      'id' => 'pp_gallery_slider',
      'type' => 'puregallery',
      'desc' => 'Click Create Slider to create your gallery for slider.',
      'post_type' => 'post',
      )
    )
);*/



$meta_box_filters = array(

  'id'        => 'pp_metabox_pf_tax',
  'title'     => 'Portfolio Template Options',
  'desc'      => '',
  'pages'     => array('page' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(

    array(
      'label' => 'Select Filters to display',
      'id' => 'portfolio_filters',
      'type' => 'taxonomy-checkbox',
      'desc' => 'Select filters from which you want to display your portfolio items.',
      'std' => '',
      'rows' => '',
      'post_type' => '',
      'taxonomy' => 'filters',
      'class' => 'filters-checbox' ),
    array(
      'id'          => 'pp_filters_switch',
      'label'       => 'Filters buttons display',
      'desc'        => '',
      'std'         => '',
      'type'        => 'select',
      'class'       => '',
      'choices' => array(
        array(
          'label' => 'Yes',
          'value' => 'yes'
          ),
        array(
          'label' => 'No',
          'value' => 'no'
          )
        )
      ),
    array(
      'id'          => 'pp_portfolio_title',
      'label'       => 'Title of Portfolio Section',
      'desc'        => 'Overwrites setting from Theme Options.',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),

    )

);






$my_meta_box_pf = array(
  'id'        => 'pp_metabox_pf',
  'title'     => 'Portfolio Options',
  'desc'      => '',
  'pages'     => array('portfolio' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_pf_type',
      'label'       => 'Portfolio type',
      'desc'        => '',
      'std'         => '',
      'type'        => 'select',
      'class'       => '',
      'choices' => array(
        array(
          'label' => 'Gallery',
          'value' => 'gallery'
          ),
        array(
          'label' => 'Video',
          'value' => 'video'
          )
        )
      ),
    array(
      'id'          => 'pp_pf_lightbox',
      'label'       => 'Lightbox status',
      'desc'        => 'Display item with a link to lightbox or permalink when used in shortcode or "Recent work" section',
      'std'         => '',
      'type'        => 'select',
      'class'       => '',
      'choices' => array(
        array(
          'label' => 'Permalink',
          'value' => 'permalink'
          ),
        array(
          'label' => 'Lightbox',
          'value' => 'lightbox'
          )
        )
      ),
    array(
      'id'          => 'pp_pf_layout',
      'label'       => 'Portfolio layout',
      'desc'        => '',
      'std'         => '',
      'type'        => 'select',
      'class'       => '',
      'choices' => array(
        array(
          'label' => 'Wide',
          'value' => 'wide'
          ),
        array(
          'label' => 'Half',
          'value' => 'half'
          )
        )
      ),
    array(
      'label' => 'Gallery slider',
      'id'    => 'pp_gallery_slider',
      'type'  => 'gallery',
      'desc'  => 'Click Create Slider to create your gallery for slider.',
      'post_type' => 'post',

      ),
    array(
      'id'          => 'pp_pfvideo_link',
      'label'       => 'Link to Video',
      'desc'        => 'Just link, not embed code, this field is used for oEmbed.',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),
    array(
      'id'          => 'pp_pfvideo_embed',
      'label'       => 'Embed code for Video',
      'desc'        => 'Place here embed code for videos services that do not support oEmbed',
      'std'         => '',
      'type'        => 'textarea',
      'class'       => '',
      )
    )
);


$testimonials = array(
  'id'        => 'pp_metabox_testimonials',
  'title'     => 'Testimonials info',
  'desc'      => 'Fill field below to use testimonials in slider',
  'pages'     => array( 'testimonial' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_author',
      'label'       => 'Author of testimonial',
      'desc'        => '',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),
    array(
      'id'          => 'pp_link',
      'label'       => 'Link to authors website (optional)',
      'desc'        => '',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),
    array(
      'id'          => 'pp_position',
      'label'       => 'Enter their position in their specific company.',
      'desc'        => '',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),
	array(
      'id'          => 'pp_project_info',
      'label'       => 'Enter their project info.',
      'desc'        => '',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),
    array(
      'id'          => 'pp_second_content',
      'label'       => 'Enter their second content.',
      'desc'        => '',
      'std'         => '',
      'type'        => 'textarea',
      'class'       => '',
      ),
    )
  );

$team = array(
  'id'        => 'pp_team',
  'title'     => 'Subtitle',
  'desc'      => '',
  'pages'     => array( 'team' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_position',
      'label'       => 'Position',
      'desc'        => 'Position of team member.',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      ),
    array(
      'label'       => 'Social profiles',
      'id'          => 'pp_socialicons',
      'type'        => 'list-item',
      'desc'        => 'Manage socials icons.',
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
            array('value'=> 'wordpress','label' => 'WordPress','src'=> ''),
            array('value'=> 'facebook','label' => 'Facebook','src'=> ''),
            array('value'=> 'linkedin','label' => 'LinkedIN','src'=> ''),
            array('value'=> 'steam','label' => 'Steam','src'=> ''),
            array('value'=> 'tumblr','label' => 'Tumblr','src'=> ''),
            array('value'=> 'github','label' => 'GitHub','src'=> ''),
            array('value'=> 'delicious','label' => 'Delicious','src'=> ''),
            array('value'=> 'instagram','label' => 'Instagram','src'=> ''),
            array('value'=> 'xing','label' => 'Xing','src'=> ''),
            array('value'=> 'amazon','label'=> 'Amazon','src'=> ''),
            array('value'=> 'dropbox','label'=> 'Dropbox','src'=> ''),
            array('value'=> 'paypal','label'=> 'PayPal','src'=> ''),
            array('value'=> 'lastfm','label' => 'LastFM','src'=> ''),
            array('value'=> 'gplus','label' => 'Google Plus','src'=> ''),
            array('value'=> 'yahoo','label' => 'Yahoo','src'=> ''),
            array('value'=> 'pinterest','label' => 'Pinterest','src'=> ''),
            array('value'=> 'dribbble','label' => 'Dribbble','src'=> ''),
            array('value'=> 'flickr','label' => 'Flickr','src'=> ''),
            array('value'=> 'reddit','label' => 'Reddit','src'=> ''),
            array('value'=> 'vimeo','label' => 'Vimeo','src'=> ''),
            array('value'=> 'spotify','label' => 'Spotify','src'=> ''),
            array('value'=> 'rss','label' => 'RSS','src'=> ''),
            array('value'=> 'youtube','label' => 'YouTube','src'=> ''),
            array('value'=> 'blogger','label' => 'Blogger','src'=> ''),
            array('value'=> 'appstore','label' => 'AppStore','src'=> ''),
            array('value'=> 'evernote','label' => 'Evernote','src'=> ''),
            array('value'=> 'digg','label' => 'Digg','src'=> ''),
            array('value'=> 'forrst','label' => 'Forrst','src'=> ''),
            array('value'=> 'fivehundredpx','label' => '500px','src'=> ''),
            array('value'=> 'stumbleupon','label' => 'StumbleUpon','src'=> ''),
            array('value'=> 'dribbble','label' => 'Dribbble','src'=> '')
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
)
)
)
);


$products = array(
  'id'        => 'pp_metabox_testimonials',
  'title'     => 'Featured Hover Image',
  'pages'     => array( 'product' ),
  'context'   => 'side',
  'priority'  => 'low',
  'fields'    => array(
    array(
      'id'          => 'pp_featured_hover',
      'label'       => 'Product thumbnail on hover',
      'desc'        => '',
      'std'         => '',
      'type'        => 'upload',
      'class'       => '',
      ),
    )
  );

$subtitle = array(
  'id'        => 'pp_metabox_subtitle',
  'title'     => 'Subtitle',
  'desc'      => '',
  'pages'     => array( 'page', 'portfolio' ),
  'context'   => 'normal',
  'priority'  => 'high',
  'fields'    => array(
    array(
      'id'          => 'pp_subtitle',
      'label'       => 'Subtitle',
      'desc'        => 'Set subtitle for page.',
      'std'         => '',
      'type'        => 'text',
      'class'       => '',
      )
    )
  );

  /**
   * Register our meta boxes using the
   * ot_register_meta_box() function.
   */
  ot_register_meta_box( $meta_box_layout );
  ot_register_meta_box( $meta_box_layout_page );
  ot_register_meta_box( $post_options );
  ot_register_meta_box( $meta_box_filters );
  /*ot_register_meta_box( $gallerypage );*/
  ot_register_meta_box( $subtitle );
  ot_register_meta_box( $slider );
  ot_register_meta_box( $my_meta_box_pf );
  ot_register_meta_box( $testimonials );
  ot_register_meta_box( $team );
  ot_register_meta_box( $products );

}