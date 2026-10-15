<?php
/**
 * Walker для меню: выводит плоский список ссылок с классами из макета
 * (без <ul>/<li>), текущий пункт получает aria-current="page".
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

class Evg_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item       = $data_object;
		$link_class = isset( $args->link_class ) ? $args->link_class : '';
		$classes    = array_filter( array_map( 'sanitize_html_class', (array) $item->classes ) );
		$is_current = in_array( 'current-menu-item', (array) $item->classes, true );

		$attributes = array(
			'href'  => $item->url,
			'class' => trim( $link_class . ' ' . implode( ' ', array_diff( $classes, array( 'menu-item', 'menu-item-type-custom', 'menu-item-object-custom', 'menu-item-type-post_type', 'menu-item-object-page', 'menu-item-type-taxonomy', 'menu-item-object-category' ) ) ) ),
		);

		if ( ! empty( $item->target ) ) {
			$attributes['target'] = $item->target;
		}
		if ( ! empty( $item->xfn ) ) {
			$attributes['rel'] = $item->xfn;
		} elseif ( '_blank' === $item->target ) {
			$attributes['rel'] = 'noopener';
		}
		if ( ! empty( $item->attr_title ) ) {
			$attributes['title'] = $item->attr_title;
		}
		if ( $is_current ) {
			$attributes['aria-current'] = 'page';
		}

		$html = '';
		foreach ( $attributes as $name => $value ) {
			if ( '' === $value ) {
				continue;
			}
			$value = 'href' === $name ? esc_url( $value ) : esc_attr( $value );
			$html .= ' ' . $name . '="' . $value . '"';
		}

		$title   = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
		$output .= '<a' . $html . '>' . wp_kses_post( $title ) . '</a>';
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
