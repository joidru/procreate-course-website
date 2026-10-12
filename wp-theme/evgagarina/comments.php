<?php
/**
 * Комментарии.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="evg-comments" aria-labelledby="comments-title">
	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title" class="checkout-heading">
			<?php
			$evg_count = (int) get_comments_number();
			echo esc_html( $evg_count . ' ' . evg_plural( $evg_count, array( 'комментарий', 'комментария', 'комментариев' ) ) );
			?>
		</h2>
		<ol class="evg-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit'       => 'checkout-submit',
			'title_reply'        => __( 'Оставить комментарий', 'evgagarina' ),
			'title_reply_before' => '<h2 id="reply-title" class="checkout-heading">',
			'title_reply_after'  => '</h2>',
		)
	);
	?>
</section>
