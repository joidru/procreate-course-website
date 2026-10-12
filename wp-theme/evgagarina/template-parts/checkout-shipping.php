<?php
/**
 * Separate AJAX fragment; retain WooCommerce shipping markup for carrier plugins.
 *
 * @package evgagarina
 */
defined( 'ABSPATH' ) || exit;
?>
<div id="evg-checkout-shipping">
	<?php if ( WC()->cart && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<section class="checkout-panel checkout-delivery" aria-labelledby="shipping-heading">
			<h2 id="shipping-heading" class="checkout-heading"><?php esc_html_e( 'Способ доставки', 'evgagarina' ); ?></h2>
			<table class="checkout-shipping">
				<tbody>
					<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
					<?php wc_cart_totals_shipping_html(); ?>
					<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
				</tbody>
			</table>
		</section>
	<?php endif; ?>
</div>
