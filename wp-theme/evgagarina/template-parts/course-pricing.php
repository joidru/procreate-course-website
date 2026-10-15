<?php defined('ABSPATH') || exit;
global $product; ?>
<section id="pricing" class="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
    <div class="overflow-hidden rounded-[2rem] border border-border bg-card">
        <div class="grid items-center md:grid-cols-2">
            <div class="relative md:block">
                <?php echo $product->get_image('large', array('class' => 'aspect-square w-full object-cover', 'loading' => 'lazy')); ?>
            </div>
            <div class="p-7 md:p-12">
                <p class="text-sm font-medium text-primary">Стоимость</p>
                <h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">Начните рисовать уже сегодня</h2>
                <?php get_template_part('template-parts/course-price', null, array('large' => true)); ?>
                <?php
                $product_id = get_the_ID();

                $items = evg_course_included_get_selected($product_id);

                if (!empty($items)) : ?>
                    <ul class="mt-7 flex flex-col gap-3">
                        <?php foreach ($items as $item) : ?>
                            <li class="flex items-center gap-3 text-sm text-foreground">
                                <span class="flex h-5 w-5 flex-none items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check h-3.5 w-3.5" aria-hidden="true">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                </span><?php echo esc_html($item); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php get_template_part('template-parts/course-purchase'); ?>
                <p class="mt-3 text-center text-xs text-muted-foreground">После оплаты доступ к урокам открывается в личном кабинете</p>
            </div>
        </div>
    </div>
</section>