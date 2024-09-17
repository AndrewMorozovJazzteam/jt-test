<?php
/**
 * Custom widgets for astrum theme
 *
 *
 * @package Astrum
 * @since Astrum 1.0
 */


add_action('widgets_init', 'purepress_load_widgets'); // Loads widgets here
function purepress_load_widgets() {
    register_widget('purepress_flickr');
    register_widget('purepress_contact');
    register_widget('purepress_tabbed');
    register_widget('purepress_twitter_widget');
     if(class_exists('NS_MC_Plugin')){ register_widget('Astrum_NS_Widget_MailChimp'); }
}

// flickr functions

function ppimage_from_description($data) {
    preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $matches);
    return $matches[1][0];
}

function ppselect_image($img, $size) {
    $img = explode('/', $img);
    $filename = array_pop($img);
    // The sizes listed here are the ones Flickr provides by default.  Pass the array index in the        $size variable to selct one.
    // 0 for square, 1 for thumb, 2 for small, etc.
    $s = array(
        '_s.', // square
        '_t.', // thumb
        '_m.', // small
        '.',   // medium
        '_b.'  // large
        );
    $img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
    return implode('/', $img);
}


// Flickr widget
class purepress_flickr extends WP_Widget {

    function purepress_flickr() {
        $widget_ops = array('classname' => 'purepress-flickr', 'description' => 'Widget for Flickr photos');
        $control_ops = array('width' => 300);
        parent::__construct('purepress_flickr', 'Astrum Flickr', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $count = $instance['count'];

        echo $before_title . $title . $after_title;
        $rss_url = '';
        if ($instance['type'] == "user") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $instance['id'] . '&tags=&format=rss_200'; }
        elseif ($instance['type'] == "favorite") { $rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $instance['id'] . '&format=rss_200'; }
        elseif ($instance['type'] == "set") { $rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $instance['set'] . '&nsid=' . $instance['id'] . '&format=rss_200'; }
        elseif ($instance['type'] == "group") { $rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $instance['id'] . '&format=rss_200'; }
        elseif ($instance['type'] == "public" || $instance['type'] == "community") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $instance['tags'] . '&format=rss_200'; }
        else {
            print '<strong>No "type" parameter has been setup. Check your flickr widget settings.</strong>';

        }
        // Check if another plugin is using RSS, may not work
        include_once (ABSPATH . WPINC . '/class-simplepie.php');
        error_reporting(E_ERROR);
        $feed = new SimplePie($rss_url);
        $feed->handle_content_type();

        //$items = array_slice($rss->items, 0, $instance['count']);

        $print_flickr = '<div class="flickr-widget-blog"><ul>';

        $i = 0;
        foreach ($feed->get_items() as $item):

            if(++$i > $count) {
                break;
            }
            if ($enclosure = $item->get_enclosure()) {
                $img = ppimage_from_description($item->get_description());
                $thumb_url = ppselect_image($img, 0);
                $full_url = ppselect_image($img, 4);
                $print_flickr .= '<li><a  href="' .$item->get_link() . '" title="'. $enclosure->get_title(). '"><img alt="'. $enclosure->get_title().'" id="photo_' . $i . '" src="' . $thumb_url . '" /></a></li>'."\n";
            }
        endforeach;

        echo $print_flickr.'</ul></div><div class="clearfix"></div>';
        echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['count'] = $new_instance['count'];
            $instance['type'] = $new_instance['type'];
            $instance['id'] = $new_instance['id'];
            $instance['set'] = $new_instance['set'];
            $instance['tas'] = $new_instance['tags'];
            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = strip_tags($instance['title']);
            $count = $instance['count'];
            $type = $instance['type'];
            $id = $instance['id'];
            $set = $instance['set'];
            $tags = $instance['tags'];
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('type'); ?>">Display photos from</label>
                <select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" id="type">
                    <option <?php if($instance['type'] == 'user') { echo 'selected'; } ?> value="user">user</option>
                    <option <?php if($instance['type'] == 'set') { echo 'selected'; } ?> value="set">set</option>
                    <option <?php if($instance['type'] == 'favorite') { echo 'selected'; } ?> value="favorite">favorite</option>
                    <option <?php if($instance['type'] == 'group') { echo 'selected'; } ?> value="group">group</option>
                    <option <?php if($instance['type'] == 'public') { echo 'selected'; } ?> value="public">community</option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('id'); ?>">User or Group ID (<a href="http://idgettr.com/">find ID</a>)</label>
                <input  id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" size="20" />

            </p>

            <p>
                <label for="<?php echo $this->get_field_id('set'); ?>">Set ID (<a href="http://idgettr.com/">find your ID</a> )</label>
                <input  id="<?php echo $this->get_field_id('set'); ?>" name="<?php echo $this->get_field_name('set'); ?>"  type="text"  value="<?php echo $set; ?>" size="40" />

            </p>

            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>">Tags (optional) <small>Comma separated, no spaces</small> </label>
                <input  id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>"  type="text" value="<?php echo $tags; ?>" size="40" />

            </p>

            <p>
                <label for="<?php echo $this->get_field_id('count'); ?>">How many photos?
                    <select class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" >
                        <?php for ($i=1; $i<=20; $i++) { ?>
                        <option <?php if ($count == $i) { echo 'selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </label>
            </p>

            <?php
        }

    } // eof Flickr widget


// Contact info widget
    class purepress_contact extends WP_Widget {

        function purepress_contact() {
            $widget_ops = array('classname' => 'purepress-contact', 'description' => 'Nicely styled contact info widget');
            $control_ops = array('width' => 300, 'height' => 350);
            parent::__construct('purepress_contact', 'Astrum Contact Info', $widget_ops, $control_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $address = $instance['address'];
            $phone = $instance['phone'];
            $fax = $instance['fax'];
            $email = $instance['email'];
            echo $before_widget;
            echo $before_title . $title . $after_title;
            ?>
            <ul  class="get-in-touch">
                <?php
                if($address) { ?>
                <li><i class="icon-map-marker"></i> <p><strong><?php _e('Address', 'purepress'); ?>:</strong> <?php echo $address; ?></p></li>
                <?php }
                if($phone) { ?>
                <li><i class="icon-phone"></i> <p><strong><?php _e('Phone', 'purepress'); ?>:</strong> <?php echo $phone; ?></p></li>
                <?php }
                if($fax) { ?>
                <li><i class="icon-user"></i> <p><strong><?php _e('Fax', 'purepress'); ?>:</strong> <?php echo $fax; ?></p></li>
                <?php }
                if($email) { ?>
                <li><i class="icon-envelope"></i> <p><strong><?php _e('Email', 'purepress'); ?>:</strong> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p></li>
                <?php } ?>
            </ul>
            <?php
            echo $after_widget;
        }


        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['address'] = $new_instance['address'];
            $instance['phone'] = $new_instance['phone'];
            $instance['fax'] = $new_instance['fax'];
            $instance['email'] = $new_instance['email'];

            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = strip_tags($instance['title']);
            $address = strip_tags($instance['address']);
            $phone = strip_tags($instance['phone']);
            $fax = strip_tags($instance['fax']);
            $email = strip_tags($instance['email']);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Title:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address', 'purepress'); ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo $address; ?>" size="20" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone', 'purepress'); ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo $phone; ?>" size="20" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax', 'purepress'); ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo $fax; ?>" size="20" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email', 'purepress'); ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" size="20" />
                </label>
            </p>


            <?php
        }
    }



/**
 * Twitter widget astrum
 *
 * @since 1.0
 * TODO: update for API 1.1
 */
class purepress_twitter_widget extends WP_Widget {

//  class pu_tweet_widget extends WP_Widget {
    private $twitter_title = "Twitter";
    private $twitter_username = "purethemes";
    private $twitter_postcount = "1";
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'purepress_twitter_widget',      // Base ID
            'Astrum Twitter Widget',       // Name
            array(
                'classname'     =>  'purepress_twitter_widget',
                'description'   =>  __('A widget that displays your latest tweets.', 'framework')
                )
            );
        // Load JavaScript and stylesheets
        $this->register_scripts_and_styles();
    } // end constructor
    /**
     * Registers and enqueues stylesheets for the administration panel and the
     * public facing site.
     */
    public function register_scripts_and_styles() {
    } // end register_scripts_and_styles
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        /* Our variables from the widget settings. */
        $this->twitter_title = apply_filters('widget_title', $instance['title'] );
        $this->twitter_username = $instance['username'];
        $this->twitter_postcount = $instance['postcount'];
        //$this->twitter_follow_text = $instance['tweettext'];
        $transName = 'list_tweets';
        $cacheTime = 20;

        /* Before widget (defined by themes). */
        echo $before_widget;
        if ( $this->twitter_title )
            echo $before_title . $this->twitter_title . $after_title;
        echo ' <div class="twitter_box">';
        $consumer_key = ot_get_option('pp_twitter_ck');
        if(!empty($consumer_key)) {
        $tweets = $this->get_tweets( $args['widget_id'], $instance );
        if( !empty( $tweets['tweets'] ) AND empty( $tweets['tweets']->errors ) ) {

            $user = current( $tweets['tweets'] );
            $user = $user->user;
          /*echo '
                <div class="twitter-profile">
                <img id="twitter-av" src="' . $user->profile_image_url . '">
                <h3><a class="heading-text-color" href="http://twitter.com/' . $user->screen_name . '">' . $user->screen_name . '</a></h3>
                <div class="description content">' . $user->description . '</div>
                </div>  ';*/


                echo '<ul  id="twitter">';
                foreach( $tweets['tweets'] as $tweet ) {
                    if( is_object( $tweet ) ) {
                        $tweet_text = htmlentities($tweet->text, ENT_QUOTES);
                        $tweet_text = preg_replace( '/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', 'http://$1', $tweet_text );
                        echo '
                        <li class="twitter-item">
                        <span class="content">' . $this->hyperlinks($tweet_text) . '</span></br>
                        <b class="date"><a>' . human_time_diff( strtotime( $tweet->created_at ) ) . ' ago </a></b>
                        </li>';
                    }
                }
                echo '</ul><div class="clearfix"></div>
                <a href="https://twitter.com/'.$user->screen_name.'" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @'.$user->screen_name.'</a>';
                ?><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                <?php
            }
        }
            echo '</div>';

            /* After widget (defined by themes). */
            echo $after_widget;
        }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        // Strip tags to remove HTML (important for text inputs)
        foreach($new_instance as $k => $v){
            $instance[$k] = strip_tags($v);
        }
        return $instance;
    }

    function retrieve_tweets( $widget_id, $instance ) {
        global $cb;
        $timeline = $cb->statuses_userTimeline( 'screen_name=' . $instance['username']. '&count=' . $instance['postcount'] . '&exclude_replies=true' );
        return $timeline;
    }
    function save_tweets( $widget_id, $instance ) {
        $timeline = $this->retrieve_tweets( $widget_id, $instance );
        $tweets = array( 'tweets' => $timeline, 'update_time' => time() + ( 60 * 5 ) );
        update_option( 'astrum_tweets_' . $widget_id, $tweets );
        return $tweets;
    }
    function get_tweets( $widget_id, $instance ) {
        $tweets = get_option( 'astrum_tweets_' . $widget_id );
        if( empty( $tweets ) OR time() > $tweets['update_time'] ) {
            $tweets = $this->save_tweets( $widget_id, $instance );
        }
        return $tweets;
    }
    /**
     * Create the form for the Widget admin
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form( $instance ) {
        /* Set up some default widget settings. */
        $defaults = array(
            'title' => $this->twitter_title,
            'username' => $this->twitter_username,
            'postcount' => $this->twitter_postcount,
        //'tweettext' => $this->twitter_follow_text,
            );
            $instance = wp_parse_args( (array) $instance, $defaults ); ?>
            <!-- Widget Title: Text Input -->
            <p>Don't forget to fill the <strong>Twitter OAuth</strong> form in Theme Options.</p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />

            <!-- Username: Text Input -->

            <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter Username e.g. purethemes', 'purepress') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />

            <!-- Postcount: Text Input -->

            <label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of tweets (max 20)', 'purepress') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" />

            <!-- Tweettext: Text Input -->


            <?php
        }
    /**
     * Find links and create the hyperlinks
     */
    private function hyperlinks($text) {
        $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
        $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);
        // match name@address
        $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
            //mach #trendingtopics. Props to Michael Voigt
        $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
        return $text;
    }
    /**
     * Find twitter usernames and link to them
     */
    private function twitter_users($text) {
     $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $text);
     return $text;
 }
        /**
         * Encode single quotes in your tweets
         */
        private function encode_tweet($text) {
            $text = mb_convert_encoding( $text, "HTML-ENTITIES", "UTF-8");
            return $text;
        }
    }



    class purepress_tabbed extends WP_Widget {

        function purepress_tabbed() {
            $widget_ops = array('classname' => 'purepress-tabbed', 'description' => 'Tabbed widget for post and comments');
            $control_ops = array('width' => 300, 'height' => 350);
            parent::__construct('purepress_tabbed', 'Astrum Tabbed Widget', $widget_ops, $control_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $recent = $instance['recent'];
            $popular = $instance['popular'];
            $comments = $instance['comments'];
            $number = $instance['number'];
            echo $before_widget;
            ?>
            <ul class="tabs-nav blog">
                <?php if($recent) { ?><li class="active"><a href="#tab1" title="Recent Posts"><i class="icon-time"></i></a></li><?php } ?>
                <?php if($popular) { ?><li><a href="#tab2" title="Popular Posts"><i class="icon-star"></i></a></li><?php } ?>
                <?php if($comments) { ?><li><a href="#tab3" title="Recent Comments"><i class="icon-comments-alt"></i></a></li><?php } ?>
            </ul>
            <!-- Tabs Content -->
            <div class="tabs-container">
                 <?php if($recent) { ?>
                 <div class="tab-content" id="tab1">
                     <!-- Recent Posts -->
                     <ul class="widget-tabs">
                        <?php echo self::showLatest($posts = $number); ?>
                    </ul>
                </div>
                <?php } ?>
                <?php if($popular) { ?>
                <div class="tab-content" id="tab2">
                    <!-- Popular Posts -->
                    <ul class="widget-tabs">
                       <?php echo self::showLatest($posts = $number, $orderby = "comment_count"); ?>
                   </ul>
               </div>
               <?php } ?>

               <?php if($comments) { ?>
               <div class="tab-content" id="tab3">
                   <!-- Recent Comments -->
                   <ul class="widget-tabs comments">
                       <?php echo self::showLatestComments($posts = $number); ?>
                   </ul>
               </div>
               <?php } ?>
           </div>


       <?php
       echo $after_widget;
   }


   function update($new_instance, $old_instance) {
    $instance = $old_instance;
      $instance['recent'] = strip_tags($new_instance['recent']);
      $instance['popular'] = strip_tags($new_instance['popular']);
      $instance['comments'] = strip_tags($new_instance['comments']);
      $instance['number'] = strip_tags($new_instance['number']);
    return $instance;
}

function form($instance) {
    $instance = wp_parse_args((array) $instance, array('title' => ''));
    $title = strip_tags($instance['title']);
    $recent = esc_attr($instance['recent']);
    $popular = esc_attr($instance['popular']);
    $comments = esc_attr($instance['comments']);
    $number = esc_attr($instance['number']);
    ?>
    <p>Set tabs to display:</p>
    <p>
        <input id="<?php echo $this->get_field_id('recent'); ?>" name="<?php echo $this->get_field_name('recent'); ?>" type="checkbox" value="1" <?php checked( '1', $recent ); ?>/>
        <label for="<?php echo $this->get_field_id('recent'); ?>"><?php _e('Recent posts','purepress'); ?></label>
    </p><p>
        <input id="<?php echo $this->get_field_id('popular'); ?>" name="<?php echo $this->get_field_name('popular'); ?>" type="checkbox" value="1" <?php checked( '1', $popular ); ?>/>
        <label for="<?php echo $this->get_field_id('popular'); ?>"><?php _e('Popular posts','purepress'); ?></label>
    </p><p>
        <input id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" type="checkbox" value="1" <?php checked( '1', $comments ); ?>/>
        <label for="<?php echo $this->get_field_id('comments'); ?>"><?php _e('Latest comments','purepress'); ?></label>
    </p>

    <label>Set number of items to display
        <select id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>">
            <?php for ($i=1; $i < 10; $i++) { ?>
                <option <?php if ($number == $i) echo 'selected'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
        </label>
    <?php
}

/**
     * Display Latest posts
     */
        static function showLatest( $posts = 3, $orderby = 'post_date' ) {
            global $post;
            $latest = get_posts(
                array(
                    'suppress_filters' => false,
                    'ignore_sticky_posts' => 1,
                    'orderby' => $orderby,
                    'order' => 'desc',
                    'numberposts' => $posts )
                );

            ob_start();

            $date_format = get_option('date_format');
            foreach($latest as $post) :
                setup_postdata($post);
            ?>

            <!-- Post #1 -->
            <li>
                <?php if ( has_post_thumbnail() ) { ?>
                <div class="widget-thumb">
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('square-thumb'); ?></a>
                </div>
                <?php } ?>

                <div class="widget-text">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <span><?php echo get_the_date(); ?></span>
                </div>
                <div class="clearfix"></div>
            </li>

            <?php endforeach;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }

        static function showLatestComments( $posts = 3 ) {
            global $post;

            $comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $posts, 'status' => 'approve', 'post_status' => 'publish' ) ) );

            ob_start();

            if ( $comments ) {
            // Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
                $post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
                _prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

                foreach ( (array) $comments as $comment) { ?>
                <li>
                    <div class="widget-thumb">
                        <a href="<?php echo esc_url( get_comment_link($comment->comment_ID) ); ?>">
                            <?php echo get_avatar( $comment->comment_author_email, 100 ); ?>
                        </a>
                    </div>

                    <div class="widget-text">
                        <span><?php echo $comment->comment_author; ?> on:</span>
                        <h4><a href="<?php echo esc_url( get_comment_link($comment->comment_ID) ); ?>"><?php echo $comment->post_title; ?></a></h4>
                    </div>
                    <div class="clearfix"></div>
                </li>
                <?php

            }
        }
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     * Display most commented posts
     */
} //eof tabbed




/**
 * @author James Lafferty
 * @since 0.1
 */

class Astrum_NS_Widget_MailChimp extends WP_Widget {
    private $default_failure_message;
    private $default_loader_graphic = '/images/loader.gif';
    private $default_signup_text;
    private $default_success_message;
    private $default_title;
    private $successful_signup = false;
    private $subscribe_errors;
    private $astrum_ns_mc_plugin;

    /**
     * @author James Lafferty
     * @since 0.1
     */
    public function Astrum_NS_Widget_MailChimp () {
        $this->default_failure_message = __('There was a problem processing your submission.','purepress');
        $this->default_signup_text = __('Join','purepress');
        $this->default_success_message = __('Thank you for joining our mailing list. Please check your email for a confirmation link.','purepress');
        $this->default_title = __('Newsletter.','purepress');
        $widget_options = array('classname' => 'widget_ns_mailchimp', 'description' => __( "Displays a sign-up form for a MailChimp mailing list.", 'mailchimp-widget'));
        parent::__construct('astrum_ns_widget_mailchimp', __('Astrum MailChimp List Signup', 'mailchimp-widget'), $widget_options);
        $this->astrum_ns_mc_plugin = NS_MC_Plugin::get_instance();
        $this->default_loader_graphic = get_template_directory_uri() . $this->default_loader_graphic;
        add_action('init', array(&$this, 'add_scripts'));
        add_action('parse_request', array(&$this, 'process_submission'));
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function add_scripts () {
        wp_dequeue_script('ns-mc-widget');
        wp_enqueue_script('ns-mc-widget1', get_template_directory_uri() . '/js/mailchimp-widget.js', array('jquery'), false);
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function form ($instance) {
        $mcapi = $this->astrum_ns_mc_plugin->get_mcapi();
        if (false == $mcapi) {
            echo $this->astrum_ns_mc_plugin->get_admin_notices();
        } else {
            $this->lists = $mcapi->lists();
            $defaults = array(
                'failure_message' => $this->default_failure_message,
                'title' => $this->default_title,
                'signup_text' => $this->default_signup_text,
                'success_message' => $this->default_success_message,
                'collect_first' => false,
                'collect_last' => false,
                'old_markup' => false
            );
            $vars = wp_parse_args($instance, $defaults);
            extract($vars);
            ?>
                    <h3><?php echo  __('General Settings', 'mailchimp-widget'); ?></h3>
                    <p>
                        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo  __('Title :', 'mailchimp-widget'); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                    </p>
                    <p>
                        <label for="<?php echo $this->get_field_id('desc'); ?>"><?php echo  __('Description :', 'mailchimp-widget'); ?></label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>"><?php echo $desc; ?></textarea>
                    </p>
                    <p>
                        <label for="<?php echo $this->get_field_id('current_mailing_list'); ?>"><?php echo __('Select a Mailing List :', 'mailchimp-widget'); ?></label>
                        <select class="widefat" id="<?php echo $this->get_field_id('current_mailing_list');?>" name="<?php echo $this->get_field_name('current_mailing_list'); ?>">
            <?php
            foreach ($this->lists['data'] as $key => $value) {
                $selected = (isset($current_mailing_list) && $current_mailing_list == $value['id']) ? ' selected="selected" ' : '';
                ?>
                        <option <?php echo $selected; ?>value="<?php echo $value['id']; ?>"><?php echo __($value['name'], 'mailchimp-widget'); ?></option>
                <?php
            }
            ?>
                        </select>
                    </p>
                    <p><strong>N.B.</strong><?php echo  __('This is the list your users will be signing up for in your sidebar.', 'mailchimp-widget'); ?></p>
                    <p>
                        <label for="<?php echo $this->get_field_id('signup_text'); ?>"><?php echo __('Sign Up Button Text :', 'mailchimp-widget'); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id('signup_text'); ?>" name="<?php echo $this->get_field_name('signup_text'); ?>" value="<?php echo $signup_text; ?>" />
                    </p>
                    <h3><?php echo __('Personal Information', 'mailchimp-widget'); ?></h3>
                    <p><?php echo __("These fields won't (and shouldn't) be required. Should the widget form collect users' first and last names?", 'mailchimp-widget'); ?></p>
                    <p>
                        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('collect_first'); ?>" name="<?php echo $this->get_field_name('collect_first'); ?>" <?php echo  checked($collect_first, true, false); ?> />
                        <label for="<?php echo $this->get_field_id('collect_first'); ?>"><?php echo  __('Collect first name.', 'mailchimp-widget'); ?></label>
                        <br />
                        <input type="checkbox" class="checkbox" id="<?php echo  $this->get_field_id('collect_last'); ?>" name="<?php echo $this->get_field_name('collect_last'); ?>" <?php echo checked($collect_last, true, false); ?> />
                        <label><?php echo __('Collect last name.', 'mailchimp-widget'); ?></label>
                    </p>
                    <h3><?php echo __('Notifications', 'mailchimp-widget'); ?></h3>
                    <p><?php echo  __('Use these fields to customize what your visitors see after they submit the form', 'mailchimp-widget'); ?></p>
                    <p>
                        <label for="<?php echo $this->get_field_id('success_message'); ?>"><?php echo __('Success :', 'mailchimp-widget'); ?></label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id('success_message'); ?>" name="<?php echo $this->get_field_name('success_message'); ?>"><?php echo $success_message; ?></textarea>
                    </p>
                    <p>
                        <label for="<?php echo $this->get_field_id('failure_message'); ?>"><?php echo __('Failure :', 'mailchimp-widget'); ?></label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id('failure_message'); ?>" name="<?php echo $this->get_field_name('failure_message'); ?>"><?php echo $failure_message; ?></textarea>
                    </p>
            <?php

        }
    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function process_submission () {

        if (isset($_GET[$this->id_base . '_email'])) {

            header("Content-Type: application/json");

            //Assume the worst.
            $response = '';
            $result = array('success' => false, 'error' => $this->get_failure_message($_GET['ns_mc_number']));

            $merge_vars = array();

            if (! is_email($_GET[$this->id_base . '_email'])) { //Use WordPress's built-in is_email function to validate input.

                $response = json_encode($result); //If it's not a valid email address, just encode the defaults.

            } else {

                $mcapi = $this->astrum_ns_mc_plugin->get_mcapi();

                if (false == $this->astrum_ns_mc_plugin) {

                    $response = json_encode($result);

                } else {

                    if (isset($_GET[$this->id_base . '_first_name']) && is_string($_GET[$this->id_base . '_first_name'])) {

                        $merge_vars['FNAME'] = $_GET[$this->id_base . '_first_name'];

                    }

                    if (isset($_GET[$this->id_base . '_last_name']) && is_string($_GET[$this->id_base . '_last_name'])) {

                        $merge_vars['LNAME'] = $_GET[$this->id_base . '_last_name'];

                    }

                    $subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_GET['ns_mc_number']), $_GET[$this->id_base . '_email'], $merge_vars);

                    if (false == $subscribed) {

                        $response = json_encode($result);

                    } else {

                        $result['success'] = true;
                        $result['error'] = '';
                        $result['success_message'] =  $this->get_success_message($_GET['ns_mc_number']);
                        $response = json_encode($result);

                    }

                }

            }

            exit($response);

        } elseif (isset($_POST[$this->id_base . '_email'])) {

            $this->subscribe_errors = '<div class="notification closeable error"><p>'  . $this->get_failure_message($_POST['ns_mc_number']) .  '</p></div>';

            if (! is_email($_POST[$this->id_base . '_email'])) {

                return false;

            }

            $mcapi = $this->astrum_ns_mc_plugin->get_mcapi();

            if (false == $mcapi) {

                return false;

            }

            if (is_string($_POST[$this->id_base . '_first_name'])  && '' != $_POST[$this->id_base . '_first_name']) {

                $merge_vars['FNAME'] = strip_tags($_POST[$this->id_base . '_first_name']);

            }

            if (is_string($_POST[$this->id_base . '_last_name']) && '' != $_POST[$this->id_base . '_last_name']) {

                $merge_vars['LNAME'] = strip_tags($_POST[$this->id_base . '_last_name']);

            }

            $subscribed = $mcapi->listSubscribe($this->get_current_mailing_list_id($_POST['ns_mc_number']), $_POST[$this->id_base . '_email'], $merge_vars);

            if (false == $subscribed) {

                return false;

            } else {

                $this->subscribe_errors = '';

                //setcookie($this->id_base . '-' . $this->number, $this->hash_mailing_list_id(), time() + 31556926);

                $this->successful_signup = true;

                $this->signup_success_message = '<p>' . $this->get_success_message($_POST['ns_mc_number']) . '</p>';

                return true;

            }

        }

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function update ($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['collect_first'] = ! empty($new_instance['collect_first']);

        $instance['collect_last'] = ! empty($new_instance['collect_last']);

        $instance['current_mailing_list'] = esc_attr($new_instance['current_mailing_list']);

        $instance['failure_message'] = esc_attr($new_instance['failure_message']);

        $instance['signup_text'] = esc_attr($new_instance['signup_text']);

        $instance['success_message'] = esc_attr($new_instance['success_message']);

        $instance['title'] = esc_attr($new_instance['title']);

        $instance['desc'] = esc_attr($new_instance['desc']);

        return $instance;

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    public function widget ($args, $instance) {

        extract($args);



            echo $before_widget . $before_title . $instance['title'] . $after_title;

            if ($this->successful_signup) {
                echo $this->signup_success_message;
            } else {
                ?>
                <p><?php echo $instance['desc']; ?></p>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="<?php echo $this->id_base . '_form-' . $this->number; ?>" method="post">
                    <?php echo $this->subscribe_errors;?>
                    <?php
                        if ($instance['collect_first']) {
                    ?>
                    <input value="<?php echo __('First Name :', 'mailchimp-widget'); ?>" onblur="if(this.value=='')this.value='<?php echo __('First Name :', 'mailchimp-widget'); ?>';" onfocus="if(this.value=='<?php echo __('First Name :', 'mailchimp-widget'); ?>')this.value='';" type="text" name="<?php echo $this->id_base . '_first_name'; ?>" />
                    <br />
                    <br />
                    <?php
                        }
                        if ($instance['collect_last']) {
                    ?>
                    <input value="<?php echo __('Last Name :', 'mailchimp-widget'); ?>" onblur="if(this.value=='')this.value='<?php echo __('Last Name :', 'mailchimp-widget'); ?>';" onfocus="if(this.value=='<?php echo __('Last Name :', 'mailchimp-widget'); ?>')this.value='';" type="text" name="<?php echo $this->id_base . '_last_name'; ?>" />
                    <br />
                    <br />
                    <?php
                        }
                    ?>
                        <input type="hidden" name="ns_mc_number" value="<?php echo $this->number; ?>" />
                        <input onblur="if(this.value=='')this.value='mail@example.com';" onfocus="if(this.value=='mail@example.com')this.value='';" value="mail@example.com" id="<?php echo $this->id_base; ?>-email-<?php echo $this->number; ?>" type="text" name="<?php echo $this->id_base; ?>_email" />
                        <input class="button" type="submit" name="<?php echo __($instance['signup_text'], 'mailchimp-widget'); ?>" value="<?php echo __($instance['signup_text'], 'mailchimp-widget'); ?>" />
                    </form>
                        <script>jQuery('#<?php echo $this->id_base; ?>_form-<?php echo $this->number; ?>').ns_mc_widget({"url" : "<?php echo $_SERVER['PHP_SELF']; ?>", "cookie_id" : "<?php echo $this->id_base; ?>-<?php echo $this->number; ?>", "cookie_value" : "<?php echo $this->hash_mailing_list_id(); ?>", "loader_graphic" : "<?php echo $this->default_loader_graphic; ?>"}); </script>
                <?php
            }
            echo $after_widget;


    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    private function hash_mailing_list_id () {

        $options = get_option($this->option_name);

        $hash = md5($options[$this->number]['current_mailing_list']);

        return $hash;

    }

    /**
     * @author James Lafferty
     * @since 0.1
     */

    private function get_current_mailing_list_id ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['current_mailing_list'];

    }

    /**
     * @author James Lafferty
     * @since 0.5
     */

    private function get_failure_message ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['failure_message'];

    }

    /**
     * @author James Lafferty
     * @since 0.5
     */

    private function get_success_message ($number = null) {

        $options = get_option($this->option_name);

        return $options[$number]['success_message'];

    }

}

?>
