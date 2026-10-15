<?php
defined( 'ABSPATH' ) || exit;
global $product;
$url = evg_product_buy_url( $product );
$in_cart = WC()->cart && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) );
$already_purchased = false;
if ( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $already_purchased = wc_customer_bought_product(
        $user->user_email,
        $user->ID,
        $product->get_id()
    );
}
$courses_url = function_exists( 'evg_llms_courses_url' ) ? evg_llms_courses_url() : '';
if ( ! $courses_url ) {
    $courses_url = wc_get_page_permalink( 'myaccount' );
}
if ( $in_cart && $product->is_type( 'simple' ) ) {
	$url = wc_get_checkout_url();
}
?>
<div class="course-landing-purchase">
<?php if ( $already_purchased ) : ?>
<div class="course-purchase-owned" role="status">
    <div class="course-purchase-owned__heading"><?php echo evg_icon( 'check-circle', 20 ); ?><?php esc_html_e( 'Вы уже приобрели этот курс', 'evgagarina' ); ?></div>
    <p><?php esc_html_e( 'Материалы курса доступны в вашем личном кабинете.', 'evgagarina' ); ?></p>
</div>
<a href="<?php echo esc_url( $courses_url ); ?>" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full bg-primary px-7 py-4 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90"><?php esc_html_e( 'Перейти к обучению', 'evgagarina' ); ?><?php echo evg_icon( 'arrow-right', 16 ); ?></a>
<?php elseif ( $product->is_type( 'external' ) || ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) ) : ?>
<a href="<?php echo esc_url( $url ); ?>" rel="nofollow" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-primary px-7 py-4 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90"><?php echo esc_html( $in_cart ? __( 'Перейти к оформлению', 'evgagarina' ) : __( 'Оплатить и получить доступ', 'evgagarina' ) ); ?><?php echo evg_icon( 'arrow-right', 16 ); ?></a>
<?php elseif ( ! $product->is_type( 'simple' ) ) : ?>
<?php woocommerce_template_single_add_to_cart(); ?>
<?php else : ?>
<p class="course-purchase-unavailable" role="status"><?php esc_html_e( 'Набор на курс сейчас закрыт.', 'evgagarina' ); ?></p>
<?php endif; ?>
</div>
