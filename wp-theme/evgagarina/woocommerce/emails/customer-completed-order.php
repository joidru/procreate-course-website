<?php

/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if (! defined('ABSPATH')) {
    exit;
}

if (!$order) return;

$items = $order->get_items();

$product_data = [];

foreach ($items as $item_id => $item) {

    // Получаем название товара
    $product_name = $item->get_name();

    // Выводим информацию о каждом товаре
    $product_data[$item_id]['name'] = '<strong>«' . $product_name . '»</strong>';

    $product = $item->get_product();
    $product_data[$item_id]['product_link'] = get_permalink($item->get_meta('_llms_pid')); // Ссылка на товар


    if ($product && $product->is_downloadable()) {
        // Получаем ссылки на скачивание
        $downloads = $item->get_item_downloads();

        if (! empty($downloads)) {
            foreach ($downloads as $download_id => $download) {
                // Выводим ссылку на скачивание
                $product_data[$item_id]['url'] = '<a href="' . $download['download_url'] . '">' . $download['name'] . '</a>';
            }
        }
    }
}

?>

<p>Здравствуйте, <?php echo esc_html($order->get_billing_first_name()); ?></p>
<p>Благодарим вас за покупку:</p>
<?php
foreach ($product_data as $item) {
    echo '<p>' . $item['name'] . ' - <a href="' . $item['product_link'] . '">Перейти</a></p>';
}

?>
<p>Номер заказа: <?php echo esc_html($order->get_order_number()); ?></p>
<hr>
<p>При возникновении любых трудностей, получения чека, или решения других вопросов, вы всегда можете обратиться на почту поддержки <a href="mailto:courses@evgagarina.com">courses@evgagarina.com</a></p>
<hr>
<p>С уважением, <?php echo get_bloginfo('name') ?></p>