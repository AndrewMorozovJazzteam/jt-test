<?php
/* Aqua Featured Block - PureThemes */
if(!class_exists('PP_Team_Block')) {
    class PP_Team_Block extends AQ_Block {

        function __construct() {
            $block_options = array(
                'name' => 'Team',
                'size' => 'span16',
                'last' => ''

                );

            //create the widget
            parent::__construct('PP_Team_Block', $block_options);
        }

        function form($instance) {

            $defaults = array(
               'limit'=>'4',
               'title' => 'Team',
               'members' => '',
               );

            $limit_options = array();
            for ($i=0; $i < 25 ; $i++) {
                $limit_options[$i] = $i;
            }
            $wp_query = new WP_Query(
                array(
                    'post_type' => array('team'),
                    'showposts' => 99,
                    )
                );
            $team_members = array();
            while( $wp_query->have_posts() ) : $wp_query->the_post();
            $team_members[$wp_query->post->ID] = get_the_title();
            endwhile;
            $instance = wp_parse_args($instance, $defaults);
            extract($instance); ?>
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
            <p class="description">
                <label for="<?php echo $this->get_field_id('filters') ?>">
                    Select members (for all leave blank)<br/>
                    <?php echo aq_field_multiselect('members', $block_id, $team_members, $members); ?>
                </label>
            </p>

            <?php }

            function block($instance) {
                extract($instance);
                $width = AQ_Block::transform_span_to_gs($size);
                $randID = rand(1, 99);


                if(!empty($members)) {
                    $args = array(
                        'post_type' => array('team'),
                        'showposts' => $limit,
                        'post__in' => $members,
                        );
                } else {
                 $args = array(
                    'post_type' => array('team'),
                    'showposts' => $limit,

                    );
             }

             $wp_query = new WP_Query( $args );
             ?>

             <div class="team showbiz-container">
                <h3 class="headline"><?php echo $title; ?></h3>
                <span class="line" style="margin-bottom:0;"></span>

                <!-- Navigation -->
                <div class="showbiz-navigation">
                    <div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
                    <div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
                </div>
                <div class="clearfix"></div>

                <!-- Entries -->
                <div class="showbiz" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
                    <div class="overflowholder">
                        <ul>
                         <?php   if ( $wp_query->have_posts() ):
                         while( $wp_query->have_posts() ) : $wp_query->the_post();

                         $id = $wp_query->post->ID;
                         $position = get_post_meta($id, 'pp_position', true);
                         $social = get_post_meta($id, 'pp_socialicons', true);

                        echo '<li>';
                        if ( has_post_thumbnail() ) {
                            echo '<a href="'.get_permalink().'">';
                            echo get_the_post_thumbnail($wp_query->post->ID,'portfolio-3col', array('class' => 'mediaholder team-img'));
                            echo "</a>";
                        }
                        echo '<div class="team-name"><h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5> <span>'.$position.'</span></div>
                        <div class="team-about"><p>'.get_the_content().'</p></div>';
                        if(!empty($social)){

                            echo '<ol class="social-icons">';
                            foreach ($social as $icon) {
                               echo '<li><a class="'.$icon['icons_service'].'" href="'.$icon['icons_url'].'"><i class="icon-'.$icon['icons_service'].'"></i></a></li>';
                           }
                           echo '</ol>';
                       }

                       echo '<div class="clearfix"></div></li>';


        endwhile;  // close the Loop
        endif; wp_reset_query(); ?>
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
