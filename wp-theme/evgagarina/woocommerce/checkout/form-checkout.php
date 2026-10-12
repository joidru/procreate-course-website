<?php
/**
 * Оформление заказа (из checkout.html).
 *
 * Левая колонка — контакты и способ оплаты, правая — состав заказа,
 * купон, итоги и кнопка. Всё внутри одной формы checkout, поэтому
 * стандартный checkout.js WooCommerce работает без изменений.
 *
 * @package evgagarina
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Подтвердить заказ', 'evgagarina' ) );
?>
<div class="checkout-wrap">
	<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="checkout-back"><?php echo evg_icon( 'arrow-left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Вернуться к каталогу', 'evgagarina' ); ?></a>

	<h1 class="checkout-title"><?php esc_html_e( 'Оформление заказа', 'evgagarina' ); ?></h1>

	<form name="checkout" method="post" class="checkout woocommerce-checkout checkout-grid" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php esc_attr_e( 'Оформление заказа', 'evgagarina' ); ?>" novalidate>

		<div class="checkout-form">
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<section class="checkout-panel" id="customer_details" aria-labelledby="contact-heading">
					<h2 id="contact-heading" class="checkout-heading"><?php esc_html_e( 'Контактные данные', 'evgagarina' ); ?></h2>
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</section>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/checkout-shipping' ); ?>

			<?php if ( WC()->cart->needs_payment() ) : ?>
				<section class="checkout-panel" aria-labelledby="payment-heading">
					<h2 id="payment-heading" class="checkout-heading"><?php esc_html_e( 'Способ оплаты', 'evgagarina' ); ?></h2>
					<?php woocommerce_checkout_payment(); ?>
					<?php get_template_part( 'template-parts/checkout-notice' ); ?>
				</section>
			<?php else : ?>
				<?php woocommerce_checkout_payment(); ?>
			<?php endif; ?>
		</div>

		<aside class="checkout-summary" aria-labelledby="order_review_heading">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<h2 id="order_review_heading" class="checkout-heading"><?php esc_html_e( 'Ваш заказ', 'evgagarina' ); ?></h2>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			<?php wc_get_template( 'checkout/terms.php' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<?php
			echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'woocommerce_order_button_html',
				'<button type="submit" class="checkout-submit button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>'
			);
			?>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

			<?php get_template_part( 'template-parts/checkout-legal' ); ?>
		</aside>
	</form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
