<?php
/**
 * Один способ оплаты в виде карточки .checkout-method.
 *
 * @package evgagarina
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$evg_desc = $gateway->get_description();
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<label class="checkout-method" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>">
		<span class="checkout-method-body">
			<span class="checkout-method-title"><?php echo wp_kses_post( $gateway->get_title() ); ?></span>
			<?php if ( $evg_desc && ! $gateway->has_fields() ) : ?>
				<span class="checkout-method-text"><?php echo wp_kses_post( wp_strip_all_tags( $evg_desc ) ); ?></span>
			<?php endif; ?>
		</span>
		<span class="checkout-method-icon"><?php echo $gateway->get_icon() ? wp_kses_post( $gateway->get_icon() ) : evg_icon( 'card', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</label>
	<?php if ( $gateway->has_fields() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php echo ! $gateway->chosen ? 'style="display:none;"' : ''; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
