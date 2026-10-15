<?php
/**
 * Консоль кабинета.
 *
 * @package evgagarina
 * @version 4.4.0
 *
 * @var WP_User $current_user
 */

defined( 'ABSPATH' ) || exit;

$evg_links = array(
	'orders'       => __( 'последние заказы', 'evgagarina' ),
	'edit-address' => __( 'адрес', 'evgagarina' ),
	'edit-account' => __( 'данные анкеты', 'evgagarina' ),
);
$evg_courses_url = function_exists( 'evg_llms_courses_url' ) ? evg_llms_courses_url() : '';
?>
<section aria-labelledby="h-dashboard">
	<h2 id="h-dashboard" class="acc-h2"><?php esc_html_e( 'Консоль', 'evgagarina' ); ?></h2>
	<div class="acc-box">
		<p>
			<?php
			printf(
				/* translators: %s: имя пользователя */
				esc_html__( 'Добро пожаловать, %s!', 'evgagarina' ),
				'<strong>' . esc_html( $current_user->first_name ? $current_user->first_name : $current_user->display_name ) . '</strong>'
			);
			?>
			<?php esc_html_e( 'Здесь можно посмотреть', 'evgagarina' ); ?>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>"><?php echo esc_html( $evg_links['orders'] ); ?></a><?php if ( $evg_courses_url ) : ?>, <?php esc_html_e( 'открыть', 'evgagarina' ); ?> <a href="<?php echo esc_url( $evg_courses_url ); ?>"><?php esc_html_e( 'свои курсы', 'evgagarina' ); ?></a><?php endif; ?>,
			<?php esc_html_e( 'изменить', 'evgagarina' ); ?>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>"><?php echo esc_html( $evg_links['edit-address'] ); ?></a>
			<?php esc_html_e( 'и', 'evgagarina' ); ?>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>"><?php echo esc_html( $evg_links['edit-account'] ); ?></a>.
		</p>
	</div>
	<?php
	do_action( 'woocommerce_account_dashboard' );
	do_action( 'woocommerce_before_my_account' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
	do_action( 'woocommerce_after_my_account' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
	?>
</section>
