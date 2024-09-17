<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Blog_Block')) {
    class PP_Blog_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Recent blog posts',
                'size' => 'span12',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Blog_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'Recent News',
                'limit' => '',
                'orderby' => '',
                'queryorder' => '',
                'categories' => array(),
                'tags' => array(),
                );

            $post_categories = ($temp = get_terms('category')) ? $temp : array();
            $categories_options = array();
            foreach($post_categories as $cat) {
                $categories_options[$cat->term_id] = $cat->name;
            }

            $post_tags = ($temp = get_terms('post_tag')) ? $temp : array();
            $tags_options = array();
            foreach($post_tags as $tag) {
                $tags_options[$tag->term_id] = $tag->name;
            }

            $limit_options = array();
            for ($i=0; $i < 25 ; $i++) {
             $limit_options[$i] = $i;
         }

         $instance = wp_parse_args($instance, $defaults);
         extract($instance); ?>
         <p>Note: You should only use this block on a full-width template</p>
         <p class="description half">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Title (required)<br/>
                <?php echo aq_field_input('title', $block_id, $title) ?>
            </label>
        </p>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('limit') ?>">
                Limit (required)<br/>
                <?php echo aq_field_select('limit', $block_id, $limit_options, $limit) ?>
            </label>
        </p>
        <?php $orderby_options = array(
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
        ); ?>
        <p class="description half ">
            <label for="<?php echo $this->get_field_id('orderby') ?>">
                Orderby<br/>
                <?php echo aq_field_select('orderby', $block_id, $orderby_options, $orderby) ?>
            </label>
        </p>
              <?php $order_options = array(
                    'ASC' => 'from lowest to highest values (1, 2, 3; a, b, c)' ,
                    'DESC' => 'from highest to lowest values (3, 2, 1; c, b, a)' ,

        ); ?>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('queryorder') ?>">
                Order (ASC/DSC)<br/>
                <?php echo aq_field_select('queryorder', $block_id, $order_options, $queryorder) ?>
            </label>
        </p>
        <p class="description half">
                <label for="<?php echo $this->get_field_id('categories') ?>">
                Posts Categories (leave empty to display all)<br/>
                <?php echo aq_field_multiselect('categories', $block_id, $categories_options, $categories); ?>
                </label>
            </p>
            <p class="description half last">
                <label for="<?php echo $this->get_field_id('types') ?>">
                Posts Tags (leave empty to display all)<br/>
                <?php echo aq_field_multiselect('tags', $block_id, $tags_options, $tags); ?>
                </label>
            </p>

        <?php
    }

    function block($instance) {
        extract($instance);
        $width = AQ_Block::transform_span_to_gs($size);
        $randID = rand(1, 99);
        ?>
        <div class="">
            <h2 class="headline"><?php echo $title; ?></h2>
            <span class="line" style="margin-bottom:0;"></span>
        </div>

        <!-- ShowBiz Carousel -->
        <div class="showbiz-container recent-work">

            <!-- Navigation -->
            <div class="showbiz-navigation">
                <div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
                <div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
            </div>
            <div class="clearfix"></div>

            <!-- Portfolio Entries -->
            <div class="showbiz" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
              <div class="overflowholder">
                    <ul>
                        <?php
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $limit,
                            'orderby' => $orderby,
                            'queryorder' => $queryorder,
                            );
                        if($categories) $args['category__in'] = $categories;
                        if($tags) $args['tag__in'] = $tags;



                        $wp_query = new WP_Query( $args );
                        if($wp_query->found_posts > 1) { $mfpclass= "mfp-gallery"; } else { $mfpclass= "mfp-image"; }
                        if ( $wp_query->have_posts() ):
                            while( $wp_query->have_posts() ) : $wp_query->the_post();
                        ?>

                         <!-- Item -->
                        <li>
                            <div class="blog-item media">
                                <figure>
                                    <div class="mediaholder <?php if(!has_post_thumbnail()) { echo "textholder"; } ?>">
                                        <?php
                                        $thumb = get_post_thumbnail_id();
                                        $img_url = wp_get_attachment_url($thumb);
                                        ?>
                                        <?php if(has_post_thumbnail()){ ?>
                                          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                <?php the_post_thumbnail('portfolio-4col'); ?>
                                                <div class="hovercover">
                                                    <div class="hovericon"><i class="hoverlink"></i></div>
                                                </div>
                                            </a>
                                        <?php } ?>
                                        <?php
                                        if(!has_post_thumbnail()){
                                            $excerpt = get_the_excerpt();
                                            $short_excerpt = string_limit_words($excerpt,30); echo '<p>'.$short_excerpt.'..</p>';
                                        } ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>">
                                        <figcaption class="item-description">

                                            <p class="item-h5"><?php the_title(); ?></p>
                                            <span><?php
                                                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
                                                $time_string = sprintf( $time_string,esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );
                                                echo $time_string;
                                            ?></span>
                                        </figcaption>
                                    </a>

                                </figure>
                            </div>
                        </li>
                    <?php   endwhile;  // close the Loop
                        endif; ?>
                    </ul>
                    <div class="clearfix"></div>

                </div>
                <div class="clearfix"></div>

            </div>
        </div>
            <?php
        }


        function update($new_instance, $old_instance) {
            $new_instance = aq_recursive_sanitize($new_instance);
            return $new_instance;
        }
    }
}
