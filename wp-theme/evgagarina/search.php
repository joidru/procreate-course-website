<?php
/**
 * Результаты поиска.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/blog-list',
	null,
	array(
		'eyebrow'  => __( 'Поиск', 'evgagarina' ),
		/* translators: %s: поисковый запрос */
		'title'    => esc_html( sprintf( __( 'Результаты: «%s»', 'evgagarina' ), get_search_query( false ) ) ),
		'lead'     => '',
		'featured' => false,
	)
);

get_footer();
