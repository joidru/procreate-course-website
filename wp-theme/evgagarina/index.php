<?php
/**
 * Резервный шаблон.
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
		'title'    => esc_html( get_bloginfo( 'name' ) ),
		'lead'     => get_bloginfo( 'description' ),
		'featured' => false,
	)
);

get_footer();
