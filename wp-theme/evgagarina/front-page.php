<?php

/**
 * Главная страница.
 *
 * Если в «Настройки → Чтение» выбрана «Ваши последние записи», WordPress
 * всё равно использует этот шаблон — блог при этом доступен через home.php
 * только при статической главной.
 *
 * @package evgagarina
 */

defined('ABSPATH') || exit;

get_header();

$evg_products = function_exists('wc_get_products')
	? wc_get_products(
		array(
			'status'     => 'publish',
			'visibility' => 'catalog',
			'limit'      => (int) apply_filters('evg_front_catalog_limit', 24),
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
		)
	)
	: array();

$evg_categories = array();
foreach ($evg_products as $evg_product) {
	foreach ((array) get_the_terms($evg_product->get_id(), 'product_cat') as $evg_term) {
		if ($evg_term instanceof WP_Term && 'uncategorized' !== $evg_term->slug) {
			$evg_categories[$evg_term->slug] = $evg_term->name;
		}
	}
}

$evg_collage = array();
foreach ($evg_products as $evg_product) {
	if ($evg_product->get_image_id() && count($evg_collage) < 4) {
		$evg_collage[] = wp_get_attachment_image_url($evg_product->get_image_id(), 'large');
	}
}
if (count($evg_collage) < 4) {
	$evg_collage = array(
		EVG_URI . '/assets/images/cover-basics.jpg',
		EVG_URI . '/assets/images/cover-watercolor.jpg',
		EVG_URI . '/assets/images/cover-dreams.jpg',
		EVG_URI . '/assets/images/cover-flowers.png',
	);
}

$evg_start_url    = evg_start_url();
?>
<main id="content">
	<section id="top" class="relative overflow-hidden">
		<div class="mx-auto max-w-6xl px-5 pt-12 pb-6 md:px-8 md:pt-20 md:pb-16">
			<div class="grid items-center gap-10 md:grid-cols-2 md:gap-12">
				<div class="flex flex-col items-start">
					<p class="home-eyebrow"><?php echo esc_html(evg_mod('evg_hero_eyebrow')); ?></p>
					<h1 class="mt-6 text-balance font-display text-4xl font-bold leading-[1.05] tracking-tight text-foreground sm:text-5xl md:text-6xl">
						<?php echo esc_html(evg_mod('evg_hero_title')); ?>
						<span class="text-primary"><?php echo esc_html(evg_mod('evg_hero_accent')); ?></span>
					</h1>
					<p class="mt-5 max-w-md text-pretty text-base leading-relaxed text-muted-foreground md:text-lg"><?php echo esc_html(evg_mod('evg_hero_lead')); ?></p>
					<div class="mt-7 flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
						<a href="#courses" class="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-7 py-3.5 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90"><?php esc_html_e('Выбрать курс', 'evgagarina'); ?></a>
						<a href="<?php echo esc_url($evg_start_url); ?>" class="inline-flex items-center justify-center rounded-full border border-border bg-card px-7 py-3.5 text-base font-medium text-foreground transition-colors hover:bg-secondary"><?php echo esc_html(evg_mod('evg_hero_secondary')); ?></a>
					</div>
					<dl class="home-facts">
						<?php if ($evg_products) : ?>
							<div>
								<dt><?php esc_html_e('Курсов и наборов', 'evgagarina'); ?></dt>
								<dd><?php echo esc_html(count($evg_products)); ?></dd>
							</div>
						<?php endif; ?>
						<div>
							<dt><?php esc_html_e('Доступ', 'evgagarina'); ?></dt>
							<dd><?php echo esc_html(evg_mod('evg_fact_access')); ?></dd>
						</div>
						<div>
							<dt><?php esc_html_e('Язык', 'evgagarina'); ?></dt>
							<dd><?php echo esc_html(evg_mod('evg_fact_language')); ?></dd>
						</div>
					</dl>
				</div>
				<div class="home-collage" aria-hidden="true">
					<?php foreach ($evg_collage as $evg_src) : ?>
						<img src="<?php echo esc_url($evg_src); ?>" alt="" width="500" height="500">
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section id="courses" class="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
		<div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
			<div class="max-w-2xl">
				<p class="text-sm font-medium text-primary"><?php esc_html_e('Каталог', 'evgagarina'); ?></p>
				<h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl"><?php esc_html_e('Курсы и материалы', 'evgagarina'); ?></h2>
			</div>
			<?php if (count($evg_categories) > 1) : ?>
				<div class="home-filters" role="group" aria-label="<?php esc_attr_e('Фильтр каталога', 'evgagarina'); ?>">
					<button type="button" data-filter="all" aria-pressed="true"><?php esc_html_e('Все', 'evgagarina'); ?></button>
					<?php foreach ($evg_categories as $evg_slug => $evg_name) : ?>
						<button type="button" data-filter="<?php echo esc_attr($evg_slug); ?>" aria-pressed="false"><?php echo esc_html($evg_name); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($evg_products) : ?>
			<ul class="course-grid">
				<?php
				foreach ($evg_products as $evg_product) {
					get_template_part('template-parts/product-card', null, array('product' => $evg_product));
				}
				?>
			</ul>
		<?php else : ?>
			<p class="mt-8 text-muted-foreground"><?php esc_html_e('Курсы скоро появятся.', 'evgagarina'); ?></p>
		<?php endif; ?>
	</section>
	<?php get_template_part('template-parts/course-author', null, ['url' => $evg_start_url]); ?>

	<?php
	while (have_posts()) :
		the_post();
		if ('' !== trim(get_the_content())) :
	?>
			<section class="mx-auto max-w-3xl px-5 py-16 md:px-8">
				<div class="prose"><?php the_content(); ?></div>
			</section>
	<?php
		endif;
	endwhile;
	?>
</main>
<?php
get_footer();
