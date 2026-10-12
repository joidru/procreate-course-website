<?php
/**
 * Блок «Об авторе» из профиля пользователя WordPress. Внутри цикла.
 *
 * Имя — «Отображать как», текст — «Биографическая информация»,
 * подпись, фото и соцсети — поля темы в профиле (inc/author.php).
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

$author_id = (int) get_the_author_meta( 'ID' );
$bio       = get_the_author_meta( 'description', $author_id );
if ( ! $author_id || ! $bio ) {
	return;
}

$eyebrow = get_user_meta( $author_id, 'evg_author_eyebrow', true );
$name    = get_the_author_meta( 'display_name', $author_id );
$links   = array();
foreach ( evg_author_networks() as $key => $label ) {
	$url = get_the_author_meta( 'evg_' . $key, $author_id );
	if ( $url ) {
		$links[ $label ] = $url;
	}
}
?>
<section class="article-author" aria-labelledby="author-title">
	<img src="<?php echo esc_url( evg_author_photo_url( $author_id, 300 ) ); ?>" width="300" height="300" alt="<?php echo esc_attr( $name ); ?>" class="article-author__photo" loading="lazy">
	<div class="article-author__body">
		<p class="article-author__eyebrow"><?php echo esc_html( $eyebrow ? $eyebrow : __( 'Об авторе', 'evgagarina' ) ); ?></p>
		<h2 id="author-title" class="article-author__name"><?php echo esc_html( $name ); ?></h2>
		<p class="article-author__text"><?php echo wp_kses( $bio, array( 'a' => array( 'href' => true ), 'strong' => array(), 'em' => array() ) ); ?></p>
		<?php if ( $links ) : ?>
			<ul class="article-author__social" aria-label="<?php esc_attr_e( 'Соцсети автора', 'evgagarina' ); ?>">
				<?php foreach ( $links as $label => $url ) : ?>
					<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $label ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
