<?php
/**
 * Страница не найдена.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="content" class="result-page">
	<section class="result-card" aria-labelledby="nf-title">
		<p class="blog-eyebrow">404</p>
		<h1 id="nf-title" class="result-title"><?php esc_html_e( 'Страница не найдена', 'evgagarina' ); ?></h1>
		<p class="result-lead"><?php esc_html_e( 'Возможно, ссылка устарела. Загляните в каталог курсов или блог.', 'evgagarina' ); ?></p>
		<div class="result-actions">
			<a href="<?php echo esc_url( home_url( '/#courses' ) ); ?>" class="checkout-submit"><?php esc_html_e( 'Смотреть курсы', 'evgagarina' ); ?></a>
			<a href="<?php echo esc_url( evg_blog_url() ); ?>" class="result-secondary"><?php esc_html_e( 'Перейти в блог', 'evgagarina' ); ?></a>
		</div>
	</section>
</main>
<?php
get_footer();
