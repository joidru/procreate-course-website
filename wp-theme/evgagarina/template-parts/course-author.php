<?php
defined('ABSPATH') || exit;
$url = $args['url'] ?? '';
$name_btn = $args['name_btn'] ?? evg_mod('evg_hero_secondary');
if (is_product()) {
    $url = '#pricing';
}
$evg_author_image = (int) evg_mod('evg_author_image');
?>
<section id="author" class="bg-accent/40">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-5 py-16 md:grid-cols-2 md:gap-16 md:px-8 md:py-24">
        <div class="order-2 md:order-1">
            <p class="text-sm font-medium text-primary"><?php esc_html_e('Об авторе', 'evgagarina'); ?></p>
            <h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl"><?php echo esc_html(evg_mod('evg_author_name')); ?></h2>
            <div class="mt-5 flex flex-col gap-4 text-pretty text-base leading-relaxed text-muted-foreground">
                <?php echo wp_kses_post(wpautop(evg_mod('evg_author_text'))); ?>
            </div>
            <a href="<?php echo esc_url($url); ?>" class="mt-7 inline-flex items-center justify-center rounded-full bg-foreground px-7 py-3.5 text-base font-medium text-background transition-opacity hover:opacity-90"><?php echo esc_html($name_btn); ?></a>
        </div>
        <div class="order-1 md:order-2">
            <div class="overflow-hidden rounded-[1.75rem] border border-border bg-card p-2">
                <?php
                if ($evg_author_image) {
                    echo wp_get_attachment_image($evg_author_image, 'large', false, array('class' => 'aspect-[4/5] w-full rounded-[1.25rem] object-cover', 'loading' => 'lazy'));
                } else {
                    printf(
                        '<img loading="lazy" src="%s" alt="%s" class="aspect-[4/5] w-full rounded-[1.25rem] object-cover">',
                        esc_url(EVG_URI . '/assets/images/course-oliva1.jpg'),
                        esc_attr__('Оливковая ветвь на экране iPad — рисунок Евгении Гагариной', 'evgagarina')
                    );
                }
                ?>
            </div>
        </div>
    </div>
</section>