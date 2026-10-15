document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.evg-course-reviews').forEach(section => {
    const {productId, nonce, ajaxUrl} = section.dataset;
    const modal = section.querySelector('.evg-review-modal');
    const write = section.querySelector('.evg-reviews-write');
    const more = section.querySelector('.evg-reviews-more');
    const list = section.querySelector('#evg-reviews-list');
    let page = 1, loading = false, previousFocus = null;
    // Lightweight artwork lightbox; event delegation also covers AJAX-loaded reviews.
    const lightbox = document.createElement('div');
    lightbox.className = 'evg-review-lightbox';
    lightbox.hidden = true;
    lightbox.innerHTML = '<button type="button" class="evg-review-lightbox__close" aria-label="Закрыть изображение">×</button><img alt="Работа ученика">';
    lightbox.setAttribute('role', 'dialog');
    lightbox.setAttribute('aria-modal', 'true');
    lightbox.setAttribute('aria-label', 'Просмотр работы ученика');
    document.body.appendChild(lightbox);
    const lightboxImage = lightbox.querySelector('img');
    const lightboxClose = lightbox.querySelector('button');
    let lightboxTrigger = null;
    const closeLightbox = () => {
      if (lightbox.hidden) return;
      lightbox.hidden = true;
      lightboxImage.removeAttribute('src');
      document.body.classList.remove('evg-review-lightbox-open');
      if (lightboxTrigger) lightboxTrigger.focus();
    };
    section.addEventListener('click', event => {
      const link = event.target.closest('.evg-review-card__image');
      if (!link || !section.contains(link)) return;
      event.preventDefault();
      lightboxTrigger = link;
      lightboxImage.src = link.href;
      lightbox.hidden = false;
      document.body.classList.add('evg-review-lightbox-open');
      lightboxClose.focus();
    });
    lightboxClose.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', event => { if (event.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', event => {
      if (lightbox.hidden) return;
      if (event.key === 'Escape') closeLightbox();
      if (event.key === 'Tab') { event.preventDefault(); lightboxClose.focus(); }
    });

    // Keep cards compact and open long reviews in a separate dialog.
    const reviewDialog = document.createElement('div');
    reviewDialog.className = 'evg-review-detail';
    reviewDialog.hidden = true;
    reviewDialog.setAttribute('role', 'dialog');
    reviewDialog.setAttribute('aria-modal', 'true');
    reviewDialog.setAttribute('aria-label', 'Полный отзыв ученика');
    reviewDialog.innerHTML = '<div class="evg-review-detail__backdrop"></div><div class="evg-review-detail__panel" tabindex="-1"><button type="button" class="evg-review-detail__close" aria-label="Закрыть отзыв">×</button><div class="evg-review-detail__content"></div></div>';
    document.body.appendChild(reviewDialog);
    const detailPanel = reviewDialog.querySelector('.evg-review-detail__panel');
    const detailContent = reviewDialog.querySelector('.evg-review-detail__content');
    const detailClose = reviewDialog.querySelector('.evg-review-detail__close');
    let detailTrigger = null;
    const closeDetail = () => {
      if (reviewDialog.hidden) return;
      reviewDialog.hidden = true;
      detailContent.replaceChildren();
      document.body.classList.remove('evg-review-detail-open');
      if (detailTrigger) detailTrigger.focus();
    };
    const updateReadButtons = () => {
      section.querySelectorAll('.evg-review-card').forEach(card => {
        const text = card.querySelector('.evg-review-card__text');
        const button = card.querySelector('.evg-review-card__read');
        if (!text || !button) return;
        // Detect overflow by measuring an unconstrained clone, including paragraph breaks.
        const probe = text.cloneNode(true);
        probe.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none;display:block;-webkit-line-clamp:unset;max-height:none;height:auto;width:' + text.clientWidth + 'px';
        card.appendChild(probe);
        const lineHeight = parseFloat(getComputedStyle(text).lineHeight) || 26;
        button.hidden = probe.scrollHeight > lineHeight * 5 + 2;
        probe.remove();
      });
    };
    section.addEventListener('click', event => {
      const button = event.target.closest('.evg-review-card__read');
      if (!button || !section.contains(button)) return;
      const card = button.closest('.evg-review-card');
      detailTrigger = button;
      detailContent.replaceChildren();
      const header = card.querySelector('.evg-review-card__top').cloneNode(true);
      const body = card.querySelector('.evg-review-card__text').cloneNode(true);
      body.classList.add('evg-review-detail__text');
      detailContent.append(header, body);
      const artwork = card.querySelector('.evg-review-card__image');
      if (artwork) {
        const image = artwork.cloneNode(true);
        image.classList.add('evg-review-detail__image');
        detailContent.appendChild(image);
      }
      reviewDialog.hidden = false;
      document.body.classList.add('evg-review-detail-open');
      detailClose.focus();
    });
    detailClose.addEventListener('click', closeDetail);
    reviewDialog.querySelector('.evg-review-detail__backdrop').addEventListener('click', closeDetail);
    document.addEventListener('keydown', event => {
      if (reviewDialog.hidden) return;
      if (event.key === 'Escape') closeDetail();
      if (event.key === 'Tab') {
        const focusable = Array.from(detailPanel.querySelectorAll('button, a[href]')).filter(el => el.getClientRects().length);
        if (!focusable.length) return;
        const first = focusable[0], last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
      }
    });
    // Images inside the full-review dialog use the existing artwork viewer.
    reviewDialog.addEventListener('click', event => {
      const link = event.target.closest('.evg-review-card__image');
      if (!link) return;
      event.preventDefault();
      lightboxTrigger = link;
      lightboxImage.src = link.href;
      lightbox.hidden = false;
      document.body.classList.add('evg-review-lightbox-open');
      lightboxClose.focus();
    });
    updateReadButtons();
    window.addEventListener('resize', updateReadButtons);

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
        updateReadButtons();
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
