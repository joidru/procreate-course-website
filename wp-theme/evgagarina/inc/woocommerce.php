<?php

/**
 * Интеграция с WooCommerce: хелперы цены, поля чекаута, мета товара, скрипты.
 *
 * @package evgagarina
 */

defined('ABSPATH') || exit;

/* ---------------------------------------------------------------------------
 * Стили WooCommerce: оставляем только базовые (layout/smallscreen отключаем,
 * разметку и вид даёт тема).
 * ------------------------------------------------------------------------- */
add_filter(
	'woocommerce_enqueue_styles',
	static function ($styles) {
		unset($styles['woocommerce-layout'], $styles['woocommerce-smallscreen'], $styles['woocommerce-general']);
		return $styles;
	}
);

add_filter(
	'woocommerce_update_order_review_fragments',
	static function ($fragments) {
		ob_start();
		get_template_part('template-parts/checkout-shipping');
		$fragments['#evg-checkout-shipping'] = ob_get_clean();
		return $fragments;
	}
);

function evg_wc_enqueue()
{
	if (is_checkout() && ! is_order_received_page()) {
		wp_enqueue_script(
			'evg-checkout',
			EVG_URI . '/assets/js/checkout.js',
			array('jquery', 'wc-checkout'),
			evg_asset_version('/assets/js/checkout.js'),
			array('in_footer' => true)
		);
		wp_localize_script(
			'evg-checkout',
			'evgCheckout',
			array(
				'emptyCoupon' => __('Введите код купона', 'evgagarina'),
				'network'     => __('Не удалось применить купон. Попробуйте ещё раз.', 'evgagarina'),
			)
		);
	}
}
add_action('wp_enqueue_scripts', 'evg_wc_enqueue', 20);

/* ---------------------------------------------------------------------------
 * Хелперы для карточек и страницы товара.
 * ------------------------------------------------------------------------- */

/**
 * Обычная цена товара (для вариативного — минимальная обычная).
 */
function evg_product_regular_price(WC_Product $product)
{
	if ($product->is_type('variable')) {
		return (float) $product->get_variation_regular_price('min', true);
	}
	return (float) wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
}

/**
 * Бейдж «−60%» для товара со скидкой или пустая строка.
 */
function evg_product_discount_badge(WC_Product $product)
{
	if (! $product->is_on_sale()) {
		return '';
	}
	$regular = evg_product_regular_price($product);
	$current = (float) wc_get_price_to_display($product);
	if ($product->is_type('variable')) {
		$current = (float) $product->get_variation_price('min', true);
	}
	if ($regular <= 0 || $current >= $regular) {
		return '';
	}
	return '−' . round(100 - ($current / $regular * 100)) . '%';
}

/**
 * Цена в формате макета: <strong>1980 ₽</strong> <s>4980 ₽</s>.
 */
function evg_product_price_html(WC_Product $product)
{
	if ('' === $product->get_price()) {
		return '';
	}

	$current = $product->is_type('variable') ? (float) $product->get_variation_price('min', true) : (float) wc_get_price_to_display($product);
	$prefix  = $product->is_type('variable') && $product->get_variation_price('min') !== $product->get_variation_price('max') ? __('от ', 'evgagarina') : '';

	if (0.0 === $current) {
		return '<span class="sr-only">' . esc_html__('Цена:', 'evgagarina') . '</span><strong>' . esc_html__('Бесплатно', 'evgagarina') . '</strong>';
	}

	$html = '<span class="sr-only">' . esc_html__('Цена:', 'evgagarina') . '</span><strong>' . esc_html($prefix) . wp_strip_all_tags(wc_price($current)) . '</strong>';

	$regular = evg_product_regular_price($product);
	if ($product->is_on_sale() && $regular > $current) {
		$html .= ' <s><span class="sr-only">' . esc_html__('Было:', 'evgagarina') . '</span>' . wp_strip_all_tags(wc_price($regular)) . '</s>';
	}
	return $html;
}

/**
 * Ссылка «Купить»: простой товар сразу кладётся в корзину и ведёт на оформление.
 */
function evg_product_buy_url(WC_Product $product)
{
	if ($product->is_type('external')) {
		return $product->get_product_url();
	}
	if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
		return add_query_arg('add-to-cart', $product->get_id(), wc_get_checkout_url());
	}
	return '#pricing';
}

/**
 * Первая рубрика товара (кроме «Без рубрики»).
 */
function evg_product_kind(WC_Product $product)
{
	foreach ((array) get_the_terms($product->get_id(), 'product_cat') as $term) {
		if ($term instanceof WP_Term && 'uncategorized' !== $term->slug) {
			return $term->name;
		}
	}
	return __('Курс', 'evgagarina');
}

/* ---------------------------------------------------------------------------
 * Поля товара в админке: подпись карточки.
 * ------------------------------------------------------------------------- */
function evg_product_admin_fields()
{
	echo '<div class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_evg_card_note',
			'label'       => __('Подпись в карточке', 'evgagarina'),
			'placeholder' => __('30 уроков', 'evgagarina'),
			'desc_tip'    => true,
			'description' => __('Показывается в каталоге после рубрики: «Курс · 30 уроков».', 'evgagarina'),
		)
	);
	echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'evg_product_admin_fields');

function evg_product_admin_save($post_id)
{
	// Nonce проверяет WooCommerce перед вызовом woocommerce_process_product_meta.
	// phpcs:disable WordPress.Security.NonceVerification.Missing
	if (isset($_POST['_evg_card_note'])) {
		update_post_meta($post_id, '_evg_card_note', sanitize_text_field(wp_unslash($_POST['_evg_card_note'])));
	}
	// phpcs:enable
}
add_action('woocommerce_process_product_meta', 'evg_product_admin_save');

/* ---------------------------------------------------------------------------
 * Покупка: курсы покупаются по одному, после добавления — сразу оформление.
 * ------------------------------------------------------------------------- */
add_filter(
	'woocommerce_is_sold_individually',
	static function ($sold, $product) {
		return $product->is_virtual() || $product->is_downloadable() ? true : $sold;
	},
	10,
	2
);

add_filter(
	'woocommerce_add_to_cart_redirect',
	static function () {
		return wc_get_checkout_url();
	}
);

/* Повторное добавление уже лежащего в корзине курса не должно давать ошибку. */
add_filter(
	'woocommerce_add_to_cart_sold_individually_found_in_cart',
	static function ($found) {
		if ($found && ! wp_doing_ajax()) {
			wp_safe_redirect(wc_get_checkout_url());
			exit;
		}
		return $found;
	}
);

/* ---------------------------------------------------------------------------
 * Чекаут.
 * ------------------------------------------------------------------------- */

/* Купон и платёжные методы выводит сам шаблон form-checkout.php. */
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);

function evg_checkout_fields($fields)
{
	$fields['billing'] = array(
		'billing_full_name' => array(
			'type' => 'text',
			'label' => __('Имя и фамилия', 'evgagarina'),
			'placeholder' => __('Иван Иванов', 'evgagarina'),
			'required' => true,
			'autocomplete' => 'name',
			'class' => array('form-row-wide'),
			'priority' => 10,
		),
		'billing_email' => array(
			'type' => 'email',
			'label' => __('Email', 'evgagarina'),
			'placeholder' => 'you@example.com',
			'description' => __('На этот адрес придёт доступ к материалам', 'evgagarina'),
			'required' => true,
			'validate' => array('email'),
			'autocomplete' => 'email',
			'class' => array('form-row-wide'),
			'priority' => 20,
		),
		'billing_messenger' => array(
			'type' => 'text',
			'label' => __('Мессенджер', 'evgagarina'),
			'placeholder' => __('Telegram @username или другой контакт', 'evgagarina'),
			'required' => false,
			'class' => array('form-row-wide'),
			'custom_attributes' => array('maxlength' => 200),
			'priority' => 30,
		),
	);
	unset($fields['order']['order_comments']);
	return $fields;
}
add_filter('woocommerce_checkout_fields', 'evg_checkout_fields', 100);

// Core checkout.js animates .description with slideUp/slideDown on focus.
// Give the persistent email hint its own class while preserving aria-describedby.
add_filter('woocommerce_form_field', static function ($html, $key) {
	if ('billing_email' === $key) {
		$html = str_replace('class="description"', 'class="evg-email-hint"', $html);
		$html = str_replace('aria-hidden="true"', 'aria-hidden="false"', $html);
	}
	return $html;
}, 10, 2);

add_filter('woocommerce_checkout_get_value', static function ($value, $key) {
	if (null === $value && 'billing_full_name' === $key && WC()->customer) {
		return trim(WC()->customer->get_billing_first_name() . ' ' . WC()->customer->get_billing_last_name());
	}
	return $value;
}, 10, 2);

// Keep native name properties populated for gateways, emails and customer profiles.
add_filter('woocommerce_checkout_posted_data', static function ($data) {
	$name = sanitize_text_field($data['billing_full_name'] ?? '');
	$parts = preg_split('/\s+/u', trim($name), 2);
	$data['billing_full_name'] = $name;
	$data['billing_first_name'] = $parts[0] ?? '';
	$data['billing_last_name'] = $parts[1] ?? '';
	$data['billing_messenger'] = sanitize_text_field($data['billing_messenger'] ?? '');
	return $data;
});

add_action('woocommerce_after_checkout_validation', static function ($data, $errors) {
	if ('' === trim($data['billing_full_name'] ?? '')) {
		$errors->add('evg_name_required', __('Укажите имя и фамилию.', 'evgagarina'));
	}
	if (strlen($data['billing_messenger'] ?? '') > 800) {
		$errors->add('evg_messenger_length', __('Сократите контакт мессенджера до 200 символов.', 'evgagarina'));
	}
}, 10, 2);

add_action('woocommerce_checkout_create_order', static function ($order, $data) {
	$order->update_meta_data('_billing_full_name', sanitize_text_field($data['billing_full_name'] ?? ''));
	$order->update_meta_data('_billing_messenger', sanitize_text_field($data['billing_messenger'] ?? ''));
}, 10, 2);

add_action('woocommerce_admin_order_data_after_billing_address', static function ($order) {
	$messenger = $order->get_meta('_billing_messenger');
	if ($messenger) {
		echo '<p><strong>' . esc_html__('Мессенджер', 'evgagarina') . ':</strong> ' . esc_html($messenger) . '</p>';
	}
});

/**
 * Оформление полей под макет: класс .checkout-field, подпись «необязательно».
 */
function evg_form_field_args($args)
{
	$args['class'][]       = 'checkout-field';
	$args['input_class'][] = 'checkout-input';
	return $args;
}
add_filter('woocommerce_form_field_args', 'evg_form_field_args');

/* Звёздочка и «(необязательно)» как в макете. */
add_filter(
	'woocommerce_form_field',
	static function ($field) {
		$field = str_replace(
			array('<abbr class="required" title="обязательно">*</abbr>', '<abbr class="required" title="required">*</abbr>'),
			'<span class="checkout-required" aria-hidden="true">*</span>',
			$field
		);
		return preg_replace('#<span class="optional">\(([^)]*)\)</span>#u', '<span class="checkout-optional">($1)</span>', $field);
	}
);

/**
 * Сумма обычных цен товаров в корзине (без скидок по распродаже).
 */
function evg_cart_regular_total()
{
	$total = 0.0;
	foreach (WC()->cart->get_cart() as $item) {
		$product = $item['data'];
		$regular = $product->get_regular_price();
		$total  += (float) wc_get_price_to_display($product, array('price' => '' !== $regular ? $regular : $product->get_price())) * (int) $item['quantity'];
	}
	return $total;
}

/**
 * Скидка по распродаже: разница между обычными ценами и текущим подытогом.
 */
function evg_cart_sale_savings()
{
	$subtotal = WC()->cart->display_prices_including_tax()
		? (float) WC()->cart->get_subtotal() + (float) WC()->cart->get_subtotal_tax()
		: (float) WC()->cart->get_subtotal();
	return max(0, evg_cart_regular_total() - $subtotal);
}

/* ---------------------------------------------------------------------------
 * Личный кабинет.
 * ------------------------------------------------------------------------- */

/* Названия пунктов как в макете. */
add_filter(
	'woocommerce_account_menu_items',
	static function ($items) {
		$labels = array(
			'dashboard'       => __('Консоль', 'evgagarina'),
			'orders'          => __('Заказы', 'evgagarina'),
			'downloads'       => __('Загрузки', 'evgagarina'),
			'edit-address'    => __('Адрес', 'evgagarina'),
			'edit-account'    => __('Анкета', 'evgagarina'),
			'customer-logout' => __('Выйти', 'evgagarina'),
		);
		foreach ($labels as $key => $label) {
			if (isset($items[$key])) {
				$items[$key] = $label;
			}
		}
		return $items;
	},
	20
);

/* В цифровом магазине нужен только платёжный адрес. */
add_filter(
	'woocommerce_my_account_get_addresses',
	static function ($addresses) {
		if (wc_shipping_enabled()) {
			return $addresses;
		}
		return array('billing' => __('Платёжный адрес', 'evgagarina'));
	}
);

/* Выход из кабинета без страницы подтверждения. */
add_action(
	'template_redirect',
	static function () {
		if (is_wc_endpoint_url('customer-logout') && is_user_logged_in() && empty($_GET['_wpnonce'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect(str_replace('&amp;', '&', wp_logout_url(home_url('/'))));
			exit;
		}
	}
);

/* ---------------------------------------------------------------------------
 * Хлебные крошки и обёртки WooCommerce не нужны — шаблоны выводят своё.
 * ------------------------------------------------------------------------- */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Send course launch notifications only when a product becomes available.
 * Store notification status per order and product to avoid repeat sends.
 */
add_action('woocommerce_product_set_stock_status', 'evg_notify_course_available', 20, 3);

function evg_notify_course_available($product_id, $stock_status, $product)
{
    if ('instock' !== $stock_status || ! $product instanceof WC_Product || $product->is_type('grouped') || $product->is_type('variable')) {
        return;
    }

    // WooCommerce triggers this hook only on a stock-status transition.
    $page = 1;
    do {
        $orders = wc_get_orders(array(
            'status' => array('completed'),
            'limit' => 100,
            'paged' => $page,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        foreach ($orders as $order) {
            $email = sanitize_email($order->get_billing_email());
            if (! is_email($email)) {
                continue;
            }

            $contains_product = false;
            foreach ($order->get_items('line_item') as $item) {
                if ((int) $item->get_product_id() === (int) $product_id || (int) $item->get_variation_id() === (int) $product_id) {
                    $contains_product = true;
                    break;
                }
            }
            if (! $contains_product) {
                continue;
            }

            $meta_key = '_evg_course_launch_notified_' . absint($product_id);
            if ($order->get_meta($meta_key, true)) {
                continue;
            }

            ob_start();
            wc_get_template('emails/customer-preorder-available.php', array('product' => $product));
            $message = ob_get_clean();
            if (! $message) {
                continue;
            }

            $sent = wp_mail(
                $email,
                'Старт курса «' . wp_strip_all_tags($product->get_name()) . '»',
                $message,
                array('Content-Type: text/html; charset=UTF-8')
            );

            if ($sent) {
                $order->update_meta_data($meta_key, current_time('mysql', true));
                $order->save();
            }
        }

        $page++;
    } while (count($orders) === 100);
}
