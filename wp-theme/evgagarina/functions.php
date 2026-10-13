<?php
/**
 * Функции темы «Евгения Гагарина».
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

define( 'EVG_VERSION', '1.0.0' );
define( 'EVG_DIR', get_template_directory() );
define( 'EVG_URI', get_template_directory_uri() );

require EVG_DIR . '/inc/setup.php';
require EVG_DIR . '/inc/customizer.php';
require EVG_DIR . '/inc/class-evg-nav-walker.php';
require EVG_DIR . '/inc/template-tags.php';
require EVG_DIR . '/inc/enqueue.php';
require EVG_DIR . '/inc/author.php';
require EVG_DIR . '/inc/auth.php';

if ( class_exists( 'WooCommerce' ) ) {
	require EVG_DIR . '/inc/woocommerce.php';
	require EVG_DIR . '/inc/course-program-admin.php';
	require EVG_DIR . '/inc/course-included-admin.php';
}

if ( class_exists( 'LifterLMS' ) ) {
	require EVG_DIR . '/inc/lifterlms.php';
}
