<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Woocommerce_Block')) {
    class PP_Woocommerce_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'WooCommerce block',
                'size' => 'span16',
                'last' => ''
                );

            //create the widget
            parent::__construct('PP_Woocommerce_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
                'title' => 'Recent Products',
                'orderby'=> 'date',
                'order'=> 'DESC',
                'per_page'  => '12',
                'width' => 'sixteen',
                'place' => 'center',
                );


         $limit_options = array();
            for ($i=0; $i < 25 ; $i++) {
             $limit_options[$i] = $i;
         }

         $instance = wp_parse_args($instance, $defaults);
         extract($instance); ?>
         <p class="description half">
            <label for="<?php echo $this->get_field_id('title') ?>">
                Title (required)<br/>
                <?php echo aq_field_input('title', $block_id, $title) ?>
            </label>
        </p>
        <p class="description half last">
            <label for="<?php echo $this->get_field_id('per_page') ?>">
                Limit (required)<br/>
                <?php echo aq_field_select('per_page', $block_id, $limit_options, $per_page) ?>
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

        <?php
    }

    function block($instance) {
        extract($instance);
        $width = AQ_Block::transform_span_to_gs($size);
        $randID = rand(1, 99);
        ?>
        <div class="">
            <h3 class="headline"><?php echo $title; ?></h3>
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
            'suppress_filters' => false,
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page' => $per_page,
            'orderby' => $orderby,
            'order' => $order,
            'meta_query' => array(
                array(
                    'key' => '_visibility',
                    'value' => array('catalog', 'visible'),
                    'compare' => 'IN'
                    )
                )
            );

$products = new WP_Query( $args );
 if ( $products->have_posts() ):
                            while( $products->have_posts() ) : $products->the_post(); ?>
<li>
    <div class="portfolio-item media">
        <figure>
            <div class="mediaholder">
            <?php if ( has_post_thumbnail()) { ?>
                <a href="<?php the_permalink(); ?>" >
                    <?php the_post_thumbnail('portfolio-4col');  ?>
                <div class="hovercover">
                    <div class="hovericon"><i class="hoverlink"></i></div>
                </div>
                </a>
            <?php } ?>
            </div>
            <a href="<?php echo get_permalink(); ?>" >
                <figcaption class="item-description">
                    <h5><?php the_title(); ?></h5>
                        <?php $product = get_product();
                        echo  $product->get_price_html();
                        ?>

                </figcaption>
            </a>
        </figure>
    </div>
</li>
<?php  endwhile;  // close the Loop
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
