<?php
/**
 * Template Name: Home Page Template
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage purepress
 * @since purepress 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header('new-design');


$sliderstatus = ot_get_option( 'pp_slider_on' );
if($sliderstatus == 'yes') {

    if (function_exists('icl_get_languages')) {

          $languages = icl_get_languages('skip_missing=0&orderby=code');
           if(!empty($languages)){
                foreach($languages as $l){
                    if(ICL_LANGUAGE_CODE == $l['language_code']) {
                    //echo '<section class="slider">'; putRevSlider(ot_get_option( 'pp_revo_slider'.$l['language_code'])); echo "</section>";
                    }
                }
           }
    } else {

        //echo '<section class="slider">'; putRevSlider(ot_get_option( 'pp_revo_slider' )); echo "</section>";
    }
}
while ( have_posts() ) : the_post(); ?>
<!-- 960 Container -->
<div class="container">

  <?php
        global $post;
        $pattern = get_shortcode_regex();

        $content = get_the_content();
        $tmp_flag = false;
        if(has_shortcode( $content, 'template' )) { $tmp_flag = true; }
        ?>
        <?php if($tmp_flag == false) { ?><div class="sixteen columns"> <?php } ?>
            <?php the_content(); ?>

        <?php if($tmp_flag == false) { ?></div> <?php } ?>

</div>
<?php endwhile; // end of the loop.

get_footer('new-design'); ?>