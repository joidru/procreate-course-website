<?php
/**
 * Архивы записей: рубрики, метки, автор, даты.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/blog-list',
	null,
	array(
		'eyebrow'  => __( 'Блог', 'evgagarina' ),
		'title'    => esc_html( wp_strip_all_tags( get_the_archive_title() ) ),
		'lead'     => wp_strip_all_tags( get_the_archive_description() ),
		'featured' => false,
	)
);

get_footer();
