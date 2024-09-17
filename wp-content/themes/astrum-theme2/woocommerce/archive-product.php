<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$htype = ot_get_option('pp_header_menu');
get_header($htype);  ?>
<?php
$sliderstatus = ot_get_option( 'pp_shop_slider_on' );
if($sliderstatus == 'yes') {
    if (function_exists('icl_get_languages')) {
          $languages = icl_get_languages('skip_missing=0&orderby=code');
           if(!empty($languages)){
                foreach($languages as $l){
                    if(ICL_LANGUAGE_CODE == $l['language_code']) {
                    echo '<section class="slider">';    putRevSlider(ot_get_option( 'pp_shop_revo_slider'.$l['language_code'])); echo "</section>";
                    }
                }
           }
    } else {
        echo '<section class="slider">';    putRevSlider(ot_get_option( 'pp_shop_revo_slider' )); echo "</section>";
    }
} ?>
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

		<section id="titlebar" <?php if($sliderstatus == 'yes') { echo 'class="with_slider"'; } ?>>
			<!-- Container -->
			<div class="container">
				<div class="eight columns">
					<h2 class="page-title"><?php woocommerce_page_title(); ?></h2>
				</div>

				<div class="eight columns">
					<nav id="breadcrumbs">
						<?php  if(ot_get_option('pp_breadcrumbs') != 'no') woocommerce_breadcrumb(); ?>
					</nav>
				</div>
			</div>
			<!-- Container / End -->
		</section>

	<?php endif; ?>

<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');
		?>

		<!-- Container -->

			<?php do_action( 'woocommerce_archive_description' ); ?>

			<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
				?>
				<div class="clearfix"></div>
				<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>

			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>
<div class="clearfix"></div>
		<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
		?>


		<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action('woocommerce_sidebar');
		?>
</div>

		<?php get_footer('shop'); ?>
