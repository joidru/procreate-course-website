<?php
/**
 * LifterLMS внутри кабинета WooCommerce.
 *
 * Кабинетом управляет WooCommerce; LifterLMS (через интеграцию с WooCommerce)
 * лишь добавляет в него свои разделы. Здесь мы только упорядочиваем их,
 * даём русские подписи и помечаем классами для стилей из theme.css.
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ключи разделов LifterLMS в меню кабинета. Названия эндпоинтов зависят
 * от настроек LifterLMS, поэтому сопоставляем по подстроке.
 */
function evg_llms_endpoint_type( $endpoint ) {
	$map = array(
		'certificate'  => 'certificates',
		'achievement'  => 'achievements',
		'notification' => 'notifications',
		'grade'        => 'grades',
		'membership'   => 'memberships',
		'course'       => 'courses',
	);
	foreach ( $map as $needle => $type ) {
		if ( false !== strpos( $endpoint, $needle ) ) {
			return $type;
		}
	}
	return '';
}

function evg_llms_account_menu_items( $items ) {
	$labels = array(
		'courses'       => __( 'Мои курсы', 'evgagarina' ),
		'certificates'  => __( 'Мои сертификаты', 'evgagarina' ),
		'achievements'  => __( 'Достижения', 'evgagarina' ),
		'notifications' => __( 'Уведомления', 'evgagarina' ),
		'grades'        => __( 'Оценки', 'evgagarina' ),
		'memberships'   => __( 'Подписки', 'evgagarina' ),
	);

	$llms   = array();
	$others = array();
	foreach ( $items as $endpoint => $label ) {
		$type = evg_llms_endpoint_type( $endpoint );
		if ( $type && 'dashboard' !== $endpoint ) {
			$llms[ $endpoint ] = isset( $labels[ $type ] ) ? $labels[ $type ] : $label;
		} else {
			$others[ $endpoint ] = $label;
		}
	}

	if ( ! $llms ) {
		return $items;
	}

	// «Мои курсы» — сразу после консоли, как в макете account.html.
	$result = array();
	if ( isset( $others['dashboard'] ) ) {
		$result['dashboard'] = $others['dashboard'];
		unset( $others['dashboard'] );
	}
	$result += $llms;

	$logout = null;
	if ( isset( $others['customer-logout'] ) ) {
		$logout = $others['customer-logout'];
		unset( $others['customer-logout'] );
	}
	$result += $others;
	if ( null !== $logout ) {
		$result['customer-logout'] = $logout;
	}

	return $result;
}
add_filter( 'woocommerce_account_menu_items', 'evg_llms_account_menu_items', 99 );

function evg_llms_menu_item_classes( $classes, $endpoint ) {
	$type = evg_llms_endpoint_type( $endpoint );
	if ( $type && 'dashboard' !== $endpoint ) {
		$classes[] = 'acc-nav-llms';
		$classes[] = 'acc-nav-llms-' . $type;
	}
	return $classes;
}
add_filter( 'woocommerce_account_menu_item_classes', 'evg_llms_menu_item_classes', 10, 2 );

/**
 * Ссылка на «Мои курсы» в кабинете (для консоли и шапки).
 */
function evg_llms_courses_url() {
	if ( function_exists( 'wc_get_account_menu_items' ) ) {
		foreach ( array_keys( wc_get_account_menu_items() ) as $endpoint ) {
			if ( 'courses' === evg_llms_endpoint_type( $endpoint ) ) {
				return wc_get_account_endpoint_url( $endpoint );
			}
		}
	}
	return '';
}

/**
 * Помечаем body, чтобы стили LifterLMS переопределялись только в кабинете.
 */
function evg_llms_body_class( $classes ) {
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$classes[] = 'evg-llms-account';
	}
	return $classes;
}
add_filter( 'body_class', 'evg_llms_body_class' );
