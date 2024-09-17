<?php
/**
 * Astrum functions and definitions
 *
 * @package Astrum
 * @since Astrum 1.0
 */

/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_false' );

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Required: include OptionTree.
 */
include_once( 'option-tree/ot-loader.php' );

/**
 * Theme Options
 */
include_once( 'theme-options.php' );
include_once( 'meta-boxes.php' );


if ( ! function_exists( 'is_woocommerce_activated' ) ) {
    function is_woocommerce_activated() {
        if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
    }
}

/**
 * Set default background properties
 *
 * @since Astrum 1.0
 */

// $bgargs = array(
//     'default-color' => 'ffffff',
//     'default-image' => get_template_directory_uri() . '/images/bg/noise.png',
//     );
// add_theme_support( 'custom-background', $bgargs );

/**
 * Execute shortcodes in widgets
 *
 * @since Astrum 1.0
 */

add_filter('widget_text', 'do_shortcode');


global $fontawesome;
$fontawesome = array ( 'icon-glass' => 'glass', 'icon-music' => 'music', 'icon-search' => 'search', 'icon-envelope-alt' => 'envelope-alt', 'icon-heart' => 'heart', 'icon-star' => 'star', 'icon-star-empty' => 'star-empty', 'icon-user' => 'user', 'icon-film' => 'film', 'icon-th-large' => 'th-large', 'icon-th' => 'th', 'icon-th-list' => 'th-list', 'icon-ok' => 'ok', 'icon-remove' => 'remove', 'icon-zoom-in' => 'zoom-in', 'icon-zoom-out' => 'zoom-out', 'icon-off' => 'off', 'icon-signal' => 'signal', 'icon-cog' => 'cog', 'icon-trash' => 'trash', 'icon-home' => 'home', 'icon-file-alt' => 'file-alt', 'icon-time' => 'time', 'icon-road' => 'road', 'icon-download-alt' => 'download-alt', 'icon-download' => 'download', 'icon-upload' => 'upload', 'icon-inbox' => 'inbox', 'icon-play-circle' => 'play-circle', 'icon-repeat' => 'repeat', 'icon-refresh' => 'refresh', 'icon-list-alt' => 'list-alt', 'icon-lock' => 'lock', 'icon-flag' => 'flag', 'icon-headphones' => 'headphones', 'icon-volume-off' => 'volume-off', 'icon-volume-down' => 'volume-down', 'icon-volume-up' => 'volume-up', 'icon-qrcode' => 'qrcode', 'icon-barcode' => 'barcode', 'icon-tag' => 'tag', 'icon-tags' => 'tags', 'icon-book' => 'book', 'icon-bookmark' => 'bookmark', 'icon-print' => 'print', 'icon-camera' => 'camera', 'icon-font' => 'font', 'icon-bold' => 'bold', 'icon-italic' => 'italic', 'icon-text-height' => 'text-height', 'icon-text-width' => 'text-width', 'icon-align-left' => 'align-left', 'icon-align-center' => 'align-center', 'icon-align-right' => 'align-right', 'icon-align-justify' => 'align-justify', 'icon-list' => 'list', 'icon-indent-left' => 'indent-left', 'icon-indent-right' => 'indent-right', 'icon-facetime-video' => 'facetime-video', 'icon-picture' => 'picture', 'icon-pencil' => 'pencil', 'icon-map-marker' => 'map-marker', 'icon-adjust' => 'adjust', 'icon-tint' => 'tint', 'icon-edit' => 'edit', 'icon-share' => 'share', 'icon-check' => 'check', 'icon-move' => 'move', 'icon-step-backward' => 'step-backward', 'icon-fast-backward' => 'fast-backward', 'icon-backward' => 'backward', 'icon-play' => 'play', 'icon-pause' => 'pause', 'icon-stop' => 'stop', 'icon-forward' => 'forward', 'icon-fast-forward' => 'fast-forward', 'icon-step-forward' => 'step-forward', 'icon-eject' => 'eject', 'icon-chevron-left' => 'chevron-left', 'icon-chevron-right' => 'chevron-right', 'icon-plus-sign' => 'plus-sign', 'icon-minus-sign' => 'minus-sign', 'icon-remove-sign' => 'remove-sign', 'icon-ok-sign' => 'ok-sign', 'icon-question-sign' => 'question-sign', 'icon-info-sign' => 'info-sign', 'icon-screenshot' => 'screenshot', 'icon-remove-circle' => 'remove-circle', 'icon-ok-circle' => 'ok-circle', 'icon-ban-circle' => 'ban-circle', 'icon-arrow-left' => 'arrow-left', 'icon-arrow-right' => 'arrow-right', 'icon-arrow-up' => 'arrow-up', 'icon-arrow-down' => 'arrow-down', 'icon-share-alt' => 'share-alt', 'icon-resize-full' => 'resize-full', 'icon-resize-small' => 'resize-small', 'icon-plus' => 'plus', 'icon-minus' => 'minus', 'icon-asterisk' => 'asterisk', 'icon-exclamation-sign' => 'exclamation-sign', 'icon-gift' => 'gift', 'icon-leaf' => 'leaf', 'icon-fire' => 'fire', 'icon-eye-open' => 'eye-open', 'icon-eye-close' => 'eye-close', 'icon-warning-sign' => 'warning-sign', 'icon-plane' => 'plane', 'icon-calendar' => 'calendar', 'icon-random' => 'random', 'icon-comment' => 'comment', 'icon-magnet' => 'magnet', 'icon-chevron-up' => 'chevron-up', 'icon-chevron-down' => 'chevron-down', 'icon-retweet' => 'retweet', 'icon-shopping-cart' => 'shopping-cart', 'icon-folder-close' => 'folder-close', 'icon-folder-open' => 'folder-open', 'icon-resize-vertical' => 'resize-vertical', 'icon-resize-horizontal' => 'resize-horizontal', 'icon-bar-chart' => 'bar-chart', 'icon-twitter-sign' => 'twitter-sign', 'icon-facebook-sign' => 'facebook-sign', 'icon-camera-retro' => 'camera-retro', 'icon-key' => 'key', 'icon-cogs' => 'cogs', 'icon-comments' => 'comments', 'icon-thumbs-up-alt' => 'thumbs-up-alt', 'icon-thumbs-down-alt' => 'thumbs-down-alt', 'icon-star-half' => 'star-half', 'icon-heart-empty' => 'heart-empty', 'icon-signout' => 'signout', 'icon-linkedin-sign' => 'linkedin-sign', 'icon-pushpin' => 'pushpin', 'icon-external-link' => 'external-link', 'icon-signin' => 'signin', 'icon-trophy' => 'trophy', 'icon-github-sign' => 'github-sign', 'icon-upload-alt' => 'upload-alt', 'icon-lemon' => 'lemon', 'icon-phone' => 'phone', 'icon-check-empty' => 'check-empty', 'icon-bookmark-empty' => 'bookmark-empty', 'icon-phone-sign' => 'phone-sign', 'icon-twitter' => 'twitter', 'icon-facebook' => 'facebook', 'icon-github' => 'github', 'icon-unlock' => 'unlock', 'icon-credit-card' => 'credit-card', 'icon-rss' => 'rss', 'icon-hdd' => 'hdd', 'icon-bullhorn' => 'bullhorn', 'icon-bell' => 'bell', 'icon-certificate' => 'certificate', 'icon-hand-right' => 'hand-right', 'icon-hand-left' => 'hand-left', 'icon-hand-up' => 'hand-up', 'icon-hand-down' => 'hand-down', 'icon-circle-arrow-left' => 'circle-arrow-left', 'icon-circle-arrow-right' => 'circle-arrow-right', 'icon-circle-arrow-up' => 'circle-arrow-up', 'icon-circle-arrow-down' => 'circle-arrow-down', 'icon-globe' => 'globe', 'icon-wrench' => 'wrench', 'icon-tasks' => 'tasks', 'icon-filter' => 'filter', 'icon-briefcase' => 'briefcase', 'icon-fullscreen' => 'fullscreen', 'icon-group' => 'group', 'icon-link' => 'link', 'icon-cloud' => 'cloud', 'icon-beaker' => 'beaker', 'icon-cut' => 'cut', 'icon-copy' => 'copy', 'icon-paper-clip' => 'paper-clip', 'icon-save' => 'save', 'icon-sign-blank' => 'sign-blank', 'icon-reorder' => 'reorder', 'icon-list-ul' => 'list-ul', 'icon-list-ol' => 'list-ol', 'icon-strikethrough' => 'strikethrough', 'icon-underline' => 'underline', 'icon-table' => 'table', 'icon-magic' => 'magic', 'icon-truck' => 'truck', 'icon-pinterest' => 'pinterest', 'icon-pinterest-sign' => 'pinterest-sign', 'icon-google-plus-sign' => 'google-plus-sign', 'icon-google-plus' => 'google-plus', 'icon-money' => 'money', 'icon-caret-down' => 'caret-down', 'icon-caret-up' => 'caret-up', 'icon-caret-left' => 'caret-left', 'icon-caret-right' => 'caret-right', 'icon-columns' => 'columns', 'icon-sort' => 'sort', 'icon-sort-down' => 'sort-down', 'icon-sort-up' => 'sort-up', 'icon-envelope' => 'envelope', 'icon-linkedin' => 'linkedin', 'icon-undo' => 'undo', 'icon-legal' => 'legal', 'icon-dashboard' => 'dashboard', 'icon-comment-alt' => 'comment-alt', 'icon-comments-alt' => 'comments-alt', 'icon-bolt' => 'bolt', 'icon-sitemap' => 'sitemap', 'icon-umbrella' => 'umbrella', 'icon-paste' => 'paste', 'icon-lightbulb' => 'lightbulb', 'icon-exchange' => 'exchange', 'icon-cloud-download' => 'cloud-download', 'icon-cloud-upload' => 'cloud-upload', 'icon-user-md' => 'user-md', 'icon-stethoscope' => 'stethoscope', 'icon-suitcase' => 'suitcase', 'icon-bell-alt' => 'bell-alt', 'icon-coffee' => 'coffee', 'icon-food' => 'food', 'icon-file-text-alt' => 'file-text-alt', 'icon-building' => 'building', 'icon-hospital' => 'hospital', 'icon-ambulance' => 'ambulance', 'icon-medkit' => 'medkit', 'icon-fighter-jet' => 'fighter-jet', 'icon-beer' => 'beer', 'icon-h-sign' => 'h-sign', 'icon-plus-sign-alt' => 'plus-sign-alt', 'icon-double-angle-left' => 'double-angle-left', 'icon-double-angle-right' => 'double-angle-right', 'icon-double-angle-up' => 'double-angle-up', 'icon-double-angle-down' => 'double-angle-down', 'icon-angle-left' => 'angle-left', 'icon-angle-right' => 'angle-right', 'icon-angle-up' => 'angle-up', 'icon-angle-down' => 'angle-down', 'icon-desktop' => 'desktop', 'icon-laptop' => 'laptop', 'icon-tablet' => 'tablet', 'icon-mobile-phone' => 'mobile-phone', 'icon-circle-blank' => 'circle-blank', 'icon-quote-left' => 'quote-left', 'icon-quote-right' => 'quote-right', 'icon-spinner' => 'spinner', 'icon-circle' => 'circle', 'icon-reply' => 'reply', 'icon-github-alt' => 'github-alt', 'icon-folder-close-alt' => 'folder-close-alt', 'icon-folder-open-alt' => 'folder-open-alt', 'icon-expand-alt' => 'expand-alt', 'icon-collapse-alt' => 'collapse-alt', 'icon-smile' => 'smile', 'icon-frown' => 'frown', 'icon-meh' => 'meh', 'icon-gamepad' => 'gamepad', 'icon-keyboard' => 'keyboard', 'icon-flag-alt' => 'flag-alt', 'icon-flag-checkered' => 'flag-checkered', 'icon-terminal' => 'terminal', 'icon-code' => 'code', 'icon-reply-all' => 'reply-all', 'icon-mail-reply-all' => 'mail-reply-all', 'icon-star-half-empty' => 'star-half-empty', 'icon-location-arrow' => 'location-arrow', 'icon-crop' => 'crop', 'icon-code-fork' => 'code-fork', 'icon-unlink' => 'unlink', 'icon-question' => 'question', 'icon-info' => 'info', 'icon-exclamation' => 'exclamation', 'icon-superscript' => 'superscript', 'icon-subscript' => 'subscript', 'icon-eraser' => 'eraser', 'icon-puzzle-piece' => 'puzzle-piece', 'icon-microphone' => 'microphone', 'icon-microphone-off' => 'microphone-off', 'icon-shield' => 'shield', 'icon-calendar-empty' => 'calendar-empty', 'icon-fire-extinguisher' => 'fire-extinguisher', 'icon-rocket' => 'rocket', 'icon-maxcdn' => 'maxcdn', 'icon-chevron-sign-left' => 'chevron-sign-left', 'icon-chevron-sign-right' => 'chevron-sign-right', 'icon-chevron-sign-up' => 'chevron-sign-up', 'icon-chevron-sign-down' => 'chevron-sign-down', 'icon-html5' => 'html5', 'icon-css3' => 'css3', 'icon-anchor' => 'anchor', 'icon-unlock-alt' => 'unlock-alt', 'icon-bullseye' => 'bullseye', 'icon-ellipsis-horizontal' => 'ellipsis-horizontal', 'icon-ellipsis-vertical' => 'ellipsis-vertical', 'icon-rss-sign' => 'rss-sign', 'icon-play-sign' => 'play-sign', 'icon-ticket' => 'ticket', 'icon-minus-sign-alt' => 'minus-sign-alt', 'icon-check-minus' => 'check-minus', 'icon-level-up' => 'level-up', 'icon-level-down' => 'level-down', 'icon-check-sign' => 'check-sign', 'icon-edit-sign' => 'edit-sign', 'icon-external-link-sign' => 'external-link-sign', 'icon-share-sign' => 'share-sign', 'icon-compass' => 'compass', 'icon-collapse' => 'collapse', 'icon-collapse-top' => 'collapse-top', 'icon-expand' => 'expand', 'icon-eur' => 'eur', 'icon-gbp' => 'gbp', 'icon-usd' => 'usd', 'icon-inr' => 'inr', 'icon-jpy' => 'jpy', 'icon-cny' => 'cny', 'icon-krw' => 'krw', 'icon-btc' => 'btc', 'icon-file' => 'file', 'icon-file-text' => 'file-text', 'icon-sort-by-alphabet' => 'sort-by-alphabet', 'icon-sort-by-alphabet-alt' => 'sort-by-alphabet-alt', 'icon-sort-by-attributes' => 'sort-by-attributes', 'icon-sort-by-attributes-alt' => 'sort-by-attributes-alt', 'icon-sort-by-order' => 'sort-by-order', 'icon-sort-by-order-alt' => 'sort-by-order-alt', 'icon-thumbs-up' => 'thumbs-up', 'icon-thumbs-down' => 'thumbs-down', 'icon-youtube-sign' => 'youtube-sign', 'icon-youtube' => 'youtube', 'icon-xing' => 'xing', 'icon-xing-sign' => 'xing-sign', 'icon-youtube-play' => 'youtube-play', 'icon-dropbox' => 'dropbox', 'icon-stackexchange' => 'stackexchange', 'icon-instagram' => 'instagram', 'icon-flickr' => 'flickr', 'icon-adn' => 'adn', 'icon-bitbucket' => 'bitbucket', 'icon-bitbucket-sign' => 'bitbucket-sign', 'icon-tumblr' => 'tumblr', 'icon-tumblr-sign' => 'tumblr-sign', 'icon-long-arrow-down' => 'long-arrow-down', 'icon-long-arrow-up' => 'long-arrow-up', 'icon-long-arrow-left' => 'long-arrow-left', 'icon-long-arrow-right' => 'long-arrow-right', 'icon-apple' => 'apple', 'icon-windows' => 'windows', 'icon-android' => 'android', 'icon-linux' => 'linux', 'icon-dribbble' => 'dribbble', 'icon-skype' => 'skype', 'icon-foursquare' => 'foursquare', 'icon-trello' => 'trello', 'icon-female' => 'female', 'icon-male' => 'male', 'icon-gittip' => 'gittip', 'icon-sun' => 'sun', 'icon-moon' => 'moon', 'icon-archive' => 'archive', 'icon-bug' => 'bug', 'icon-vk' => 'vk', 'icon-weibo' => 'weibo', 'icon-renren' => 'renren' );

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Astrum 1.0
 */

if ( ! isset( $content_width ) )
	$content_width = 1180; /* pixels */

if ( ! function_exists( 'astrum_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Astrum 1.0
 */
function astrum_setup() {

    $catalogmode = ot_get_option('pp_woo_catalog');
    if ($catalogmode == "yes") {
        remove_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart',30 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
    }

    /* Change number of items */
    $wooitems = ot_get_option('pp_wooitems','9');
    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.$wooitems.';' ), 20 );

    /**
     * Custom template tags for this theme.
     */
    require( get_template_directory() . '/inc/template-tags.php' );

    /**
     * Custom optiontree fonts.
     */
    require( get_template_directory() . '/inc/otfonts.php' );

	/**
	 * Custom blocks for Aqua Page Builder.
	 */
	require( get_template_directory() . '/inc/pagebuilder.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

    /**
     * Custom functions that act independently of the theme templates
     */
    require( get_template_directory() . '/inc/tgmpa.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

    /**
     * Shortcodes
     */
    require( get_template_directory() . '/inc/shortcodes.php' );

    /**
	 * Widgets
	 */
    require( get_template_directory() . '/inc/widgets.php' );

    /**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Astrum, use a find and replace
	 * to change 'purepress' to the name of your theme in all the template files
	 */
    load_theme_textdomain( 'purepress', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
    add_theme_support( 'post-thumbnails' );


    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    set_post_thumbnail_size(860, 0, true); //size of thumbs
    add_image_size('blog-medium', 320, 0, true);  //4col

    //set to 472
    add_image_size('portfolio-wide', 1180, 0, true);     //slider
    add_image_size('portfolio-half', 775, 0, true); //2col
    add_image_size('portfolio-3col', 380, 271, true);  //3col
    add_image_size('portfolio-4col', 280, 200, true);  //4col
    add_image_size('square-thumb', 130, 130, true);  //4col
	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'purepress' ),
       ) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'image', 'video', 'gallery' ) );

    /**
     * Enable support for WooCommerce
     */
    add_theme_support( 'woocommerce' );
    //define( 'WOOCOMMERCE_USE_CSS', false );
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );

}
endif; // astrum_setup
add_action( 'after_setup_theme', 'astrum_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Astrum 1.0
 */
function astrum_widgets_init() {
 register_sidebar(array(
    'id' => 'sidebar',
    'name' => 'Sidebar',
    'before_widget' => '<div id="%1$s" class="widget  %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="headline">',
    'after_title' => '</h3><span class="line"></span><div class="clearfix"></div>',
    ));

 register_sidebar(array(
    'id' => 'shop',
    'name' => 'Shop',
    'before_widget' => '<div id="%1$s" class="widget  %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="headline">',
    'after_title' => '</h3><span class="line"></span><div class="clearfix"></div>',
    ));
	
	
// 	(ND)

 register_sidebar(array(
    'id' => 'nd-footer-socials',
    'name' => '(ND) Footer Socials',
    'description' => 'Footer Socials for widgets in Footer.',
    'before_widget' => '',
    'after_widget' => '',
    ));
 register_sidebar(array(
    'id' => 'nd-footer-0',
    'name' => '(ND) Footer Info',
    'description' => 'Info for widgets in Footer.',
    'before_widget' => '',
    'after_widget' => '',
    ));
 register_sidebar(array(
    'id' => 'nd-footer-1',
    'name' => '(ND) Footer 1nd Column',
    'description' => '1nd column for widgets in Footer.',
    'before_widget' => '',
    'after_widget' => '',
    ));
 register_sidebar(array(
    'id' => 'nd-footer-2',
    'name' => '(ND) Footer 2rd Column',
    'description' => '4rd column for widgets in Footer.',
    'before_widget' => '',
    'after_widget' => '',
    ));
 register_sidebar(array(
    'id' => 'nd-footer-3',
    'name' => '(ND) Footer 3th Column',
    'description' => '3th column for widgets in Footer.',
    'before_widget' => '',
    'after_widget' => '',
    ));
	
// 	(ND) END
	
 register_sidebar(array(
    'id' => 'footer1st',
    'name' => 'Footer 1st Column',
    'description' => '1st column for widgets in Footer.',
    'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ));
 register_sidebar(array(
    'id' => 'footer2nd',
    'name' => 'Footer 2nd Column',
    'description' => '2nd column for widgets in Footer.',
    'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ));
 register_sidebar(array(
    'id' => 'footer3rd',
    'name' => 'Footer 3rd Column',
    'description' => '3rd column for widgets in Footer.',
    'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ));
 register_sidebar(array(
    'id' => 'footer4th',
    'name' => 'Footer 4th Column',
    'description' => '4th column for widgets in Footer.',
    'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ));

     //custom sidebars:
 if (ot_get_option('incr_sidebars')):
    $pp_sidebars = ot_get_option('incr_sidebars');
    foreach ($pp_sidebars as $pp_sidebar) {
        register_sidebar(array(
            'name' => $pp_sidebar["title"],
            'id' => $pp_sidebar["id"],
            'before_widget' => '<div id="%1$s" class="widget  %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="headline">',
            'after_title' => '</h3><span class="line"></span><div class="clearfix"></div>',
            ));
    }
endif;

}
add_action( 'widgets_init', 'astrum_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function astrum_scripts() {
    global $post;

    $color = get_theme_mod('astrum_main_color','#73b819');  //E.g. #FF0000
    $custom_css = "a, a:visited{ color: {$color}; }";
    wp_add_inline_style( 'style', $custom_css );

   $uniq_id_for_style_and_script = uniqid();

//     wp_enqueue_style( 'style', get_stylesheet_uri(), '',  $uniq_id_for_style_and_script ); ND-R
    //wp_enqueue_style( 'style', get_stylesheet_uri() );


    //wp_enqueue_style('woocommerce', get_template_directory_uri() .'/css/woocommerce.css');


//     wp_enqueue_script( 'easing', get_template_directory_uri() . '/js/jquery.easing.min.js', array( 'jquery' ), '', true ); ND-R
//     wp_enqueue_script( 'tpplugins', get_template_directory_uri() . '/js/jquery.themepunch.plugins.min.js', array( 'jquery' ), '', true ); ND-R
//     wp_enqueue_script( 'showbizpro', get_template_directory_uri() . '/js/jquery.themepunch.showbizpro.min.js', array( 'jquery' ), '', true ); ND-R
//     wp_enqueue_script( 'tooltips', get_template_directory_uri() . '/js/jquery.tooltips.min.js', array( 'jquery' ), '', true ); ND-R
    //wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '' );
//     wp_enqueue_script( 'superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array( 'jquery' ), '', true ); ND-R
    //wp_enqueue_script( 'twitter', get_template_directory_uri() . '/js/jquery.twitter.js', array( 'jquery' ), '', true );
//     wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array( 'jquery' ), '', true ); ND-R
    //wp_enqueue_script( 'jpanelmenu', get_template_directory_uri() . '/js/jquery.jpanelmenu.js', array( 'jquery' ), '', true );
//     wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ), '', true ); ND-R

//     wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), $uniq_id_for_style_and_script, true ); ND-R

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
  }

  if( get_post_meta( $post->ID, '_wp_page_template', true ) == 'page-portfolio-old.php' && is_singular() ){
	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'old-portfolio', get_template_directory_uri() . '/new_design_assets/js/old-portfolio.js?ver='.$uniq_id_for_style_and_script, array( 'jquery' ), '', true );
	wp_enqueue_style( 'old-portfolio-style', get_template_directory_uri().'/new_design_assets/css/old-portfolio.css?ver='.$uniq_id_for_style_and_script, '',  $uniq_id_for_style_and_script );
  }

  if ( is_singular() && wp_attachment_is_image() ) {
      wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
  }

  wp_localize_script( 'custom', 'astrum',
    array(
        'ajaxurl'=>admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce'),
        'flexslidespeed' => ot_get_option('pp_flex_slideshowspeed',7000),
        'flexanimspeed' => ot_get_option('pp_flex_animationspeed',600),
        'flexanimationtype' => ot_get_option('pp_flex_animationtype','fade'),
        'breakpoint' => ot_get_option('pp_menu_breakpoint','767'),
        'sticky' => ot_get_option('pp_sticky_menu','true')
        )
    );
}
add_action( 'wp_enqueue_scripts', 'astrum_scripts' );


function astrum_admin_script() {
    global $pagenow;
    if (is_admin() && $pagenow == 'post-new.php' OR $pagenow == 'post.php') {
        if ( ! did_action( 'wp_enqueue_media' ) )
            wp_enqueue_media();

        wp_register_style('astrum-css', get_template_directory_uri() . '/css/astrum.admin.css');
        wp_register_script('astrum-scripts', get_template_directory_uri() . '/inc/script.js');
        wp_enqueue_style('astrum-css');
        wp_enqueue_script('astrum-scripts');
    }
}
add_action('admin_enqueue_scripts', 'astrum_admin_script');





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
/* Testimonials Custom Post Type */
/* ----------------------------------------------------- */

if (!function_exists('register_cpt_testimonials')) {
    add_action( 'init', 'register_cpt_testimonials' );

    function register_cpt_testimonials() {

        $labels = array(
            'name' => __( 'Testimonials','purepress'),
            'singular_name' => __( 'testimonial','purepress'),
            'add_new' => __( 'Add New','purepress' ),
            'add_new_item' => __( 'Add New Testimonial','purepress' ),
            'edit_item' => __( 'Edit Testimonial','purepress'),
            'new_item' => __( 'New Testimonial','purepress'),
            'view_item' => __( 'View Testimonial','purepress'),
            'search_items' => __( 'Search Testimonials','purepress'),
            'not_found' => __( 'No testimonials found','purepress'),
            'not_found_in_trash' => __( 'No testimonials found in Trash','purepress'),
            'parent_item_colon' => __( 'Parent testimonial:','purepress'),
            'menu_name' => __( 'Testimonials','purepress'),
            );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'description' => __('Display your works by filters','purepress'),
            'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,

        //'menu_icon' => TEMPLATE_URL . 'work.png',
            'show_in_nav_menus' => false,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => 'testimonial'),
            'capability_type' => 'post'
            );

        register_post_type( 'testimonial', $args );
    }
}


/* ----------------------------------------------------- */
/* Team Custom Post Type */
/* ----------------------------------------------------- */

if (!function_exists('register_cpt_team')) {
    add_action( 'init', 'register_cpt_team' );

    function register_cpt_team() {

        $labels = array(
            'name' => __( 'Team','purepress'),
            'singular_name' => __( 'Team','purepress'),
            'add_new' => __( 'Add New','purepress' ),
            'add_new_item' => __( 'Add New Team Member','purepress' ),
            'edit_item' => __( 'Edit Team Member','purepress'),
            'new_item' => __( 'New Team Member','purepress'),
            'view_item' => __( 'View Team Member','purepress'),
            'search_items' => __( 'Search Team Members','purepress'),
            'not_found' => __( 'No Team Members found','purepress'),
            'not_found_in_trash' => __( 'No Team Members found in Trash','purepress'),
            'parent_item_colon' => __( 'Parent member:','purepress'),
            'menu_name' => __( 'Team','purepress'),
            );

        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
        //'menu_icon' => TEMPLATE_URL . 'work.png',
            'show_in_nav_menus' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => array( 'slug' => 'team'),
            'capability_type' => 'post'
            );
        register_post_type( 'team', $args );
    }
}
if (!function_exists('astrum_custom_taxonomy_post_class')) {
/*
 * Adds terms from a custom taxonomy to post_class
 */
add_filter( 'post_class', 'astrum_custom_taxonomy_post_class', 10, 3 );

    function astrum_custom_taxonomy_post_class( $classes, $class, $ID ) {
        $taxonomy = 'filters';
        $terms = get_the_terms( (int) $ID, $taxonomy );
        if( !empty( $terms ) ) {
            foreach( (array) $terms as $order => $term ) {
                if( !in_array( $term->slug, $classes ) ) {
                    $classes[] = $term->slug;
                }
            }
        }
        return $classes;
    }
}

/*
** WOOCOMMERCE
*/

remove_action( 'woocommerce_before_main_content',    'woocommerce_breadcrumb', 20, 0);
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
/**
 * Hook in on activation
 */
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'astrum_woocommerce_image_dimensions', 1 );

/**
 * Define image sizes
 */
function astrum_woocommerce_image_dimensions() {
    $catalog = array(
        'width'     => '280',   // px
        'height'    => '280',   // px
        'crop'      => 1        // true
        );

    $single = array(
        'width'     => '600',   // px
        'height'    => '600',   // px
        'crop'      => 1        // true
        );

    $thumbnail = array(
        'width'     => '130',   // px
        'height'    => '130',   // px
        'crop'      => 0        // false
        );

    // Image sizes
    update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
    update_option( 'shop_single_image_size', $single );         // Single product image
    update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
}

/**
 * Custom Add To Cart Messages
 *
 **/
add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message' );
function custom_add_to_cart_message() {
    global $woocommerce;

    // Output success messages
    if (get_option('woocommerce_cart_redirect_after_add')=='yes') :
        $return_to  = get_permalink(woocommerce_get_page_id('shop'));
    $message    = sprintf('<div class="notification closeable success"><p id="added_cart_info">%s <a href="%s" class="button color">%s</a></p></div>', __('Product successfully added to your cart.', 'purepress'), $return_to, __('Continue Shopping &rarr;', 'purepress') );
    else :
        $message  = sprintf('<div class="notification closeable success"><p id="added_cart_info">%s <a href="%s" class="button color">%s</a></p></div>', __('Product successfully added to your cart.', 'purepress'), get_permalink(woocommerce_get_page_id('cart')), __('View Cart &rarr;', 'purepress') );
    endif;

    return $message;
}



/*remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
*/
/**
 * WooCommerce Loop Product Thumbs
 **/

 if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {

    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    }
 }


/**
 * WooCommerce Product Thumbnail
 **/
 if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {

    function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce;

        if ( ! $placeholder_width )
            $placeholder_width = wc_get_image_size( 'shop_catalog_image_width' );
        if ( ! $placeholder_height )
            $placeholder_height = wc_get_image_size( 'shop_catalog_image_height' );

            $output = '';
            $hover = get_post_meta($post->ID, 'pp_featured_hover', TRUE);
            if($hover) {
                $hoverid = pn_get_attachment_id_from_url($hover);
                $hoverimage = wp_get_attachment_image_src($hoverid, $size);
                $output .= '<img src="'.$hoverimage[0].'" class="on-hover" width="'.$hoverimage[1].'" height="'.$hoverimage[2].'" />';
            }

            if ( has_post_thumbnail() ) {

                $output .= get_the_post_thumbnail( $post->ID, $size );

            } else {

                $output .= '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';

            }
            return $output;
    }
 }
// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

function woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;

    ob_start();
    ?>
    <a class="cart-contents" title="<?php _e('View your shopping cart', 'purepress'); ?>"> <?php echo $woocommerce->cart->get_cart_total(); ?></a>
    <?php

    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}

add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_content_fragment');

function woocommerce_header_add_to_cart_content_fragment( $fragments ) {
    global $woocommerce;

    ob_start();?>

        <ul>
        <?php
        if (sizeof($woocommerce->cart->cart_contents)>0) :
            foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
                $_product = $cart_item['data'];
                if ($_product->exists() && $cart_item['quantity']>0) :
                   echo '<li class="cart_list_product"><a href="' . esc_url( get_permalink( intval( $cart_item['product_id'] ) ) ) . '">';
                   echo $_product->get_image();
                   echo apply_filters( 'woocommerce_cart_widget_product_title', $_product->get_title(), $_product ) . '</a>';
                   if($_product instanceof woocommerce_product_variation && is_array($cart_item['variation'])) :
                       echo woocommerce_get_formatted_variation( $cart_item['variation'] );
                     endif;
                   echo '<span class="quantity">' . $cart_item['quantity'] . ' &times; ' . woocommerce_price( $_product->get_price() ) . '</span></li>';
                endif;
            endforeach;
        else:
            echo '<li class="empty">' . __( 'No products in the cart.', 'purepress' ) . '</li>';
        endif; ?>
        </ul>

    <?php $fragments['div.cart_products ul'] = ob_get_clean();
    return $fragments;
}


function astrum_add_to_wishlist($label) {
    $label = '<i class="icon-star"></i>';
    return $label;
}
add_filter( 'yith_wcwl_button_label', 'astrum_add_to_wishlist',1 );
add_filter( 'yith-wcwl-browse-wishlist-label', 'astrum_add_to_wishlist',1 );

function astrum_yith_wcwl_wishlist_title($title) {
    $wishlist_title = get_option( 'yith_wcwl_wishlist_title' );
    $title ='<h3 class="headline">' . $wishlist_title . '</h3><span class="line" style="margin-bottom:35px;"></span><div class="clearfix"></div>';
    return $title;
}
add_filter('yith_wcwl_wishlist_title','astrum_yith_wcwl_wishlist_title');

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
function add_wishlisht_button() {
    if (is_plugin_active('yith-woocommerce-wishlist/init.php')) {
        echo do_shortcode('[yith_wcwl_add_to_wishlist]');
    }
}
add_filter( 'astrum_wishlist', 'add_wishlisht_button' );


remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);


add_action('plugins_loaded', 'wpml_fix_ajax_install');
function wpml_fix_ajax_install(){
    global $sitepress;
    if(defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['action']) && isset($_REQUEST['lang']) ){
        // remove WPML legacy filter, as it is not doing its job for ajax calls
        remove_filter('locale', array($sitepress, 'locale'));
        add_filter('locale', 'wpml_ajax_fix_locale');
        function wpml_ajax_fix_locale($locale){
            global $sitepress;
            // simply return the locale corresponding to the "lang" parameter in the request
            return $sitepress->get_locale($_REQUEST['lang']);
        }
    }
}

if(function_exists( 'set_revslider_as_theme' )){
    add_action( 'init', 'astrum_rev_slider' );
    function astrum_rev_slider() {
     set_revslider_as_theme();
    }
}

/*menu current*/
add_filter( 'nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 4 );
function filter_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	
		//$atts['class'] = 'menu-link';

		if ( $item->current ) {
			$atts['class'] .= ' menu-link-active-current';
		}
	

	return $atts;
}

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_resource_hints', 2 );


add_action( 'admin_init', 'add_theme_caps');

function add_theme_caps() {

	//              author.                                   WP_Role
	$role = get_role( 'author' );
	
	 //                          
	$role->add_cap( 'edit_pages' );	
	$role->add_cap( 'edit_others_pages');
	$role->add_cap( 'edit_published_pages' );
	$role->add_cap( 'publish_pages');
	$role->add_cap( 'edit_posts' );	
	$role->add_cap( 'edit_others_posts');
	$role->add_cap( 'edit_published_posts' );
	$role->add_cap( 'publish_posts');
	$role->add_cap( 'manage_categories');

	
}


add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );


add_filter( 'aioseo_disable_title_rewrites', 'change_wordpress_seo_title' );
function change_wordpress_seo_title(){
    if (str_contains($_SERVER['REQUEST_URI'], '/tag/')) {
        return true;
		
    }
    return false;
	
}


if (str_contains($_SERVER['REQUEST_URI'], '/tag/') ) {
	remove_action('wp_head', '_wp_render_title_tag',1);
} else {
	add_action('wp_head', '_wp_render_title_tag',1);
} 




function fb_disable_feed() {
wp_redirect(get_option('siteurl'));
}
add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );


function register_lwptoc_sidebar(){
	register_sidebar( array(
		'name' => "Sidebar for TOC",
		'id' => 'lwptoc-sidebar',
		'description' => 'For right sidebar',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	) );
}
add_action( 'widgets_init', 'register_lwptoc_sidebar' );


function getUrlPage( $form_tag )
{
if ( $form_tag['name'] == 'url-adress' ) {
$form_tag['values'][] = htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'');
}
return $form_tag;
}
if ( !is_admin() ) {
add_filter( 'wpcf7_form_tag', 'getUrlPage' );
}


function my_custom_widget_title_tag( $params ) {
	if ($params[0]['widget_name'] == 'Text') {
		$params[0]['before_title'] = '<p class="footer-widget-text">' ;
		$params[0]['after_title']  = '</p>' ;
	}
	return $params;
}
add_filter( 'dynamic_sidebar_params' , 'my_custom_widget_title_tag' );

add_action('add_meta_boxes', 'add_imagebanner', 1);
function add_imagebanner() {
	add_meta_box( 'imagebanner', 'banners for ToC', 'imagebanner_fields_func', array('portfolio', 'page', 'post'), 'normal', 'high'  );
}


function imagebanner_fields_func( $post ){
	?>
	<p>Image shortcode :<br>
		<textarea type="text" name="ib_fields[imagebanner]" rows="10"  style="width:100%;height:100%;"><?php echo get_post_meta($post->ID, 'imagebanner', 1); ?></textarea>
	</p>
	<input type="hidden" name="ib_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}

add_action( 'save_post', 'imagebanner_fields_update', 0 );
function imagebanner_fields_update( $post_id ){
	if (
		   empty( $_POST['ib_fields'] )
		|| ! wp_verify_nonce( $_POST['ib_fields_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	)
		return false;
	$_POST['ib_fields'] = array_map( 'sanitize_text_field', $_POST['ib_fields'] );
	
	foreach( $_POST['ib_fields'] as $key => $value ){
		if( empty($value) ){
			delete_post_meta( $post_id, $key );
			continue;
		}
		update_post_meta( $post_id, $key, $value );
	}
	return $post_id;
}

/**
 * 
 *
 * @version 1.0
 */
if( 'Disable srcset/sizes' ){
	add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );


	add_filter( 'wp_calculate_image_sizes', '__return_false',  99 );


	// WP < 5.5
	remove_filter( 'the_content', 'wp_make_content_images_responsive' );
	// WP > 5.5
	add_filter( 'wp_img_tag_add_srcset_and_sizes_attr', '__return_false' );
}


add_action( 'post_tag_edit_form_fields', 'imagebanners_for_tag', 10, 2 );
 
function imagebanners_for_tag( $term, $taxonomy ) {
 
	$imagebanners_for_tag = get_term_meta( $term->term_id, 'imagebanners_for_tag', true );

 
	echo '<tr class="form-field">
	<th>
		<label for="seo_title">Banners for tag</label>
	</th>
	<td>
		<textarea name="imagebanners_for_tag" id="imagebanners_for_tag" type="text" />'.esc_attr( $imagebanners_for_tag ) .'</textarea>
	</td>
	</tr>';
 
}



add_action(  'edited_post_tag', 'save_imagebanners_for_tag' );
 
function save_imagebanners_for_tag( $term_id ) {
 	//echo 1;
	if( isset( $_POST[ 'imagebanners_for_tag' ] ) ) {
		update_term_meta( $term_id, 'imagebanners_for_tag', sanitize_text_field( $_POST[ 'imagebanners_for_tag' ] ) );
	} else {
		delete_term_meta( $term_id, 'imagebanners_for_tag' );
	}
}


	function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );    
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		//add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
		//add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
	}
	add_action( 'init', 'disable_emojis' );

	add_filter('option_use_smilies', '__return_false');



/*add_filter( 'aioseo_schema_disable', 'aioseo_disable_schema_products' );
function aioseo_disable_schema_products( $disabled ) {

      return true;
}*/

 add_action( 'init', 'blockusers_init' ); function blockusers_init() { 
 
 if (
	 is_admin() &&! ( current_user_can( 'administrator') || current_user_can( 'ceo') || current_user_can( 'editor') || current_user_can( 'author') || current_user_can( 'contributor') ) &&! ( defined( 'DOING_AJAX') && DOING_AJAX ) 
 ) { wp_redirect( home_url() ); exit; } } 
 
 
 add_filter('wpcf7_autop_or_not', '__return_false');


include_once( 'inc/new_walker_for_menu.php' ); 
include_once( 'inc/walker_for_new_design.php' ); 



function add_defer_attribute($tag, $handle) {
    $scripts_to_defer = array(
        'inbound-analytics', // inboundAnalytics.min.js
		'tp-tools', // jquery.themepunch.tools.min.js
		'magnific-popup-js', // magnific-popup-js
    );

    if (in_array($handle, $scripts_to_defer)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}

add_filter('script_loader_tag', 'add_defer_attribute', 9999, 2);

function remove_scripts_and_styles_on_homepage() {

	
	//wp_deregister_script('twitter');
	wp_dequeue_style( 'global-styles' );

    if (is_front_page()) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style('contact-form-7');
    }
}

add_action('wp_enqueue_scripts', 'remove_scripts_and_styles_on_homepage', 9999);


add_action( 'after_setup_theme', 'register_mobile_menu' );

function register_mobile_menu() {
	register_nav_menu( 'mobile', 'Mobile Menu' );
}


function add_dns_prefetch() {
    echo '<link rel="dns-prefetch" href="https://fonts.googleapis.com">';
}

add_action('wp_head', 'add_dns_prefetch', 0);


function sidebar_new_title_tag_for_widget( $params ) {
	if ( $params[0]['name'] == 'Sidebar' ) {
		$params[0]['before_title'] = '<span class="h3-headline">' ;
		$params[0]['after_title']  = '</span><span class="line"></span><div class="clearfix"></div>' ;
	} 
	return $params;
}
add_filter( 'dynamic_sidebar_params' , 'sidebar_new_title_tag_for_widget' );



function sidebar_for_history(){
	register_sidebar( array(
		'name' => 'Sidebar for history',
		'id' => 'history-sidebar',
		'description' => 'Sidebar for history.',
		'before_widget' => '<div class="history-widget-block">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'sidebar_for_history' );

remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );




/* Case */
add_action('add_meta_boxes', 'my_link_case');

function my_link_case() {
	
	add_meta_box( 'link_case', 'Portfolio Link', 'link_case_box_func', 'page', 'side',  'high'  );
}

// код блока
function link_case_box_func( $post ){	
	$link_case = get_post_meta($post->ID, 'link_case', 1);
	


	?>

		<p>  
			<div style="width: 220px;">Portfolio Link:</div>
			<input type="text" name="link_case[link_case]" value="<?php if ( $link_case != '' ) { echo $link_case; } else {echo '';} ?>" > 
		</p>

	
	
	<input type="hidden" name="link_case_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}


// включаем обновление полей при сохранении
add_action( 'save_post_page', 'my_link_case_update', 0 );

## Сохраняем данные, при сохранении поста
function my_link_case_update( $post_id ){
	//var_dump($_POST['link_case']);
	//die();
	
	if (
		   empty( $_POST['link_case'] )
		|| ! wp_verify_nonce( $_POST['link_case_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	) {	
		
		return false;	
	}


	$_POST['link_case'] = array_map( 'sanitize_text_field', $_POST['link_case'] ); // чистим все данные от пробелов по краям
	foreach( $_POST['link_case'] as $key => $value ){
		if( empty($value) ){
			delete_post_meta( $post_id, $key );// удаляем поле если значение пустое	
			continue;
		} 	

		update_post_meta( $post_id, $key, $value );
		
	}	
	return $post_id;
}


// DISABLE GUTENBERG
function disable_block_editor_for_page_ids( $use_block_editor, $post ) {

    $excluded_ids = array( 9542, 2360, 26660, 23358, 23731, 23826, 23966, 23968, 24186, 24205, 24605, 24615, 24663, 24693, 24744, 24758, 24813, 24956, 24996, 25037,12520, 9845, 16388, 9,5249, 14499, 7959,15030, 9425, 15464, 11207,15826,13703,173,10844,9971,12953, 327,13561,5603, 9569, 19312, 14305, 19074, 25633, 18857, 18528, 8376, 26350);
    if ( in_array( $post->ID, $excluded_ids ) ) {
        return false;
    }
    return $use_block_editor;
}
add_filter( 'use_block_editor_for_post', 'disable_block_editor_for_page_ids', 10, 2 );


// Landing pages autop fix
if (!function_exists('inbound_shortcode_empty_paragraph_fix')) {
    function inbound_shortcode_empty_paragraph_fix($content) {
        $array = array('<p>[' => '[', ']</p>' => ']', ']<br />' => ']');

        $content = strtr($content, $array);

        return $content;
    }
}

add_filter('the_content', 'inbound_shortcode_empty_paragraph_fix');

add_filter('widget_text', 'do_shortcode', 11);


add_action( 'add_meta_boxes', 'add_custom_post_image_meta_box' );

function add_custom_post_image_meta_box() {
		add_meta_box(
        	'custom-post-image',
        	__( 'Custom Miniature' ),
        	'custom_post_image_meta_box_callback',
        	'post',
        	'side',
        	'high'
    	);
}

function custom_post_image_meta_box_callback( $post ) {
    $custom_post_image_url = get_post_meta( $post->ID, 'custom_post_image_url', true );
    ?>
	<script>
		jQuery(document).ready(function ($) {
		  $("#custom_post_image_upload_button").click(function (evt) {
		    evt.preventDefault();
		    const custom_uploader = wp.media({ title: "Upload Image", button: { text: "Choose Image" }, multiple: false });
		    custom_uploader.on("select", function () {
			  const url = custom_uploader.state().get("selection").first().toJSON().url;
		      $("#custom_post_image_url").val(url);
			  $("#custom_post_image_url-img").attr("src", url);
		    });
		    custom_uploader.open();
		  });
		});
	</script>
	<style>
	#custom_post_image_url-img[src=""] {
    	display: none;
	}
		#custom-post-image .handle-order-higher{
			display: none!important;
		}
		#custom-post-image .handle-order-lower{
			display: none!important;
		}
	</style>
	<div style=" display: flex; flex-direction: column; gap: 16px; ">
    	<div style="display: flex; gap: 8px;">
			<input type="button" id="custom_post_image_upload_button" class="button" value="<?php _e( 'Upload Image' ); ?>">
			<input type="text" name="custom_post_image_url" id="custom_post_image_url" class="widefat" value="<?php echo esc_attr( $custom_post_image_url ); ?>">
		</div>
		<img id="custom_post_image_url-img" alt="Invalid Miniature Image" src="<?php echo esc_attr( $custom_post_image_url ); ?>"/>
    </div>
    <?php
}


add_action( 'save_post', 'save_custom_post_image_meta_data' );

function save_custom_post_image_meta_data( $post_id ) {
    if ( isset( $_POST['custom_post_image_url'] ) ) {
        update_post_meta( $post_id, 'custom_post_image_url', esc_url( $_POST['custom_post_image_url'] ) );
    }
}


add_action(
    'after_setup_theme',
    function() {
        add_theme_support( 'html5', [ 'script', 'style' ] );
    }
);



add_filter( 'aioseo_description', 'aioseo_filter_description' );

function aioseo_filter_description( $description ) {
    if ( str_contains($_SERVER['REQUEST_URI'], 'business-articles') ) {
       return "Explore strategic insights and expert advice driving your company's innovation and helping navigate technological and business challenges.";
    } elseif ( str_contains($_SERVER['REQUEST_URI'], 'category/company-news') ) {
        return "Stay in the loop with updates on the Jazzteam's company life, industry recognitions, and the latest software development projects!";
    } elseif ( str_contains($_SERVER['REQUEST_URI'], 'category/technical-articles') ){
        return "Gain valuable insights on software development, IT solutions, and the latest industry trends from Jazzteam's seasoned experts. Our practical tips in digital engineering can help you stay ahead in the fast-paced world of innovative technologies!";
    } elseif ( str_contains($_SERVER['REQUEST_URI'], '/tag/') ){
	return '';
    }

    return $description;
 }


add_filter( 'aioseo_robots_meta', 'aioseo_filter_robots_meta' );
function aioseo_filter_robots_meta( $attributes ) {
   if ( is_tag() ) {
      $attributes['noindex']  = 'noindex';
   }
   return $attributes;
}

/* new breadcrumbs */
include_once( 'inc/new-breadcrumbs.php' );

// Remove X-Robots-Tag on sitemap.xml page
add_action('init', 'start_output_buffering_for_sitemap');
function start_output_buffering_for_sitemap() {
    if (strpos($_SERVER['REQUEST_URI'], '/sitemap.xml') !== false) {
        ob_start('remove_x_robots_tag_from_headers');
    }
}

function remove_x_robots_tag_from_headers($buffer) {
    header_remove('X-Robots-Tag');
    return $buffer;
}


// Mutate canonical url
function get_canonical_url(){
	global $wp;
	$request_uri = $wp->request;
    $full_url = trailingslashit(home_url($request_uri));
	return $full_url;
}

function handle_canonical_url($url){
	$canonical_url = get_canonical_url();
	
	if($canonical_url) {
		return $canonical_url;
	} 

	return '';
}

add_filter('aioseo_canonical_url', 'handle_canonical_url', 10, 3);



function findFastTraversablePages($from, $max, $trashold, $target) {
    $pages = [];
    
    for ($i = $from; $i < $max; $i++) {
        if ( ($i < ($target - $trashold) || $i > ($target + $trashold)) && $i % 10 === 0) {
            $pages[] = $i;
        }
    }
      
    return array_slice($pages, 0, 3);
  }
  
  function pagination($ext_paged = -1, $ext_max_page = -1) {
    if (is_singular()) return;
  
    if($ext_paged != -1 && $ext_max_page != -1) {
        $paged = $ext_paged;
        $max_page = $ext_max_page;
    } else {
        global $wp_query;
        $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
        $max_page = $wp_query->max_num_pages;
    }
  
    if ($max_page <= 1){
      echo '<div class="pag">';
      echo '<div class="pag__unit pag__unit--tablet-xl-hidden"><span class="pag__info">Page 1 of 1</span></div>';
      echo '<div class="pag__unit"><span class="pag__current">1</span></div>';
      echo '</div>';
      return;
    }
  
    // Configuration similar to wp_pagenavi
    $pages_to_show = 5;
    $start_page = max(1, $paged - floor($pages_to_show / 2));
    $end_page = min($max_page, $start_page + $pages_to_show - 1);
  
  
    if ($end_page - $start_page < $pages_to_show - 1) {
        $start_page = max(1, $end_page - $pages_to_show + 1);
    }
  
  
    echo '<div class="pag">';
    echo '<div class="pag__unit pag__unit--tablet-xl-hidden"><span class="pag__info">Page '.$paged.' of '.$max_page.'</span></div>';
  
    // First Page
    if ($paged > 1) {
      if($start_page >= 2){
        echo '<div class="pag__unit pag__unit--mobile-hidden"><a class="pag__link" href="' . get_pagenum_link(1) . '">First</a></div>';
      }
  
      echo '<div class="pag__unit"><a class="pag__link" href="' . get_pagenum_link($paged - 1) . '">&laquo;</a></div>';
  
      if($start_page >= 2){
        echo '<div class="pag__unit pag__unit--mobile"><a class="pag__link" href="' . get_pagenum_link(1) . '">1</a></div>';
      }
  
    }
  
    // Fast smaller traversable pages
    $page_blocks = findFastTraversablePages(1, $paged, 4, $paged);
    if(count($page_blocks) > 0){
      echo '<div class="pag__unit pag__unit--tablet-xxl-hidden"><span class="pag__ets">...</span></div>';
  
      echo array_reduce($page_blocks, function ($acc, $curr){
        return $acc . '<div class="pag__unit pag__unit--tablet-xxl-hidden"><a class="pag__link" href="' . get_pagenum_link($curr) . '">' . $curr . '</a></div>';
      });
    }
  
    // Dots before current pages
    if ($start_page > 1) {
      echo '<div class="pag__unit"><span class="pag__ets">...</span></div>';
    }
  
    // Main Pages
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $paged) {
            echo '<div class="pag__unit"><span class="pag__current">' . $i . '</span></div>';
        } else {
          $mob_hidden_class = '';
          
          if($end_page < $max_page && $i === $end_page){
            $mob_hidden_class = 'pag__unit--mobile-hidden';
          }
  
          if($start_page > 1 && $i === $start_page){
            $mob_hidden_class = 'pag__unit--mobile-hidden';
          }
  
            echo '<div class="pag__unit '. $mob_hidden_class .'" data-island><a class="pag__link" href="' . get_pagenum_link($i) . '">'. $i .'</a></div>';
        }
    }
  
    // Dots after current pages
    if ($end_page < $max_page) {
      echo '<div class="pag__unit"><span class="pag__ets">...</span></div>';
    }
  
    // Fast larger traversable pages
    $page_blocks = findFastTraversablePages($paged, $max_page, 4, $paged);
   
    if(count($page_blocks) > 0){
      echo array_reduce($page_blocks, function ($acc, $curr){
        return $acc . '<div class="pag__unit pag__unit--tablet-xxl-hidden"><a class="pag__link" href="' . get_pagenum_link($curr) . '">' . $curr . '</a></div>';
      });
  
      echo '<div class="pag__unit pag__unit--tablet-xxl-hidden"><span class="pag__ets">...</span></div>';
    }
    
    // Last Page
    if ($paged < $max_page) {
      if($end_page < $max_page){
        echo '<div class="pag__unit pag__unit--mobile"><a class="pag__link" href="' . get_pagenum_link($max_page) . '">' . $max_page .'</a></div>';
      }
  
      echo '<div class="pag__unit"><a class="pag__link" href="' . get_pagenum_link($paged + 1) . '">&raquo;</a></div>';
  
      if($end_page < $max_page) {
        echo '<div class="pag__unit pag__unit--mobile-hidden"><a class="pag__link" href="' . get_pagenum_link($max_page) . '">Last</a></div>';
      }
    }
  
    echo '</div>';
  }




include_once( 'inc/test-pages.php' );
?>