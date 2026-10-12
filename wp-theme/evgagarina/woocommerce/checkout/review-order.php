<?php
/**
 * Состав заказа и итоги в правой колонке чекаута.
 *
 * Корневой элемент с классом .woocommerce-checkout-review-order-table
 * WooCommerce перерисовывает по AJAX при изменении корзины или купона.
 *
 * @package evgagarina
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

$evg_savings = evg_cart_sale_savings();
?>
<div class="woocommerce-checkout-review-order-table">
	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

	<ul class="checkout-items" role="list">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				continue;
			}
			$parent = wc_get_product( $_product->get_parent_id() ? $_product->get_parent_id() : $_product->get_id() );
			?>
			<li class="checkout-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				<?php echo $_product->get_image( 'evg-square', array( 'alt' => '', 'width' => 72, 'height' => 72 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="checkout-item-body">
					<p class="checkout-item-kind"><?php echo esc_html( evg_product_kind( $parent ? $parent : $_product ) ); ?></p>
					<p class="checkout-item-title"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></p>
					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<p class="checkout-item-qty">
						<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', '× ' . esc_html( $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="checkout-item-price"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<?php if ( wc_coupons_enabled() ) : ?>
		<div class="checkout-coupon">
			<button type="button" id="coupon-toggle" aria-expanded="false" aria-controls="coupon-panel"><?php esc_html_e( 'Есть купон?', 'evgagarina' ); ?> <span><?php esc_html_e( 'Ввести код', 'evgagarina' ); ?></span></button>
			<div id="coupon-panel" hidden>
				<label for="evg-coupon" class="sr-only"><?php esc_html_e( 'Код купона', 'evgagarina' ); ?></label>
				<input id="evg-coupon" type="text" autocomplete="off" placeholder="<?php esc_attr_e( 'Код купона', 'evgagarina' ); ?>">
				<button type="button" id="coupon-apply"><?php esc_html_e( 'Применить', 'evgagarina' ); ?></button>
			</div>
			<p id="coupon-status" class="checkout-hint" aria-live="polite"></p>
		</div>
	<?php endif; ?>

	<dl class="checkout-totals">
		<?php if ( $evg_savings > 0 ) : ?>
			<div><dt><?php esc_html_e( 'Стоимость', 'evgagarina' ); ?></dt><dd><?php echo wp_kses_post( wc_price( evg_cart_regular_total() ) ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Скидка', 'evgagarina' ); ?></dt><dd class="checkout-discount">−<?php echo wp_kses_post( wc_price( $evg_savings ) ); ?></dd></div>
		<?php else : ?>
			<div class="cart-subtotal"><dt><?php esc_html_e( 'Стоимость', 'evgagarina' ); ?></dt><dd><?php wc_cart_totals_subtotal_html(); ?></dd></div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<dt><?php wc_cart_totals_coupon_label( $coupon ); ?></dt>
				<dd class="checkout-discount"><?php wc_cart_totals_coupon_html( $coupon ); ?></dd>
			</div>
		<?php endforeach; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee"><dt><?php echo esc_html( $fee->name ); ?></dt><dd><?php wc_cart_totals_fee_html( $fee ); ?></dd></div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>"><dt><?php echo esc_html( $tax->label ); ?></dt><dd><?php echo wp_kses_post( $tax->formatted_amount ); ?></dd></div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="tax-total"><dt><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></dt><dd><?php wc_cart_totals_taxes_total_html(); ?></dd></div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		<div class="checkout-total order-total"><dt><?php esc_html_e( 'Итого', 'evgagarina' ); ?></dt><dd><?php wc_cart_totals_order_total_html(); ?></dd></div>
		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</dl>

</div>
