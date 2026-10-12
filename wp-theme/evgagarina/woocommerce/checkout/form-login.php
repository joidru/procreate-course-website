<?php
/**
 * «Уже покупали?» на чекауте — открывает окно входа темы.
 *
 * @package evgagarina
 * @version 9.9.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}
?>
<div class="checkout-wrap checkout-login-reminder">
	<p class="checkout-notice" role="note">
		<?php echo evg_icon( 'user', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span>
			<?php esc_html_e( 'Уже покупали у нас?', 'evgagarina' ); ?>
			<a href="<?php echo esc_url( wp_login_url( wc_get_checkout_url() ) ); ?>" data-auth-open><?php esc_html_e( 'Войдите', 'evgagarina' ); ?></a>,
			<?php esc_html_e( 'чтобы покупка появилась в личном кабинете.', 'evgagarina' ); ?>
		</span>
	</p>
</div>
