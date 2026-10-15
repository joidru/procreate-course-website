<?php
/** Course reviews. */
defined('ABSPATH') || exit;
global $product;
if (!$product instanceof WC_Product) return;
$id = $product->get_id();
$total = (int) get_comments(array('post_id' => $id, 'type' => 'review', 'status' => 'approve', 'count' => true));
$comments = get_comments(array('post_id' => $id, 'type' => 'review', 'status' => 'approve', 'number' => 3, 'orderby' => 'comment_date_gmt', 'order' => 'DESC'));
$sum = 0;
$all_ratings = get_comments(array('post_id' => $id, 'type' => 'review', 'status' => 'approve', 'fields' => 'ids'));
foreach ($all_ratings as $comment_id) $sum += (int) get_comment_meta($comment_id, 'rating', true);
$average = $total ? number_format_i18n($sum / $total, 1) : '';
$eligible = evg_course_review_can_post($id);
$existing = is_user_logged_in() && evg_course_review_existing($id, get_current_user_id());
?>
<section id="course-reviews" class="evg-course-reviews mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24" data-product-id="<?php echo esc_attr($id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('evg_course_reviews')); ?>" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
    <div class="evg-reviews-heading">
        <div class="evg-reviews-heading__text">
            <p class="text-sm font-medium text-primary">Отзывы учеников</p>
            <h2 class="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">Что говорят о курсе</h2>
            <p class="mt-4 text-pretty text-base leading-relaxed text-muted-foreground">Реальные истории, работы и впечатления учеников, которые уже прошли этот курс и применяют знания на практике.</p>
        </div>
        <div class="evg-reviews-heading__actions">
            <div class="evg-reviews-heading__row">
                <?php if ($total) : ?><div class="evg-reviews-summary"><strong><?php echo esc_html($average); ?></strong><span><span class="evg-review-stars" aria-label="Рейтинг курса">★★★★★</span><small><?php echo esc_html(sprintf('На основе %d отзывов', $total)); ?></small></span></div><?php endif; ?>
                <?php if ($eligible && !$existing) : ?><button type="button" class="evg-reviews-write evg-gradient-button">Написать отзыв <?php echo evg_icon('arrow-right', 16); ?></button><?php endif; ?>
            </div>
            <p class="evg-reviews-eligibility">
                <?php if ($existing) : ?>Вы уже отправили отзыв об этом курсе.
                <?php elseif (!$eligible) : ?><?php echo evg_icon('lock', 16); ?> Оставить отзыв могут только ученики курса после покупки.
                <?php else : ?>Поделитесь впечатлениями и покажите свою работу.
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php if ($total) : ?>
        <div class="evg-reviews-grid" id="evg-reviews-list"><?php foreach ($comments as $comment) evg_course_review_card($comment); ?></div>
        <?php if ($total > 3) : ?><div class="evg-reviews-more-wrap"><button type="button" class="evg-reviews-more">Показать ещё отзывы <span class="evg-reviews-remaining">(<?php echo esc_html($total - 3); ?>)</span> <?php echo evg_icon('chevron-down', 18); ?></button></div><?php endif; ?>
    <?php else : ?>
        <p class="evg-reviews-empty">Пока нет отзывов об этом курсе. Станьте первым, кто поделится впечатлениями!</p>
    <?php endif; ?>
    <?php if ($eligible && !$existing) : ?>
    <div class="evg-review-modal" hidden>
        <div class="evg-review-modal__backdrop" data-close></div>
        <div class="evg-review-modal__panel" role="dialog" aria-modal="true" aria-labelledby="evg-review-modal-title" tabindex="-1">
            <button type="button" class="evg-review-modal__close" aria-label="Закрыть" data-close>×</button>
            <div class="evg-review-form-view">
                <h3 id="evg-review-modal-title">Написать отзыв</h3>
                <p class="evg-review-muted">Поделитесь своим опытом — это поможет другим ученикам.</p>
                <form class="evg-review-form" enctype="multipart/form-data">
                    <label>Ваша оценка <span class="evg-required">*</span></label>
                    <div class="evg-review-rating" role="radiogroup" aria-label="Оценка курса">
                        <?php for ($i=5; $i>=1; $i--) : ?><label><input type="radio" name="rating" value="<?php echo esc_attr($i); ?>" <?php checked($i, 5); ?> required><span aria-label="<?php echo esc_attr($i . ' из 5'); ?>">★</span></label><?php endfor; ?>
                    </div>
                    <label for="evg-review-body">Ваш отзыв <span class="evg-required">*</span></label>
                    <textarea id="evg-review-body" name="review" rows="5" minlength="10" maxlength="3000" required placeholder="Расскажите, что вам понравилось и чему вы научились..."></textarea>
                    <label for="evg-review-image">Работа ученика (необязательно)</label>
                    <input id="evg-review-image" type="file" name="review_image" accept="image/jpeg,image/png,image/webp">
                    <small class="evg-review-muted">JPG, PNG или WebP, до 5 МБ</small>
                    <p class="evg-review-form-error" role="alert" hidden></p>
                    <button class="evg-gradient-button evg-review-submit" type="submit">Отправить отзыв</button>
                    <small class="evg-review-muted">Отзыв появится после проверки модератором.</small>
                </form>
            </div>
            <div class="evg-review-success" hidden><div class="evg-review-success__icon">✓</div><h3>Спасибо за ваш отзыв!</h3><p>Мы отправили его на модерацию. После проверки он появится на странице курса.</p><button type="button" class="evg-gradient-button" data-close>Закрыть</button></div>
        </div>
    </div>
    <?php endif; ?>
</section>
