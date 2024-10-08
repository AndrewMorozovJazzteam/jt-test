<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
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
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form role="search" method="get" class="search-form" id="searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
    <button class="search-btn" type="submit"><i class="icon-search"></i></button>
    <input type="text" class="search" name="s" id="s" onblur="if(this.value=='')this.value='<?php _e('Search for products','purepress'); ?>';" onfocus="if(this.value=='<?php _e('Search for products','purepress'); ?>')this.value='';"  value="<?php esc_attr_e( 'Search for products', 'purepress' ); ?>" />
    <input type="hidden" name="post_type" value="product" />
</form>
<div class="clearfix"></div>
