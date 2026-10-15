<?php
/**
 * Подключение стилей и скриптов.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

function evg_asset_version( $relative ) {
	$file = EVG_DIR . $relative;
	return file_exists( $file ) ? EVG_VERSION . '.' . filemtime( $file ) : EVG_VERSION;
}

function evg_enqueue_assets() {
	wp_enqueue_style( 'evg-main', EVG_URI . '/assets/css/main.css', array(), evg_asset_version( '/assets/css/main.css' ) );
	wp_enqueue_style( 'evg-theme', EVG_URI . '/assets/css/theme.css', array( 'evg-main' ), evg_asset_version( '/assets/css/theme.css' ) );
	wp_enqueue_style( 'evg-style', get_stylesheet_uri(), array( 'evg-theme' ), EVG_VERSION );

	wp_enqueue_script(
		'evg-theme',
		EVG_URI . '/assets/js/theme.js',
		array(),
		evg_asset_version( '/assets/js/theme.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script( 'evg-course-reviews', EVG_URI . '/assets/js/course-reviews.js', array(), evg_asset_version( '/assets/js/course-reviews.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}

	wp_enqueue_script( 'evg-notices', EVG_URI . '/assets/js/notices.js', array( 'evg-theme' ), evg_asset_version( '/assets/js/notices.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );

	$data = array(
		'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
		'loggedIn'   => is_user_logged_in(),
		'accountUrl' => evg_account_url(),
		'i18n'       => array(
			'enterLogin'    => __( 'Введите email или логин', 'evgagarina' ),
			'enterPassword' => __( 'Введите пароль', 'evgagarina' ),
			'showPassword'  => __( 'Показать пароль', 'evgagarina' ),
			'hidePassword'  => __( 'Скрыть пароль', 'evgagarina' ),
			'network'       => __( 'Нет связи с сервером. Проверьте интернет и попробуйте ещё раз.', 'evgagarina' ),
			'enterCoupon'   => __( 'Введите промокод', 'evgagarina' ),
		),
	);

	if ( ! is_user_logged_in() ) {
		$data['nonce'] = wp_create_nonce( 'evg_auth' );
	}

	wp_add_inline_script( 'evg-theme', 'window.evgTheme = ' . wp_json_encode( $data ) . ';', 'before' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'evg_enqueue_assets' );

/**
 * Предзагрузка основного шрифта (кириллица).
 */
function evg_preload_fonts() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( EVG_URI . '/assets/fonts/9c72aa0f40e4eef8-s.p.1y4-pdgsjb-pw.woff2' )
	);
}
add_action( 'wp_head', 'evg_preload_fonts', 2 );
