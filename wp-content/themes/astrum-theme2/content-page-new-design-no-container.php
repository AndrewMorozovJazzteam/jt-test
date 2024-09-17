<?php
/**
 * The template used for displaying page content in page.php
 **/
?>
<section id="titlebar" class="titlebar">
    <!-- Container -->
    <div class="container container-heading">
	<h1 class="headline-first headline-first--indent-strict"><?php the_title(); ?><?php $subtitle = get_post_meta($post->ID, 'pp_subtitle', true); if($subtitle)  echo "<span>".$subtitle."</span>";?><?php edit_post_link( __( 'Edit', 'purepress' ), '', '', 0, 'link edit-link' ); ?></h1>
	<div class="heading-breadcrumbs"><?php echo nd_breadcrumbs();?></div>
    </div>
    <!-- Container / End -->
</section>
<main class="article-content">
	  <?php the_content(); ?>
</main>





