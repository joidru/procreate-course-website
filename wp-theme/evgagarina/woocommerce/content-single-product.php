<?php
/**
 * Лендинг курса: исходная разметка basics.html с данными WooCommerce.
 * @package evgagarina
 */
defined( 'ABSPATH' ) || exit;
global $product;
do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
$facts = array();
foreach ( $product->get_attributes() as $attribute ) {
	if ( ! $attribute->get_visible() || count( $facts ) >= 4 ) {
		continue;
	}
	$values = $attribute->is_taxonomy()
		? wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) )
		: $attribute->get_options();
	if ( ! is_wp_error( $values ) ) {
		$facts[ wc_attribute_label( $attribute->get_name(), $product ) ] = implode( ', ', $values );
	}
}
?>
<main id="content" class="course-landing">
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'course-landing__content', $product ); ?>>
		<?php
		get_template_part( 'template-parts/course-hero' );
		get_template_part( 'template-parts/course-facts', null, array( 'facts' => $facts ) );
		get_template_part( 'template-parts/course-program' );
		if ( $product->get_description() ) : ?>
			<section class="mx-auto max-w-6xl px-5 pb-16 md:px-8" aria-label="Подробная программа курса"><div class="prose"><?php the_content(); ?></div></section>
		<?php endif;
		get_template_part( 'template-parts/course-works' );
		get_template_part( 'template-parts/course-included' );
		get_template_part( 'template-parts/course-pricing' );
		get_template_part( 'template-parts/course-author' );
		get_template_part( 'template-parts/course-faq' );
		if ( isset( WC()->structured_data ) ) {
			WC()->structured_data->generate_product_data( $product );
		}
		?>
	</div>
</main>
<?php do_action( 'woocommerce_after_single_product' ); ?>
