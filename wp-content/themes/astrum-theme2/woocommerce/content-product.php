<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count

// Extra post classes
$classes = array();
$woocommerce_loop['loop']++;
if($woocommerce_loop['columns'] == 4) {
	$classes[] = 'three columns';
} elseif ($woocommerce_loop['columns'] == 3) {
	$classes[] = 'four columns';
} elseif ($woocommerce_loop['columns'] == 2) {
	$classes[] = 'six columns';
}
if($woocommerce_loop['columns'] !=99) {
	if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
		$classes[] = 'alpha';
	if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
		$classes[] = 'omega';
}
?>
<li <?php post_class( $classes ); ?>>
	<figure>
		<div class="mediaholder">
			<?php
				$thumb = get_post_thumbnail_id();
                $fullsize = wp_get_attachment_image_src($thumb, 'full');
			?>
			<a href="<?php the_permalink(); ?>"  title="<?php the_title(); ?>">
				<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
				<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>

			<?php
			$hover = get_post_meta($post->ID, 'pp_featured_hover', TRUE);
			if($hover) {
				$hoverid = pn_get_attachment_id_from_url($hover);
				$hoverimage = wp_get_attachment_image_src($hoverid, 'shop_catalog');
			} else {
			?>
			<div class="hovercover">
				<div class="hovericon"><i class="hoverlink"></i></div>
			</div>
			<?php } ?>
		</a>
	</div>
	<a href="<?php the_permalink(); ?>">
		<figcaption class="item-description">
			<h5><?php the_title(); ?></h5>

			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			do_action('astrum_wishlist');

			?>


			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</figcaption>
	</a>
</figure>
</li>