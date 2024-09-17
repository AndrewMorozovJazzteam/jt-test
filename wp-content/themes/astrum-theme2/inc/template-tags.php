<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package astrum
 * @since astrum 1.0
 */

if ( ! function_exists( 'astrum_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since astrum 1.0
 */
function astrum_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'astrum' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'purepress' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'purepress' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'purepress' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'purepress' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // astrum_content_nav

if ( ! function_exists( 'astrum_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since astrum 1.0
 */
function astrum_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'astrum' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'purepress' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="avatar"><?php echo get_avatar( $comment, 70 ); ?></div>
				<div class="comment-des">
				<div class="comment-by">
					<?php printf( '<strong>%s</strong>', get_comment_author_link() ); ?>
					<span class="reply"><span style="color:#ccc"> </span><?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="icon-reply"></i>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
					<span class="date">	<?php printf( __( '%1$s at %2$s', 'astrum' ), get_comment_date(), get_comment_time() ); ?></span>
				</div>
				<?php comment_text(); ?>
				</div>
				<div class="clearfix"></div>
		</div><!-- #comment-## -->
	<?php
			break;
	endswitch;
}
endif; // ends check for astrum_comment()


add_filter('comment_form_defaults', 'pp_comment_defaults');
function pp_comment_defaults($defaults) {
    $req = get_option('require_name_email');
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $commenter = wp_get_current_commenter();
    $user = wp_get_current_user();
    $user_identity = $user->display_name;
    $defaults = array(
        'fields' => array(
            'author' => '<div><label for="author">' . __('Name','purepress') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author"  type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></div>',
            'url' => '<div><label for="url">' . __('Email','purepress') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></div>',
            'email' => '<div><label for="email">' . __('Url','purepress') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="url" name="url" type="text"   value="' . esc_attr($commenter['comment_author_url']) . '" size="30"' . $aria_req . ' /></div>'
            ),
        'comment_field' => '<div><label for="comment">' . __('Add Comment', 'purepress') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>',
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
        'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
        'comment_notes_before' => '<fieldset>',
        'comment_notes_after' => '</fieldset>',
        'id_form' => 'commentform',
        'id_submit' => 'submit',
        'title_reply' => __('Leave a Comment','purepress'),
        'title_reply_to' => __('Leave a Reply %s','purepress'),
        'cancel_reply_link' => __('Cancel reply','purepress'),
        'label_submit' => __('Add Comment','purepress'),
        );

return $defaults;
}




if ( ! function_exists( 'astrum_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since astrum 1.0
 */

function astrum_posted_on() {
  echo "<ul>";
  $time_string = '<time itemprop="datePublished" class="entry-date date updated published" datetime="%1$s">%2$s</time>';

  $time_string = sprintf( $time_string,
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() )
  );
if(is_single()) {
  $metas = ot_get_option('pp_meta_single',array());
  if (in_array("author", $metas) || in_array("date", $metas)) {
      echo '<li itemscope itemtype="http://data-vocabulary.org/Person">';
    if (in_array("author", $metas)) {
echo __('By','purepress').' <a class="author-link" itemprop="url" rel="author" href="'.get_author_posts_url(get_the_author_meta('ID' )).'"><span class="vcard author"><span class="fn" itemprop="name">'; the_author_meta('display_name'); echo'</span></span></a>';
    }
    if (in_array("author", $metas) || in_array("date", $metas)) {
      echo " - ";
    }
    if (in_array("date", $metas)) {
      echo $time_string;
    }
      echo '</li>';
  }
  if (in_array("cat", $metas)) {
    if(has_category()) { echo '<li>'; the_category(', '); echo '</li>'; }
  }
  if (in_array("tags", $metas)) {
    if(has_tag()) { echo '<li>'; the_tags('',', '); echo '</li>'; }
  }
  if (in_array("com", $metas)) {
    echo '<li>'; comments_popup_link( __('With 0 comments','purepress'), __('With 1 comment','purepress'), __('With % comments','purepress'), 'comments-link', __('Comments are off','purepress')); echo '</li>';
  }
} else {
  $metas = ot_get_option('pp_meta_blog',array());
   if (in_array("author", $metas) || in_array("date", $metas)) {
      echo '<li itemscope itemtype="http://data-vocabulary.org/Person">';
    if (in_array("author", $metas)) {
        echo __('By','purepress').' <a class="author-link" itemprop="url" rel="author" href="'.get_author_posts_url(get_the_author_meta('ID' )).'"><span class="vcard author"><span class="fn" itemprop="name">'; the_author_meta('display_name'); echo'</span></span></a>';
    }
    if (in_array("author", $metas) || in_array("date", $metas)) {
      echo " - ";
    }
    if (in_array("date", $metas)) {
      echo $time_string;
    }
      echo '</li>';
  }
  if (in_array("cat", $metas)) {
    if(has_category()) { echo '<li>'; the_category(', '); echo '</li>'; }
  }
  if (in_array("tags", $metas)) {
    if(has_tag()) { echo '<li>'; the_tags('',', '); echo '</li>'; }
  }
  if (in_array("com", $metas)) {
    echo '<li>'; comments_popup_link( __('With 0 comments','purepress'), __('With 1 comment','purepress'), __('With % comments','purepress'), 'comments-link', __('Comments are off','purepress')); echo '</li>';
  }
}
echo "</ul>";
}
endif;


/**
 * Returns true if a blog has more than 1 category
 *
 * @since astrum 1.0
 */
function astrum_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so astrum_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so astrum_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in astrum_categorized_blog
 *
 * @since astrum 1.0
 */
function astrum_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'astrum_category_transient_flusher' );
add_action( 'save_post', 'astrum_category_transient_flusher' );

/**
 * Limits number of words from string
 *
 * @since astrum 1.0
 */
function string_limit_words($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
        //add a ... at last article when more than limit word count
        return implode(' ', $words) ;
    } else {
        //otherwise
        return implode(' ', $words);
    }
}

function dimox_breadcrumbs() {
  $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = ''; // delimiter between crumbs
  $home = __('Home','purepress'); // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<li class="current_element">'; // tag before the current crumb
  $after = '</li>'; // tag after the current crumb

  global $post;
  $homeLink = home_url();
  $frontpageuri = astrum_get_posts_page('url');
  $frontpagetitle = ot_get_option('pp_blog_page');
  $output = '';
  if (is_home() || is_front_page()) {
    if ($showOnHome == 1)
      echo '<nav id="breadcrumbs"><ul><li>';
      _e('You are here:','purepress');
      echo '</li><li><a href="' . $homeLink . '"></i>' . $home . '</a></li>';
      echo '<li>' . $frontpagetitle . '</li>';
      echo '</ul></nav>';

  } else {

    $output .= '<nav id="breadcrumbs"><ul><li>'.__('You are here:','purepress').'</li><li><a href="' . $homeLink . '">' . $home . '</a>' . $delimiter . '</li> ';

    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) $output .= '<li>'.get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ').'<li>';
      $output .= $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;

    } elseif ( is_search() ) {
      $output .= $before . 'Search results for "' . get_search_query() . '"' . $after;

    } elseif ( is_day() ) {
      $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
      $output .= '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . '</li> ';
      $output .= $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      $output .= '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' </li>';
      $output .= $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      $output .= $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        $output .= '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
        if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        $output .= '<li>'.$cats.'</li>';
        if ($showCurrent == 1) $output .= $before . get_the_title() . $after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      $output .= $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      //$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      $output .= '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
      if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) $output .= $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        $output .= $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) $output .= ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) $output .= ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

    } elseif ( is_tag() ) {
      $output .= $before . __('Posts tagged','purepress').' "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_author() ) {
     global $author;
     $userdata = get_userdata($author);
     $output .= $before . __('Articles posted by ','purepress') . $userdata->display_name . $after;

   } elseif ( is_404() ) {
    $output .= $before .  __('Error 404','purepress') . $after;
  }

  if ( get_query_var('paged') ) {
    if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ' (';
      $output .= '<li>'.__('Page','purepress') . ' ' . get_query_var('paged').'</li>';
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ')';
}

$output .= '</ul></nav>';
return $output;
}
} // end dimox_breadcrumbs()



function incr_number_to_width($width) {
    switch ($width) {
        case '1':
        return "one";
        break;
        case '2':
        return "two";
        break;
        case '3':
        return "three";
        break;
        case '4':
        return "four";
        break;
        case '5':
        return "five";
        break;
        case '6':
        return "six";
        break;
        case '7':
        return "seven";
        break;
        case '8':
        return "eight";
        break;
        case '9':
        return "nine";
        break;
        case '10':
        return "ten";
        break;
        case '11':
        return "eleven";
        break;
        case '12':
        return "twelve";
        break;
        case '13':
        return "thirteen";
        break;
        case '14':
        return "fourteen";
        break;
        case '15':
        return "fifteen";
        break;
        case '16':
        return "sixteen";
        break;

        default:
        return "thirteen";
        break;
    }
}


function new_excerpt_more($more) {
    global $post;
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');


function pp_tag_cloud_filter($args = array()) {
   $args['smallest'] = 14;
   $args['largest'] = 14;
   $args['unit'] = 'px';
   return $args;
}
add_filter('widget_tag_cloud_args', 'pp_tag_cloud_filter', 90);



function num_posts_portfolio($query)
{
    if ($query->is_main_query() && $query->is_post_type_archive('portfolio') && !is_admin()) {
        $showpost = ot_get_option('pp_portfolio_showpost','9');
        $query->set('posts_per_page', $showpost);
        }
}

add_action('pre_get_posts', 'num_posts_portfolio');


if ( ! function_exists( 'astrum_get_posts_page' ) ) :

function astrum_get_posts_page($info) {
  if( get_option('show_on_front') == 'page') {
    $posts_page_id = get_option( 'page_for_posts');
    $posts_page = get_page( $posts_page_id);
    $posts_page_title = $posts_page->post_title;
    $posts_page_url = get_page_uri($posts_page_id  );
  }
  else $posts_page_title = $posts_page_url = '';

  if ($info == 'url') {
    return $posts_page_url;
  } elseif ($info == 'title') {
    return $posts_page_title;
  } else {
    return false;
  }
}
endif;


function astrum_language_list(){
  if (function_exists('icl_get_languages')) {
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if(!empty($languages)){
        echo '<div id="astrum_language_list"><ul>';
        foreach($languages as $l){
            echo '<li>';
            if($l['country_flag_url']){
                if(!$l['active']) echo '<a href="'.$l['url'].'">';
                echo '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['native_name'].'" width="18" />';
                if(!$l['active']) echo '</a>';
            }

            echo '</li>';
        }
        echo '</ul></div>';
    }
  }
}


function twentyten_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );


/*
  Get ID of attachment by URL
  Credits: http://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
*/
if ( ! function_exists( 'pn_get_attachment_id_from_url' ) ) :
function pn_get_attachment_id_from_url( $attachment_url = '' ) {

  global $wpdb;
  $attachment_id = false;

  // If there is no url, return.
  if ( '' == $attachment_url )
    return;

  // Get the upload directory paths
  $upload_dir_paths = wp_upload_dir();

  // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
  if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

    // If this is the URL of an auto-generated thumbnail, get the URL of the original image
    $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

    // Remove the upload path base directory from the attachment URL
    $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

    // Finally, run a custom database query to get the attachment ID from the modified attachment URL
    $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

  }

  return $attachment_id;
}
endif;

function astrum_ajax_portfolio(){

  $postid = $_POST['post'];
  $nonce = $_POST['nonce'];
  if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
    die ( 'Forbidden!');
  }
  if(empty($postid) || $postid === 0) {
    die();
  } else {
    include(locate_template('inc/portfolio_ajax.php'));
    wp_reset_query();
  }
  exit;
}

add_action( 'wp_ajax_nopriv_astrum_ajax_portfolio', 'astrum_ajax_portfolio');
add_action( 'wp_ajax_astrum_ajax_portfolio', 'astrum_ajax_portfolio');

function astrum_get_next_post_id(){
  $postid = $_POST['post'];
  $nonce = $_POST['nonce'];
   if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
    die ( 'Forbidden!');
  }
  if(empty($postid) || $postid === 0) {
    die();
  } else {
    global $post;
    $post = get_post($postid);
    $next_post = get_next_post();
    if($next_post) {
      echo $next_post->ID;
    } else {
      echo 0;
    }
  }
  die();
}
add_action( 'wp_ajax_nopriv_astrum_get_next_post_id', 'astrum_get_next_post_id');
add_action( 'wp_ajax_astrum_get_next_post_id', 'astrum_get_next_post_id');

function astrum_get_prev_post_id(){
  $postid = $_POST['post'];
  $nonce = $_POST['nonce'];
   if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
    die ( 'Forbidden!');
  }
   if(empty($postid) || $postid === 0) {
    die();
  } else {
    global $post;
    $post = get_post($postid);
    $prev_post = get_previous_post();

    if($prev_post) {
      echo $prev_post->ID;
    } else {
      echo 0;
    }
  }
  die();
}


add_action( 'wp_ajax_nopriv_astrum_get_prev_post_id', 'astrum_get_prev_post_id');
add_action( 'wp_ajax_astrum_get_prev_post_id', 'astrum_get_prev_post_id');


//ordering taxonomies
function astrum_taxonomy_add_new_meta_field() {
  // this will add the custom meta field to the add new term page
  ?>
  <div class="form-field">
    <label for="term_meta[order_meta]"><?php _e( 'Order number', 'astrum' ); ?></label>
    <input type="text" name="term_meta[order_meta]" id="term_meta[order_meta]" value="">
  </div>
<?php
}
add_action( 'filters_add_form_fields', 'astrum_taxonomy_add_new_meta_field', 10, 2 );


function astrum_taxonomy_edit_meta_field($term) {

  // put the term ID into a variable
  $t_id = $term->term_id;

  // retrieve the existing value(s) for this meta field. This returns an array
  $term_meta = get_option( "taxonomy_$t_id" ); ?>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="term_meta[order_meta]"><?php _e( 'Order number', 'astrum' ); ?></label></th>
    <td>
      <input type="text" name="term_meta[order_meta]" id="term_meta[order_meta]" value="<?php echo esc_attr( $term_meta['order_meta'] ) ? esc_attr( $term_meta['order_meta'] ) : ''; ?>">
    </td>
  </tr>
<?php
}
add_action( 'filters_edit_form_fields', 'astrum_taxonomy_edit_meta_field', 10, 2 );


// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
  if ( isset( $_POST['term_meta'] ) ) {
    $t_id = $term_id;
    $term_meta = get_option( "taxonomy_$t_id" );
    $cat_keys = array_keys( $_POST['term_meta'] );
    foreach ( $cat_keys as $key ) {
      if ( isset ( $_POST['term_meta'][$key] ) ) {
        $term_meta[$key] = $_POST['term_meta'][$key];
      }
    }
    // Save the option array.
    update_option( "taxonomy_$t_id", $term_meta );
  }
}
add_action( 'edited_filters', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_filters', 'save_taxonomy_custom_meta', 10, 2 );




add_filter('get_terms', 'custom_term_sort', 10, 3);

function custom_term_sort($terms, $taxonomies, $args)
{
        // Controls behavior when get_terms is called at unusual times resulting in a terms array without objects
        $empty = false;

        // Create collector arrays
        $ordered_terms = array();
        $unordered_terms = array();

        // Add taxonomy order to terms
        foreach($terms as $term)
        {
                // Only set tax_order if value is an object
                if(is_object($term))
                {
                        $term_meta = get_option( "taxonomy_$term->term_id" );
                        if($taxonomy_sort = $term_meta['order_meta'])
                        {
                                $term->tax_order = (int) $taxonomy_sort;
                                $ordered_terms[] = $term;
                        }
                        else
                        {
                                $term->tax_order = (int) 0;
                                $unordered_terms[] = $term;
                        }
                }
                else
                        $empty = true;
        }

        // Only sort by tax_order if there are items to sort, otherwise return the original array
        if(!$empty && count($ordered_terms) > 0)
                quickSort($ordered_terms);
        else
                return $terms;

        // Combine the newly ordered items with the unordered items and return
        return array_merge($ordered_terms, $unordered_terms);
}

function quickSort(&$array)
{
        $cur = 1;
        $stack[1]['l'] = 0;
        $stack[1]['r'] = count($array)-1;

        do
        {
                $l = $stack[$cur]['l'];
                $r = $stack[$cur]['r'];
                $cur--;

                do
                {
                        $i = $l;
                        $j = $r;
                        $tmp = $array[(int)( ($l+$r)/2 )];

                        // partion the array in two parts.
                        // left from $tmp are with smaller values,
                        // right from $tmp are with bigger ones
                        do
                        {
                                while( $array[$i]->tax_order < $tmp->tax_order )
                                $i++;

                                while( $tmp->tax_order < $array[$j]->tax_order )
                                $j--;

                                // swap elements from the two sides
                                if( $i <= $j)
                                {
                                         $w = $array[$i];
                                         $array[$i] = $array[$j];
                                         $array[$j] = $w;

                                        $i++;
                                        $j--;
                                }

                        }while( $i <= $j );

                        if( $i < $r )
                        {
                                $cur++;
                                $stack[$cur]['l'] = $i;
                                $stack[$cur]['r'] = $r;
                        }
                        $r = $j;

                }while( $l < $r );

        }while( $cur != 0 );
}
