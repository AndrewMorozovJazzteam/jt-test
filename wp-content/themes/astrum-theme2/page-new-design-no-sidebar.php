<?php
/*
*
* Template Name: New design without sidebar
* Template Post Type: post, page, test-pages
*/


get_header('new-design');

while ( have_posts() ) : the_post();
	get_template_part( 'content', 'page-new-design-no-sidebar' );
endwhile;  

get_footer('new-design'); ?>