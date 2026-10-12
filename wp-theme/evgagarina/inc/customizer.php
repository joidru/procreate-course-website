<?php
/**
 * Настройки темы в «Внешний вид → Настроить».
 *
 * @package evgagarina
 */

defined( 'ABSPATH' ) || exit;

/**
 * Значения по умолчанию — тексты из макета.
 */
function evg_defaults() {
	return array(
		'evg_hero_eyebrow'      => 'Онлайн-школа рисования на iPad',
		'evg_hero_title'        => 'Курсы по',
		'evg_hero_accent'       => 'Procreate',
		'evg_hero_lead'         => 'Курсы, видео-уроки и наборы кистей от художницы Евгении Гагариной. На русском языке, в своём темпе, с доступом навсегда.',
		'evg_hero_secondary'    => 'Начать с основ',
		'evg_start_url'         => '',
		'evg_fact_access'       => 'Навсегда',
		'evg_fact_language'     => 'Русский',
		'evg_author_name'       => 'Евгения Гагарина',
		'evg_author_text'       => "Художница и преподаватель цифрового рисунка. Много лет рисую в Procreate и помогаю ученикам пройти путь от первых линий до законченных иллюстраций.\n\nКаждый курс — это понятная последовательность, реальные примеры и рабочие материалы: кисти, исходники и текстуры. Если вы только начинаете, начните с «Основ рисования».",
		'evg_author_image'      => 0,
		'evg_footer_tagline'    => 'Онлайн-курсы и кисти для рисования в Procreate на iPad.',
		'evg_contact_email'     => 'courses@evgagarina.com',
		'evg_legal_line'        => 'Гагарина Евгения Владимировна. ИНН 182300831797',
		'evg_blog_cta_title'    => 'Хотите научиться рисовать в Procreate?',
		'evg_blog_cta_text'     => 'Начните с курса «Основы рисования» — 30 уроков от первых линий до законченной иллюстрации.',
		'evg_blog_cta_label'    => 'Начать с основ',
		'evg_checkout_notice'   => '<strong>Если оплата не проходит,</strong> отключите VPN и попробуйте ещё раз.',
		'evg_yandex_login_url'  => '',
		'evg_yandex_shortcode'  => '',
	);
}

function evg_mod( $key ) {
	$defaults = evg_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

function evg_customize_register( WP_Customize_Manager $wp_customize ) {
	$defaults = evg_defaults();

	$wp_customize->add_panel(
		'evg_panel',
		array(
			'title'    => __( 'Тема «Евгения Гагарина»', 'evgagarina' ),
			'priority' => 30,
		)
	);

	$sections = array(
		'evg_home'   => __( 'Главная страница', 'evgagarina' ),
		'evg_blog'   => __( 'Блог', 'evgagarina' ),
		'evg_footer' => __( 'Подвал и контакты', 'evgagarina' ),
		'evg_shop'   => __( 'Оплата и вход', 'evgagarina' ),
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'evg_panel' ) );
	}

	$text_fields = array(
		'evg_hero_eyebrow'     => array( 'evg_home', __( 'Надпись над заголовком', 'evgagarina' ), 'text' ),
		'evg_hero_title'       => array( 'evg_home', __( 'Заголовок', 'evgagarina' ), 'text' ),
		'evg_hero_accent'      => array( 'evg_home', __( 'Выделенное слово заголовка', 'evgagarina' ), 'text' ),
		'evg_hero_lead'        => array( 'evg_home', __( 'Подзаголовок', 'evgagarina' ), 'textarea' ),
		'evg_hero_secondary'   => array( 'evg_home', __( 'Текст второй кнопки', 'evgagarina' ), 'text' ),
		'evg_start_url'        => array( 'evg_home', __( 'Ссылка «Начать с основ» (пусто — первый товар каталога)', 'evgagarina' ), 'url' ),
		'evg_fact_access'      => array( 'evg_home', __( 'Факт «Доступ»', 'evgagarina' ), 'text' ),
		'evg_fact_language'    => array( 'evg_home', __( 'Факт «Язык»', 'evgagarina' ), 'text' ),
		'evg_author_name'      => array( 'evg_home', __( 'Блок «Об авторе»: имя', 'evgagarina' ), 'text' ),
		'evg_author_text'      => array( 'evg_home', __( 'Блок «Об авторе»: текст', 'evgagarina' ), 'textarea' ),
		'evg_blog_cta_title'   => array( 'evg_blog', __( 'Карточка-призыв: заголовок', 'evgagarina' ), 'text' ),
		'evg_blog_cta_text'    => array( 'evg_blog', __( 'Карточка-призыв: текст', 'evgagarina' ), 'textarea' ),
		'evg_blog_cta_label'   => array( 'evg_blog', __( 'Карточка-призыв: кнопка', 'evgagarina' ), 'text' ),
		'evg_footer_tagline'   => array( 'evg_footer', __( 'Описание под названием', 'evgagarina' ), 'textarea' ),
		'evg_contact_email'    => array( 'evg_footer', __( 'Email для вопросов', 'evgagarina' ), 'email' ),
		'evg_legal_line'       => array( 'evg_footer', __( 'Юридическая строка (после ©)', 'evgagarina' ), 'text' ),
		'evg_checkout_notice'  => array( 'evg_shop', __( 'Подсказка под способами оплаты (HTML)', 'evgagarina' ), 'textarea' ),
		'evg_yandex_login_url' => array( 'evg_shop', __( 'URL входа через Яндекс (из настроек плагина)', 'evgagarina' ), 'url' ),
		'evg_yandex_shortcode' => array( 'evg_shop', __( 'Или шорткод кнопки Яндекса из плагина', 'evgagarina' ), 'text' ),
	);

	foreach ( $text_fields as $key => $field ) {
		list( $section, $label, $type ) = $field;

		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'evg_checkout_notice' === $key ? 'wp_kses_post' : 'sanitize_textarea_field';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		}

		$wp_customize->add_setting( $key, array( 'default' => $defaults[ $key ], 'sanitize_callback' => $sanitize ) );
		$wp_customize->add_control( $key, array( 'section' => $section, 'label' => $label, 'type' => $type ) );
	}

	$wp_customize->add_setting( 'evg_author_image', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'evg_author_image',
			array(
				'section'   => 'evg_home',
				'label'     => __( 'Блок «Об авторе»: фото', 'evgagarina' ),
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting( 'evg_offer_page', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		'evg_offer_page',
		array(
			'section' => 'evg_footer',
			'label'   => __( 'Страница договора оферты', 'evgagarina' ),
			'type'    => 'dropdown-pages',
		)
	);
}
add_action( 'customize_register', 'evg_customize_register' );
