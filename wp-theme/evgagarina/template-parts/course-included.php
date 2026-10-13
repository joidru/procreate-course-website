<?php
/** Per-product What's Included section. */
defined('ABSPATH') || exit;
global $product;
if (!$product) return;
$data = evg_course_included_get($product->get_id());
if ($data['enabled'] !== 'yes') return;
?>
<section id="included" class="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
<div class="grid gap-10 md:grid-cols-[1fr_1.3fr] md:gap-16">
<div>
<p class="text-sm font-medium text-primary">Что входит</p>
<?php if ($data['title'] !== '') : ?><h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl"><?php echo esc_html($data['title']); ?></h2><?php endif; ?>
<?php if ($data['subtitle'] !== '') : ?><p class="mt-4 text-pretty text-base leading-relaxed text-muted-foreground"><?php echo esc_html($data['subtitle']); ?></p><?php endif; ?>
</div>
<?php if (!empty($data['items'])) : ?>
<ul class="grid gap-x-6 gap-y-1 sm:grid-cols-2">
<?php foreach (evg_course_included_normalize_items($data['items']) as $item) : ?>
<li class="flex items-start gap-3 border-b border-border/70 py-3 text-sm text-foreground">
<span class="mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full bg-primary/10 text-primary">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-3.5 w-3.5" aria-hidden="true"><path d="M20 6 9 17l-5-5" /></svg>
</span><?php echo esc_html($item['text']); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
</section>
