<?php
/**
 * Загрузки: кисти, палитры, исходники.
 *
 * @package evgagarina
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads );
?>
<section aria-labelledby="h-downloads">
	<h2 id="h-downloads" class="acc-h2"><?php esc_html_e( 'Загрузки', 'evgagarina' ); ?></h2>

	<?php if ( $has_downloads ) : ?>
		<?php do_action( 'woocommerce_before_available_downloads' ); ?>
		<ul class="acc-downloads" role="list">
			<?php foreach ( $downloads as $download ) : ?>
				<li class="acc-box acc-download">
					<div class="acc-download-body">
						<p class="acc-download-product">
							<?php if ( $download['product_url'] ) : ?>
								<a href="<?php echo esc_url( $download['product_url'] ); ?>"><?php echo esc_html( $download['product_name'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $download['product_name'] ); ?>
							<?php endif; ?>
						</p>
						<p class="acc-download-file"><?php echo esc_html( $download['download_name'] ); ?></p>
						<p class="acc-muted">
							<?php
							if ( ! empty( $download['access_expires'] ) ) {
								/* translators: %s: дата окончания доступа */
								printf( esc_html__( 'Доступно до %s', 'evgagarina' ), esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ) );
							} else {
								esc_html_e( 'Бессрочный доступ', 'evgagarina' );
							}
							if ( is_numeric( $download['downloads_remaining'] ) ) {
								/* translators: %s: оставшееся число скачиваний */
								echo ' · ' . esc_html( sprintf( __( 'осталось скачиваний: %s', 'evgagarina' ), $download['downloads_remaining'] ) );
							}
							?>
						</p>
					</div>
					<a href="<?php echo esc_url( $download['download_url'] ); ?>" class="checkout-submit acc-btn"><?php echo evg_icon( 'download', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Скачать', 'evgagarina' ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php do_action( 'woocommerce_after_available_downloads' ); ?>
	<?php else : ?>
		<div class="acc-box acc-empty">
			<p><?php esc_html_e( 'Кисти, палитры и исходники из купленных курсов появятся здесь.', 'evgagarina' ); ?></p>
			<a href="<?php echo esc_url( evg_section_url( 'courses' ) ); ?>" class="acc-link"><?php esc_html_e( 'Смотреть каталог', 'evgagarina' ); ?></a>
		</div>
	<?php endif; ?>
</section>
<?php
do_action( 'woocommerce_after_account_downloads', $has_downloads );
