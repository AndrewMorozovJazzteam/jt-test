<?php
/*
*
* Template Name: New design no container
*
*/


get_header('new-design');

while ( have_posts() ) : the_post();
	get_template_part( 'content', 'page-new-design-no-container' );
endwhile;  

get_footer('new-design'); ?>