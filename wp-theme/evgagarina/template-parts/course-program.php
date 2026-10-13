<?php
/** Editable course program for each WooCommerce product. */
defined('ABSPATH') || exit;
global $product;
if (!$product) return;
$data = evg_course_program_get($product->get_id());
if ($data['enabled'] !== 'yes') return;
$icons = evg_course_program_icons();
?>
<section id="program" class="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
  <div class="max-w-2xl">
    <p class="text-sm font-medium text-primary">Программа</p>
    <?php if ($data['title'] !== '') : ?>
      <h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl"><?php echo esc_html($data['title']); ?></h2>
    <?php endif; ?>
    <?php if ($data['subtitle'] !== '') : ?>
      <p class="mt-4 text-pretty text-base leading-relaxed text-muted-foreground"><?php echo esc_html($data['subtitle']); ?></p>
    <?php endif; ?>
  </div>
  <?php if (!empty($data['items'])) : ?>
  <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($data['items'] as $item) :
      $icon = isset($icons[$item['icon']]) ? $icons[$item['icon']] : $icons['boxes'];
    ?>
    <div class="group rounded-2xl border border-border bg-card p-6 transition-colors hover:border-primary/40">
      <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent text-primary">
        <?php echo $icon; // SVG from fixed theme allowlist only. ?>
      </div>
      <h3 class="mt-4 font-display text-lg font-semibold text-foreground"><?php echo esc_html($item['title']); ?></h3>
      <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><?php echo esc_html($item['description']); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
