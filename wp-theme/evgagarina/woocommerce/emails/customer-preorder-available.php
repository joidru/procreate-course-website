<?php
/**
 * Course launch notification. Receives a WC_Product as $product.
 *
 * @package evgagarina
 */
defined('ABSPATH') || exit;
if (! isset($product) || ! $product instanceof WC_Product) {
    return;
}
$account_url = home_url('/account/my-courses/');
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;padding:0;background:#101010;color:#efeae5;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#101010;"><tr><td align="center" style="padding:32px 14px;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#1d1d1d;border:1px solid #393235;border-radius:16px;">
<tr><td style="padding:30px 32px 16px;font-size:18px;font-weight:bold;letter-spacing:.04em;color:#efeae5;"><?php echo esc_html(get_bloginfo('name')); ?></td></tr>
<tr><td style="padding:0 32px 18px;"><h1 style="font-size:27px;line-height:1.3;margin:0;color:#efeae5;">Курс уже доступен!</h1></td></tr>
<tr><td style="padding:0 32px 24px;color:#d4cbc6;font-size:15px;line-height:1.7;">Здравствуйте! Стартовал курс <strong style="color:#efeae5;">«<?php echo esc_html($product->get_name()); ?>»</strong>. Уроки уже ждут вас в личном кабинете.</td></tr>
<tr><td style="padding:0 32px 28px;"><a href="<?php echo esc_url($account_url); ?>" style="display:inline-block;padding:14px 22px;background:#ef4d96;color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;border-radius:9px;">Начать обучение</a></td></tr>
<tr><td style="padding:0 32px 24px;color:#a69c96;font-size:13px;line-height:1.7;">Приятного просмотра и обучения!</td></tr>
<tr><td style="padding:22px 32px;border-top:1px solid #393235;color:#a69c96;font-size:13px;line-height:1.7;">Если возникнут вопросы, напишите на <a href="mailto:courses@evgagarina.com" style="color:#ff89ba;">courses@evgagarina.com</a>.<br><?php echo esc_html(get_bloginfo('name')); ?></td></tr>
</table></td></tr></table>
</body></html>
