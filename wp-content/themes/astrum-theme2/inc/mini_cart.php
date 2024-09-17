    <?php global $woocommerce; ?>

    <div id="astrum_header_cart">
        <div class="cart_contents">
            <i class="icon-shopping-cart"></i> <a class="cart-contents" title="<?php _e('View your shopping cart', 'woothemes'); ?>"> <?php echo WC()->cart->get_cart_subtotal(); ?></a>
        </div>
        <div class="cart_products">
            <ul>
                <?php
                if (sizeof( WC()->cart->get_cart() ) > 0) :
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                    $product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
                    $thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                    $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

                    ?>
                    <li class="cart_list_product">
                        <a href="<?php echo get_permalink( $product_id ); ?>">
                            <?php echo $thumbnail . $product_name; ?>
                        </a>

                        <?php echo WC()->cart->get_item_data( $cart_item ); ?>

                        <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                    </li>
                    <?php
                            }
                        }
                    ?>

                <?php else : ?>

                    <li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

                <?php endif; ?>



                </ul>

                <p class="buttons">
                    <a href="<?php echo WC()->cart->get_cart_url(); ?>" class="button gray"><?php _e( 'View Cart &rarr;', 'woocommerce' ); ?></a>
                    <a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button color checkout"><?php _e( 'Checkout &rarr;', 'woocommerce' ); ?></a>
                </p>

            </div>
    </div>