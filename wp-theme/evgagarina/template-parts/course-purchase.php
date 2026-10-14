<?php
defined( 'ABSPATH' ) || exit;
global $product;
$url = evg_product_buy_url( $product );
$in_cart = WC()->cart && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) );
if ( $in_cart && $product->is_type( 'simple' ) ) {
	$url = wc_get_checkout_url();
}
?>
<div class="course-landing-purchase">
<?php if ( $product->is_type( 'external' ) || ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) ) : ?>
<a href="<?php echo esc_url( $url ); ?>" rel="nofollow" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-primary px-7 py-4 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90"><?php echo esc_html( $in_cart ? __( 'Перейти к оформлению', 'evgagarina' ) : __( 'Оплатить и получить доступ', 'evgagarina' ) ); ?><?php echo evg_icon( 'arrow-right', 16 ); ?></a>
<?php elseif ( ! $product->is_type( 'simple' ) ) : ?>
<?php woocommerce_template_single_add_to_cart(); ?>
<?php else : ?>
<p class="course-purchase-unavailable" role="status"><?php esc_html_e( 'Набор на курс сейчас закрыт.', 'evgagarina' ); ?></p>
<?php endif; ?>
</div>
