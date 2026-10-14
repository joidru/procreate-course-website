<?php
/**
 * Completed order email, styled for the course website.
 *
 * @package evgagarina
 * @version 3.7.0
 */
defined('ABSPATH') || exit;
if (! $order instanceof WC_Order) {
    return;
}

$customer_name = $order->get_billing_first_name();
if (! $customer_name) {
    $customer_name = $order->get_meta('_billing_full_name');
}
$customer_name = $customer_name ? ', ' . $customer_name : '';
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/');
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;padding:0;background:#101010;color:#efeae5;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#101010;"><tr><td align="center" style="padding:32px 14px;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#1d1d1d;border:1px solid #393235;border-radius:16px;">
<tr><td style="padding:30px 32px 16px;font-size:18px;font-weight:bold;letter-spacing:.04em;color:#efeae5;"><?php echo esc_html(get_bloginfo('name')); ?></td></tr>
<tr><td style="padding:0 32px 18px;"><h1 style="font-size:27px;line-height:1.3;margin:0;color:#efeae5;">Спасибо за покупку!</h1></td></tr>
<tr><td style="padding:0 32px 20px;color:#d4cbc6;font-size:15px;line-height:1.7;">Здравствуйте<?php echo esc_html($customer_name); ?>! Ваш заказ № <?php echo esc_html($order->get_order_number()); ?> выполнен.</td></tr>
<?php foreach ($order->get_items('line_item') as $item) :
    $product = $item->get_product();
    $course_id = absint($item->get_meta('_llms_pid', true));
    $course_url = $course_id && 'publish' === get_post_status($course_id) ? get_permalink($course_id) : '';
    if (! $course_url && $product) {
        $course_url = get_permalink($product->get_parent_id() ?: $product->get_id());
    }
    ?>
<tr><td style="padding:0 32px 14px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#262326;border:1px solid #453940;border-radius:12px;"><tr><td style="padding:20px;">
<div style="font-size:17px;font-weight:bold;line-height:1.4;color:#efeae5;"><?php echo esc_html($item->get_name()); ?></div>
<?php if ($course_url) : ?>
<div style="padding-top:18px;"><a href="<?php echo esc_url($course_url); ?>" style="display:inline-block;padding:12px 20px;border-radius:9px;background:#ef4d96;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;">Перейти к курсу</a></div>
<?php endif; ?>
<?php if ($product && $product->is_downloadable()) :
    foreach ($item->get_item_downloads() as $download) : ?>
<div style="padding-top:12px;"><a href="<?php echo esc_url($download['download_url']); ?>" style="color:#ff89ba;text-decoration:underline;"><?php echo esc_html($download['name']); ?></a></div>
<?php endforeach; endif; ?>
</td></tr></table>
</td></tr>
<?php endforeach; ?>
<tr><td style="padding:12px 32px 28px;color:#d4cbc6;font-size:14px;line-height:1.7;">Все доступные курсы можно найти в <a href="<?php echo esc_url($account_url); ?>" style="color:#ff89ba;">личном кабинете</a>.</td></tr>
<tr><td style="padding:22px 32px;border-top:1px solid #393235;color:#a69c96;font-size:13px;line-height:1.7;">Нужна помощь или чек? Напишите на <a href="mailto:courses@evgagarina.com" style="color:#ff89ba;">courses@evgagarina.com</a>.<br><?php echo esc_html(get_bloginfo('name')); ?></td></tr>
</table></td></tr></table>
</body></html>
