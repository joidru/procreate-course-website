<?php
/**
 * Поддержка возможностей темы, меню, размеры изображений.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

function evg_setup() {
	load_theme_textdomain( 'evgagarina', EVG_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 80,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 3,
			),
		)
	);

	add_image_size( 'evg-card', 760, 540, true );
	add_image_size( 'evg-square', 600, 600, true );
	add_image_size( 'evg-cover', 1536, 1068, false );

	register_nav_menus(
		array(
			'primary' => __( 'Основное меню (шапка)', 'evgagarina' ),
			'footer'  => __( 'Документы (подвал)', 'evgagarina' ),
		)
	);
}
add_action( 'after_setup_theme', 'evg_setup' );

function evg_content_width() {
	$GLOBALS['content_width'] = 768;
}
add_action( 'after_setup_theme', 'evg_content_width', 0 );

/**
 * Классы body из макета.
 */
function evg_body_class( $classes ) {
	$classes[] = 'antialiased';
	$classes[] = 'font-sans';
	return $classes;
}
add_filter( 'body_class', 'evg_body_class' );

/**
 * theme-color для мобильных браузеров.
 */
function evg_meta_theme_color() {
	echo '<meta name="theme-color" content="#101010">' . "\n";
}
add_action( 'wp_head', 'evg_meta_theme_color', 1 );

/**
 * Многоточие в анонсах и длина анонса.
 */
add_filter(
	'excerpt_more',
	static function () {
		return '…';
	}
);
add_filter(
	'excerpt_length',
	static function () {
		return 26;
	}
);

/**
 * Якоря у заголовков h2 в статьях + автоматическое оглавление.
 * Если в тексте уже есть блок .article-toc, оглавление не добавляется.
 */
function evg_article_toc( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$headings = array();
	$used_ids = array();

	$content = preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#is',
		static function ( $match ) use ( &$headings, &$used_ids ) {
			$attrs = $match[1];
			$text  = wp_strip_all_tags( $match[2] );

			if ( preg_match( '#\sid=["\']([^"\']+)["\']#', $attrs, $id_match ) ) {
				$id = $id_match[1];
			} else {
				$id = sanitize_title( $text );
				if ( '' === $id ) {
					$id = 'section';
				}
				$base = $id;
				$i    = 2;
				while ( in_array( $id, $used_ids, true ) ) {
					$id = $base . '-' . $i++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}

			$used_ids[] = $id;
			$headings[] = array(
				'id'   => $id,
				'text' => $text,
			);

			return '<h2' . $attrs . '>' . $match[2] . '</h2>';
		},
		$content
	);

	$min_headings = (int) apply_filters( 'evg_toc_min_headings', 3 );
	if ( count( $headings ) < $min_headings || false !== strpos( $content, 'article-toc' ) ) {
		return $content;
	}

	$toc  = '<nav class="article-toc" aria-label="' . esc_attr__( 'Содержание статьи', 'evgagarina' ) . '">';
	$toc .= '<p class="article-toc-title">' . esc_html__( 'Содержание', 'evgagarina' ) . '</p><ol>';
	foreach ( $headings as $heading ) {
		$toc .= '<li><a href="#' . esc_attr( $heading['id'] ) . '">' . esc_html( $heading['text'] ) . '</a></li>';
	}
	$toc .= '</ol></nav>';

	$position = strpos( $content, '<h2' );
	return substr( $content, 0, $position ) . $toc . substr( $content, $position );
}
add_filter( 'the_content', 'evg_article_toc', 20 );
