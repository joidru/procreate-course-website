<?php

/** Course-specific WooCommerce reviews and submissions. */
defined('ABSPATH') || exit;

function evg_course_review_can_post($product_id)
{
    if (!is_user_logged_in() || !function_exists('wc_customer_bought_product')) return false;
    $user = wp_get_current_user();
    return wc_customer_bought_product($user->user_email, $user->ID, $product_id);
}

function evg_course_review_existing($product_id, $user_id)
{
    $comments = get_comments(array(
        'post_id' => $product_id,
        'user_id' => $user_id,
        'type' => 'review',
        'status' => 'all',
        'count' => true,
    ));
    return (int) $comments > 0;
}

function evg_course_review_card($comment)
{
    $rating = max(1, min(5, (int) get_comment_meta($comment->comment_ID, 'rating', true)));
    $image_id = absint(get_comment_meta($comment->comment_ID, '_evg_review_image', true));
    $name = $comment->comment_author;
?>
    <article class="evg-review-card">
        <div class="evg-review-card__top">
            <?php echo get_avatar($comment, 44, '', '', array('class' => 'evg-review-avatar')); ?>
            <div class="evg-review-person"><strong><?php echo esc_html($name); ?></strong><span><?php echo esc_html(get_comment_date(get_option('date_format'), $comment)); ?></span></div>
            <span class="evg-review-stars" aria-label="<?php echo esc_attr(sprintf('Оценка: %d из 5', $rating)); ?>"><?php echo esc_html(str_repeat('★', $rating) . str_repeat('☆', 5 - $rating)); ?></span>
        </div>
        <div class="evg-review-card__text"><?php echo wpautop(esc_html($comment->comment_content)); ?></div>
        <?php if ($image_id) : ?>
            <a class="evg-review-card__image" href="<?php echo esc_url(wp_get_attachment_url($image_id)); ?>" target="_blank" rel="noopener noreferrer">
                <?php echo wp_get_attachment_image($image_id, 'medium_large', false, array('loading' => 'lazy', 'alt' => 'Работа ученика')); ?>
            </a>
        <?php endif; ?>
    </article>
<?php
}

function evg_course_reviews_fetch()
{
    check_ajax_referer('evg_course_reviews', 'nonce');
    $product_id = absint($_POST['product_id'] ?? 0);
    $page = max(1, absint($_POST['page'] ?? 1));
    if ('product' !== get_post_type($product_id) || 'publish' !== get_post_status($product_id)) wp_send_json_error('Курс не найден.', 404);
    $query = new WP_Comment_Query(array(
        'post_id' => $product_id,
        'type' => 'review',
        'status' => 'approve',
        'number' => 3,
        'offset' => ($page - 1) * 3,
        'orderby' => 'comment_date_gmt',
        'order' => 'DESC',
    ));
    ob_start();
    foreach ($query->comments as $comment) evg_course_review_card($comment);
    $html = ob_get_clean();
    $total = (int) get_comments(array('post_id' => $product_id, 'type' => 'review', 'status' => 'approve', 'count' => true));
    wp_send_json_success(array('html' => $html, 'has_more' => $page * 3 < $total));
}
add_action('wp_ajax_evg_course_reviews_more', 'evg_course_reviews_fetch');
add_action('wp_ajax_nopriv_evg_course_reviews_more', 'evg_course_reviews_fetch');

function evg_course_review_submit()
{
    check_ajax_referer('evg_course_reviews', 'nonce');
    $product_id = absint($_POST['product_id'] ?? 0);
    if ('product' !== get_post_type($product_id) || 'publish' !== get_post_status($product_id)) wp_send_json_error('Курс не найден.', 404);
    if (!evg_course_review_can_post($product_id)) wp_send_json_error('Оставить отзыв могут только покупатели курса.', 403);
    $user = wp_get_current_user();
    if (evg_course_review_existing($product_id, $user->ID)) wp_send_json_error('Вы уже оставили отзыв об этом курсе.', 409);
    $rating = absint($_POST['rating'] ?? 0);
    $body = trim(sanitize_textarea_field(wp_unslash($_POST['review'] ?? '')));
    if ($rating < 1 || $rating > 5 || mb_strlen($body) < 10 || mb_strlen($body) > 3000) wp_send_json_error('Выберите оценку и напишите отзыв от 10 до 3000 символов.', 400);

    $image_id = 0;
    if (!empty($_FILES['review_image']['name'])) {
        $file = $_FILES['review_image'];
        if ((int) $file['size'] > 5 * 1024 * 1024 || (int) $file['error'] !== UPLOAD_ERR_OK) wp_send_json_error('Изображение должно быть не больше 5 МБ.', 400);
        $info = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
        if (!in_array($info['ext'] ?? '', array('jpg', 'jpeg', 'png', 'webp'), true)) wp_send_json_error('Поддерживаются JPG, PNG и WebP.', 400);
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        $image_id = media_handle_upload('review_image', $product_id, array(), array('test_form' => false));
        if (is_wp_error($image_id)) wp_send_json_error('Не удалось загрузить изображение.', 400);
    }

    $comment_id = wp_insert_comment(array(
        'comment_post_ID' => $product_id,
        'comment_author' => $user->display_name,
        'comment_author_email' => $user->user_email,
        'user_id' => $user->ID,
        'comment_content' => $body,
        'comment_type' => 'review',
        'comment_approved' => 0,
        'comment_date' => current_time('mysql'),
        'comment_date_gmt' => current_time('mysql', true),
    ));
    if (!$comment_id) {
        if ($image_id) wp_delete_attachment($image_id, true);
        wp_send_json_error('Не удалось сохранить отзыв.', 500);
    }
    update_comment_meta($comment_id, 'rating', $rating);
    if ($image_id) update_comment_meta($comment_id, '_evg_review_image', $image_id);
    wp_send_json_success(array('message' => 'Спасибо! Ваш отзыв отправлен на модерацию.'));
}
add_action('wp_ajax_evg_course_review_submit', 'evg_course_review_submit');
