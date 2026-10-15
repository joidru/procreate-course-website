<?php
defined( 'ABSPATH' ) || exit;
global $product;
$large = ! empty( $args['large'] );
$badge = evg_product_discount_badge( $product );
$until = $product->get_date_on_sale_to();
?>
<div class="course-landing-price <?php echo $large ? 'mt-6' : 'mt-8'; ?> flex flex-wrap items-baseline gap-3 <?php echo $large ? 'course-landing-price--large' : ''; ?>">
	<?php echo wp_kses_post( evg_product_price_html( $product ) ); ?>
	<?php if ( $badge ) : ?><span class="rounded-full bg-accent px-3 py-1 text-xs font-medium text-accent-foreground"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
</div>
<?php if ( $product->is_on_sale() && $until && $until->getTimestamp() > time() ) : ?>
<p class="mt-2 text-xs text-muted-foreground"><?php printf( esc_html__( 'Скидка действует до %s включительно', 'evgagarina' ), esc_html( wc_format_datetime( $until, 'j F' ) ) ); ?></p>
<?php endif; ?>
