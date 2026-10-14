<?php defined('ABSPATH') || exit;
global $product; ?>
<section id="top" class="relative overflow-hidden">
    <div class="mx-auto max-w-6xl px-5 pt-12 pb-6 md:px-8 md:pt-20 md:pb-16">
        <div class="grid items-center gap-10 md:grid-cols-2 md:gap-12">
            <div class="flex flex-col items-start">
                <?php
                $stock_status = $product->get_stock_status();
                $course_status = 'onbackorder' === $stock_status ? 'preorder' : ('instock' === $stock_status ? 'available' : '');
                ?>
                <div class="course-hero-badges">
                    <p class="home-eyebrow"><?php echo esc_html(evg_mod('evg_hero_eyebrow')); ?></p>
                    <?php if ($course_status) : ?>
                        <span class="course-status-badge course-status-badge--<?php echo esc_attr($course_status); ?>">
                            <span class="course-status-badge__dot" aria-hidden="true"></span>
                            <?php echo esc_html('preorder' === $course_status ? 'Предзаказ' : 'Доступен'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <h1 class="mt-6 text-balance font-display text-4xl font-bold leading-[1.05] tracking-tight text-foreground sm:text-5xl md:text-6xl"><?php echo str_replace('Procreate', '<span class="text-primary">Procreate</span>', esc_html(get_the_title())); ?></h1>
                <div class="mt-5 max-w-md text-pretty text-base leading-relaxed text-muted-foreground md:text-lg"><?php echo wp_kses_post(apply_filters('woocommerce_short_description', $product->get_short_description())); ?></div>
                <?php get_template_part('template-parts/course-price', null, array('large' => false)); ?>
                <div class="mt-7 flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                    <a href="#pricing" class="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-7 py-3.5 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90">Записаться на курс<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <a href="#works" class="inline-flex items-center justify-center rounded-full border border-border bg-card px-7 py-3.5 text-base font-medium text-foreground transition-colors hover:bg-secondary">Смотреть работы</a>
                </div>
            </div>
            <div class="relative">
                <div class="overflow-hidden rounded-[1.75rem] border border-border bg-card p-2 shadow-xl shadow-foreground/5">
                    <?php echo $product->get_image('large', array('class' => 'aspect-square w-full rounded-[1.25rem] object-cover', 'loading' => 'eager', 'fetchpriority' => 'high')); ?>
                </div>
            </div>
        </div>
    </div>
</section>