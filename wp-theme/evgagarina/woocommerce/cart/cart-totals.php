<?php
/**
 * Итоги корзины в виде .checkout-summary.
 *
 * @package evgagarina
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

$evg_savings = evg_cart_sale_savings();
?>
<aside class="cart_totals checkout-summary <?php echo WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : ''; ?>" aria-labelledby="cart-totals-heading">
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>
	<h2 id="cart-totals-heading" class="checkout-heading"><?php esc_html_e( 'Итого', 'evgagarina' ); ?></h2>

	<dl class="checkout-totals">
		<?php if ( $evg_savings > 0 ) : ?>
			<div><dt><?php esc_html_e( 'Стоимость', 'evgagarina' ); ?></dt><dd><?php echo wp_kses_post( wc_price( evg_cart_regular_total() ) ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Скидка', 'evgagarina' ); ?></dt><dd class="checkout-discount">−<?php echo wp_kses_post( wc_price( $evg_savings ) ); ?></dd></div>
		<?php else : ?>
			<div class="cart-subtotal"><dt><?php esc_html_e( 'Стоимость', 'evgagarina' ); ?></dt><dd><?php wc_cart_totals_subtotal_html(); ?></dd></div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>"><dt><?php wc_cart_totals_coupon_label( $coupon ); ?></dt><dd class="checkout-discount"><?php wc_cart_totals_coupon_html( $coupon ); ?></dd></div>
		<?php endforeach; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee"><dt><?php echo esc_html( $fee->name ); ?></dt><dd><?php wc_cart_totals_fee_html( $fee ); ?></dd></div>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
		<div class="checkout-total order-total"><dt><?php esc_html_e( 'Итого', 'evgagarina' ); ?></dt><dd><?php wc_cart_totals_order_total_html(); ?></dd></div>
		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</dl>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<table class="checkout-shipping"><?php wc_cart_totals_shipping_html(); ?></table>
	<?php endif; ?>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</aside>
