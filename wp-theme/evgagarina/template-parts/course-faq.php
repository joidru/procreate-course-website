<?php
defined( 'ABSPATH' ) || exit;
global $product;
$faq = evg_product_faq( $product );
if ( ! $faq ) {
	get_template_part( 'template-parts/course-faq-default' );
	return;
}
?>
<section class="mx-auto max-w-3xl px-5 py-16 md:px-8 md:py-24">
<h2 class="text-balance text-center font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">Частые вопросы</h2>
<div class="mt-10 flex flex-col gap-3">
<?php foreach ( $faq as $index => $item ) : ?>
<div class="overflow-hidden rounded-2xl border border-border bg-card">
<button data-faq id="faq-button-<?php echo esc_attr( $index ); ?>" aria-controls="faq-<?php echo esc_attr( $index ); ?>" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left md:px-6 md:py-5">
<span class="font-display text-base font-semibold text-foreground md:text-lg"><?php echo esc_html( $item['q'] ); ?></span>
<?php echo evg_icon( 'plus', 20 ); ?>
</button>
<p <?php echo 0 === $index ? '' : 'hidden'; ?> class="faq-answer" id="faq-<?php echo esc_attr( $index ); ?>"><?php echo esc_html( $item['a'] ); ?></p>
</div>
<?php endforeach; ?>
</div>
</section>
