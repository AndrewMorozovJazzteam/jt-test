<?php
/*
*
* Template Name: New design main page
*
*/


get_header('new-design');

while ( have_posts() ) : the_post();
	get_template_part( 'content', 'page-new-design' );
endwhile;  

get_footer('new-design'); ?>