<?php
/**
 * The main template file.
 * @package WordPress
 */
//$htype = ot_get_option('pp_header_menu');
get_header('new-design'); ?>
<!-- Titlebar
    ================================================== -->
	<section id="titlebar" class="titlebar">
        <!-- Container -->
        <div class="container">

            <div class="eight columns">
                <h1 class="headline-first headline-first--indent-strict"> <?php $pf_title = get_post_meta($post->ID, 'pp_portfolio_title', true); if($pf_title) { echo $pf_title;} else { $pp_portfolio_page = ot_get_option('pp_portfolio_page');
                if (function_exists('icl_register_string')) {
                    icl_register_string('Portfolio page title','pp_portfolio_page', $pp_portfolio_page);
                    echo icl_t('Portfolio page title','pp_portfolio_page', $pp_portfolio_page); 
                    }
                    else {
                        //echo $pp_portfolio_page;
                        echo 'IT Solutions in ';
                    } } ?> <?php if(is_tax()) { $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );  if($term) echo $term->name; } ?></h1>
            </div>
            <div class="eight columns">
                <nav id="breadcrumbs">
                  <?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>
                </nav>
            </div>
      </div>
      <!-- Container / End -->
    </section>
      <!-- 960 Container / End -->
<?php $layout = ot_get_option('pp_portfolio_layout');

      if ($layout == '4') {
        get_template_part('pftpl4col-new-design');
    } else if ($layout == '2') {
        get_template_part('pftpl');
    } else {
        get_template_part('pftpl3col');
    }

?>

<div class="container container--with-m-fourty"><?php echo do_shortcode('[template id="25244"]');?></div>
<?php

    get_footer('new-design');
?>