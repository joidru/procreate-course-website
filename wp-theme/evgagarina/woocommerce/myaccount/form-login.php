<?php
/**
 * Кабинет для гостя: та же форма, что и в окне входа, только на странице.
 * Отправка идёт через AJAX темы (inc/auth.php), без JS — на wp-login.php.
 *
 * @package evgagarina
 * @version 9.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );
?>
<div class="result-wrap">
	<section class="result-card acc-login" aria-labelledby="acc-login-title">
		<p class="result-eyebrow"><?php esc_html_e( 'Личный кабинет', 'evgagarina' ); ?></p>
		<h1 id="acc-login-title" class="result-title"><?php esc_html_e( 'Вход', 'evgagarina' ); ?></h1>
		<p class="result-text"><?php esc_html_e( 'Войдите, чтобы открыть свои курсы, загрузки и заказы.', 'evgagarina' ); ?></p>
		<div class="result-actions">
			<a href="<?php echo esc_url( wp_login_url( evg_account_url() ) ); ?>" class="checkout-submit result-btn" data-auth-open><?php esc_html_e( 'Войти', 'evgagarina' ); ?></a>
		</div>
		<?php evg_yandex_login_button(); ?>
	</section>
</div>
<?php
do_action( 'woocommerce_after_customer_login_form' );
