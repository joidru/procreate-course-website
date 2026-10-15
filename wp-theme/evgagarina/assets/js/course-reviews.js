document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.evg-course-reviews').forEach(section => {
    const {productId, nonce, ajaxUrl} = section.dataset;
    const modal = section.querySelector('.evg-review-modal');
    const write = section.querySelector('.evg-reviews-write');
    const more = section.querySelector('.evg-reviews-more');
    const list = section.querySelector('#evg-reviews-list');
    let page = 1, loading = false, previousFocus = null;
    const open = () => {
      if (!modal) return;
      previousFocus = document.activeElement;
      modal.hidden = false;
      document.body.classList.add('evg-review-modal-open');
      modal.querySelector('.evg-review-modal__panel').focus();
    };
    const close = () => {
      if (!modal) return;
      modal.hidden = true;
      document.body.classList.remove('evg-review-modal-open');
      if (previousFocus) previousFocus.focus();
    };
    if (write) write.addEventListener('click', open);
    if (modal) {
      modal.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', close));
      document.addEventListener('keydown', e => {
        if (modal.hidden) return;
        if (e.key === 'Escape') close();
        if (e.key === 'Tab') {
          const focusable = Array.from(modal.querySelectorAll('button:not([disabled]), input:not([disabled]), textarea:not([disabled])')).filter(el => el.getClientRects().length);
          if (!focusable.length) return;
          const first = focusable[0], last = focusable[focusable.length - 1];
          if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
          else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
      });
      const form = modal.querySelector('.evg-review-form');
      form.addEventListener('submit', async e => {
        e.preventDefault();
        const error = form.querySelector('.evg-review-form-error');
        error.hidden = true;
        const submit = form.querySelector('button[type="submit"]');
        const data = new FormData(form);
        data.append('action', 'evg_course_review_submit');
        data.append('nonce', nonce);
        data.append('product_id', productId);
        submit.disabled = true;
        try {
          const response = await fetch(ajaxUrl, {method:'POST', body:data, credentials:'same-origin'});
          const result = await response.json();
          if (!result.success) throw new Error(typeof result.data === 'string' ? result.data : 'Не удалось отправить отзыв.');
          modal.querySelector('.evg-review-form-view').hidden = true;
          modal.querySelector('.evg-review-success').hidden = false;
          if (write) write.remove();
          const note = section.querySelector('.evg-reviews-eligibility');
          if (note) note.textContent = 'Вы уже отправили отзыв об этом курсе.';
          modal.querySelector('.evg-review-success button').focus();
        } catch (err) {
          error.textContent = err.message || 'Ошибка отправки.';
          error.hidden = false;
        } finally { submit.disabled = false; }
      });
    }
    if (more) more.addEventListener('click', async () => {
      if (loading) return;
      loading = true; more.disabled = true;
      const data = new URLSearchParams({action:'evg_course_reviews_more', nonce, product_id:productId, page:String(page + 1)});
      try {
        const response = await fetch(ajaxUrl, {method:'POST', body:data, credentials:'same-origin'});
        const result = await response.json();
        if (!result.success) throw new Error('Ошибка загрузки');
        list.insertAdjacentHTML('beforeend', result.data.html);
        page++;
        if (!result.data.has_more) more.remove();
        else {
          const remaining = section.querySelector('.evg-reviews-remaining');
          if (remaining) remaining.textContent = '(' + Math.max(0, parseInt(remaining.textContent.replace(/\D/g, ''), 10) - 3) + ')';
        }
      } catch (err) { more.textContent = 'Не удалось загрузить. Попробовать ещё'; }
      finally { loading = false; more.disabled = false; }
    });
  });
});
