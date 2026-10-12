(() => {
  'use strict';
  const region = document.createElement('aside');
  region.className = 'evg-notices';
  region.setAttribute('aria-label', 'Уведомления');
  document.body.append(region);
  document.documentElement.classList.add('evg-notices-ready');
  const entries = new Map();
  const selector = '.woocommerce-message, .woocommerce-error, .woocommerce-info, #coupon-status, .woocommerce-NoticeGroup, .woocommerce-notices-wrapper';

  function sync() {
    const sources = [...document.querySelectorAll(selector)].filter(node =>
      !region.contains(node) && !node.matches('.woocommerce-NoticeGroup, .woocommerce-notices-wrapper') &&
      !node.parentElement.closest('.woocommerce-message, .woocommerce-error, .woocommerce-info')
    );
    for (const [source, entry] of entries) {
      if (!source.isConnected || !source.textContent.trim()) {
        entry.card?.remove();
        entries.delete(source);
      }
    }
    for (const source of sources) {
      const signature = source.innerHTML;
      if (!source.textContent.trim() || entries.get(source)?.signature === signature) continue;
      entries.get(source)?.card?.remove();
      const card = document.createElement('div');
      card.className = 'evg-toast';
      const error = source.matches('.woocommerce-error, .checkout-error');
      card.dataset.kind = error ? 'error' : 'info';
      card.setAttribute('role', error ? 'alert' : 'status');
      const content = source.cloneNode(true);
      content.removeAttribute('id');
      content.removeAttribute('role');
      content.removeAttribute('aria-live');
      content.className = 'evg-toast-content';
      content.querySelectorAll('[id]').forEach(node => node.removeAttribute('id'));
      const close = document.createElement('button');
      close.type = 'button';
      close.className = 'evg-toast-close';
      close.setAttribute('aria-label', 'Закрыть уведомление');
      close.textContent = '×';
      close.addEventListener('click', () => {
        card.remove();
        document.querySelector('.checkout-heading, main h1, h1')?.focus({ preventScroll: true });
      });
      content.addEventListener('click', event => {
        const link = event.target.closest('a');
        if (!link) return;
        const id = link.dataset.id;
        if (id) {
          const field = document.getElementById(id);
          if (field) { event.preventDefault(); field.focus(); field.scrollIntoView({ block: 'center', behavior: 'smooth' }); }
        }
      });
      card.append(content, close);
      entries.set(source, { signature, card });
      region.append(card);
    }
  }
  const observer = new MutationObserver(records => {
    if (records.some(record => !region.contains(record.target))) sync();
  });
  observer.observe(document.body, { childList: true, subtree: true, characterData: true });
  sync();
})();
