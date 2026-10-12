<?php
/**
 * Адреса в кабинете.
 *
 * @package evgagarina
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();
$get_addresses = apply_filters(
	'woocommerce_my_account_get_addresses',
	! wc_ship_to_billing_address_only() && wc_shipping_enabled()
		? array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		)
		: array( 'billing' => __( 'Billing address', 'woocommerce' ) ),
	$customer_id
);
?>
<section aria-labelledby="h-address">
	<h2 id="h-address" class="acc-h2"><?php esc_html_e( 'Адрес', 'evgagarina' ); ?></h2>

	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
		<?php $address = wc_get_account_formatted_address( $name ); ?>
		<div class="acc-box<?php echo $address ? '' : ' acc-empty'; ?>">
			<?php if ( count( $get_addresses ) > 1 ) : ?>
				<p class="acc-order-number"><?php echo esc_html( $address_title ); ?></p>
			<?php endif; ?>
			<?php if ( $address ) : ?>
				<address class="acc-address"><?php echo wp_kses_post( $address ); ?></address>
				<?php do_action( 'woocommerce_my_account_after_my_address', $name ); ?>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="acc-link"><?php esc_html_e( 'Изменить адрес', 'evgagarina' ); ?></a>
			<?php else : ?>
				<p><?php esc_html_e( 'Платёжный адрес пока не указан.', 'evgagarina' ); ?></p>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="acc-link"><?php esc_html_e( 'Добавить адрес', 'evgagarina' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</section>
