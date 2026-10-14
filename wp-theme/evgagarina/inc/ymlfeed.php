<?php

add_action('save_post', 'my_custom_save_function', 10, 3);

function my_custom_save_function($post_id, $post, $update)
{
    // Проверяем, не автосохранение ли это
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Проверяем права пользователя
    if (! current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Выполняем нужный код, например, для определенного типа поста (book)
    if ('product' === $post->post_type) {
        $args = [
            'status' => 'publish',
            'limit'  => -1,
        ];
        $products = wc_get_products($args);

        $yml = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<yml_catalog date="' . wp_date('Y-m-d\TH:i') . '">
    <shop>
    <name>Евгения Гагарина</name>
    <url>https://evgagarina.com/</url>
    <email>courses@evgagarina.com</email>
    <picture>https://evgagarina.com/wp-content/uploads/2024/09/cropped-logo.png</picture>
    <description>Онлайн школа по рисованию в Procreate Евгении Гагариной</description>
    <currencies><currency id="RUR" rate="1"/></currencies>
    <offers>
';

        foreach ($products as $product) {
            $programms = evg_course_program_get($product->get_id());
            if ($programms['enabled'] !== 'yes') return;
            $_duration = $product->get_meta('_duration');
            $_duration_count = $product->get_meta('_duration_count');
            if (!$_duration_count) continue;
            $yml .= '<offer id="' . $product->get_id() . '">';
            $yml .= '<name>' . $product->get_name() . '</name>';
            $yml .= '<url>' . $product->get_permalink() . '</url>';
            $yml .= '<categoryId>804</categoryId>';
            $yml .= '<price>' . $product->get_regular_price() . '</price>';
            $yml .= '<currencyId>RUR</currencyId>';
            if ($product->is_on_sale()) {
                $yml .= '<param name="Цена по скидке">' . $product->get_sale_price() . '</param>';
            }
            $yml .= '<picture>' . wp_get_attachment_image_url($product->get_image_id(), 'medium') . '</picture>';
            $yml .= '<description>' . $product->get_short_description() . '</description>';

            $yml .= '<param name="Формат обучения">Самостоятельно</param>';
            if ($_duration_count && $_duration) {
                $yml .= '<param name="Продолжительность" unit="' . $_duration . '">' . $_duration_count . '</param>';
            }

            foreach ($programms['items'] as $item) {

                if (!$item['title'] && !empty($item['description'])) {
                    $yml .= '<param name="План" unit="' . esc_html($item['title']) . '">' . esc_html($item['description']) . '</param>';
                }
            }

            $yml .= '</offer>';
        }

        $yml .= '</offers></shop></yml_catalog>';
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['basedir'] . '/feed-yml-0.xml';

        file_put_contents($file_path, $yml);
    }
}
