<?php
/** Per-product editable course program. */
defined('ABSPATH') || exit;

function evg_course_program_icons() {
    return array(
        'boxes' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-boxes h-5 w-5" aria-hidden="true">
<path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z" />
<path d="m7 16.5-4.74-2.85" />
<path d="m7 16.5 5-3" />
<path d="M7 16.5v5.17" />
<path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z" />
<path d="m17 16.5-5-3" />
<path d="m17 16.5 4.74-2.85" />
<path d="M17 16.5v5.17" />
<path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z" />
<path d="M12 8 7.26 5.15" />
<path d="m12 8 4.74-2.85" />
<path d="M12 13.5V8" />
</svg>',
        'compass' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-compass h-5 w-5" aria-hidden="true">
<circle cx="12" cy="12" r="10" />
<path d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z" />
</svg>',
        'contrast' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-contrast h-5 w-5" aria-hidden="true">
<circle cx="12" cy="12" r="10" />
<path d="M12 18a6 6 0 0 0 0-12v12z" />
</svg>',
        'palette' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-palette h-5 w-5" aria-hidden="true">
<path d="M12 22a1 1 0 0 1 0-20 10 9 0 0 1 10 9 5 5 0 0 1-5 5h-2.25a1.75 1.75 0 0 0-1.4 2.8l.3.4a1.75 1.75 0 0 1-1.4 2.8z" />
<circle cx="13.5" cy="6.5" r=".5" fill="currentColor" />
<circle cx="17.5" cy="10.5" r=".5" fill="currentColor" />
<circle cx="6.5" cy="12.5" r=".5" fill="currentColor" />
<circle cx="8.5" cy="7.5" r=".5" fill="currentColor" />
</svg>',
        'layout-grid' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid h-5 w-5" aria-hidden="true">
<rect width="7" height="7" x="3" y="3" rx="1" />
<rect width="7" height="7" x="14" y="3" rx="1" />
<rect width="7" height="7" x="14" y="14" rx="1" />
<rect width="7" height="7" x="3" y="14" rx="1" />
</svg>',
        'person-standing' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-person-standing h-5 w-5" aria-hidden="true">
<circle cx="12" cy="5" r="1" />
<path d="m9 20 3-6 3 6" />
<path d="m6 8 6 2 6-2" />
<path d="M12 10v4" />
</svg>',
    );
}
function evg_course_program_defaults() {
    return array(
        'enabled' => 'yes',
        'title' => 'Все базовые правила рисования за 30 уроков',
        'subtitle' => 'Каждый блок — это отдельный навык. Мы идём последовательно: от простого к сложному, чтобы к концу курса вы могли собрать полноценную работу.',
        'items' => array(
            array('icon'=>'boxes','title'=>'Форма и объём','description'=>'Строим объекты от простых геометрических форм к сложным, чувствуем массу и пространство.'),
            array('icon'=>'compass','title'=>'Перспектива','description'=>'Линейная и воздушная перспектива, работа с горизонтом и точками схода.'),
            array('icon'=>'contrast','title'=>'Светотень','description'=>'Свет, тень, полутень и рефлексы — как объём рождается из освещения.'),
            array('icon'=>'palette','title'=>'Теория цвета','description'=>'Цветовой круг, гармонии и настроение, работа с палитрами в Procreate.'),
            array('icon'=>'layout-grid','title'=>'Композиция','description'=>'Основные правила построения кадра, ритм, баланс и акценты в работе.'),
            array('icon'=>'person-standing','title'=>'Фигура человека','description'=>'Базовые пропорции, построение и движение человеческой фигуры.'),
        ),
    );
}
function evg_course_program_get($product_id) {
    $saved = get_post_meta($product_id, '_evg_course_program', true);
    return is_array($saved) ? wp_parse_args($saved, evg_course_program_defaults()) : evg_course_program_defaults();
}
add_action('add_meta_boxes_product', function() {
    add_meta_box('evg-course-program', 'Программа курса', 'evg_course_program_metabox', 'product', 'normal', 'default');
});
function evg_course_program_metabox($post) {
    $data = evg_course_program_get($post->ID);
    wp_nonce_field('evg_save_course_program', 'evg_course_program_nonce');
    ?>
    <div class="evg-program-editor evg-metabox-editor">
        <p><label><input type="checkbox" name="evg_program_enabled" value="yes" <?php checked($data['enabled'], 'yes'); ?>> Показывать программу курса</label></p>
        <p><label for="evg-program-title"><strong>Заголовок</strong></label><input class="widefat" id="evg-program-title" name="evg_program_title" value="<?php echo esc_attr($data['title']); ?>"></p>
        <p><label for="evg-program-subtitle"><strong>Подзаголовок / описание</strong></label><textarea class="widefat" rows="3" id="evg-program-subtitle" name="evg_program_subtitle"><?php echo esc_textarea($data['subtitle']); ?></textarea></p>
        <h3>Карточки программы</h3>
        <div id="evg-program-items" class="evg-metabox-items">
        <?php foreach ($data['items'] as $item) { evg_course_program_item($item); } ?>
        </div>
        <p><button type="button" class="button button-secondary evg-metabox-add" id="evg-program-add">+ Добавить карточку</button></p>
        <p class="description">Перетаскивайте карточки за заголовок для сортировки. Изменения сохраняются кнопкой «Обновить» товара.</p>
        <template id="evg-program-template" class="evg-metabox-template"><?php evg_course_program_item(array('icon'=>'boxes','title'=>'','description'=>'')); ?></template>
    </div>
    <?php
}
function evg_course_program_item($item) {
    $icons = evg_course_program_icons();
    ?>
    <div class="evg-program-item evg-metabox-item" data-empty-label="Новая карточка" draggable="true">
        <div class="evg-program-item-head evg-metabox-item-head"><span class="dashicons dashicons-move" aria-hidden="true"></span> <strong class="evg-program-item-label evg-metabox-item-label"><?php echo esc_html($item['title'] ?: 'Новая карточка'); ?></strong><span class="evg-program-actions evg-metabox-actions"><button type="button" class="button-link evg-program-up evg-metabox-up">↑</button> <button type="button" class="button-link evg-program-down evg-metabox-down">↓</button> <button type="button" class="button-link evg-program-duplicate evg-metabox-duplicate">Дублировать</button> <button type="button" class="button-link-delete evg-program-remove evg-metabox-remove">Удалить</button></span></div>
        <div class="evg-program-item-fields evg-metabox-item-fields">
            <p><label>Иконка <select class="evg-program-icon" name="evg_program_items[icon][]"><?php foreach ($icons as $key => $markup) : ?><option value="<?php echo esc_attr($key); ?>" <?php selected($item['icon'], $key); ?>><?php echo esc_html($key); ?></option><?php endforeach; ?></select></label> <span class="evg-program-icon-preview" aria-hidden="true"><?php echo $icons[isset($icons[$item['icon']]) ? $item['icon'] : 'boxes']; // Safe, predefined SVG only. ?></span></p>
            <p><label>Заголовок<input class="widefat evg-program-item-title evg-metabox-item-title" name="evg_program_items[title][]" value="<?php echo esc_attr($item['title']); ?>"></label></p>
            <p><label>Описание<textarea class="widefat" rows="3" name="evg_program_items[description][]"><?php echo esc_textarea($item['description']); ?></textarea></label></p>
        </div>
    </div>
    <?php
}
add_action('save_post_product', function($post_id) {
    if (!isset($_POST['evg_course_program_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['evg_course_program_nonce'])), 'evg_save_course_program')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) return;
    $raw = isset($_POST['evg_program_items']) && is_array($_POST['evg_program_items']) ? wp_unslash($_POST['evg_program_items']) : array();
    $icons = evg_course_program_icons();
    $items = array();
    $titles = isset($raw['title']) && is_array($raw['title']) ? $raw['title'] : array();
    foreach ($titles as $i => $title) {
        if (!is_scalar($title)) continue;
        $title = sanitize_text_field($title);
        $description = isset($raw['description'][$i]) && is_scalar($raw['description'][$i]) ? sanitize_textarea_field($raw['description'][$i]) : '';
        $icon = isset($raw['icon'][$i]) && is_scalar($raw['icon'][$i]) ? sanitize_key($raw['icon'][$i]) : 'boxes';
        if ($title === '' && $description === '') continue;
        $items[] = array('title'=>$title,'description'=>$description,'icon'=>isset($icons[$icon]) ? $icon : 'boxes');
    }
    update_post_meta($post_id, '_evg_course_program', array(
        'enabled' => isset($_POST['evg_program_enabled']) ? 'yes' : 'no',
        'title' => isset($_POST['evg_program_title']) ? sanitize_text_field(wp_unslash($_POST['evg_program_title'])) : '',
        'subtitle' => isset($_POST['evg_program_subtitle']) ? sanitize_textarea_field(wp_unslash($_POST['evg_program_subtitle'])) : '',
        'items' => $items,
    ));
});
add_action('admin_enqueue_scripts', function($hook) {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'product' || !in_array($hook, array('post.php','post-new.php'), true)) return;
    wp_enqueue_style('evg-product-metaboxes', EVG_URI . '/assets/css/product-metaboxes.css', array(), EVG_VERSION);
    wp_enqueue_script('evg-product-metaboxes', EVG_URI . '/assets/js/product-metaboxes.js', array(), EVG_VERSION, true);
    wp_add_inline_script('evg-product-metaboxes', 'window.evgProgramIcons = ' . wp_json_encode(evg_course_program_icons()) . ';', 'before');
});
