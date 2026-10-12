<?php
/**
 * Строка про оферту и политику конфиденциальности под кнопкой оплаты.
 *
 * Оферта — страница «Условия» из WooCommerce → Настройки → Дополнительно,
 * политика — страница из Настройки → Конфиденциальность.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$terms_url   = wc_terms_and_conditions_page_id() ? get_permalink( wc_terms_and_conditions_page_id() ) : '';
$privacy_url = get_privacy_policy_url();

if ( ! $terms_url && ! $privacy_url ) {
	return;
}
?>
<p class="checkout-legal">
	<?php esc_html_e( 'Нажимая кнопку, вы принимаете условия', 'evgagarina' ); ?>
	<?php if ( $terms_url ) : ?>
		<a href="<?php echo esc_url( $terms_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'договора оферты', 'evgagarina' ); ?></a>
	<?php endif; ?>
	<?php if ( $terms_url && $privacy_url ) : ?>
		<?php esc_html_e( 'и', 'evgagarina' ); ?>
	<?php endif; ?>
	<?php if ( $privacy_url ) : ?>
		<a href="<?php echo esc_url( $privacy_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'политики конфиденциальности', 'evgagarina' ); ?></a>
	<?php endif; ?>.
</p>
