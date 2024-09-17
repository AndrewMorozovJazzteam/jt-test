<?php
/**
 * Template Name: page old portfolio
 * 
 **/
$htype = ot_get_option('pp_header_menu');
get_header('new-design');?>

<!-- Titlebar
================================================== -->

<section id="titlebar" class="titlebar">
    <!-- Container -->
    <div class="container container-heading">
		<h1 class="headline-first headline-first--indent-strict">
		<?php $pf_title = get_post_meta($post->ID, 'pp_portfolio_title', true);
            $pp_subtitle = get_post_meta($post->ID, 'pp_subtitle', true);
            if($pf_title) { echo $pf_title;} else {  $pp_portfolio_page = ot_get_option('pp_portfolio_page');
                if (function_exists('icl_register_string')) {
                    icl_register_string('Portfolio page title','pp_portfolio_page', $pp_portfolio_page);
                    echo icl_t('Portfolio page title','pp_portfolio_page', $pp_portfolio_page); }
                else {
                    echo $pp_portfolio_page;
                } } ?>
            <?php if(!is_tax() && $pp_subtitle) { echo '<span>'.$pp_subtitle.'</span>'; } ?>
            <?php if(is_tax()) { $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );  if($term) echo '<span>/ '.$term->name.'</span>'; } ?>	
		</h1>
	<div class="heading-breadcrumbs"><?php echo nd_breadcrumbs();?></div>
    </div>
    <!-- Container / End -->
</section>


<!-- 960 Container / End -->
<?php

$layout = ot_get_option('pp_portfolio_layout');
if ($layout == '4') {
	get_template_part('pftpl4col');
} else {
	get_template_part('pftpl3col');
}

get_footer('new-design');

?>