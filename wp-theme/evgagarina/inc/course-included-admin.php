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
    <div class="evg-included-editor">
        <p><label><input type="checkbox" name="evg_included_enabled" value="yes" <?php checked($data['enabled'], 'yes'); ?>> Показывать блок «Что входит»</label></p>
        <p><label for="evg-included-title"><strong>Заголовок</strong></label><input class="widefat" id="evg-included-title" name="evg_included_title" value="<?php echo esc_attr($data['title']); ?>"></p>
        <p><label for="evg-included-subtitle"><strong>Подзаголовок / описание</strong></label><textarea class="widefat" rows="3" id="evg-included-subtitle" name="evg_included_subtitle"><?php echo esc_textarea($data['subtitle']); ?></textarea></p>
        <h3>Пункты списка</h3>
        <div id="evg-included-items"><?php foreach ($data['items'] as $item) evg_course_included_item($item); ?></div>
        <p><button type="button" class="button button-secondary" id="evg-included-add">+ Добавить пункт</button></p>
        <p class="description">Перемещайте пункты за заголовок или кнопками ↑ ↓. Для сохранения нажмите «Обновить» у товара.</p>
        <template id="evg-included-template"><?php evg_course_included_item(''); ?></template>
    </div>
    <?php
}
function evg_course_included_item($value) {
    ?>
    <div class="evg-included-item" draggable="true">
        <div class="evg-included-item-head"><span class="dashicons dashicons-move" aria-hidden="true"></span><strong class="evg-included-item-label"><?php echo esc_html($value ?: 'Новый пункт'); ?></strong><span class="evg-included-actions"><button type="button" class="button-link evg-included-up" aria-label="Вверх">↑</button> <button type="button" class="button-link evg-included-down" aria-label="Вниз">↓</button> <button type="button" class="button-link evg-included-duplicate">Дублировать</button> <button type="button" class="button-link-delete evg-included-remove">Удалить</button></span></div>
        <div class="evg-included-item-fields"><label>Текст пункта<input class="widefat evg-included-item-text" name="evg_included_items[]" value="<?php echo esc_attr($value); ?>"></label></div>
    </div>
    <?php
}
add_action('save_post_product', function($post_id) {
    if (!isset($_POST['evg_course_included_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['evg_course_included_nonce'])), 'evg_save_course_included')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) return;
    $raw = isset($_POST['evg_included_items']) && is_array($_POST['evg_included_items']) ? wp_unslash($_POST['evg_included_items']) : array();
    $items = array();
    foreach ($raw as $value) {
        if (!is_scalar($value)) continue;
        $value = sanitize_text_field($value);
        if ($value !== '') $items[] = $value;
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
    wp_enqueue_style('evg-course-included-admin', EVG_URI . '/assets/css/course-included-admin.css', array(), EVG_VERSION);
    wp_enqueue_script('evg-course-included-admin', EVG_URI . '/assets/js/course-included-admin.js', array(), EVG_VERSION, true);
});
