<?php
/**
 * Обычная страница. Страницы WooCommerce (корзина, оформление, кабинет)
 * выводятся без обёртки .prose — разметку дают шаблоны из /woocommerce.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

get_header();

$evg_is_wc_page = function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() );

while ( have_posts() ) :
	the_post();

	if ( $evg_is_wc_page ) :
		$evg_main_class = is_account_page() ? 'acc-page' : 'checkout-page';
		?>
		<main id="content" class="<?php echo esc_attr( $evg_main_class ); ?>">
			<?php the_content(); ?>
		</main>
		<?php
	else :
		?>
		<main id="content" class="blog-page">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-wrap' ); ?>>
				<header class="article-header">
					<h1 class="article-title"><?php the_title(); ?></h1>
				</header>
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'evg-cover', array( 'class' => 'article-cover' ) );
				}
				?>
				<div class="prose">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
		</main>
		<?php
	endif;
endwhile;

get_footer();
