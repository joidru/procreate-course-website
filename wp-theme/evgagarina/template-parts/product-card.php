<?php
/**
 * Карточка товара (курса / набора кистей) для каталога.
 *
 * @package evgagarina
 *
 * @var array $args { product: WC_Product }
 */

defined( 'ABSPATH' ) || exit;

$product = isset( $args['product'] ) ? $args['product'] : null;
if ( ! $product instanceof WC_Product ) {
	return;
}

$terms = get_the_terms( $product->get_id(), 'product_cat' );
$terms = is_array( $terms ) ? $terms : array();
$kinds = wp_list_pluck( $terms, 'slug' );
$kind  = $terms ? $terms[0]->name : __( 'Курс', 'evgagarina' );
$note  = get_post_meta( $product->get_id(), '_evg_card_note', true );
$badge = evg_product_discount_badge( $product );
$text  = $product->get_short_description() ? wp_strip_all_tags( $product->get_short_description() ) : wp_trim_words( wp_strip_all_tags( $product->get_description() ), 14 );

$external   = $product->is_type( 'external' ) && $product->get_product_url();
$link       = $external ? $product->get_product_url() : $product->get_permalink();
$link_attrs = $external ? ' target="_blank" rel="noopener"' : '';
?>
<li data-kind="<?php echo esc_attr( implode( ' ', $kinds ) ); ?>">
	<a class="course-card" href="<?php echo esc_url( $link ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="course-media">
			<?php
			if ( $product->get_image_id() ) {
				echo wp_get_attachment_image(
					$product->get_image_id(),
					'medium',
					false,
					array(
						'loading' => 'lazy',
						/* translators: %s: название товара */
						'alt'     => sprintf( __( 'Обложка «%s»', 'evgagarina' ), $product->get_name() ),
					)
				);
			} else {
				echo wc_placeholder_img( 'evg-square' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php if ( $badge ) : ?>
				<span class="course-badge"><?php echo esc_html( $badge ); ?></span>
			<?php endif; ?>
		</div>
		<div class="course-body">
			<p class="course-kind"><?php echo esc_html( $note ? $kind . ' · ' . $note : $kind ); ?></p>
			<h3 class="course-title"><?php echo esc_html( $product->get_name() ); ?></h3>
			<?php if ( $text ) : ?>
				<p class="course-text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<p class="course-price"><?php echo evg_product_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
	</a>
</li>
