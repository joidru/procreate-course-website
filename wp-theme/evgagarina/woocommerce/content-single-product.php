<?php
/**
 * Содержимое страницы товара.
 *
 * Первый экран: рубрика, название, краткое описание, цена и кнопка.
 * Факты — видимые атрибуты товара (до 4). Дальше — полное описание
 * (можно вставлять блоки программы), FAQ из поля «Вопросы и ответы»
 * и повторный блок покупки.
 *
 * @package evgagarina
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$evg_badge   = evg_product_discount_badge( $product );
$evg_buy_url = evg_product_buy_url( $product );
$evg_faq     = evg_product_faq( $product );
$evg_sale_to = $product->get_date_on_sale_to();
$evg_in_cart = WC()->cart && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) );
$evg_label   = $evg_in_cart ? __( 'Перейти к оформлению', 'evgagarina' ) : __( 'Записаться на курс', 'evgagarina' );
if ( ! $product->is_virtual() && $product->is_downloadable() ) {
	$evg_label = $evg_in_cart ? __( 'Перейти к оформлению', 'evgagarina' ) : __( 'Купить', 'evgagarina' );
}
if ( $evg_in_cart ) {
	$evg_buy_url = wc_get_checkout_url();
}

$evg_facts = array();
foreach ( $product->get_attributes() as $evg_attr ) {
	if ( ! $evg_attr->get_visible() || count( $evg_facts ) >= 4 ) {
		continue;
	}
	$evg_values = $evg_attr->is_taxonomy()
		? wc_get_product_terms( $product->get_id(), $evg_attr->get_name(), array( 'fields' => 'names' ) )
		: $evg_attr->get_options();
	$evg_facts[ wc_attribute_label( $evg_attr->get_name(), $product ) ] = implode( ', ', $evg_values );
}

$evg_price_block = static function () use ( $product, $evg_badge, $evg_sale_to ) {
	?>
	<div class="product-price">
		<p class="product-price__value"><?php echo evg_product_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php if ( $evg_badge ) : ?>
			<span class="course-badge"><?php echo esc_html( $evg_badge ); ?></span>
		<?php endif; ?>
	</div>
	<?php if ( $evg_sale_to ) : ?>
		<p class="product-price__note">
			<?php
			/* translators: %s: дата окончания скидки */
			printf( esc_html__( 'Скидка действует до %s включительно', 'evgagarina' ), esc_html( wc_format_datetime( $evg_sale_to, 'j F' ) ) );
			?>
		</p>
	<?php endif; ?>
	<?php
};
?>
<main id="content">
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'product-page', $product ); ?>>

		<section class="product-hero" aria-labelledby="product-title">
			<div class="product-hero__text">
				<p class="home-eyebrow"><?php echo esc_html( evg_product_kind( $product ) ); ?></p>
				<h1 id="product-title" class="product-title"><?php the_title(); ?></h1>
				<?php if ( $product->get_short_description() ) : ?>
					<div class="product-lead"><?php echo wp_kses_post( apply_filters( 'woocommerce_short_description', $product->get_short_description() ) ); ?></div>
				<?php endif; ?>

				<?php $evg_price_block(); ?>

				<div class="product-actions">
					<?php if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) : ?>
						<?php if ( $product->is_purchasable() && $product->is_in_stock() || $product->is_type( 'external' ) ) : ?>
							<a href="<?php echo esc_url( $evg_buy_url ); ?>" class="checkout-submit product-buy" rel="nofollow"><?php echo esc_html( $evg_label ); ?><?php echo evg_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<?php else : ?>
							<p class="checkout-hint"><?php esc_html_e( 'Набор на курс сейчас закрыт.', 'evgagarina' ); ?></p>
						<?php endif; ?>
					<?php else : ?>
						<?php woocommerce_template_single_add_to_cart(); ?>
					<?php endif; ?>
					<?php if ( $product->get_description() ) : ?>
						<a href="#program" class="result-btn-outline"><?php esc_html_e( 'Программа курса', 'evgagarina' ); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="product-hero__media">
				<?php
				echo $product->get_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'evg-cover',
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					)
				);
				?>
			</div>
		</section>

		<?php if ( $evg_facts ) : ?>
			<section class="product-facts-wrap" aria-label="<?php esc_attr_e( 'О курсе коротко', 'evgagarina' ); ?>">
				<dl class="product-facts">
					<?php foreach ( $evg_facts as $evg_name => $evg_value ) : ?>
						<div><dt><?php echo esc_html( $evg_name ); ?></dt><dd><?php echo esc_html( $evg_value ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
			</section>
		<?php endif; ?>

		<?php if ( $product->get_description() ) : ?>
			<section id="program" class="product-section">
				<div class="product-description prose">
					<?php the_content(); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $evg_faq ) : ?>
			<section class="product-section" aria-labelledby="faq-title">
				<h2 id="faq-title" class="product-h2"><?php esc_html_e( 'Частые вопросы', 'evgagarina' ); ?></h2>
				<div class="product-faq">
					<?php foreach ( $evg_faq as $evg_item ) : ?>
						<details class="faq-item">
							<summary><?php echo esc_html( $evg_item['q'] ); ?></summary>
							<p class="faq-answer"><?php echo esc_html( $evg_item['a'] ); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $product->is_purchasable() && $product->is_in_stock() && $product->is_type( 'simple' ) ) : ?>
			<section id="pricing" class="product-section" aria-labelledby="pricing-title">
				<div class="product-cta">
					<div>
						<h2 id="pricing-title" class="product-h2"><?php the_title(); ?></h2>
						<?php $evg_price_block(); ?>
					</div>
					<a href="<?php echo esc_url( $evg_buy_url ); ?>" class="checkout-submit product-buy" rel="nofollow"><?php echo esc_html( $evg_label ); ?></a>
				</div>
			</section>
		<?php endif; ?>

		<?php
		if ( isset( WC()->structured_data ) ) {
			WC()->structured_data->generate_product_data( $product );
		}
		?>
	</div>
</main>
<?php
do_action( 'woocommerce_after_single_product' );
