<?php
/**
 * Меню кабинета. Пункты LifterLMS («Мои курсы», «Мои сертификаты»,
 * «Уведомления») добавляются фильтром woocommerce_account_menu_items
 * из inc/lifterlms.php и выглядят так же, как пункты WooCommerce.
 *
 * @package evgagarina
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );
?>
<nav class="acc-nav woocommerce-MyAccount-navigation" aria-label="<?php esc_attr_e( 'Разделы кабинета', 'evgagarina' ); ?>">
	<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
		<?php
		$evg_current = wc_is_current_account_menu_item( $endpoint );
		$evg_classes = wc_get_account_menu_item_classes( $endpoint );
		if ( 'customer-logout' === $endpoint ) {
			$evg_classes .= ' acc-nav-logout';
		}
		?>
		<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="<?php echo esc_attr( $evg_classes ); ?>"<?php echo $evg_current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
	<?php endforeach; ?>
</nav>
<?php
do_action( 'woocommerce_after_account_navigation' );
