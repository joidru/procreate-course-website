<?php
/**
 * Заказы в кабинете — карточки вместо таблицы.
 *
 * @package evgagarina
 * @version 9.5.0
 *
 * @var bool   $has_orders
 * @var object $customer_orders
 * @var int    $current_page
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );
?>
<section aria-labelledby="h-orders">
	<h2 id="h-orders" class="acc-h2"><?php esc_html_e( 'Заказы', 'evgagarina' ); ?></h2>

	<?php if ( $has_orders ) : ?>
		<ul class="acc-orders" role="list">
			<?php
			foreach ( $customer_orders->orders as $customer_order ) :
				$order = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				if ( ! $order ) {
					continue;
				}
				$evg_names = array();
				foreach ( $order->get_items() as $evg_item ) {
					$evg_names[] = $evg_item->get_name();
				}
				$evg_actions = wc_get_account_orders_actions( $order );
				?>
				<li class="acc-box acc-order">
					<div class="acc-order-head">
						<p class="acc-order-number">
							<?php
							/* translators: %s: номер заказа */
							printf( esc_html__( 'Заказ №%s', 'evgagarina' ), esc_html( $order->get_order_number() ) );
							?>
						</p>
						<span class="acc-status acc-status-<?php echo esc_attr( $order->get_status() ); ?>"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
					</div>
					<p class="acc-order-items"><?php echo esc_html( implode( ', ', $evg_names ) ); ?></p>
					<dl class="acc-facts">
						<div><dt><?php esc_html_e( 'Дата', 'evgagarina' ); ?></dt><dd><time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time></dd></div>
						<div><dt><?php esc_html_e( 'Сумма', 'evgagarina' ); ?></dt><dd><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></dd></div>
					</dl>
					<?php if ( $evg_actions ) : ?>
						<div class="acc-order-actions">
							<?php foreach ( $evg_actions as $evg_key => $evg_action ) : ?>
								<a href="<?php echo esc_url( $evg_action['url'] ); ?>" class="<?php echo 'pay' === $evg_key ? 'checkout-submit acc-btn' : 'acc-link'; ?> <?php echo esc_attr( sanitize_html_class( $evg_key ) ); ?>"><?php echo esc_html( $evg_action['name'] ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

		<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
			<nav class="acc-pagination" aria-label="<?php esc_attr_e( 'Страницы заказов', 'evgagarina' ); ?>">
				<?php if ( 1 !== $current_page ) : ?>
					<a class="acc-link" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Новее', 'evgagarina' ); ?></a>
				<?php endif; ?>
				<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
					<a class="acc-link" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Старше', 'evgagarina' ); ?></a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<div class="acc-box acc-empty">
			<p><?php esc_html_e( 'Здесь будут ваши заказы и чеки об оплате.', 'evgagarina' ); ?></p>
			<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="acc-link"><?php esc_html_e( 'Смотреть курсы', 'evgagarina' ); ?></a>
		</div>
	<?php endif; ?>
</section>
<?php
do_action( 'woocommerce_after_account_orders', $has_orders );
