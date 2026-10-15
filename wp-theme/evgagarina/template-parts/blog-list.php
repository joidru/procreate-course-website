<?php
/**
 * Список записей блога: hero, первая запись крупно, сетка, карточка-призыв, пагинация.
 *
 * @package evgagarina
 *
 * @var array $args {
 *     eyebrow:  string
 *     title:    string  (HTML разрешён: span.blog-title-accent)
 *     lead:     string
 *     featured: bool    выделять первую запись
 * }
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	$args,
	array(
		'eyebrow'  => __( 'Блог', 'evgagarina' ),
		'title'    => '',
		'lead'     => '',
		'featured' => false,
	)
);
?>
<main id="content" class="blog-page">
	<div class="blog-wrap">
		<section class="blog-hero" aria-labelledby="blog-title">
			<p class="blog-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<h1 id="blog-title" class="blog-title"><?php echo wp_kses( $args['title'], array( 'span' => array( 'class' => true ) ) ); ?></h1>
			<?php if ( $args['lead'] ) : ?>
				<p class="blog-lead"><?php echo esc_html( $args['lead'] ); ?></p>
			<?php endif; ?>
		</section>

		<?php if ( have_posts() ) : ?>
			<section aria-label="<?php esc_attr_e( 'Статьи', 'evgagarina' ); ?>" class="blog-list">
				<?php
				if ( $args['featured'] ) {
					the_post();
					get_template_part( 'template-parts/blog-card', null, array( 'featured' => true ) );
				}
				?>
				<div class="blog-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/blog-card' );
					endwhile;
					?>
					<aside class="blog-cta" aria-labelledby="blog-cta-title">
						<p class="blog-eyebrow"><?php esc_html_e( 'Учитесь системно', 'evgagarina' ); ?></p>
						<h2 id="blog-cta-title" class="blog-cta-title"><?php esc_html_e( 'Статьи — это начало. Курсы — это практика.', 'evgagarina' ); ?></h2>
						<p class="blog-excerpt"><?php esc_html_e( 'Уроки с обратной связью, кисти и исходники для рисования в Procreate. Доступ к материалам навсегда.', 'evgagarina' ); ?></p>
						<a href="<?php echo esc_url( home_url( '/#courses' ) ); ?>" class="checkout-submit blog-cta-btn"><?php esc_html_e( 'Смотреть курсы', 'evgagarina' ); ?></a>
					</aside>
				</div>
				<?php evg_pagination(); ?>
			</section>
		<?php else : ?>
			<section class="blog-list">
				<p class="blog-lead"><?php esc_html_e( 'Здесь пока нет записей.', 'evgagarina' ); ?></p>
				<?php get_search_form(); ?>
			</section>
		<?php endif; ?>
	</div>
</main>
