<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
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
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating   = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	
	<div id="comment-<?php comment_ID(); ?>" class="comment">

		<div class="avatar"><?php echo get_avatar( $comment, $size='70' ); ?></div>

		<div class="comment-des">

			<div class="comment-by">
				<?php printf( '<strong itemprop="author">%s</strong>', get_comment_author_link() ); ?>
				<span class="reply"> <?php
				if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
					if ( $verified )
						echo '<span style="color:#ccc">/ </span>(' . __('verified owner', 'woocommerce') . ') ';
					?></span>
					<span class="date">	<time itemprop="datePublished" time datetime="<?php echo get_comment_date('c'); ?>"><?php printf( __( '%1$s at %2$s', 'nevia' ), get_comment_date(), get_comment_time() ); ?></time></span>
				</div>

				<div itemprop="description">
					<?php if ( $comment->comment_approved == '0' ) : ?>

						<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'woocommerce' ); ?></em></p>

					<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<?php if ( $rating && get_option('woocommerce_enable_review_rating') == 'yes' ) : ?>
			<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'woocommerce' ), $rating ) ?>">
				<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?></span>
			</div>
		<?php endif; ?>
	</div>
	<div class="clearfix"></div>
</div>



