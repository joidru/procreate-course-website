<?php
/**
 * Кнопка «Перейти к оформлению».
 *
 * @package evgagarina
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
?>
<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-submit checkout-button wc-forward"><?php esc_html_e( 'Перейти к оформлению', 'evgagarina' ); ?></a>
