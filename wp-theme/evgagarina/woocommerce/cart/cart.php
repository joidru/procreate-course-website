<?php
/**
 * Корзина в стиле чекаута. Обычно покупатель её не видит —
 * «Купить» ведёт сразу на оформление, — но страница должна работать.
 *
 * @package evgagarina
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>
<div class="checkout-wrap">
	<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="checkout-back"><?php echo evg_icon( 'arrow-left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Вернуться к каталогу', 'evgagarina' ); ?></a>
	<h1 class="checkout-title"><?php esc_html_e( 'Корзина', 'evgagarina' ); ?></h1>

	<div class="checkout-grid">
		<form class="woocommerce-cart-form checkout-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>
			<section class="checkout-panel" aria-labelledby="cart-heading">
				<h2 id="cart-heading" class="checkout-heading"><?php esc_html_e( 'Товары', 'evgagarina' ); ?></h2>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				<ul class="checkout-items shop_table cart woocommerce-cart-form__contents" role="list">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							continue;
						}
						$permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						?>
						<li class="checkout-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<?php echo $_product->get_image( 'evg-square', array( 'alt' => '', 'width' => 72, 'height' => 72 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<div class="checkout-item-body">
								<p class="checkout-item-kind"><?php echo esc_html( evg_product_kind( wc_get_product( $_product->get_parent_id() ? $_product->get_parent_id() : $_product->get_id() ) ) ); ?></p>
								<p class="checkout-item-title">
									<?php echo $permalink ? '<a href="' . esc_url( $permalink ) . '">' . wp_kses_post( $name ) . '</a>' : wp_kses_post( $name ); ?>
								</p>
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<p class="checkout-item-qty">
									<?php
									if ( $_product->is_sold_individually() ) {
										echo '× 1';
										printf( '<input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr( $cart_item_key ) );
									} else {
										woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $name,
											),
											$_product
										);
									}
									?>
									<span class="checkout-item-price"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</p>
							</div>
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove checkout-item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									/* translators: %s: название товара */
									esc_attr( sprintf( __( 'Удалить «%s» из корзины', 'evgagarina' ), wp_strip_all_tags( $name ) ) ),
									esc_attr( $_product->get_id() ),
									esc_attr( $_product->get_sku() ),
									evg_icon( 'x', 16 )
								),
								$cart_item_key
							);
							?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<div class="cart-actions">
					<?php if ( wc_coupons_enabled() ) : ?>
						<div class="checkout-coupon">
							<label for="coupon_code" class="sr-only"><?php esc_html_e( 'Код купона', 'evgagarina' ); ?></label>
							<div class="checkout-coupon-row">
								<input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Код купона', 'evgagarina' ); ?>">
								<button type="submit" name="apply_coupon" value="<?php esc_attr_e( 'Применить', 'evgagarina' ); ?>"><?php esc_html_e( 'Применить', 'evgagarina' ); ?></button>
							</div>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php endif; ?>
					<button type="submit" class="result-btn-outline" name="update_cart" value="<?php esc_attr_e( 'Обновить корзину', 'evgagarina' ); ?>"><?php esc_html_e( 'Обновить корзину', 'evgagarina' ); ?></button>
					<?php do_action( 'woocommerce_cart_actions' ); ?>
					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>
				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</section>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>

		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
		<div class="cart-collaterals">
			<?php woocommerce_cart_totals(); ?>
		</div>
	</div>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>
