<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php $layout = ot_get_option('pp_woo_layout'); ?>
 <div class="container <?php if($layout == 'left-sidebar') { echo "page-left"; }?>">

    <?php if(is_shop() || is_product_category() || is_product_tag() ) { ?>
        <div class="twelve <?php if($layout == 'left-sidebar') { echo "alt2"; } else { echo "alt"; } ?> columns">
    <?php } else { ?>
        <div class="twelve <?php if($layout == 'left-sidebar') { echo "alt3"; } else { echo ""; } ?> columns">
    <?php } ?>




