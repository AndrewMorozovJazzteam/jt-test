<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns',  ot_get_option('pp_woocolumns',3) );
}


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
<li <?php  wc_product_cat_class( $classes ); ?>>
<figure>
	<div class="mediaholder">
		<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
		<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
			<?php
				/**
				 * woocommerce_before_subcategory_title hook
				 *
				 * @hooked woocommerce_subcategory_thumbnail - 10
				 */
				do_action( 'woocommerce_before_subcategory_title', $category );
			?>
		</a>
	</div>
	<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
		<figcaption class="item-description">
			<h5>
				<?php
					echo $category->name;
					if ( $category->count > 0 )
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
				?>
			</h5>
			<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
			?>
			<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
		</figcaption>
	</a>
</figure>
</li>