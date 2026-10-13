/**
 * Shared editor behavior for WooCommerce product metabox repeaters.
 * Data field names remain owned by their individual PHP metaboxes.
 */
document.addEventListener('DOMContentLoaded', () => {
  const icons = window.evgProgramIcons || {};
  document.querySelectorAll('.evg-metabox-editor').forEach(editor => {
    const list = editor.querySelector('.evg-metabox-items');
    const template = editor.querySelector('template.evg-metabox-template');
    const add = editor.querySelector('.evg-metabox-add');
    if (!list || !template || !add) return;

    function updateLabel(item) {
      const input = item.querySelector('.evg-metabox-item-title');
      const label = item.querySelector('.evg-metabox-item-label');
      if (input && label) label.textContent = input.value || item.dataset.emptyLabel || 'Новый элемент';
    }
    function updateIcon(item) {
      const select = item.querySelector('.evg-program-icon');
      const preview = item.querySelector('.evg-program-icon-preview');
      if (select && preview && Object.prototype.hasOwnProperty.call(icons, select.value)) {
        preview.innerHTML = icons[select.value];
      }
    }
    function bind(item) {
      item.querySelector('.evg-metabox-item-title')?.addEventListener('input', () => updateLabel(item));
      item.querySelector('.evg-metabox-remove')?.addEventListener('click', () => item.remove());
      item.querySelector('.evg-metabox-duplicate')?.addEventListener('click', () => {
        const copy = item.cloneNode(true);
        copy.classList.remove('is-dragging');
        item.after(copy);
        bind(copy);
      });
      item.querySelector('.evg-metabox-up')?.addEventListener('click', () => {
        if (item.previousElementSibling) list.insertBefore(item, item.previousElementSibling);
      });
      item.querySelector('.evg-metabox-down')?.addEventListener('click', () => {
        if (item.nextElementSibling) list.insertBefore(item.nextElementSibling, item);
      });
      item.querySelector('.evg-program-icon')?.addEventListener('change', () => updateIcon(item));
      item.addEventListener('dragstart', event => {
        event.dataTransfer.setData('text/plain', 'move');
        event.dataTransfer.effectAllowed = 'move';
        item.classList.add('is-dragging');
      });
      item.addEventListener('dragend', () => item.classList.remove('is-dragging'));
      item.addEventListener('dragover', event => event.preventDefault());
      item.addEventListener('drop', event => {
        event.preventDefault();
        const moving = list.querySelector('.is-dragging');
        if (!moving || moving === item) return;
        const bounds = item.getBoundingClientRect();
        list.insertBefore(moving, event.clientY < bounds.top + bounds.height / 2 ? item : item.nextSibling);
      });
      updateLabel(item);
      updateIcon(item);
    }
    list.querySelectorAll('.evg-metabox-item').forEach(bind);
    add.addEventListener('click', () => {
      const item = template.content.querySelector('.evg-metabox-item')?.cloneNode(true);
      if (!item) return;
      list.appendChild(item);
      bind(item);
      item.querySelector('.evg-metabox-item-title')?.focus();
    });
  });
});
