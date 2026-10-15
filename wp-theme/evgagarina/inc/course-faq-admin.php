<?php
/** Per-product FAQ repeater metabox. */
defined('ABSPATH') || exit;

function evg_course_faq_get($product_id) {
    $data = get_post_meta($product_id, '_evg_course_faq', true);
    return is_array($data) ? $data : array();
}

add_action('add_meta_boxes_product', function() {
    add_meta_box('evg-course-faq', 'Частые вопросы', 'evg_course_faq_metabox', 'product', 'normal', 'default');
});

function evg_course_faq_metabox($post) {
    $items = evg_course_faq_get($post->ID);
    wp_nonce_field('evg_save_course_faq', 'evg_course_faq_nonce');
    ?>
    <div class="evg-faq-editor evg-metabox-editor">
        <p class="description">Добавляйте вопросы и ответы для этого курса. Порядок можно менять перетаскиванием или кнопками ↑ ↓.</p>
        <div id="evg-faq-items" class="evg-metabox-items">
            <?php foreach ($items as $item) evg_course_faq_item($item); ?>
        </div>
        <p><button type="button" class="button button-secondary evg-metabox-add" id="evg-faq-add">+ Добавить вопрос</button></p>
        <p class="description">Для сохранения нажмите «Обновить» у товара.</p>
        <template id="evg-faq-template" class="evg-metabox-template"><?php evg_course_faq_item(array('q' => '', 'a' => '')); ?></template>
    </div>
    <?php
}

function evg_course_faq_item($item) {
    $question = isset($item['q']) && is_scalar($item['q']) ? (string) $item['q'] : '';
    $answer = isset($item['a']) && is_scalar($item['a']) ? (string) $item['a'] : '';
    ?>
    <div class="evg-faq-item evg-metabox-item" data-empty-label="Новый вопрос" draggable="true">
        <div class="evg-metabox-item-head">
            <span class="dashicons dashicons-move" aria-hidden="true"></span>
            <strong class="evg-metabox-item-label"><?php echo esc_html($question ?: 'Новый вопрос'); ?></strong>
            <span class="evg-metabox-actions">
                <button type="button" class="button-link evg-metabox-up" aria-label="Вверх">↑</button>
                <button type="button" class="button-link evg-metabox-down" aria-label="Вниз">↓</button>
                <button type="button" class="button-link evg-metabox-duplicate">Дублировать</button>
                <button type="button" class="button-link-delete evg-metabox-remove">Удалить</button>
            </span>
        </div>
        <div class="evg-metabox-item-fields">
            <p><label>Вопрос<input class="widefat evg-metabox-item-title" name="evg_faq_questions[]" value="<?php echo esc_attr($question); ?>"></label></p>
            <p><label>Ответ<textarea class="widefat" rows="4" name="evg_faq_answers[]"><?php echo esc_textarea($answer); ?></textarea></label></p>
        </div>
    </div>
    <?php
}

add_action('save_post_product', function($post_id) {
    if (!isset($_POST['evg_course_faq_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['evg_course_faq_nonce'])), 'evg_save_course_faq')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) return;
    $questions = isset($_POST['evg_faq_questions']) && is_array($_POST['evg_faq_questions']) ? wp_unslash($_POST['evg_faq_questions']) : array();
    $answers = isset($_POST['evg_faq_answers']) && is_array($_POST['evg_faq_answers']) ? wp_unslash($_POST['evg_faq_answers']) : array();
    $items = array();
    foreach ($questions as $index => $question) {
        if (!is_scalar($question)) continue;
        $question = sanitize_text_field($question);
        $answer = isset($answers[$index]) && is_scalar($answers[$index]) ? sanitize_textarea_field($answers[$index]) : '';
        if ($question === '' || $answer === '') continue;
        $items[] = array('q' => $question, 'a' => $answer);
    }
    update_post_meta($post_id, '_evg_course_faq', $items);
});
