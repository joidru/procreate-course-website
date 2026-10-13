<?php
/** Editable "What's included" for each WooCommerce product. */
defined('ABSPATH') || exit;
function evg_course_included_defaults() {
    return array(
        'enabled' => 'yes',
        'title' => 'Всё для практики — в одном курсе',
        'subtitle' => 'Вы получаете не только уроки, но и рабочие материалы: кисти, исходники, текстуры и референсы. Всё, чтобы сразу начать рисовать.',
        'items' => array(
            'Все базовые правила рисования в Procreate',
            'Построение формы и объектов',
            'Теория цвета и гармония',
            'Основы композиции',
            'Перспектива и светотень',
            'Фигура человека',
            '30 уроков в личном кабинете',
            'Авторский набор кистей Procreate',
            'Готовые исходники со слоями в Procreate',
            '20 текстур холстов большого размера',
            '45 референсов для практики',
            'Чат учеников и обратная связь',
        ),
    );
}
/**
 * Normalize legacy string items and newer structured items.
 * Older product metadata remains readable without a migration.
 */
function evg_course_included_normalize_items($items) {
    $normalized = array();
    if (!is_array($items)) return $normalized;
    foreach ($items as $item) {
        if (is_string($item)) {
            $normalized[] = array('text' => $item, 'selected' => 'no');
        } elseif (is_array($item)) {
            $normalized[] = array(
                'text' => isset($item['text']) && is_scalar($item['text']) ? (string) $item['text'] : '',
                'selected' => isset($item['selected']) && $item['selected'] === 'yes' ? 'yes' : 'no',
            );
        }
    }
    return $normalized;
}
/** Return only checked items as an array of text strings. */
function evg_course_included_get_selected($product_id) {
    $data = evg_course_included_get($product_id);
    $selected = array();
    foreach (evg_course_included_normalize_items($data['items']) as $item) {
        if ($item['selected'] === 'yes' && $item['text'] !== '') {
            $selected[] = $item['text'];
        }
    }
    return $selected;
}
function evg_course_included_get($product_id) {
    $data = get_post_meta($product_id, '_evg_course_included', true);
    return is_array($data) ? wp_parse_args($data, evg_course_included_defaults()) : evg_course_included_defaults();
}
add_action('add_meta_boxes_product', function() {
    add_meta_box('evg-course-included', 'Что входит в курс', 'evg_course_included_metabox', 'product', 'normal', 'default');
});
function evg_course_included_metabox($post) {
    $data = evg_course_included_get($post->ID);
    wp_nonce_field('evg_save_course_included', 'evg_course_included_nonce');
    ?>
    <div class="evg-included-editor evg-metabox-editor">
        <p><label><input type="checkbox" name="evg_included_enabled" value="yes" <?php checked($data['enabled'], 'yes'); ?>> Показывать блок «Что входит»</label></p>
        <p><label for="evg-included-title"><strong>Заголовок</strong></label><input class="widefat" id="evg-included-title" name="evg_included_title" value="<?php echo esc_attr($data['title']); ?>"></p>
        <p><label for="evg-included-subtitle"><strong>Подзаголовок / описание</strong></label><textarea class="widefat" rows="3" id="evg-included-subtitle" name="evg_included_subtitle"><?php echo esc_textarea($data['subtitle']); ?></textarea></p>
        <h3>Пункты списка</h3>
        <div id="evg-included-items" class="evg-metabox-items"><?php foreach ($data['items'] as $item) evg_course_included_item($item); ?></div>
        <p><button type="button" class="button button-secondary evg-metabox-add" id="evg-included-add">+ Добавить пункт</button></p>
        <p class="description">Перемещайте пункты за заголовок или кнопками ↑ ↓. Для сохранения нажмите «Обновить» у товара.</p>
        <template id="evg-included-template" class="evg-metabox-template"><?php evg_course_included_item(array('text' => '', 'selected' => 'no')); ?></template>
    </div>
    <?php
}
function evg_course_included_item($value) {
    $item = evg_course_included_normalize_items(array($value));
    $item = $item[0];
    $text = $item['text'];
    ?>
    <div class="evg-included-item evg-metabox-item" data-empty-label="Новый пункт" draggable="true">
        <div class="evg-included-item-head evg-metabox-item-head">
            <span class="dashicons dashicons-move" aria-hidden="true"></span>
            <strong class="evg-included-item-label evg-metabox-item-label"><?php echo esc_html($text ?: 'Новый пункт'); ?></strong>
            <span class="evg-included-actions evg-metabox-actions">
                <button type="button" class="button-link evg-included-up evg-metabox-up" aria-label="Вверх">↑</button>
                <button type="button" class="button-link evg-included-down evg-metabox-down" aria-label="Вниз">↓</button>
                <button type="button" class="button-link evg-included-duplicate evg-metabox-duplicate">Дублировать</button>
                <button type="button" class="button-link-delete evg-included-remove evg-metabox-remove">Удалить</button>
            </span>
        </div>
        <div class="evg-included-item-fields evg-metabox-item-fields">
            <p><label>Текст пункта<input class="widefat evg-included-item-text evg-metabox-item-title" name="evg_included_items[]" value="<?php echo esc_attr($text); ?>"></label></p>
            <p><label><input type="checkbox" class="evg-included-item-selected" <?php checked($item['selected'], 'yes'); ?>> Показывать в дополнительном блоке</label></p>
            <input type="hidden" class="evg-included-item-selected-value" name="evg_included_selected[]" value="<?php echo esc_attr($item['selected']); ?>">
        </div>
    </div>
    <?php
}
add_action('save_post_product', function($post_id) {
    if (!isset($_POST['evg_course_included_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['evg_course_included_nonce'])), 'evg_save_course_included')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) return;
    $raw = isset($_POST['evg_included_items']) && is_array($_POST['evg_included_items']) ? wp_unslash($_POST['evg_included_items']) : array();
    $flags = isset($_POST['evg_included_selected']) && is_array($_POST['evg_included_selected']) ? wp_unslash($_POST['evg_included_selected']) : array();
    $items = array();
    foreach ($raw as $index => $value) {
        if (!is_scalar($value)) continue;
        $value = sanitize_text_field($value);
        if ($value === '') continue;
        $items[] = array(
            'text' => $value,
            'selected' => isset($flags[$index]) && $flags[$index] === 'yes' ? 'yes' : 'no',
        );
    }
    update_post_meta($post_id, '_evg_course_included', array(
        'enabled' => isset($_POST['evg_included_enabled']) ? 'yes' : 'no',
        'title' => isset($_POST['evg_included_title']) && is_scalar($_POST['evg_included_title']) ? sanitize_text_field(wp_unslash($_POST['evg_included_title'])) : '',
        'subtitle' => isset($_POST['evg_included_subtitle']) && is_scalar($_POST['evg_included_subtitle']) ? sanitize_textarea_field(wp_unslash($_POST['evg_included_subtitle'])) : '',
        'items' => $items,
    ));
});
add_action('admin_enqueue_scripts', function($hook) {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'product' || !in_array($hook, array('post.php', 'post-new.php'), true)) return;
    wp_enqueue_style('evg-product-metaboxes', EVG_URI . '/assets/css/product-metaboxes.css', array(), EVG_VERSION);
    wp_enqueue_script('evg-product-metaboxes', EVG_URI . '/assets/js/product-metaboxes.js', array(), EVG_VERSION, true);
});
