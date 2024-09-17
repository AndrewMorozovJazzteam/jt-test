<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Nevia
 * @since Nevia 1.0
 */

$htype = ot_get_option('pp_header_menu');
get_header('new-design');
?>

<section id="titlebar" class="titlebar">
    <!-- Container -->
    <div class="container container-heading">
          <h1 class="headline-first headline-first--indent-strict"><?php printf( __( 'Search Results for: %s', 'purepress' ), '<span class="search-result" id="spanSearch">' . get_search_query($escaped = true) . '</span>' ); ?></h1>
	<div class="heading-breadcrumbs"><?php echo nd_breadcrumbs();?></div>	
</div>
<!-- Container / End -->
</section>

<!-- 960 Container / End -->

<!-- Content
    ================================================== -->
    <!-- Container -->
    <div class="container container--split">
        <main class="article-content">
            <?php if ( have_posts() ) :
            while ( have_posts() ) : the_post();
            /* Include the Post-Format-specific template for the content.
             * If you want to overload this in a child theme then include a file
             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
             */
            $type =  get_post_type();

            switch ($type) {
                case 'product' :

                    get_template_part( 'postformats/searchproduct' );

                break;
                case 'post':
                $format = get_post_format();
                $formatslist = array('status','aside','quote','audio','chat','link');
                if( false === $format  )  $format = 'standard';

                if (in_array($format, $formatslist))  $format = 'standard';
                $thumbstyle = ot_get_option('pp_blog_thumbs');
                if($thumbstyle == 'small') {
                    get_template_part( 'postformats/'.$format , 'medium' );
                } else {
                    get_template_part( 'postformats/'.$format );
                }
                break;

                case 'page':
                get_template_part( 'postformats/searchpage' );

                break;
                case 'portfolio':
                get_template_part( 'postformats/searchpf' );
                break;
                default:
                    # code...
                break;
            }

            endwhile;
            endif; ?>

            <?php if(function_exists('pagination')) {
                pagination();
            } else { ?>
            <?php if ( get_next_posts_link() ||  get_previous_posts_link() ) : ?>
            <nav class="pagination">
                <ul>
                    <?php if ( get_next_posts_link() ) : ?>
                    <li class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'purepress' ) ); ?></li>
                <?php endif; if ( get_previous_posts_link() ) : ?>
                <li class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'purepress' ) ); ?></li>
            <?php endif; ?>
        </ul>
        <div class="clearfix"></div>
    </nav>
<?php endif; ?>
<?php } ?>

</main>
<aside class="sidebar-dynamic-right sidebar-dynamic-right--mb-fourty">
<!-- Sidebar
    ================================================== -->
    <?php get_sidebar(); ?>
</aside>
</div>
<!-- Container / End -->
<?php get_footer('new-design'); ?>