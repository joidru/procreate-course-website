<?php
/**
 * «Похожие записи» — из тех же рубрик. Внутри цикла.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$related = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'category__in'        => wp_get_post_categories( get_the_ID() ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $related->have_posts() ) {
	return;
}
?>
<section class="article-related" aria-labelledby="related-title">
	<h2 id="related-title" class="checkout-heading"><?php esc_html_e( 'Похожие записи', 'evgagarina' ); ?></h2>
	<?php
	while ( $related->have_posts() ) :
		$related->the_post();
		$minutes = evg_reading_time();
		?>
		<a href="<?php the_permalink(); ?>" class="related-card">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'evg-card', array( 'alt' => '', 'loading' => 'lazy' ) );
			}
			?>
			<span>
				<span class="related-title"><?php the_title(); ?></span>
				<span class="blog-meta"><?php echo esc_html( $minutes . ' ' . evg_plural( $minutes, array( 'минута чтения', 'минуты чтения', 'минут чтения' ) ) ); ?></span>
			</span>
		</a>
		<?php
	endwhile;
	wp_reset_postdata();
	?>
</section>
