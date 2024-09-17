<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$randID = rand(1, 99);
if ( $related_products ) : ?>



<div class="">
	<h3 class="headline"><?php _e('Related Products', 'woocommerce'); ?></h3>
	<span class="line" style="margin-bottom:0;"></span>
</div>

<!-- ShowBiz Carousel -->
<div class="showbiz-container recent-work">

	<!-- Navigation -->
	<div class="showbiz-navigation">
		<div id="showbiz_left_<?php echo $randID; ?>" class="sb-navigation-left"><i class="icon-angle-left"></i></div>
		<div id="showbiz_right_<?php echo $randID; ?>" class="sb-navigation-right"><i class="icon-angle-right"></i></div>
	</div>
	<div class="clearfix"></div>
	<div class="showbiz" data-left="#showbiz_left_<?php echo $randID; ?>" data-right="#showbiz_right_<?php echo $randID; ?>">
		<div class="overflowholder">
			<ul>
			<?php foreach ( $related_products as $related_product ) : ?>

				<?php
				 	$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>
		</ul>
	</div>
	<div class="clearfix"></div>

</div>
</div>
<?php endif;

wp_reset_postdata();
