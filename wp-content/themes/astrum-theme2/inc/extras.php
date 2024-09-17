<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package astrum
 * @since astrum 1.0
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since astrum 1.0
 */
function astrum_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'astrum_page_menu_args' );



/**
 * Adds custom classes to the array of body classes.
 *
 * @since astrum 1.0
 */
function astrum_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	return $classes;
}
add_filter( 'body_class', 'astrum_body_classes' );


/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since astrum 1.0
 */
function astrum_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'astrum_enhanced_image_navigation', 10, 2 );


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since astrum 1.1
 */
function astrum_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'purepress' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'astrum_wp_title', 10, 2);


/*
OptionTree Gallery Manager
author: purethemes.net
----------------------------------------------------- */

function ot_type_puregallery( $args = array() ) {
    /* turns arguments array into variables */
    extract( $args );
    global $post;

    $current_post_id = $post->ID;

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-post_attachments_checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

    /* description */
    echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . ' <br/><a href="#" class="delete-gallery">Delete gallery</a></div>' : '';

    /* format setting inner wrapper */
    echo '<div class="format-setting-inner">';

    /* setup the post types */
    $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
    global $pagenow;
    if($pagenow == 'themes.php' ) {
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post_mime_type' => 'image',
            'post__in' => explode( ",", $field_value),
            'posts_per_page' => '-1',
            'orderby' => 'post__in'
            );
    } else {
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post__in' => explode( ",", $field_value),
            'post_mime_type' => 'image',
            'posts_per_page' => '-1',
            'orderby' => 'post__in'
            );
    }

    /* query posts array */
    $query = new WP_Query( $args  );

    /* has posts */ echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" />';
    if ( $query->have_posts() ) {
        echo '<ul style="margin:0px" id="option-tree-gallery-list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<li>';
            $thumbnail = wp_get_attachment_image_src( $query->post->ID, 'thumbnail');
            echo '<img  src="' . $thumbnail[0] . '" width="60" height="60" />';
            echo '</li>';

        }
        echo "</ul>";
        echo '<a title="Add images" class="option-tree-attachments-update option-tree-ui-button blue right hug-right addgallery" href="#">Edit Slider Gallery</a>';

    } else {
        echo '<ul style="margin:0px" id="option-tree-gallery-list"></ul><p>' . __( 'No Gallery', 'option-tree' ) . '</p>';
        echo '<a title="Add images" class="option-tree-attachments-update option-tree-ui-button blue right hug-right addgallery" href="#">Create Slider Gallery</a>';
    }

    echo '</div>';
    echo '</div>';
}

//fake and dirty shortcode for stupid media uploader
function media_view_settings($settings, $post ) {
    if (!is_object($post)) return $settings;
    $shortcode = '[gallery ';
    $ids = get_post_meta($post->ID, 'pp_gallery_slider', TRUE);
    $ids = explode(",", $ids);

    if (is_array($ids))
        $shortcode .= 'ids = "' . implode(',',$ids) . '"]';
    else
        $shortcode .= "id = \"{$post->ID}\"]";
    $settings['astrumgallery'] = array('shortcode' => $shortcode);
    return $settings;
}
add_filter( 'media_view_settings','media_view_settings', 10, 2 );

function ot_type_attachments_ajax_update() {
    if ( !empty( $_POST['ids'] ) )  {
        $args = array(
           'post_type' => 'attachment',
           'post_status' => 'inherit',
           'post__in' => $_POST['ids'],
           'post_mime_type' => 'image',
           'posts_per_page' => '-1',
           'orderby' => 'post__in'
           );
        $return = '';
        /* query posts array */
        $query = new WP_Query( $args  );
        $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
        /* has posts */
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $return .= '<li>';
                $thumbnail = wp_get_attachment_image_src( $query->post->ID, 'thumbnail');
                $return .=  '<img  src="' . $thumbnail[0] . '" width="60" height="60" />';
                $return .=  '</li>';

            }

        } else {
            $return .=  '<p>' . __( 'No Posts Found', 'option-tree' ) . '</p>';
        }
        echo $return;
        exit();
    }
}
add_action( 'wp_ajax_attachments_update', 'ot_type_attachments_ajax_update' );


// Twitter function
add_action( 'init', 'astrum_twitter_api' );
function astrum_twitter_api() {
    global $cb;
    $consumer_key = ot_get_option('pp_twitter_ck');
    $consumer_secret = ot_get_option('pp_twitter_cs');
    $access_token = ot_get_option('pp_twitter_at');
    $access_secret = ot_get_option('pp_twitter_ts');
    require_once('codebird.php');
    Codebird::setConsumerKey( $consumer_key, $consumer_secret );
    $cb = Codebird::getInstance();
    $cb->setToken( $access_token, $access_secret );
}

// Add search box to menu
//add_filter('wp_nav_menu_items','add_search_box', 10, 2);
function add_search_box($items, $args) {
    if( ot_get_option('pp_search') == 'enable' && $_SERVER['REQUEST_URI'] != '/TestDesign/main-page-new-design/') {
        if( $args->theme_location == 'primary' ) {
            $items .= '<li class="search-container"><div id="search-form">
            <form method="get" class="search-form" action="'.esc_url( home_url( '/' ) ).'" role="search">
            <input type="text" name="s" id="s" class="search-text-box" />
            </form>
            </div></li>';
        }
    }
    return $items;
}

// Extend Purethemes.net Shortcodes plugin
function astrum_shortcodes_list( $pt_shortcodes ) {

    $ptsc_icons = array ( 'icon-glass' => 'icon-glass', 'icon-music' => 'icon-music', 'icon-search' => 'icon-search', 'icon-envelope' => 'icon-envelope', 'icon-heart' => 'icon-heart', 'icon-star' => 'icon-star', 'icon-star-empty' => 'icon-star-empty', 'icon-user' => 'icon-user', 'icon-film' => 'icon-film', 'icon-th-large' => 'icon-th-large', 'icon-th' => 'icon-th', 'icon-th-list' => 'icon-th-list', 'icon-ok' => 'icon-ok', 'icon-remove' => 'icon-remove', 'icon-zoom-in' => 'icon-zoom-in', 'icon-zoom-out' => 'icon-zoom-out', 'icon-off' => 'icon-off', 'icon-signal' => 'icon-signal', 'icon-cog' => 'icon-cog', 'icon-trash' => 'icon-trash', 'icon-home' => 'icon-home', 'icon-file' => 'icon-file', 'icon-time' => 'icon-time', 'icon-road' => 'icon-road', 'icon-download-alt' => 'icon-download-alt', 'icon-download' => 'icon-download', 'icon-upload' => 'icon-upload', 'icon-inbox' => 'icon-inbox', 'icon-play-circle' => 'icon-play-circle', 'icon-rotate-right' => 'icon-rotate-right', 'icon-refresh' => 'icon-refresh', 'icon-list-alt' => 'icon-list-alt', 'icon-lock' => 'icon-lock', 'icon-flag' => 'icon-flag', 'icon-headphones' => 'icon-headphones', 'icon-volume-off' => 'icon-volume-off', 'icon-volume-down' => 'icon-volume-down', 'icon-volume-up' => 'icon-volume-up', 'icon-qrcode' => 'icon-qrcode', 'icon-barcode' => 'icon-barcode', 'icon-tag' => 'icon-tag', 'icon-tags' => 'icon-tags', 'icon-book' => 'icon-book', 'icon-bookmark' => 'icon-bookmark', 'icon-print' => 'icon-print', 'icon-camera' => 'icon-camera', 'icon-font' => 'icon-font', 'icon-bold' => 'icon-bold', 'icon-italic' => 'icon-italic', 'icon-text-height' => 'icon-text-height', 'icon-text-width' => 'icon-text-width', 'icon-align-left' => 'icon-align-left', 'icon-align-center' => 'icon-align-center', 'icon-align-right' => 'icon-align-right', 'icon-align-justify' => 'icon-align-justify', 'icon-list' => 'icon-list', 'icon-indent-left' => 'icon-indent-left', 'icon-indent-right' => 'icon-indent-right', 'icon-facetime-video' => 'icon-facetime-video', 'icon-picture' => 'icon-picture', 'icon-pencil' => 'icon-pencil', 'icon-map-marker' => 'icon-map-marker', 'icon-adjust' => 'icon-adjust', 'icon-tint' => 'icon-tint', 'icon-edit' => 'icon-edit', 'icon-share' => 'icon-share', 'icon-check' => 'icon-check', 'icon-move' => 'icon-move', 'icon-step-backward' => 'icon-step-backward', 'icon-fast-backward' => 'icon-fast-backward', 'icon-backward' => 'icon-backward', 'icon-play' => 'icon-play', 'icon-pause' => 'icon-pause', 'icon-stop' => 'icon-stop', 'icon-forward' => 'icon-forward', 'icon-fast-forward' => 'icon-fast-forward', 'icon-step-forward' => 'icon-step-forward', 'icon-eject' => 'icon-eject', 'icon-chevron-left' => 'icon-chevron-left', 'icon-chevron-right' => 'icon-chevron-right', 'icon-plus-sign' => 'icon-plus-sign', 'icon-minus-sign' => 'icon-minus-sign', 'icon-remove-sign' => 'icon-remove-sign', 'icon-ok-sign' => 'icon-ok-sign', 'icon-question-sign' => 'icon-question-sign', 'icon-info-sign' => 'icon-info-sign', 'icon-screenshot' => 'icon-screenshot', 'icon-remove-circle' => 'icon-remove-circle', 'icon-ok-circle' => 'icon-ok-circle', 'icon-ban-circle' => 'icon-ban-circle', 'icon-arrow-left' => 'icon-arrow-left', 'icon-arrow-right' => 'icon-arrow-right', 'icon-arrow-up' => 'icon-arrow-up', 'icon-arrow-down' => 'icon-arrow-down', 'icon-mail-forward' => 'icon-mail-forward', 'icon-resize-full' => 'icon-resize-full', 'icon-resize-small' => 'icon-resize-small', 'icon-plus' => 'icon-plus', 'icon-minus' => 'icon-minus', 'icon-asterisk' => 'icon-asterisk', 'icon-exclamation-sign' => 'icon-exclamation-sign', 'icon-gift' => 'icon-gift', 'icon-leaf' => 'icon-leaf', 'icon-fire' => 'icon-fire', 'icon-eye-open' => 'icon-eye-open', 'icon-eye-close' => 'icon-eye-close', 'icon-warning-sign' => 'icon-warning-sign', 'icon-plane' => 'icon-plane', 'icon-calendar' => 'icon-calendar', 'icon-random' => 'icon-random', 'icon-comment' => 'icon-comment', 'icon-magnet' => 'icon-magnet', 'icon-chevron-up' => 'icon-chevron-up', 'icon-chevron-down' => 'icon-chevron-down', 'icon-retweet' => 'icon-retweet', 'icon-shopping-cart' => 'icon-shopping-cart', 'icon-folder-close' => 'icon-folder-close', 'icon-folder-open' => 'icon-folder-open', 'icon-resize-vertical' => 'icon-resize-vertical', 'icon-resize-horizontal' => 'icon-resize-horizontal', 'icon-bar-chart' => 'icon-bar-chart', 'icon-camera-retro' => 'icon-camera-retro', 'icon-key' => 'icon-key', 'icon-cogs' => 'icon-cogs', 'icon-comments' => 'icon-comments', 'icon-thumbs-up' => 'icon-thumbs-up', 'icon-thumbs-down' => 'icon-thumbs-down', 'icon-star-half' => 'icon-star-half', 'icon-heart-empty' => 'icon-heart-empty', 'icon-signout' => 'icon-signout', 'icon-pushpin' => 'icon-pushpin', 'icon-external-link' => 'icon-external-link', 'icon-signin' => 'icon-signin', 'icon-trophy' => 'icon-trophy', 'icon-github-sign' => 'icon-github-sign', 'icon-upload-alt' => 'icon-upload-alt', 'icon-lemon' => 'icon-lemon', 'icon-phone' => 'icon-phone', 'icon-check-empty' => 'icon-check-empty', 'icon-bookmark-empty' => 'icon-bookmark-empty', 'icon-phone-sign' => 'icon-phone-sign', 'icon-github' => 'icon-github', 'icon-unlock' => 'icon-unlock', 'icon-credit-card' => 'icon-credit-card', 'icon-rss' => 'icon-rss', 'icon-hdd' => 'icon-hdd', 'icon-bullhorn' => 'icon-bullhorn', 'icon-bell' => 'icon-bell', 'icon-certificate' => 'icon-certificate', 'icon-hand-right' => 'icon-hand-right', 'icon-hand-left' => 'icon-hand-left', 'icon-hand-up' => 'icon-hand-up', 'icon-hand-down' => 'icon-hand-down', 'icon-circle-arrow-left' => 'icon-circle-arrow-left', 'icon-circle-arrow-right' => 'icon-circle-arrow-right', 'icon-circle-arrow-up' => 'icon-circle-arrow-up', 'icon-circle-arrow-down' => 'icon-circle-arrow-down', 'icon-globe' => 'icon-globe', 'icon-wrench' => 'icon-wrench', 'icon-tasks' => 'icon-tasks', 'icon-filter' => 'icon-filter', 'icon-briefcase' => 'icon-briefcase', 'icon-fullscreen' => 'icon-fullscreen', 'icon-group' => 'icon-group', 'icon-link' => 'icon-link', 'icon-cloud' => 'icon-cloud', 'icon-beaker' => 'icon-beaker', 'icon-cut' => 'icon-cut', 'icon-copy' => 'icon-copy', 'icon-paper-clip' => 'icon-paper-clip', 'icon-save' => 'icon-save', 'icon-sign-blank' => 'icon-sign-blank', 'icon-reorder' => 'icon-reorder', 'icon-list-ul' => 'icon-list-ul', 'icon-list-ol' => 'icon-list-ol', 'icon-strikethrough' => 'icon-strikethrough', 'icon-underline' => 'icon-underline', 'icon-table' => 'icon-table', 'icon-magic' => 'icon-magic', 'icon-truck' => 'icon-truck', 'icon-money' => 'icon-money', 'icon-caret-down' => 'icon-caret-down', 'icon-caret-up' => 'icon-caret-up', 'icon-caret-left' => 'icon-caret-left', 'icon-caret-right' => 'icon-caret-right', 'icon-columns' => 'icon-columns', 'icon-sort' => 'icon-sort', 'icon-sort-down' => 'icon-sort-down', 'icon-sort-up' => 'icon-sort-up', 'icon-envelope-alt' => 'icon-envelope-alt', 'icon-rotate-left' => 'icon-rotate-left', 'icon-legal' => 'icon-legal', 'icon-dashboard' => 'icon-dashboard', 'icon-comment-alt' => 'icon-comment-alt', 'icon-comments-alt' => 'icon-comments-alt', 'icon-bolt' => 'icon-bolt', 'icon-sitemap' => 'icon-sitemap', 'icon-umbrella' => 'icon-umbrella', 'icon-paste' => 'icon-paste', 'icon-lightbulb' => 'icon-lightbulb', 'icon-exchange' => 'icon-exchange', 'icon-cloud-download' => 'icon-cloud-download', 'icon-cloud-upload' => 'icon-cloud-upload', 'icon-user-md' => 'icon-user-md', 'icon-stethoscope' => 'icon-stethoscope', 'icon-suitcase' => 'icon-suitcase', 'icon-bell-alt' => 'icon-bell-alt', 'icon-coffee' => 'icon-coffee', 'icon-food' => 'icon-food', 'icon-file-alt' => 'icon-file-alt', 'icon-building' => 'icon-building', 'icon-hospital' => 'icon-hospital', 'icon-ambulance' => 'icon-ambulance', 'icon-medkit' => 'icon-medkit', 'icon-fighter-jet' => 'icon-fighter-jet', 'icon-beer' => 'icon-beer', 'icon-h-sign' => 'icon-h-sign', 'icon-plus-sign-alt' => 'icon-plus-sign-alt', 'icon-double-angle-left' => 'icon-double-angle-left', 'icon-double-angle-right' => 'icon-double-angle-right', 'icon-double-angle-up' => 'icon-double-angle-up', 'icon-double-angle-down' => 'icon-double-angle-down', 'icon-angle-left' => 'icon-angle-left', 'icon-angle-right' => 'icon-angle-right', 'icon-angle-up' => 'icon-angle-up', 'icon-angle-down' => 'icon-angle-down', 'icon-desktop' => 'icon-desktop', 'icon-laptop' => 'icon-laptop', 'icon-tablet' => 'icon-tablet', 'icon-mobile-phone' => 'icon-mobile-phone', 'icon-circle-blank' => 'icon-circle-blank', 'icon-quote-left' => 'icon-quote-left', 'icon-quote-right' => 'icon-quote-right', 'icon-spinner' => 'icon-spinner', 'icon-circle' => 'icon-circle', 'icon-mail-reply' => 'icon-mail-reply', 'icon-folder-close-alt' => 'icon-folder-close-alt', 'icon-folder-open-alt' => 'icon-folder-open-alt', 'icon-expand-alt' => 'icon-expand-alt', 'icon-collapse-alt' => 'icon-collapse-alt', 'icon-smile' => 'icon-smile', 'icon-frown' => 'icon-frown', 'icon-meh' => 'icon-meh', 'icon-gamepad' => 'icon-gamepad', 'icon-keyboard' => 'icon-keyboard', 'icon-flag-alt' => 'icon-flag-alt', 'icon-flag-checkered' => 'icon-flag-checkered', 'icon-terminal' => 'icon-terminal', 'icon-code' => 'icon-code', 'icon-reply-all' => 'icon-reply-all', 'icon-mail-reply-all' => 'icon-mail-reply-all', 'icon-star-half-empty' => 'icon-star-half-empty', 'icon-location-arrow' => 'icon-location-arrow', 'icon-crop' => 'icon-crop', 'icon-code-fork' => 'icon-code-fork', 'icon-unlink' => 'icon-unlink', 'icon-question' => 'icon-question', 'icon-info' => 'icon-info', 'icon-exclamation' => 'icon-exclamation', 'icon-superscript' => 'icon-superscript', 'icon-subscript' => 'icon-subscript', 'icon-eraser' => 'icon-eraser', 'icon-puzzle-piece' => 'icon-puzzle-piece', 'icon-microphone' => 'icon-microphone', 'icon-microphone-off' => 'icon-microphone-off', 'icon-shield' => 'icon-shield', 'icon-calendar-empty' => 'icon-calendar-empty', 'icon-fire-extinguisher' => 'icon-fire-extinguisher', 'icon-rocket' => 'icon-rocket', 'icon-maxcdn' => 'icon-maxcdn', 'icon-chevron-sign-left' => 'icon-chevron-sign-left', 'icon-chevron-sign-right' => 'icon-chevron-sign-right', 'icon-chevron-sign-up' => 'icon-chevron-sign-up', 'icon-chevron-sign-down' => 'icon-chevron-sign-down', 'icon-html5' => 'icon-html5', 'icon-css3' => 'icon-css3', 'icon-anchor' => 'icon-anchor', 'icon-unlock-alt' => 'icon-unlock-alt', 'icon-bullseye' => 'icon-bullseye', 'icon-ellipsis-horizontal' => 'icon-ellipsis-horizontal', 'icon-ellipsis-vertical' => 'icon-ellipsis-vertical', 'icon-rss-sign' => 'icon-rss-sign', 'icon-play-sign' => 'icon-play-sign', 'icon-ticket' => 'icon-ticket', 'icon-minus-sign-alt' => 'icon-minus-sign-alt', 'icon-check-minus' => 'icon-check-minus', 'icon-level-up' => 'icon-level-up', 'icon-level-down' => 'icon-level-down', 'icon-check-sign' => 'icon-check-sign', 'icon-edit-sign' => 'icon-edit-sign', 'icon-external-link-sign' => 'icon-external-link-sign', 'icon-share-sign' => 'icon-share-sign', );

    $ptsc_orderby = array(
        'none' => 'none' ,
        'ID' => 'ID' ,
        'author' => 'author' ,
        'title' => 'title' ,
        'name' => 'name' ,
        'date' => 'date' ,
        'modified' => 'modified' ,
        'parent' => 'parent' ,
        'rand' => 'rand' ,
        'comment_count' => 'comment_count' ,
        );

    $ptsc_limit = array();
    for ($i=0; $i < 25 ; $i++) {
       $ptsc_limit[$i] = $i;
    }

    $ptsc_order = array(
        'ASC' => 'from lowest to highest values (1, 2, 3; a, b, c)' ,
        'DESC' => 'from highest to lowest values (3, 2, 1; c, b, a)' ,
        );

    $ptsc_places = array(
        'none' => 'None' , 'first' => 'First' , 'last' => 'Last' , 'center' => 'Center'
    );

    $ptsc_width = array(
        'one' => 'One' ,
        'two' => 'Two' ,
        'three' => 'Three' ,
        'four' => 'Four' ,
        'five' => 'Five' ,
        'six' => 'Six' ,
        'seven' => 'Seven' ,
        'eight' => 'Eight' ,
        'nine' => 'Nine' ,
        'ten' => 'Ten' ,
        'eleven' => 'Eleven' ,
        'twelve' => 'Twelve' ,
        'thirteen' => 'Thirteen' ,
        'fourteen' => 'Fourteen' ,
        'fifteen' => 'Fifteen' ,
        'sixteen' => 'Sixteen' ,
    );

    $ptsc_perc = array();
    for ($i=0; $i < 101 ; $i=$i+5) {
        $ptsc_perc[$i] = $i."%";
    }

    $portfolio_filters = get_terms('filters');
    $filters_options = array();
    foreach($portfolio_filters as $filter) {
        $filters_options[$filter->slug] = $filter->name;
    }

    $members_query = new WP_Query(
        array(
            'post_type' => array('team'),
            'showposts' => 99,
            )
        );
    $ptsc_members = array();
    while( $members_query->have_posts() ) : $members_query->the_post();
        $ptsc_members[$members_query->post->ID] = get_the_title();
    endwhile;


    $ptsc_socials = array(
        'twitter' => 'Twitter',
        'wordpress' => 'WordPress',
        'facebook' => 'Facebook',
        'linkedin' => 'LinkedIN',
        'steam' => 'Steam',
        'tumblr' => 'Tumblr',
        'github' => 'GitHub',
        'delicious' => 'Delicious',
        'instagram' => 'Instagram',
        'xing' => 'Xing',
        'amazon'=> 'Amazon',
        'dropbox'=> 'Dropbox',
        'paypal'=> 'PayPal',
        'lastfm' => 'LastFM',
        'gplus' => 'Google Plus',
        'yahoo' => 'Yahoo',
        'pinterest' => 'Pinterest',
        'dribbble' => 'Dribbble',
        'flickr' => 'Flickr',
        'reddit' => 'Reddit',
        'vimeo' => 'Vimeo',
        'spotify' => 'Spotify',
        'rss' => 'RSS',
        'youtube' => 'YouTube',
        'blogger' => 'Blogger',
        'appstore' => 'AppStore',
        'evernote' => 'Evernote',
        'digg' => 'Digg',
        'forrst' => 'Forrst',
        'fivehundredpx' => '500px',
        'stumbleupon' => 'StumbleUpon',
        'dribbble' => 'Dribbble'
    );

    /* set arrays for shortcodes form */
    $astrum_pt_shortcodes = array(
    'line' => array(
        'label' => 'Divider line',
        'has_content' => false,
        ),
    'headline' => array(
        'label' => 'Headline (styled header)',
        'has_content' => true,
        'params' => array(
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                )
            )
        ),
    'icon' => array(
        'label' => 'Icon',
        'has_content' => false,
        'params' => array(
            'icon' => array(
                'type' => 'select',
                'label' => 'Icon',
                'desc' => 'Select the color for dropcap',
                'options' => $ptsc_icons,
                'std' => '',
            ),
        )
    ),
    'highlight' => array(
        'label' => 'Highlight (text)',
        'has_content' => true,
        'params' => array(
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                ),
            'style' => array(
                'type' => 'select',
                'label' => 'Color',
                'desc' => 'Select the color for a highlight',
                'options' => array(
                    'gray' => 'Gray',
                    'light' => 'Light',
                    'color' => 'Curent Main Color'
                ),
                'std' => '',
            ),
        )
    ),
    'iconbox' => array(
        'label' => 'Iconbox',
        'has_content' => true,
        'wrapper' => 'iconwrapper',
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
                ),
            'link' => array(
                'type' => 'text',
                'label' => 'URL',
                'desc' => 'Set url for iconbox',
                'std' => '',
            ),
            'icon' => array(
                'type' => 'select',
                'label' => 'Icon',
                'desc' => 'Select the icon to display',
                'options' => $ptsc_icons,
                'std' => '',
            ),
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'If the item is already in a column, you need to select place in the row it takes.',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width for iconbox',
                'options' => $ptsc_width,
                'std' => 'four'
            ),
        )
    ),
    'recent_work' => array(
        'label' => 'Recent work',
        'has_content' => false,
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'limit' => array(
                'type' => 'select',
                'label' => 'Limit',
                'desc' => 'Select the limit for items',
                'options' => $ptsc_limit,
                'std' => '8'
            ),
            'orderby' => array(
                'type' => 'select',
                'label' => 'Orderby',
                'options' => $ptsc_orderby,
                'std' => '',
            ),
            'order' => array(
                'type' => 'select',
                'label' => 'Order',
                'options' => $ptsc_order,
                'std' => '',
            ),
            'filters' => array(
                'type' => 'select-multi',
                'label' => 'Filters',
                'desc' => 'Select filters from which you want to display items (leave empty for all)',
                'options' => $filters_options,
                'std' => '',
            ),
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'If the item is already in a column, you need to select place in the row it takes',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width for element',
                'options' => $ptsc_width,
                'std' => 'sixteen'
            ),
        )
    ),
    'testimonials' => array(
        'label' => 'Testimonials',
        'has_content' => false,
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'limit' => array(
                'type' => 'select',
                'label' => 'Limit',
                'desc' => 'Select the limit for items',
                'options' => $ptsc_limit,
                'std' => '8'
            ),
            'orderby' => array(
                'type' => 'select',
                'label' => 'Orderby',
                'options' => $ptsc_orderby,
                'std' => '',
            ),
            'order' => array(
                'type' => 'select',
                'label' => 'Order',
                'options' => $ptsc_order,
                'std' => '',
            ),
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'If the item is already in a column, you need to select place in the row it takes',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width for item',
                'options' => $ptsc_width,
                'std' => 'eight'
            ),
        )
    ),
    'happytestimonials' => array(
        'label' => 'Happy Testimonials',
        'has_content' => false,
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'limit' => array(
                'type' => 'select',
                'label' => 'Limit',
                'desc' => 'Select the limit for items',
                'options' => $ptsc_limit,
                'std' => '8'
            ),
            'orderby' => array(
                'type' => 'select',
                'label' => 'Orderby',
                'options' => $ptsc_orderby,
                'std' => '',
            ),
            'order' => array(
                'type' => 'select',
                'label' => 'Order',
                'options' => $ptsc_order,
                'std' => '',
            ),
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'If the item is already in a column, you need to select place in the row it takes',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width for item',
                'options' => $ptsc_width,
                'std' => 'sixteen'
            ),
        )
    ),
    'team' => array(
        'label' => 'Team',
        'has_content' => false,
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'limit' => array(
                'type' => 'select',
                'label' => 'Limit',
                'desc' => 'Select the limit for items',
                'options' => $ptsc_limit,
                'std' => '8'
            ),
            'members' => array(
                'type' => 'select-multi',
                'label' => 'Team members',
                'desc' => 'Select the members of a team to display, leave empty for all',
                'options' => $ptsc_members,
                'std' => '',
            ),
        )
    ),
    'column' => array(
        'label' => 'Column',
        'has_content' => false,
        'params' => array(
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'If the columns is already in a container, you need to select place in the row it takes',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width of column',
                'options' => $ptsc_width,
                'std' => 'four'
            ),
            'custom_class' => array(
                'type' => 'text',
                'label' => 'Custom class (optional)',
                'std' => '',
            )
        )
    ),
    'noticebox' => array(
        'label' => 'Notice box',
        'has_content' => true,
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'link' => array(
                'type' => 'text',
                'label' => 'URL',
                'desc' => 'Set url',
                'std' => '',
            ),
            'icon' => array(
                'type' => 'select',
                'label' => 'Icon',
                'desc' => 'Select the icon for notice box',
                'options' => $ptsc_icons,
                'std' => '',
            ),
             'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => '',
            ),
        )
    ),
    'tooltip' => array(
            'label' => 'Tooltip link',
            'has_content' => true,
            'params' => array(
                'title' => array(
                    'type' => 'text',
                    'label' => 'Title',
                    'desc' => 'Set title',
                    'std' => '',
                ),
                'url' => array(
                    'type' => 'text',
                    'label' => 'URL',
                    'std' => '',
                ),
                'side' => array(
                    'type' => 'select',
                    'label' => 'Select',
                    'desc' => 'Select the color for dropcap',
                    'options' => array(
                        'top' => 'Top',
                        'left' => 'Left',
                        'bottom' => 'Bottom',
                        'right' => 'Right',
                    ),
                    'std' => '',
                ),
                'content' => array(
                    'type' => 'textarea',
                    'label' => 'Content',
                    'std' => '',
                ),
            )
        ),
    'skillbar' => array(
        'label' => 'Skills bar',
        'has_content' => false,
        'wrapper' => 'skillbars',
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'icon' => array(
                'type' => 'select',
                'label' => 'Icon',
                'desc' => 'Select the icon for skill',
                'options' => $ptsc_icons,
                'std' => '',
            ),
            'value' => array(
                'type' => 'select',
                'label' => 'Value',
                'options' => $ptsc_perc,
                'std' => '',
            ),

        )
    ),
    'pricing_table' => array(
        'label' => 'Pricing Table',
        'has_content' => true,
        'params' => array(
            'type' => array(
                'type' => 'select',
                'label' => 'Type of table style',
                'desc' => 'Set title',
                'options' => array(
                    'standard' => 'Standard',
                    'featured' => 'Featured' ,
                    'premium' => 'Premium'
                ),
                'std' => '',
            ),
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'desc' => 'Place in the row',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'desc' => 'Select the width for table',
                'options' => $ptsc_width,
                'std' => 'four'
            ),
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'desc' => 'Set title',
                'std' => '',
            ),
            'currency' => array(
                'type' => 'text',
                'label' => 'Currency',
                'desc' => 'Set currenct ($)',
                'std' => '$'
            ),
            'price' => array(
                'type' => 'text',
                'label' => 'Price',
                'desc' => 'Set price (just number)',
                'std' => '',
            ),
            'per' => array(
                'type' => 'text',
                'label' => 'Per',
                'std' => 'per'
            ),
            'buttonstyle' => array(
                'type' => 'select',
                'label' => 'Button style',
                'desc' => 'Set style of "sign up" button',
                'options' => array(
                   'light' => 'Light',
                   'color' => 'Color'
                   ),
                'std' => ''
            ),
            'buttonlink' => array(
                'type' => 'text',
                'label' => 'URL',
                'desc' => 'Set URL for "sign up" button',
                'std' => ''
            ),
            'buttontext' => array(
                'type' => 'text',
                'label' => 'Button label',
                'desc' => 'Set label for "sign up" button (leave empty to hide button)',
                'std' => 'Sign up'
            ),
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => ''
            ),
        )
    ),
    'box' => array(
            'label' => 'Alert box',
            'has_content' => true,
            'params' => array(
                'type' => array(
                    'type' => 'select',
                    'label' => 'Type of box',
                    'options' => array(
                        'success' => 'Success',
                        'error' => 'Error',
                        'warning' => 'Warning',
                        'notice' => 'Notice',
                    ),
                    'std' => '',
                ),
                'content' => array(
                    'type' => 'textarea',
                    'label' => 'Content',
                    'std' => '',
                ),
            )
        ),
    'social_icon' => array(
        'label' => 'Social icon',
        'has_content' => false,
        'wrapper' => 'social_icons',
        'params' => array(
            'service' => array(
                'type' => 'select',
                'label' => 'Social site',
                'desc' => 'Set social icon',
                'options' => $ptsc_socials,
                'std' => '',
            ),
            'url' => array(
                'type' => 'text',
                'label' => 'URL',
                'std' => ''
            ),
        )
    ),
    'list' => array(
        'label' => 'List',
        'has_content' => true,
        'params' => array(
            'type' => array(
                'type' => 'select',
                'label' => 'List style',
                'desc' => 'Set title',
                'options' => array(
                    'check' => 'Check',
                    'arrow' => 'Arrow',
                    'checkbg' => 'Check with background',
                    'arrowbg' => 'Arrow with background',
                ),
                'std' => ''
            ),
             'content' => array(
                'type' => 'textarea',
                'label' => 'Content',
                'std' => ''
            ),
        )
    ),
    'googlemap' => array(
        'label' => 'Google maps',
        'has_content' => false,
        'params' => array(
            'address' => array(
                'type' => 'text',
                'label' => 'Address',
                'std' => '',
            ),
            'width' => array(
                'type' => 'text',
                'label' => 'Width',
                'std' => '',
            ),
            'height' => array(
                'type' => 'text',
                'label' => 'Height',
                'std' => '',
            )
        )
    ),
    'clients_carousel' => array(
        'label' => 'Clients carousel',
        'has_content' => true,
        'params' => array(
            'title' => array(
                'type' => 'text',
                'label' => 'Title',
                'std' => '',
            ),
            'width' => array(
                'type' => 'select',
                'label' => 'Width',
                'options' => $ptsc_width,
                'std' => 'sixteen'
            ),
            'place' => array(
                'type' => 'select',
                'label' => 'Placement',
                'options' => $ptsc_places,
                'std' => '',
            ),
            'content' => array(
                'type' => 'textarea',
                'label' => 'Content (put list of images here)',
                'std' => '',
            ),
        )
    ),
);
$pt_shortcodes = array_merge($pt_shortcodes, $astrum_pt_shortcodes);
return $pt_shortcodes;
}


function add_shortcodes() {
    add_filter( 'ptsc_shortcodes', 'astrum_shortcodes_list' );
}
add_action( 'init', 'add_shortcodes' );



add_filter( 'woocommerce_breadcrumb_defaults', 'astrum_change_breadcrumbs' );
function astrum_change_breadcrumbs( $defaults ) {
     return array(
            'delimiter'   => ' ',
            'wrap_before' => '<nav id="breadcrumbs"><ul><li>'.__('You are here:','purepress').'</li>',
            'wrap_after'  => '</nav>',
            'before'      => '<li>',
            'after'       => '</li>',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
        );
}