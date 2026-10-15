<?php

/**
 * Запись блога.
 *
 * @package evgagarina
 */

defined('ABSPATH') || exit;

get_header();
?>
<main id="content" class="blog-page">
	<?php
	while (have_posts()) :
		the_post();
	?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('article-wrap'); ?>>
			<div class="breadcrumb">
				<?php yoast_breadcrumb() ?>
			</div>

			<header class="article-header">
				<?php echo evg_post_tags_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
				?>
				<h1 class="article-title"><?php the_title(); ?></h1>
				<p class="blog-meta">
					<?php echo esc_html(evg_post_meta_line()); ?>
					<span class="sr-only"> · <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time></span>
				</p>
			</header>

			<?php
			if (has_post_thumbnail()) {
				$evg_thumb_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
				the_post_thumbnail(
					'evg-cover',
					array(
						'class'         => 'article-cover',
						'alt'           => $evg_thumb_alt ? $evg_thumb_alt : '',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					)
				);
			}
			?>

			<div class="prose">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="evg-page-links" aria-label="' . esc_attr__('Страницы статьи', 'evgagarina') . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>

			<?php get_template_part('template-parts/author-box'); ?>

			<?php
			$evg_cta_title = evg_mod('evg_blog_cta_title');
			if ($evg_cta_title) :
			?>
				<aside class="article-cta" aria-labelledby="article-cta-title">
					<h2 id="article-cta-title" class="blog-cta-title"><?php echo esc_html($evg_cta_title); ?></h2>
					<p class="blog-excerpt"><?php echo esc_html(evg_mod('evg_blog_cta_text')); ?></p>
					<a href="<?php echo esc_url(evg_start_url()); ?>" class="checkout-submit blog-cta-btn"><?php echo esc_html(evg_mod('evg_blog_cta_label')); ?></a>
				</aside>
			<?php endif; ?>

			<?php get_template_part('template-parts/related-posts'); ?>

			<?php
			if (comments_open() || get_comments_number()) {
				comments_template();
			}
			?>
		</article>
	<?php
	endwhile;
	?>
</main>
<?php
get_footer();
