<?php
/**
 * Редактирование адреса.
 *
 * @package evgagarina
 * @version 9.3.0
 *
 * @var string $load_address
 * @var array  $address
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? __( 'Платёжный адрес', 'evgagarina' ) : __( 'Адрес доставки', 'evgagarina' );

do_action( 'woocommerce_before_edit_account_address_form' );
?>
<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>
	<section aria-labelledby="h-address-edit">
		<h2 id="h-address-edit" class="acc-h2"><?php echo esc_html( apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) ); ?></h2>
		<form method="post" class="acc-box acc-form" novalidate>
			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>
			<div class="woocommerce-address-fields__field-wrapper">
				<?php
				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				?>
			</div>
			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>
			<button type="submit" class="checkout-submit acc-btn" name="save_address" value="<?php esc_attr_e( 'Сохранить адрес', 'evgagarina' ); ?>"><?php esc_html_e( 'Сохранить адрес', 'evgagarina' ); ?></button>
			<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
			<input type="hidden" name="action" value="edit_address">
		</form>
	</section>
<?php endif; ?>
<?php
do_action( 'woocommerce_after_edit_account_address_form' );
