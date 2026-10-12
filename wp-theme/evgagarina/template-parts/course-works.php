<?php defined( 'ABSPATH' ) || exit; global $product; ?>
<?php if ( $product->get_gallery_image_ids() ) : ?>
<section id="works" class="bg-ink text-ink-foreground">
<div class="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
<div class="max-w-xl">
<p class="text-sm font-medium text-primary">Работы курса</p>
<h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight md:text-4xl">Что вы научитесь рисовать</h2>
</div>
<p class="max-w-sm text-sm leading-relaxed text-ink-foreground/70">Примеры работ, которые ученики создают в процессе обучения — от простых этюдов до законченных иллюстраций.</p>
</div>
<div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
<?php foreach ( $product->get_gallery_image_ids() as $image_id ) : ?>
<?php if ( ! wp_attachment_is_image( $image_id ) ) { continue; } ?>
<figure class="group overflow-hidden rounded-2xl border border-white/10 bg-white/5">
<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'loading' => 'lazy', 'class' => 'aspect-[3/4] w-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
<?php $caption = wp_get_attachment_caption( $image_id ); if ( $caption ) : ?>
<figcaption class="px-3 py-3 text-xs text-ink-foreground/70"><?php echo esc_html( $caption ); ?></figcaption>
<?php endif; ?>
</figure>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>
