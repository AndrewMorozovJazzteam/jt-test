<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nevia
 * @since Nevia 1.0
 */
$htype = ot_get_option('pp_header_menu');
get_header('new-design');
get_template_part('loop');
get_footer('new-design'); ?>
