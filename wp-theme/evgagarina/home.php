<?php
/**
 * Страница записей (блог).
 *
 * Заголовок — название страницы записей, подзаголовок — её отрывок
 * («Настройки → Чтение → Страница записей»).
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

get_header();

$evg_posts_page = (int) get_option( 'page_for_posts' );
$evg_lead       = $evg_posts_page && has_excerpt( $evg_posts_page )
	? get_the_excerpt( $evg_posts_page )
	: __( 'Разборы инструментов и приёмов, которые пригодятся в каждой иллюстрации: от настройки холста до работы со светом и цветом.', 'evgagarina' );

get_template_part(
	'template-parts/blog-list',
	null,
	array(
		'eyebrow'  => $evg_posts_page ? get_the_title( $evg_posts_page ) : __( 'Блог', 'evgagarina' ),
		'title'    => __( 'Заметки о рисовании <span class="blog-title-accent">в Procreate</span>', 'evgagarina' ),
		'lead'     => $evg_lead,
		'featured' => ! is_paged(),
	)
);

get_footer();
