<?php
/**
 * Карточка записи в списке блога. Внутри цикла.
 *
 * @package evgagarina
 *
 * @var array $args { featured: bool }
 */

defined( 'ABSPATH' ) || exit;

$featured = ! empty( $args['featured'] );
$prefix   = $featured ? 'blog-featured' : 'blog-card';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $prefix ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="<?php echo esc_attr( $prefix ); ?>-media" tabindex="-1" aria-hidden="true">
			<?php
			the_post_thumbnail(
				'evg-card',
				array(
					'alt'     => '',
					'loading' => $featured ? 'eager' : 'lazy',
				)
			);
			?>
		</a>
	<?php endif; ?>
	<div class="<?php echo esc_attr( $prefix ); ?>-body">
		<?php echo evg_post_tags_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<h2 class="<?php echo esc_attr( $prefix ); ?>-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="blog-excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<p class="blog-meta"><?php echo esc_html( evg_post_meta_line() ); ?></p>
		<?php if ( $featured ) : ?>
			<a href="<?php the_permalink(); ?>" class="blog-read"><?php esc_html_e( 'Читать статью', 'evgagarina' ); ?><?php echo evg_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<?php endif; ?>
	</div>
</article>
