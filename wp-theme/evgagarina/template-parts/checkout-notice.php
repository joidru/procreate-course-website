<?php
/**
 * Предупреждение про VPN под способами оплаты (текст — в Настройщике).
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$notice = evg_mod( 'evg_checkout_notice' );
if ( ! $notice ) {
	return;
}
?>
<div class="checkout-notice" role="note">
	<?php echo evg_icon( 'info', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<p><?php echo wp_kses( $notice, array( 'strong' => array(), 'a' => array( 'href' => true ) ) ); ?></p>
</div>
