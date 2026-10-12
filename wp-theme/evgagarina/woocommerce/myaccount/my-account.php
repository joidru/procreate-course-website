<?php
/**
 * Обёртка личного кабинета (из account.html): приветствие, меню и раздел.
 *
 * @package evgagarina
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$evg_user = wp_get_current_user();
$evg_name = $evg_user->first_name ? $evg_user->first_name : $evg_user->display_name;
?>
<div class="blog-wrap acc-wrap">
	<section class="acc-hero" aria-labelledby="acc-title">
		<span class="acc-avatar acc-avatar-lg" aria-hidden="true"><?php echo esc_html( evg_user_initial( $evg_user ) ); ?></span>
		<div>
			<p class="blog-eyebrow"><?php esc_html_e( 'Личный кабинет', 'evgagarina' ); ?></p>
			<h1 id="acc-title" class="acc-title"><?php esc_html_e( 'Привет,', 'evgagarina' ); ?> <span class="blog-title-accent"><?php echo esc_html( $evg_name ); ?></span></h1>
		</div>
	</section>

	<div class="acc-layout">
		<?php do_action( 'woocommerce_account_navigation' ); ?>

		<div class="acc-content woocommerce-MyAccount-content">
			<?php woocommerce_output_all_notices(); ?>
			<?php do_action( 'woocommerce_account_content' ); ?>
		</div>
	</div>
</div>
