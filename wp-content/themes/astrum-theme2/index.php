<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nevia
 * @since Nevia 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header('new-design');?>

<?php get_template_part('loop'); ?>

<?php get_footer('new-design'); ?>