<?php

/**
 * Вспомогательные функции для шаблонов.
 *
 * @package evgagarina
 */

defined('ABSPATH') || exit;

/**
 * SVG-иконки (Lucide), используемые в макете.
 */
function evg_icon($name, $size = 24, $class = '')
{
	$paths = array(
		'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-left'   => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
		'chevron-left' => '<path d="m15 18-6-6 6-6"/>',
		'user'         => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'menu'         => '<path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/>',
		'close'        => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'eye'          => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
		'eye-off'      => '<path d="M10.7 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-2.2 3.1"/><path d="M6.6 6.6A17.4 17.4 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/><path d="m2 2 20 20"/>',
		'check'        => '<path d="M20 6 9 17l-5-5"/>',
		'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'info'         => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
		'card'         => '<rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/>',
		'download'     => '<path d="M12 15V3"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/>',
		'plus'         => '<path d="M5 12h14"/><path d="M12 5v14"/>',
		'sparkles'     => '<path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/><path d="M20 2v4"/><path d="M22 4h-4"/><circle cx="4" cy="20" r="2"/>',
	);

	if (! isset($paths[$name])) {
		return '';
	}

	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"%2$s aria-hidden="true">%3$s</svg>',
		(int) $size,
		$class ? ' class="' . esc_attr($class) . '"' : '',
		$paths[$name]
	);
}

/**
 * Русское склонение: evg_plural( 5, array( 'минута', 'минуты', 'минут' ) ).
 */
function evg_plural($number, array $forms)
{
	$n  = abs((int) $number) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) {
		return $forms[2];
	}
	if ($n1 > 1 && $n1 < 5) {
		return $forms[1];
	}
	if (1 === $n1) {
		return $forms[0];
	}
	return $forms[2];
}

function evg_logo_url()
{
	$logo_id = (int) get_theme_mod('custom_logo');
	if ($logo_id) {
		$url = wp_get_attachment_image_url($logo_id, 'thumbnail');
		if ($url) {
			return $url;
		}
	}
	return EVG_URI . '/assets/images/evgeniya-logo.png';
}

/**
 * Ссылка на секцию главной: на главной — якорь, на других страницах — /#id.
 */
function evg_section_url($id)
{
	return is_front_page() ? '#' . $id : home_url('/#' . $id);
}

function evg_blog_url()
{
	$page_id = (int) get_option('page_for_posts');
	return $page_id ? get_permalink($page_id) : home_url('/');
}

function evg_account_url()
{
	if (function_exists('wc_get_page_permalink')) {
		return wc_get_page_permalink('myaccount');
	}
	return is_user_logged_in() ? admin_url('profile.php') : wp_login_url();
}

/**
 * Ссылка «Начать с основ»: из настроек темы или первый товар каталога.
 */
function evg_start_url()
{
	$url = evg_mod('evg_start_url');
	if ($url) {
		return $url;
	}
	if (function_exists('wc_get_products')) {
		$products = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 1,
				'orderby' => 'menu_order',
				'order'   => 'ASC',
				'return'  => 'ids',
			)
		);
		if ($products) {
			return get_permalink($products[0]);
		}
	}
	return evg_section_url('courses');
}

function evg_user_initial($user = null)
{
	$user = $user ? $user : wp_get_current_user();
	$name = $user->first_name ? $user->first_name : $user->display_name;
	return function_exists('mb_substr') ? mb_strtoupper(mb_substr($name, 0, 1)) : strtoupper(substr($name, 0, 1));
}

function evg_user_first_name($user = null)
{
	$user = $user ? $user : wp_get_current_user();
	return $user->first_name ? $user->first_name : $user->display_name;
}

/**
 * Время чтения (≈180 слов в минуту для русского текста).
 */
function evg_reading_time($post = null)
{
	$post  = get_post($post);
	$text  = wp_strip_all_tags(strip_shortcodes($post->post_content));
	$words = preg_match_all('/[\p{L}\p{N}]+/u', $text);
	return max(1, (int) ceil($words / 180));
}

function evg_post_meta_line($post = null)
{
	$post    = get_post($post);
	$minutes = evg_reading_time($post);
	return sprintf(
		'%1$s · %2$d %3$s',
		get_the_author_meta('display_name', $post->post_author),
		$minutes,
		evg_plural($minutes, array('минута чтения', 'минуты чтения', 'минут чтения'))
	);
}

function evg_post_tags_html($post = null)
{
	$terms = get_the_category(get_post($post)->ID);
	if (! $terms) {
		return '';
	}
	$html = '<p class="blog-tags">';
	foreach (array_slice($terms, 0, 3) as $term) {
		$html .= '<span>' . esc_html($term->name) . '</span>';
	}
	return $html . '</p>';
}

/**
 * Меню с фолбэком на ссылки из макета, если меню не назначено.
 */
function evg_default_menu_links($location)
{
	if ('footer' === $location) {
		$links    = array();
		$offer_id = (int) get_theme_mod('evg_offer_page');
		if ($offer_id) {
			$links[] = array(get_the_title($offer_id), get_permalink($offer_id));
		}
		$privacy = get_privacy_policy_url();
		if ($privacy) {
			$links[] = array(__('Политика конфиденциальности', 'evgagarina'), $privacy);
		}
		return $links;
	}

	return array(
		array(__('Курсы', 'evgagarina'), evg_section_url('courses')),
		array(__('Об авторе', 'evgagarina'), evg_section_url('author')),
		array(__('Блог', 'evgagarina'), evg_blog_url()),
		array(__('Контакты', 'evgagarina'), '#contacts'),
	);
}

function evg_menu($location, $link_class)
{
	if (has_nav_menu($location)) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'walker'         => new Evg_Nav_Walker(),
				'link_class'     => $link_class,
				'fallback_cb'    => false,
			)
		);
		return;
	}

	$is_blog = is_home() || is_singular('post') || is_category() || is_tag();
	foreach (evg_default_menu_links($location) as $link) {
		$current = $is_blog && evg_blog_url() === $link[1] && 'primary' === $location;
		printf(
			'<a href="%1$s" class="%2$s"%3$s>%4$s</a>',
			esc_url($link[1]),
			esc_attr($link_class),
			$current ? ' aria-current="page"' : '',
			esc_html($link[0])
		);
	}
}

/**
 * Пагинация в стиле темы.
 */
function evg_pagination()
{
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => evg_icon('chevron-left', 16) . '<span class="sr-only">' . esc_html__('Назад', 'evgagarina') . '</span>',
			'next_text'          => '<span class="sr-only">' . esc_html__('Вперёд', 'evgagarina') . '</span>' . evg_icon('arrow-right', 16),
			'screen_reader_text' => __('Страницы', 'evgagarina'),
			'class'              => 'evg-pagination',
		)
	);
}
