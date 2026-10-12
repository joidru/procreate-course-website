<?php defined( 'ABSPATH' ) || exit; global $product; ?>
<section class="mx-auto max-w-6xl px-5 md:px-8">
<dl class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-border bg-border md:grid-cols-4">
<?php if ( $args['facts'] ) : foreach ( $args['facts'] as $label => $value ) : ?>
<div class="bg-card px-5 py-6 text-center"><dt class="text-xs uppercase tracking-wide text-muted-foreground"><?php echo esc_html( $label ); ?></dt><dd class="mt-2 font-display text-xl font-bold text-foreground md:text-2xl"><?php echo esc_html( $value ); ?></dd></div>
<?php endforeach; else : ?>
<div class="bg-card px-5 py-6 text-center">
<dt class="text-xs uppercase tracking-wide text-muted-foreground">Время обучения</dt>
<dd class="mt-2 font-display text-xl font-bold text-foreground md:text-2xl">~1 неделя</dd>
</div>
<div class="bg-card px-5 py-6 text-center">
<dt class="text-xs uppercase tracking-wide text-muted-foreground">Объём курса</dt>
<dd class="mt-2 font-display text-xl font-bold text-foreground md:text-2xl">30 уроков</dd>
</div>
<div class="bg-card px-5 py-6 text-center">
<dt class="text-xs uppercase tracking-wide text-muted-foreground">Консультации</dt>
<dd class="mt-2 font-display text-xl font-bold text-foreground md:text-2xl">Бесплатно</dd>
</div>
<div class="bg-card px-5 py-6 text-center">
<dt class="text-xs uppercase tracking-wide text-muted-foreground">Доступ к курсу</dt>
<dd class="mt-2 font-display text-xl font-bold text-foreground md:text-2xl">Навсегда</dd>
</div>
<?php endif; ?>
</dl>
</section>
