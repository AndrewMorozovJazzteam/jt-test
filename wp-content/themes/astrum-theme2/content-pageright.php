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
        <div class="container container-heading">
		<h1 class="headline-first headline-first--indent-strict"><?php the_title(); ?><?php $subtitle = get_post_meta($post->ID, 'pp_subtitle', true); if($subtitle)  echo "<span>".$subtitle."</span>";?><?php edit_post_link( __( 'Edit', 'purepress' ), '', '', 0, 'link edit-link' ); ?><? if ( is_user_logged_in() ) {  echo ' <a class="arrow-case-link" href="'.get_post_meta($post->ID, 'link_case', 1).'"></a>';  } ?></h1>
        	<div class="heading-breadcrumbs"><?php echo nd_breadcrumbs();?></div>
	</div>
        <!-- Container / End -->
    </section>
<!-- Content
    ================================================== -->



    <!-- Container --><?php global $post;?>
    <div  id="post-<?php the_ID(); ?>" <?php post_class('container container--split');  echo get_post_meta($post->ID, "pp_sidebar_set", $single = true) === 'history-sidebar' ? ' data-sticky-sidebar' : ''; ?> >
        <main class="article-content" >
			<div class="project-content-x">
				<?php the_content(); ?>
			</div>
            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'purepress' ), 'after' => '</div>' ) ); ?>
              
			
        </main>
          <aside class="sidebar-static-right">
			  <?php  get_sidebar(); ?>
		  </aside>
    </div>
    <!-- Page Content / End -->



