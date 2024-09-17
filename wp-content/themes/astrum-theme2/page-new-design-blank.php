<?php
/*
*
* Template Name: New design blank page
*
*/


get_header('new-design');

while ( have_posts() ) : the_post();
	get_template_part( 'content', 'page-new-design-blank' );
endwhile;  

get_footer('new-design'); ?>