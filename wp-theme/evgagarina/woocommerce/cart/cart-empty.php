<?php
/**
 * Пустая корзина.
 *
 * @package evgagarina
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="result-wrap">
	<section class="result-card" aria-labelledby="cart-empty-title">
		<?php do_action( 'woocommerce_cart_is_empty' ); ?>
		<p class="result-eyebrow"><?php esc_html_e( 'Корзина', 'evgagarina' ); ?></p>
		<h1 id="cart-empty-title" class="result-title"><?php esc_html_e( 'Здесь пока пусто', 'evgagarina' ); ?></h1>
		<p class="result-text"><?php esc_html_e( 'Выберите курс или набор кистей в каталоге — оформление займёт минуту.', 'evgagarina' ); ?></p>
		<div class="result-actions">
			<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="checkout-submit result-btn"><?php esc_html_e( 'Перейти к каталогу', 'evgagarina' ); ?></a>
		</div>
	</section>
</div>
