<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Nevia
 * @since Nevia 1.0
 */
?>
<!-- Titlebar
    ================================================== -->
    <?php if( !is_front_page() ) { ?>
    <section id="titlebar" class="titlebar">
        <!-- Container -->
        <div class="container container-heading">
		<h1 class="headline-first headline-first--indent-strict"><?php the_title(); ?><?php $subtitle = get_post_meta($post->ID, 'pp_subtitle', true); if($subtitle)  echo "<span>".$subtitle."</span>";?><?php edit_post_link( __( 'Edit', 'purepress' ), '', '', 0, 'link edit-link' ); ?></h1>
        	<div class="heading-breadcrumbs"><?php echo nd_breadcrumbs();?></div>
	</div>
        <!-- Container / End -->
    </section>
    <?php } ?>
<!-- Content
    ================================================== -->

    <!-- Container -->
    <div  id="post-<?php the_ID(); ?>" <?php post_class('container container--with-m-fourty'); ?> >
        <?php
        global $post;
        if ( has_shortcode( $post->post_content, 'template' ) ) {  $tmp_flag = true; }
        ?>
        <?php if(!isset($tmp_flag)) { ?><div class="sixteen columns"> <?php } ?>
        <?php the_content(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'purepress' ), 'after' => '</div>' ) ); ?>
        <?php if(!isset($tmp_flag)) { ?></div> <?php } ?>

 </div>

