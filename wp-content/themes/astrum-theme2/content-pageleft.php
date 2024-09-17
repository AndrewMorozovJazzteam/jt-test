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
    <section id="titlebar" class="titlebar">
        <!-- Container -->
        <div class="container">

            <div class="eight columns">

                <h1><?php the_title(); ?><?php $subtitle = get_post_meta($post->ID, 'pp_subtitle', true); if($subtitle)  echo "<span>".$subtitle."</span>";?><?php edit_post_link( __( 'Edit', 'purepress' ), '<span class="edit-link">', '</span>' ); ?></h1>
            </div>
            <div class="eight columns">
                <?php if(ot_get_option('pp_breadcrumbs') != 'no') echo dimox_breadcrumbs(); ?>

            </div>

        </div>
        <!-- Container / End -->
    </section>
<!-- Content
    ================================================== -->

    <!-- Container -->
    <div  id="post-<?php the_ID(); ?>" <?php post_class('container page-left'); ?> >

        <div class="twelve alt2 columns" >
            <?php the_content(); ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'purepress' ), 'after' => '</div>' ) ); ?>
              
        </div>
    <?php  get_sidebar(); ?>
    </div>
    <!-- Page Content / End -->