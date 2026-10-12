<?php
/**
 * Каталог (магазин и рубрики товаров) — сетка карточек как на главной.
 *
 * @package evgagarina
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="content" class="blog-page">
	<div class="blog-wrap">
		<header class="blog-hero">
			<p class="blog-eyebrow"><?php esc_html_e( 'Каталог', 'evgagarina' ); ?></p>
			<h1 class="blog-title"><?php woocommerce_page_title(); ?></h1>
			<?php
			$evg_desc = is_product_taxonomy() ? term_description() : '';
			if ( $evg_desc ) :
				?>
				<div class="blog-lead"><?php echo wp_kses_post( $evg_desc ); ?></div>
			<?php endif; ?>
		</header>

		<?php woocommerce_output_all_notices(); ?>

		<?php if ( woocommerce_product_loop() ) : ?>
			<ul class="course-grid" role="list">
				<?php
				while ( have_posts() ) :
					the_post();
					$evg_product = wc_get_product( get_the_ID() );
					if ( $evg_product && $evg_product->is_visible() ) {
						get_template_part( 'template-parts/product-card', null, array( 'product' => $evg_product ) );
					}
				endwhile;
				?>
			</ul>
			<?php evg_pagination(); ?>
		<?php else : ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
